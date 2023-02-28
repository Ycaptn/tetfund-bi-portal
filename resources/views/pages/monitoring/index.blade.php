@extends('layouts.app')

@section('title_postfix')
Monitoring
@stop

@section('page_title')
TETFund
@stop

@section('page_title_suffix')
Monitoring
@stop

@section('app_css')
@stop

@section('page_title_buttons')
@stop

@section('page_title_subtext')
@stop

@section('content')
    
    <div class="card radius-5 border-top border-0 border-3 border-success">
        <div class="card-body">
            <h3>All Monitoring Request</h3><hr>
            @include('tf-bi-portal::pages.monitoring.table')
            @include('tf-bi-portal::pages.monitoring.modal')
        </div>
    </div>

@stop


@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">TETFund Monitoring</h5></div>
        <p class="small">
            You can view your upcoming monitoring events with TETfund from this window or request monitoring for your projects. Click on the New Monitoring Request button to submit a request for monitoring from TETFund.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush