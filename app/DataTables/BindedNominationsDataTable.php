<?php

namespace App\DataTables;

use App\Models\NominationRequest;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

use Hasob\FoundationCore\Models\Organization;

class BindedNominationsDataTable extends DataTable
{
    protected $organization;
    protected $counter = 0;
    public function __construct(Organization $organization){
        $this->organization = $organization;
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

        $dataTable->editColumn('nominee_name', function ($query) {
            if ($query->user->first_name != null && $query->user->last_name != null){
                $str_to_return = '<strong>' . 
                                    ucfirst($query->user->first_name) . ' ' .
                                    ucfirst($query->user->middle_name ?? "") . ' ' .
                                    ucfirst($query->user->last_name) . ' || ' . 
                                    strtoupper($query->type) .
                                '</strong>
                                <br>
                                <em>
                                    <small>' . 
                                        strtolower($query->user->email) . ' || '  .
                                        strtolower($query->user->telephone) . '
                                    </small>
                                </em>';

                return $str_to_return;
            }
            return "N/A";  
        })->escapeColumns('active')->make(true);

        $dataTable->editColumn('amount_requested', function ($query) {
            if ($query->total_requested_amount != null){
                return '₦ ' . number_format($query->total_requested_amount, '2');
            }
            return "N/A";  
        })->escapeColumns('active')->make(true);

        $dataTable->addColumn('sn', function ($query) {
            return $this->counter += 1;
        });

        $dataTable->addColumn('updated_at', function ($query) {
            if ($query->updated_at != null){
                $updated_at = \Carbon\Carbon::parse($query->updated_at)->format('l jS F Y') . ' - ' . \Carbon\Carbon::parse($query->updated_at)->diffForHumans();
                return $updated_at;
            }
            return "N/A";
        });

        return $dataTable->addColumn('action', 'xyz::pages.submission_requests.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\NominationRequest $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NominationRequest $model)
    {
        return $model->newQuery()->with('user')
            ->where('beneficiary_id', $this->user_beneficiary->id)
            ->where('type', $this->intervention_name)
            ->where('head_of_institution_checked_status', 'approved')
            ->when($this->intervention_name=='tp', function($qry) {
                return  $qry->join('tf_bi_tp_nominations', 'tf_bi_tp_nominations.nomination_request_id', '=', 'tf_bi_nomination_requests.id')
                        ->where('tf_bi_tp_nominations.deleted_at', null)
                        ->select('tf_bi_nomination_requests.*', 'tf_bi_tp_nominations.total_requested_amount');
            })
            ->when($this->intervention_name=='ca', function($qry) {
                return  $qry->join('tf_bi_ca_nominations', 'tf_bi_ca_nominations.nomination_request_id', '=', 'tf_bi_nomination_requests.id')
                        ->where('tf_bi_ca_nominations.deleted_at', null)
                        ->select('tf_bi_nomination_requests.*', 'tf_bi_ca_nominations.total_requested_amount');
            })
            ->when($this->intervention_name=='tsas', function($qry) {
                return  $qry->join('tf_bi_tsas_nominations', 'tf_bi_tsas_nominations.nomination_request_id', '=', 'tf_bi_nomination_requests.id')
                        ->where('tf_bi_tsas_nominations.deleted_at', null)
                        ->select('tf_bi_nomination_requests.*', 'tf_bi_tsas_nominations.total_requested_amount');
            })
            ->when((isset($this->organization) && $this->organization != null), function ($que) {
                return $que->where("tf_bi_nomination_requests.organization_id", $this->organization->id);
            })
            ->when($this->submission_request->status != 'not-submitted', function ($que) {
                return $que->where('tf_bi_nomination_requests.bi_submission_request_id', $this->submission_request->id)
                        ->where('is_set_for_final_submission', 1);
            })
            ->when($this->submission_request->status == 'not-submitted', function ($que) {
                return $que->whereNull('tf_bi_nomination_requests.bi_submission_request_id')
                        ->where('is_set_for_final_submission', 0);
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
            //->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom' => 'B<"float-end mb-5" f><t>ip',
                'stateSave' => true,
                'order'     => [[2, 'desc']],
                'buttons'   => [
                    //['extend' => 'create', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                    //['extend' => 'export', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
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
            ['title'=>'S/N','data'=>'sn', 'name'=>'user.email'],
            Column::make('nominee_name')->name('user.first_name'),
            Column::make('amount_requested')->name("tf_bi_{$this->intervention_name}_nominations.total_requested_amount"),
            ['title'=>'Date Completed','data'=>'updated_at', 'name'=>'updated_at' ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'binded_nominations_datatable_' . time();
    }
}
