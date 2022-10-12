<?php

namespace App\DataTables;

use App\Models\Beneficiary;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

use Hasob\FoundationCore\Models\Organization;

class BeneficiaryDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'tf-bi-portal::pages.beneficiaries.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Beneficiary $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Beneficiary $model)
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
            'full_name',
            'short_name',
            'official_email',
            'official_website',
            'official_phone',
            'address_street',
            'address_town',
            'address_state',
            'head_of_institution_title',
            'geo_zone',
            'owner_agency_type',
            'tf_iterum_portal_beneficiary_status'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'beneficiaries_datatable_' . time();
    }
}
