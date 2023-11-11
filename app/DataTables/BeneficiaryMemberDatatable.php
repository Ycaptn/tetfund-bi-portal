<?php

namespace App\DataTables;

use App\Models\BeneficiaryMember;
use \Hasob\FoundationCore\Models\User;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Button;

use Hasob\FoundationCore\Models\Organization;

class BeneficiaryMemberDatatable extends DataTable
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
    public function dataTable($query) {
        $dataTable = new EloquentDataTable($query);

        $dataTable->addColumn('full_name', function ($query) {
            if ($query->first_name != null && $query->last_name != null){
                return $query->first_name . " " . $query->last_name;
            }
            return "N/A";
        });

        $dataTable->addColumn('telephone', function ($query) {
            if ($query->telephone != null || $query->member_type != null || $query->grade_level){
                return  ($query->telephone!=null ? '- '. $query->telephone.'<br>' : '').
                        ($query->member_type!=null ? '- '.ucfirst($query->member_type).' Staff<br>':'').
                        ($query->grade_level!=null ? '- Grade-Level '.$query->grade_level.'<br>' : '').
                        ($query->academic_member_level!=null ? '- '. ucwords(str_replace('_', ' ', $query->academic_member_level)) : '');
            }
            return "N/A";
        })->escapeColumns('active')->make(true);

        $dataTable->addColumn('roles', function ($query) {
            if ($query->id != null) {
                $user = User::find($query->id);
                $user_roles_arr = $user->roles()->pluck('name')->toArray();
                $user_roles_str = "";

                if(count($user_roles_arr) > 0) {
                    foreach($user_roles_arr as $user_role) {
                        $user_roles_str .= ucwords($user_role) . "<br>";
                    }
                }
                return $user_roles_str;
            }
            return "N/A";
        })->escapeColumns('active')->make(true);

        return $dataTable->addColumn('action', 'tf-bi-portal::pages.beneficiaries.partials.datatable_actions_beneficiary_members');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BeneficiaryMember $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BeneficiaryMember $model)
    {
        if ($this->organization != null){
            return $model->newQuery()->join('fc_users', 'tf_bi_beneficiary_members.beneficiary_user_id', '=', 'fc_users.id')
                    ->where([   "fc_users.organization_id"=>$this->organization->id, 
                                "tf_bi_beneficiary_members.beneficiary_id"=>$this->beneficiary_id,
                                'fc_users.deleted_at'=>null,
                                'tf_bi_beneficiary_members.deleted_at'=>null])
                    ->orderBy('fc_users.created_at', 'DESC')
                    ->select('fc_users.id','fc_users.first_name','fc_users.last_name','fc_users.email','fc_users.telephone','fc_users.is_disabled','fc_users.created_at','fc_users.updated_at','tf_bi_beneficiary_members.member_type', 'tf_bi_beneficiary_members.grade_level', 'tf_bi_beneficiary_members.academic_member_level');
        }

        return $model->newQuery()->join('fc_users', 'tf_bi_beneficiary_members.beneficiary_user_id', '=', 'fc_users.id')
                    ->where([   'fc_users.deleted_at'=>null,
                                'tf_bi_beneficiary_members.deleted_at'=>null])
                    ->orderBy('fc_users.created_at', 'DESC')
                    ->select('fc_users.id','fc_users.first_name','fc_users.last_name','fc_users.email','fc_users.telephone','fc_users.is_disabled','fc_users.created_at','fc_users.updated_at', 'tf_bi_beneficiary_members.member_type', 'tf_bi_beneficiary_members.grade_level', 'tf_bi_beneficiary_members.academic_member_level');
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
                // 'dom'       => 'Bfrtip',
                'dom' => 'B<"float-end mb-5" f><t>ip',
                'stateSave' => true,
                'buttons'   => [
                    //['extend' => 'create', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                    //['extend' => 'export', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
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
        $id = 1;
        return [
            ['title'=>'Full Name','data'=>'full_name', 'name'=>'fc_users.first_name' ],
            ['title'=>'Email','data'=>'email', 'name'=>'fc_users.email' ],
            ['title'=>'Phone || Staff-Type || Grade-Level','data'=>'telephone', 'name'=>'fc_users.telephone' ],
            ['title'=>'Roles','data'=>'roles', 'name'=>'tf_bi_beneficiary_members.member_type' ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'beneficiaries_member_datatable_' . time();
    }
}
