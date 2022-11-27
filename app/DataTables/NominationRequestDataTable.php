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
        $query_filter = [   'type'=>$this->type,
                            'status'=>'approved',
                            'details_submitted'=>1,
                            'tf_bi_nomination_requests.beneficiary_id'=>$this->user_beneficiary_id,
                            'is_set_for_final_submission'=>0
                        ];

        if ($this->organization != null){
            $query_filter['tf_bi_nomination_requests.organization_id'] = $this->organization->id;
        }

        $all_commitee_stakeholders = [
            'bi-astd-commitee-head',
            'bi-tp-commitee-head',
            'bi-ca-commitee-head',
            'bi-tsas-commitee-head',
            'bi-astd-commitee-member',
            'bi-tp-commitee-member',
            'bi-ca-commitee-member',
            'bi-tsas-commitee-member'
        ];

        // addition filters to return newly submitted for respective dashboards
        if (Auth()->user()->hasAnyRole($all_commitee_stakeholders) && !isset(request()->view_type)) {
            $query_filter['is_average_commitee_members_check'] = 0;
            $query_filter['is_desk_officer_check'] = 1;
        } else if (Auth()->user()->hasAnyRole(['bi-hoi']) && !isset(request()->view_type)) {
            $query_filter['is_head_of_institution_check'] = 0;
            $query_filter['is_desk_officer_check_after_average_commitee_members_checked'] = 1;
        } else if (Auth()->user()->hasAnyRole(['bi-desk-officer']) && !isset(request()->view_type)) {
            $query_filter['is_desk_officer_check'] = 0;
        }
        
        // request filter for selected sub-menu button
        if (isset(request()->view_type) && !empty(request()->view_type)) {
            if (request()->view_type == 'commitee_approved' && Auth()->user()->hasAnyRole(array_merge($all_commitee_stakeholders, ['bi-desk-officer']))) {

                $query_filter['is_average_commitee_members_check'] = 1;
                $query_filter['is_desk_officer_check_after_average_commitee_members_checked'] = 0;

            } else if (request()->view_type == 'hoi_approved' && Auth()->user()->hasAnyRole(['bi-desk-officer', 'bi-hoi'])) {

                $query_filter['is_head_of_institution_check'] = 1;
                $query_filter['head_of_institution_checked_status'] = 'approved';

            } else if (request()->view_type == 'final_nominations') {

                $query_filter['is_set_for_final_submission'] = 1;

            }
        }

        // final query to be returned with respective filter(s) array
        return $model->newQuery()->with('user')
            ->with('nomination_committee_votes')
            ->with('submission_request')
            ->where($query_filter)
            ->when((Auth()->user()->hasAnyRole($all_commitee_stakeholders) == true), function ($q) use ($all_commitee_stakeholders) {
                return $q->when((!isset(request()->view_type) || optional(request())->view_type!='commitee_approved'), function ($que) use ($all_commitee_stakeholders) {
                        return $que->where('is_average_commitee_members_check', 0)
                            ->WhereHas('nomination_committee_votes', function($query) use ($all_commitee_stakeholders) {
                            return $query->where('user_id','!=',auth()->user()->id);
                    });

                });
            })
            ->when((Auth()->user()->hasRole('bi-desk-officer') == true), function ($q) {
                return $q->when((isset(request()->view_type) && request()->view_type=='final_nominations'), function ($que) {
                        return $que->where('is_set_for_final_submission', 1)
                            ->WhereHas('submission_request', function($query) {
                            return $query->where('status', 'not-submitted');
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
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[2, 'desc']],
                'buttons'   => [
                    ['extend' => 'print', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
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
        return [
            //['title'=>'Requested on', 'data'=>'full_name', 'name'=>'user.first_name' ],
            Column::make('full_name')->name('user.first_name'),
            Column::make('email')->name('user.email'),
            ['title'=>'Requested on','data'=>'created_at', 'name'=>'created_at' ],
        ];
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

        $dataTable->addColumn('created_at', function ($query) {
            if ($query->created_at != null){
                $created_at = \Carbon\Carbon::parse($query->created_at)->format('jS M, Y');
                return $created_at;
            }
            return "N/A";
        });

        return $dataTable->addColumn('action', 'tf-bi-portal::pages.nomination_requests.default_datatables_actions');
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
