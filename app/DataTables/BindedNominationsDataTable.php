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
        if ($this->organization != null){
            return $model->newQuery()->with('user')
                    ->where('beneficiary_id', $this->user_beneficiary->id)
                    ->where('type', $this->intervention_name)
                    ->where('head_of_institution_checked_status', 'approved')
                    ->where('bi_submission_request_id', null)
                    ->where("organization_id", $this->organization->id);
        }
        
        return $model->newQuery()->with('user')
                    ->where('beneficiary_id', $this->user_beneficiary->id)
                    ->where('type', $this->intervention_name)
                    ->where('head_of_institution_checked_status', 'approved')
                    ->where('bi_submission_request_id', null);
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
                'dom'       => 'Bfrtip',
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
