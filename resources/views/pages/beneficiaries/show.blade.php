@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Beneficiary
@stop

@section('page_title')
Beneficiary
@stop

@section('page_title_suffix')
{{$beneficiary->full_name}}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.beneficiaries.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to Beneficiary List
</a>
@stop

@section('page_title_buttons')

    {{-- <a data-toggle="tooltip" 
        title="New" 
        data-val='{{$beneficiary->id}}' 
        class="btn btn-sm btn-primary btn-new-mdl-beneficiary-member-modal" href="#">
        <i class="fa fa-eye"></i> New
    </a>

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$beneficiary->id}}' 
        class="btn btn-sm btn-primary btn-edit-mdl-beneficiary-member-modal" href="#">
        <i class="fa fa-pencil-square-o"></i> Edit
    </a>

    @if (Auth()->user()->hasAnyRole(['','admin']))
        @include('tf-bi-portal::pages.beneficiaries.bulk-upload-modal')
    @endif --}}
@stop



@section('content')
    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body">
            <div class="row col-sm-12">
                @include('tf-bi-portal::pages.beneficiaries.modal') 
                @include('tf-bi-portal::pages.beneficiaries.show_fields')
            </div>
            <div class="col-lg-12">
                <div class="tab pb-2 mt-3" style="">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('tf-bi-portal.beneficiaries.show', $beneficiary->id) }}?sub_menu_items=beneficiary_members" class="nav-link {{(!isset(request()->sub_menu_items) || request()->sub_menu_items=="beneficiary_members")?'active':''}}">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="bx bx-user font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Members</div>
                                </div>
                            </a>                        
                        </li>

                        <li class="nav-item" role="presentation">
                            <a href="{{ route('tf-bi-portal.beneficiaries.show', $beneficiary->id) }}?sub_menu_items=submissions" class="nav-link {{(request()->sub_menu_items=="submissions")?'active':''}}">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="bx bx-paper-plane font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Submissions</div>
                                </div>
                            </a>                        
                        </li>

                        <li class="nav-item" role="presentation">
                            <a href="{{ route('tf-bi-portal.beneficiaries.show', $beneficiary->id) }}?sub_menu_items=nominations" class="nav-link {{(request()->sub_menu_items=="nominations")?'active':''}}">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="bx bx-check-square font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Nominations</div>
                                </div>
                            </a>                        
                        </li>
                    </ul>
                </div>


            {{-- sub menu contents details--}}
                
                <div class="col-sm-12 panel panel-default card-view">
                    @if(!isset(request()->sub_menu_items) || request()->sub_menu_items=="beneficiary_members")
                        {{-- <h5 class="pt-3"> 
                            <strong>
                                Beneficiary User Accounts
                            </strong>
                             <a title="Create New Beneficiary Member" class="btn btn-primary btn-sm pull-right btn-new-beneficiary-member" href="#">
                                <span class="fa fa-plus"></span> <small>Add User</small>
                            </a>
                        </h5> --}}
                    @elseif(request()->sub_menu_items=="submissions")
                        {{-- <h5 class="pt-3"> 
                            <strong>
                                Beneficiary Submissions
                            </strong>
                        </h5> --}}
                    @elseif(request()->sub_menu_items=="nominations")
                        {{-- <h5 class="pt-3"> 
                            <strong>
                                Beneficiary Nominations
                            </strong>
                        </h5> --}}
                    @endif

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
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small">
            Beneficiary details displays all the details of the Institution including details of its members, submissions, and nominations for ASTD.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush