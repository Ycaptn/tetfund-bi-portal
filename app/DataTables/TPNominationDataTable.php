<?php

namespace App\DataTables;

use App\Models\ASTDNomination as TPNomination;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

use Hasob\FoundationCore\Models\Organization;

class TPNominationDataTable extends DataTable
{
    protected $organization;

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

        return $dataTable->addColumn('action', 'tf-bi-portal::pages.t_p_nominations.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TPNomination $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TPNomination $model)
    {
        if ($this->organization != null){
            return $model->newQuery()->where("organization_id", $this->organization->id);
        }
        
        return $model->newQuery();
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
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'create', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                    ['extend' => 'export', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
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
            'email',
            'telephone',
            'gender',
            'name_title',
            'first_name',
            'middle_name',
            'last_name',
            'name_suffix',
            'bank_account_name',
            'bank_account_number',
            'bank_name',
            'bank_sort_code',
            'intl_passport_number',
            'bank_verification_number',
            'national_id_number',
            'degree_type',
            'program_title',
            'program_type',
            'fee_amount',
            'tuition_amount',
            'upgrade_fee_amount',
            'stipend_amount',
            'passage_amount',
            'medical_amount',
            'warm_clothing_amount',
            'study_tours_amount',
            'education_materials_amount',
            'thesis_research_amount',
            'final_remarks',
            'total_requested_amount',
            'total_approved_amount'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 't_p_nominations_datatable_' . time();
    }
}
