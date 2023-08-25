@extends('layouts.app')

@section('title_postfix')
Beneficiary Dashboard
@stop

@section('page_title')
Beneficiary Dashboard
@stop

@section('page_title_suffix')
{{ \Auth::user()->full_name }}
@stop

@section('app_css')
@stop

@section('page_title_buttons')
@stop

@section('page_title_subtext')
@stop

@section('content')
    
    {{-- Load all available dashboards --}}
    @foreach(\FoundationCore::get_dashboards($organization) as $idx=>$dashboard)
        @include($dashboard->value)
    @endforeach



    <div class="alert border-0 border-success border-start border-4 bg-light-success alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-success">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div class="ms-3">
                <div class="text-success">
                    <ul class="mb-0">
                        <li class="mb-2">Welcome to the upgraded TETFund Beneficiary Submission Portal for processing of submissions for <b>Physical Infrastructure, Library, ASTD, Academic Manuscripts, ICT Support, and other Special Interventions</b>.</li>
                        <li>For <b>National Research Fund (NRF)</b> submissions, please use the <a href="https://nrf.tetfund.gov.ng" style="color:blue;" target="_blank">NRF Portal</a> to process your submissions.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div> 

    @if($submission_request_obj->can_user_operate_any_intervention($current_user_roles))
        <div class="card radius-5 border-top border-0 border-3 border-success">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-2">
                        <center>
                            <a href="{{ route('tf-bi-portal.submissionRequests.create') }}" class="btn btn-primary bg-olive col-sm-6 col-md-10 mt-3" title="Process a new submission request right away.">
                                <i class="fa fa-edit"></i>
                                <br>New
                            </a>
                            <a href="" class="btn btn-app bg-orange col-md-10 mt-3 text-white" id="btn-ongoing-submission" title="Process an ongoing submission request by selecting it current stage.">
                                <i class="fa fa-copy"></i>
                                <br>Ongoing
                            </a>
                            <br/>
                        </center>
                    </div>

                    <div class="col-sm-10">
                        <ul>
                            <li class="mt-3">
                                All submissions to the Fund must be submitted <b>ONLINE</b>.
                            </li>
                            <li>
                                For <b>NEW SUBMISSIONS</b>, i.e., Request for Approval in Principle (AIP), click the New Submission Button
                            </li>
                            <li class="mt-4">
                                For <b>ONGOING SUBMISSIONS</b>, i.e. First Tranche, Second Tranche, Final Tranche, Monitoring Request, Audit Clearance etc, that the processing wasn't started or completed online, click the Ongoing Submission Button 
                            </li>
                            <li>
                                <span class="text-danger">Note the Ongoing Submissions button is only meant for submission that was started manually or was not completed through this submission portal. </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card radius-5 border-top border-0 border-3 border-success">
        <div class="card-body">
            <div class="accordion" id="acc_main">
                
                {{-- official comunications --}}
                <div class="accordion-item">
                    <h4 class="accordion-header" id="acc_platforms">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_platforms_div" aria-expanded="false" aria-controls="acc_platforms_div">
                            <b>
                                <sup class="fa-layers-counter text-white bg-danger" style="background-color:white; border-radius: 20%;">
                                    &nbsp;{{ count($official_communications) }}&nbsp;
                                </sup>
                            </b>
                            TETFund Official Communications
                        </button>
                    </h4>
                    <div id="acc_platforms_div" class="accordion-collapse collapse" aria-labelledby="acc_platforms" data-bs-parent="#acc_main" style="">
                        <div class="accordion-body">
                            @if($submission_request_obj->can_user_operate_any_intervention($current_user_roles))
                                @include('tf-bi-portal::dashboard.partials.official_communications')
                            @else
                                <div class="text-center text-danger">
                                    <i>Access to content denied!</i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- upcoming monitoring --}}
                <div class="accordion-item">
                    <h4 class="accordion-header" id="acc_org_sites">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_org_sites_div" aria-expanded="false" aria-controls="acc_org_sites_div">
                            <b>
                                <sup class="fa-layers-counter text-white bg-danger" style="background-color:white; border-radius: 20%;">
                                    &nbsp;{{ count($upcoming_monitorings) }}&nbsp;
                                </sup>
                            </b>
                            Upcoming Monitoring
                        </button>
                    </h4>
                    <div id="acc_org_sites_div" class="accordion-collapse collapse" aria-labelledby="acc_org_sites" data-bs-parent="#acc_main" style="">
                        <div class="accordion-body">
                            @if($submission_request_obj->can_user_operate_any_intervention($current_user_roles))
                                @include('tf-bi-portal::dashboard.partials.upcoming_monitorings')
                            @else
                                <div class="text-center text-danger">
                                    <i>Access to content denied!</i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                {{-- active submissions --}}
                @if($current_user->hasAnyRole(['BI-desk-officer','BI-ict','BI-astd-desk-officer','BI-works', 'BI-head-institution']))
                    <div class="accordion-item">
                        <h4 class="accordion-header" id="acc_ess">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_ess_div" aria-expanded="false" aria-controls="acc_ess_div">
                                <b>
                                    <sup class="fa-layers-counter text-white bg-danger" style="background-color:white; border-radius: 20%;">
                                        &nbsp;{{ count($active_submissions) }}&nbsp;
                                    </sup>
                                </b>
                                Active Submissions
                            </button>
                        </h4>
                        <div id="acc_ess_div" class="accordion-collapse collapse" aria-labelledby="acc_ess_div" data-bs-parent="#acc_main" style="">
                            <div class="accordion-body">
                                @include('tf-bi-portal::dashboard.partials.active_submissions')
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h4 class="accordion-header" id="acc_ess">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#app_ess_div" aria-expanded="false" aria-controls="app_ess_div">
                                <b>
                                    <sup class="fa-layers-counter text-white bg-danger" style="background-color:white; border-radius: 20%;">
                                        &nbsp;{{ count($approved_submissions) }}&nbsp;
                                    </sup>
                                </b>
                                Approved Submissions
                            </button>
                        </h4>
                        <div id="app_ess_div" class="accordion-collapse collapse" aria-labelledby="app_ess_div" data-bs-parent="#acc_main" style="">
                            <div class="accordion-body">
                                @include('tf-bi-portal::dashboard.partials.approved_submissions')
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @include('tf-bi-portal::dashboard.partials.ongoing_submision_modal')

@stop


@section('side-panel')
@stop

@push('page_scripts')
@endpush