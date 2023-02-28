<?php
namespace App\DataTables;

use App\Models\SubmissionRequest;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

use Hasob\FoundationCore\Models\Organization;

class MonitoringRequestDataTable extends DataTable
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
            return $query->title . '<br><small style="color:red">('. $query->type .')</small>';
        })->escapeColumns('active')->make(true);
        
        $dataTable->addColumn('proposed_request_date', function ($query) {
            return date('jS M, Y', strtotime($query->proposed_request_date));
        });

        $dataTable->addColumn('created_at', function ($query) {
            return date('jS M, Y', strtotime($query->created_at));
        });

        $dataTable->addColumn('status', function ($query) {
            if ($query->status == 'submitted') {
                $html_text = "<span class='text-success'>". ucwords($query->status) ."</span>";   
            } else {
                $html_text = "<span class='text-danger'>". ucwords($query->status) ."</span>";   
            }
            return $html_text;
        });

        $dataTable->addColumn('attachment', function ($query) {
            if (isset($query->attachables[0]['attachment']['id']) && $query->attachables[0]['attachment']['id'] != null) {
                $html_text = "<u><a target='__blank' href='". route('fc.attachment.show', $query->attachables[0]['attachment']['id']) ."'> Preview </a></u>";   
            } else {
                $html_text = "- - -";   
            }
            return $html_text;
        });

        return $dataTable->addColumn('action', 'tf-bi-portal::pages.monitoring.datatables_actions');
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
                ->with('attachables.attachment')
                ->where([
                    "is_monitoring_request" => true,
                    "beneficiary_id" => $this->beneficiary_id,
                ])
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
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom' => 'B<"float-end mb-5" f><t>ip',
                'stateSave' => true,
                'order'     => [[5, 'desc']],
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
            Column::make('row_number')
                    ->title('#')
                    ->render('meta.row + meta.settings._iDisplayStart + 1;')
                    ->width(50)
                    ->orderable(false),
            Column::make('title')->title('Project Title'),
            Column::make('proposed_request_date')->title('Proposed Date')->addClass('text-center'),
            Column::make('status')->title('Status')->addClass('text-center'),
            Column::make('attachment')->title('Attachment')->addClass('text-center'),
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
        return 'monitoring_datatable_' . time();
    }
}
