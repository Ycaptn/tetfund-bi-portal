<?php
namespace App\DataTables;

use App\Models\NominationRequest;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

use Hasob\FoundationCore\Models\Organization;

class NominationRequestDataTable extends DataTable
{
    protected $organization;
    protected $counter = 0;

    public function __construct(Organization $organization){
        $this->organization = $organization;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\NominationRequest $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NominationRequest $model)
    {   
        if (Auth()->user()->hasAnyRole(['admin'])) {
            return $model->newQuery()->with('user')
                ->where('beneficiary_id', $this->beneficiary_id);
        }

        $query_filter = [   'type'=>$this->type,
                            'status'=>'approved',
                            'details_submitted'=>1,
                            'tf_bi_nomination_requests.beneficiary_id'=>$this->user_beneficiary_id,
                            'is_set_for_final_submission'=>0,
                            'bi_submission_request_id'=>null
                        ];

        if ($this->organization != null){
            $query_filter['tf_bi_nomination_requests.organization_id'] = $this->organization->id;
        }

        $all_committee_members = [
            'BI-TP-committee-member',
            'BI-CA-committee-member',
            'BI-TSAS-committee-member'
        ];

        $all_committee_heads = [
            'BI-TP-committee-head',
            'BI-CA-committee-head',
            'BI-TSAS-committee-head',
        ];

        // addition filters to return newly submitted for respective dashboards
        if (Auth()->user()->hasAnyRole(array_merge($all_committee_heads, $all_committee_members)) && !isset(request()->view_type)) {
            $query_filter['is_average_committee_members_check'] = 0;
            $query_filter['is_desk_officer_check'] = 1;
        } else if (Auth()->user()->hasAnyRole(['BI-head-of-institution']) && !isset(request()->view_type)) {
            $query_filter['is_head_of_institution_check'] = 0;
            $query_filter['is_desk_officer_check_after_average_committee_members_checked'] = 1;
        } else if (Auth()->user()->hasAnyRole(['BI-desk-officer','BI-astd-desk-officer']) && !isset(request()->view_type)) {
            $query_filter['is_desk_officer_check'] = 0;
        }
        
        // request filter for selected sub-menu button
        if (isset(request()->view_type) && !empty(request()->view_type)) {
            if (request()->view_type == 'committee_approved' && Auth()->user()->hasAnyRole(array_merge($all_committee_members, $all_committee_heads, ['BI-desk-officer','BI-astd-desk-officer']))) {

                $query_filter['is_average_committee_members_check'] = 1;
                $query_filter['committee_head_checked_status'] = 'approved';
                $query_filter['is_desk_officer_check_after_average_committee_members_checked'] = 0;

            } else if (request()->view_type == 'hoi_approved' && Auth()->user()->hasAnyRole(['BI-desk-officer', 'BI-astd-desk-officer', 'BI-head-of-institution'])) {

                $query_filter['is_head_of_institution_check'] = 1;
                $query_filter['head_of_institution_checked_status'] = 'approved';
                $query_filter['is_set_for_final_submission'] = 0;
            }
        }

        // final query to be returned with respective filter(s) array
        return $model->newQuery()->with('user')
            ->with('nomination_committee_votes')
            ->with('submission_request')
            ->where($query_filter)
            ->when((Auth()->user()->hasAnyRole($all_committee_heads) == true), function ($q) {
                return $q->where('is_average_committee_members_check', 0)
                        ->when((isset(request()->view_type) && request()->view_type == 'committee_head_consideration'), function ($q2) {
                            return $q2->where('is_desk_officer_check', 1);
                        });
            })
            ->when((Auth()->user()->hasAnyRole(array_merge($all_committee_members, $all_committee_heads)) == true && !isset(request()->view_type)), function ($q) {
                
                return $q->when((!isset(request()->view_type) || optional(request())->view_type!='committee_approved'), function ($que) {                        
                    return $que->where('is_average_committee_members_check', 0)
                    ->whereDoesntHave('nomination_committee_votes', function($query) {
                        $query->where('user_id','=',auth()->user()->id);
                    });

                });
            });
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $builder = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax();

        if (!Auth()->user()->hasAnyRole(['admin'])) {
            $builder = $builder->addAction(['width' => '120px', 'printable' => false]);
        }

        return $builder->parameters([
            'dom' => 'B<"float-end mb-5" f><t>ip',
            'stateSave' => true,
            'order'     => [[3, 'desc']],
            'buttons'   => [
                ['extend' => 'print', 'className' => 'btn btn-primary btn-outline btn-xs no-corner mt-3',],
                ['extend' => 'reset', 'className' => 'btn btn-primary btn-outline btn-xs no-corner mt-3',],
                ['extend' => 'reload', 'className' => 'btn btn-primary btn-outline btn-xs no-corner mt-3',],
            ],
        ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            ['title'=>'S/N','data'=>'sn', 'name'=>'user.email'],
            Column::make('full_name')->name('user.first_name'),
            Column::make('email')->name('user.email'),
        ];

        if (Auth()->user()->hasAnyRole(['admin'])) {
            array_push($columns, Column::make('type')->name('type'));
        }

        array_push($columns, ['title'=>'Requested on','data'=>'created_at', 'name'=>'created_at' ]);

        return $columns;
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        $dataTable->addColumn('sn', function ($query) {
            return $this->counter += 1;
        });

        $dataTable->addColumn('full_name', function ($query) {
            if ($query->user->first_name != null && $query->user->last_name != null){
                return $query->user->first_name . " " . $query->user->last_name;
            }
            return "N/A";
        });

        $dataTable->addColumn('email', function ($query) {
            if ($query->user->email != null){
                return $query->user->email;
            }
            return "N/A";
        });

        $dataTable->addColumn('type', function ($query) {
            $returned_str = "N/A";
            if ($query->type != null){
                if ($query->type == 'tp') {
                    $returned_str = 'Teaching Practice';
                } elseif ($query->type == 'ca') {
                    $returned_str = 'Conference Attendance';
                } elseif($query->type == 'tsas') {
                    $returned_str = 'TETFund Scholartship for Academic Staff';
                }
            }
            return $returned_str;
        });

        $dataTable->addColumn('created_at', function ($query) {
            if ($query->created_at != null){
                $created_at = \Carbon\Carbon::parse($query->created_at)->format('jS M, Y');
                return $created_at;
            }
            return "N/A";
        });

        if (Auth()->user()->hasAnyRole(['admin'])) {
            return $dataTable;
        } else {
            return $dataTable->addColumn('action', 'tf-bi-portal::pages.nomination_requests.default_datatables_actions');
        }
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'nomination_request_datatable_' . time();
    }
}
