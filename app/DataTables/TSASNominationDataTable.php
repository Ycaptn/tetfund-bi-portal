<?php
namespace App\DataTables;

use App\Models\TSASNomination;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

use Hasob\FoundationCore\Models\Organization;

class TSASNominationDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'tf-bi-portal::pages.t_s_a_s_nominations.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TSASNomination $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TSASNomination $model)
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
                'dom' => 'B<"float-end mb-5" f><t>ip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    /*['extend' => 'create', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],*/
                    /*['extend' => 'export', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],*/
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
        return [
            'email',
            'telephone',
            'gender',
            'name_title',
            'first_name',
            'last_name',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 't_s_a_s_nominations_datatable_' . time();
    }
}
