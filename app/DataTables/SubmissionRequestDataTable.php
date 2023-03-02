<?php

namespace App\DataTables;

use App\Models\SubmissionRequest;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

use Hasob\FoundationCore\Models\Organization;

class SubmissionRequestDataTable extends DataTable
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

        $dataTable->addColumn('title', function ($query) {
            $intervention_years = $query->getInterventionYears();
            $html_returned = strtoupper($query->title) . '<br><small style="color:green"><b>Type:</b> &nbsp;'. $query->type .' -- '. implode(', ', $intervention_years) .'</small><br>';

            if ($query->is_monitoring_request==false) {
                $html_returned .= '<small style="color:red"><b>Amount Requested:</b> &nbsp; &#8358;'.number_format($query->amount_requested, 2).'</small>';
            } else {
                $html_returned .= '<small style="color:red">Monitoring Request</small>';
            }

            return $html_returned;
            
        })->escapeColumns('active')->make(true);

        $dataTable->addColumn('status', function ($query) {
            if ($query->status == 'submitted') {
                $html_text = "<span class='text-success'>". ucwords($query->status) ."</span>";   
            } else {
                $html_text = "<span class='text-danger'>". ucwords($query->status) ."</span>";   
            }
            return $html_text;
        });

        $dataTable->addColumn('created_at', function ($query) {
            return date('jS M, Y', strtotime($query->created_at));
        });

        return $dataTable;
        //return $dataTable->addColumn('action', '--');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SubmissionRequest $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SubmissionRequest $model)
    {        
        return $model->newQuery()
                ->where('beneficiary_id', $this->beneficiary_id)
                ->when($this->organization != null, function($query) {
                    $query->where('organization_id', $this->organization->id);
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
        return [
            Column::make('id')
                    ->title('#')
                    ->render('meta.row + meta.settings._iDisplayStart + 1;')
                    ->width(50)
                    ->orderable(false),
            Column::make('title')->title('Project Title'),
            Column::make('status')->title('Status')->addClass('text-center'),
            Column::make('created_at')->title('Request Date')->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'submission_requests_datatable_' . time();
    }
}
