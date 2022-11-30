@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Nomination Request
@stop

@section('page_title')
Nomination Request
@stop

@section('page_title_suffix')
Nomination Request Details {{-- {{$nominationRequest->title}} --}}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.nomination_requests.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to Nomination Request List
</a>
@stop

@section('page_title_buttons')

    {{-- <a data-toggle="tooltip" 
        title="New" 
        data-val='{{$nominationRequest->id}}' 
        class="btn btn-sm btn-primary btn-new-mdl-nominationRequest-modal pt-2" href="#">
        <i class="fa fa-eye"></i> New
    </a>&nbsp;

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$nominationRequest->id}}' 
        class="btn btn-sm btn-primary btn-edit-mdl-nominationRequest-modal pt-2" href="#">
        <i class="fa fa-pencil-square-o"></i> Edit
    </a> --}}
@stop

@php
    $nomination_type_str = strtoupper($nominationRequest->type);
@endphp

@section('content')
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">

            <div class="row col-sm-12">
                <div class="col-sm-12">
                        @if(auth()->user()->hasAnyRole(['bi-desk-officer','bi-hoi']))
                            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.partials_nomination_invite.show')
                        @endif

                        @if(auth()->user()->hasAnyRole(['bi-staff']))
                            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.partials_request_nomination.show')
                        @endif
                </div>

                <div class="col-sm-12 col-md-7">
                    <div class="container">
                        <i class="fa fa-calendar-o fa-fw"></i> <strong>Created on </strong> {{ \Carbon\Carbon::parse($nominationRequest->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($nominationRequest->created_at)->diffForHumans() !!} <br>

                       <i class="fa fa-user fa-fw"></i><b>Beneficiary Staff Name : </b> 
                            {{ ($nominationRequest->user->full_name) }} <br/>
                        <i class="fa fa-envelope fa-fw"></i><b>Beneficiary Staff Email : </b> 
                            {{ ($nominationRequest->user->email) }} <br/>
                        <i class="fa fa-bank fa-fw"></i><b>Nomination Type : </b> {{ ucwords($nomination_type_str) }} Nomination <br/>
                        
                        <i class="fa fa-thumbs-up fa-fw"></i><b>Nomination Current Status:</b> {{ (!empty($nominationRequest->status)) ? strtoupper($nominationRequest->status) : 'N/A' }}<br/><br/> 
                    </div>
                </div>

                <div class="col-sm-12 col-md-5">
                    <div class="row alert alert-info">
                        <div class="col-sm-12" style="border-bottom: 1px solid gray;">
                            <h6 class="text-center"> 
                                <strong>
                                    Attachements
                                </strong>
                            </h6>
                        </div>
                        @if(isset($nomination_request_attachments) && count($nomination_request_attachments) > 0)
                            @foreach($nomination_request_attachments as $attachment)
                                <div class="container panel">
                                    <small>
                                        <a href="{{ route('tf-bi-portal.preview-attachement', $attachment->id) }}" target="__blank">
                                            <strong>{{$attachment->label}}</strong><br>
                                        </a>
                                        <i>{{$attachment->description}}</i>
                                    </small>
                                    <hr>
                                </div>
                            @endforeach
                        @else
                            <i> <small> No attachement provided </small> </i>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small">
            This is the help message.
            This is the help message.
            This is the help message.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush