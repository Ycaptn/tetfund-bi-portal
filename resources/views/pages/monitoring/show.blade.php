@php
    $get_all_related_requests = $monitoring_request->getAllRelatedSubmissionRequest(true);
    $years_str = $monitoring_request->intervention_year1;
    // merged years, unique years & sorted years
    if (isset($years) && count($years) > 1) {
        if (count($years) > 1) {
            $years[count($years) - 1] = ' and ' . $years[count($years) - 1];
            $years_str = implode(", ", $years);
            $years_str = substr($years_str, 0,strrpos($years_str,",")) . $years[count($years) - 1];
        }
    }
@endphp

@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Monitoring
@stop

@section('page_title')
Monitoring
@stop

@section('page_title_suffix')
    {{$monitoring_request_submitted->title ?? $monitoring_request->title}} - 
    @if(!empty($monitoring_request_submitted) && ( ($monitoring_request->is_aip_request==true && $monitoring_request_submitted->has_generated_aip==true) || ( ($monitoring_request->is_first_tranche_request==true || $monitoring_request->is_second_tranche_request==true || $monitoring_request->is_final_tranche_request==true) && $monitoring_request_submitted->has_generated_disbursement_memo==true) ) )
        <b class="text-success">
            {{$monitoring_request->is_aip_request==true ? $monitoring_request->type : $monitoring_request->type.' Request' }} Granted
        </b>
    @else
        <b>({{ strtoupper(optional($monitoring_request_submitted)->request_status == 'new' ? 'In-Progress' : $monitoring_request->status ) }})</b>
    @endif
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.monitoring') }}">
    <i class="fa fa-angle-double-left"></i> Back to Monitoring Request List
</a>
@stop

@section('page_title_buttons')
    @if($monitoring_request->status == 'not-submitted')
        <a data-toggle="tooltip" 
            title="Edit Monitoring Request" 
            data-val='{{$monitoring_request->id}}' 
            class="btn btn-sm btn-info btn-edit-m-r m-2">
            &nbsp; <i class="fa fa-pencil-square-o"></i> Modify &nbsp;
        </a>

        <a data-toggle="tooltip" 
            title="Delete Monitoring Request" 
            data-val='{{$monitoring_request->id}}' 
            class="btn btn-sm btn-danger btn-delete-m-r m-2">
            &nbsp; <i class="fa fa-trash"></i> Delete &nbsp;
        </a>
    @endif
@stop



@section('content')
    <div class="card border-top border-4 border-success">
        <div class="card-body">

            @include('tf-bi-portal::pages.submission_requests.modal')
            @if($monitoring_request->status == 'not-submitted')
                <div class="row alert alert-warning">
                    <div class="col-md-9">
                        <i class="icon fa fa-warning"></i>
                        <strong>PRE-SUBMISSION NOTICE:</strong> 
                        <ul>
                            <li>This monitoring request has <strong>NOT</strong> been submitted.</li> 
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="col-sm-10 btn btn-sm btn-danger pull-right btn-submit-m-r" data-val='{{$monitoring_request->id}}' title="Submit Monitoring Request">
                            <small><span class="fa fa-paper-plane"></span> Submit Monitoring Request</small>
                        </a> 
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-sm-12 {{ empty($get_all_related_requests) ? 'col-md-9' : 'col-md-6' }}">
                    <div class="col-sm-12">
                        @if(!empty($monitoring_request_submitted))
                            <i class="fa fa-briefcase fa-fw"></i> <b>File_Number:</b> &nbsp; {{ optional($monitoring_request_submitted)->file_number }} <br/>
                        @endif
                        <i class="fa fa-calendar-o fa-fw"></i> <strong>Created on </strong> {{ \Carbon\Carbon::parse($monitoring_request->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($monitoring_request->created_at)->diffForHumans() !!} <br/>

                        <i class="fa fa-bank fa-fw"></i> <b>{{ ucwords($intervention->type) }} Intervention &nbsp; - &nbsp; </b> &nbsp; {{ $intervention->name}} <br/>
                        <i class="fa fa-briefcase fa-fw"></i> <b>Monitoring Type:</b> &nbsp; {{ $monitoring_request->type }} <br/>
                        <i class="fa fa-crosshairs fa-fw"></i> <b>Intervention Year(s) &nbsp; - &nbsp; </b> &nbsp; {{ $years_str }} <br/>
                        
                        <i class="fa fa-calendar"></i> <b>Proposed Monitoring Date &nbsp; - &nbsp; </b> &nbsp; {{ date('jS \of F, Y', strtotime($monitoring_request->proposed_request_date)) }} <br/>
                        
                        <i class="fa fa-thumbs-up fa-fw"> </i><b>Current Stage &nbsp; - &nbsp; </b> &nbsp; {{ strtoupper($monitoring_request_submitted->work_item->active_assignment->assigned_user->department->long_name ?? $monitoring_request->status) }}<br/><br/>
                    </div>
                </div>

                @if (isset($get_all_related_requests) && !empty($get_all_related_requests))
                    <div class="col-sm-12 col-md-3">
                        <div class="col-lg-12 mb-2">
                            <div class="list-group ">
                                <div class="p-1 list-group-item list-group-item-default">
                                    <small>
                                        <label class="text-center" style="border-bottom: 1px solid black; width:100%"><b>Parent & Related Requests</b></label><br>
                                        @foreach($get_all_related_requests as $idx=>$request)
                                            <a target="__new" href="{{ route('tf-bi-portal.submissionRequests.showMonitoring', $request->id??$request['id']) }}" id="request_list" class="link-primary">
                                                <div class="d-flex align-items-center">
                                                    <span class="fa fa-cube fa-fw"></span> 
                                                    <div class="flex-grow-1 ms-2">
                                                        <p class="mb-0 pb-0">
                                                            {{ ucwords($request->type??$request['type']) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach                                
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-sm-12 col-md-3">
                    <div class="text-justify">     
                        @if($monitoring_request->status == 'submitted')
                            <span class="text-success pull-right"> 
                                <strong>
                                    <span class="fa fa-check-square"></span>
                                    Request Submitted
                                </strong> 
                            </span><br>
                        @else
                            <span class="text-danger pull-right"> 
                                <strong>
                                    <span class="fa fa-times"></span>
                                    Request Not-Submitted
                                </strong>
                            </span><br>
                        @endif

                        @if($monitoring_request->status == 'submitted' && isset($monitoring_request_submitted))
                        @php
                            $dept_name = $monitoring_request_submitted->work_item->active_assignment->assigned_user->department->long_name ?? $monitoring_request_submitted->work_item->assignments[0]->assigned_user->department->long_name;
                        @endphp
                            <small>
                                @if($monitoring_request_submitted->has_generated_aip)
                                    <span class="text-success">
                                        Please note that your <b>{{ $monitoring_request->type.' Request' }}</b> has been completely processed{!! ucwords(' <b>@ TETFund ' . $dept_name . ' Department.</b>' ?? '.') !!}
                                        You are hereby notified to keep checking it status while the requesst is being processed for final approval. <br>
                                    </span>
                                @else
                                    <span class="text-danger"> 
                                        Please note that your <b>{{ $monitoring_request->type.' Request' }}</b> is currently being processed{!! ucwords(' <b>@ TETFund ' . $dept_name . ' Department.</b>' ?? '.') !!}
                                        Once final approval is completed, you will be notified as the status for this request becomes <b>Approved</b>.
                                    </span>
                                @endif          
                            </small>
                        @else
                            <small class="text-danger">
                                Please note that your <b>{{ $monitoring_request->type.' Request' }}</b> is yet to be submitted to TETFund.
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('tf-bi-portal::pages.monitoring.modal')
@stop

@section('side-panel')
<div class="card radius-5 border-top  border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">Beneficiary Monitoring</h5></div>
        <p class="small">
            Please reveiw details on your monitoring request to TETFund. Each monitoring request for various intervention stages are processed at the front line department precisely the Monitoring and Evaluation.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush