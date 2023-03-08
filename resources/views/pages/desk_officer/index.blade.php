@extends('layouts.app')

@section('title_postfix')
Desk Officer - {!! $beneficiary->full_name !!} ({!! $beneficiary->short_name !!})
@stop

@section('page_title')
Desk Officer
@stop

@section('page_title_suffix')
{!! $beneficiary->full_name !!} ({!! $beneficiary->short_name !!})
@stop

@section('app_css')
@stop

@section('page_title_buttons')
<a title="Edit Beneficiary Details" class="btn btn-primary btn-sm" href="#" id="btn-edit-beneficiary">
    <span class="fa fa-edit"></span> <small>Edit Details</small>
</a>
@stop

@section('page_title_subtext')
@stop

@section('content')
    
    <div class="card radius-5 border-top border-0 border-3 border-success">
        <div class="card-body">
            <div class="row col-sm-12">
                @include('tf-bi-portal::pages.beneficiaries.modal') 
                @include('tf-bi-portal::pages.beneficiaries.show_fields')
            </div><hr>

            <div id="beneficiary_details" class="tabcontent">
                <div class="col-sm-12 panel panel-default card-view">
                    <h6 class="pt-2"> 
                        <strong>
                            Beneficiary User Accounts
                        </strong>
                         <a title="Create New Beneficiary Member" class="btn btn-primary btn-sm pull-right btn-new-beneficiary-member" href="#">
                            <span class="fa fa-plus"></span> <small>Add User</small>
                        </a>
                    </h6>
                    @include('tf-bi-portal::pages.desk_officer.edit_beneficiary_modal')
                    @include('tf-bi-portal::pages.beneficiaries.table')
                    @include('tf-bi-portal::pages.beneficiaries.partials.beneficiary_member_modal')
                </div>
            </div>
        </div>
    </div>
@stop


@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">Desk Officer</h5></div>
        <p class="small text-justify">
            The Desk Officer is the primary represents the beneficiary in matters related to TETFund and is authorized to make submissions add other users on the TETFund submission portal as well as following up on status of all submissions to TETFund.
        </p>
    </div>
</div>
@stop