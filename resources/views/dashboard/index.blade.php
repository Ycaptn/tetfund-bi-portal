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

    @if($current_user->hasRole('BI-desk-officer'))
        <div class="card radius-5 border-top">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-1">
                        <center>
                            <a href="{{ route('tf-bi-portal.submissionRequests.create') }}" class="btn btn-primary bg-olive">
                                <i class="fa fa-edit"></i>New
                            </a>
                            <br/>
                        </center>
                    </div>

                    <div class="col-md-10">
                        <ul>
                            <li>All submissions to the Fund must be submitted <b>ONLINE</b> and <b>PHYSICAL</b>.</li>
                            <li>For <b>NEW SUBMISSIONS</b>, i.e., Request for Approval in Principle (AIP), click the New Submission Button</li>
                            {{-- <li>For <b>ONGOING SUBMISSIONS</b>, i.e. First Tranche, Second Tranche, Final Tranche, Monitoring Request, etc, click the Ongoing Submission Button</li>
                            <li>Once your submission is completed, a <b>TRACKING NUMBER</b> is provided for you, you are required to include this tracking number in the physical hard copy submission to the Fund</li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card radius-5 border-top border-0 border-3 border-success">
        <div class="card-body">
            <div class="accordion" id="acc_main">
                <div class="accordion-item">
                    <h4 class="accordion-header" id="acc_platforms">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_platforms_div" aria-expanded="false" aria-controls="acc_platforms_div">
                            TETFund Official Communications
                        </button>
                    </h4>
                    <div id="acc_platforms_div" class="accordion-collapse collapse" aria-labelledby="acc_platforms" data-bs-parent="#acc_main" style="">
                        <div class="accordion-body">
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h4 class="accordion-header" id="acc_org_sites">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_org_sites_div" aria-expanded="false" aria-controls="acc_org_sites_div">
                            Upcoming Monitoring
                        </button>
                    </h4>
                    <div id="acc_org_sites_div" class="accordion-collapse collapse" aria-labelledby="acc_org_sites" data-bs-parent="#acc_main" style="">
                        <div class="accordion-body">
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h4 class="accordion-header" id="acc_ess">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_ess_div" aria-expanded="false" aria-controls="acc_ess_div">
                            Active Submissions
                        </button>
                    </h4>
                    <div id="acc_ess_div" class="accordion-collapse collapse" aria-labelledby="acc_ess_div" data-bs-parent="#acc_main" style="">
                        <div class="accordion-body">
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


@stop


@section('side-panel')
@stop

@push('page_scripts')
@endpush