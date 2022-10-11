@extends('layouts.app')

@section('title_postfix')
Fund Availability
@stop

@section('page_title')
Fund Availability
@stop

@section('page_title_suffix')
Status
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

        </div>
    </div>


@stop


@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">Fund Availability</h5></div>
        <p class="small">
            Allocations are issued to your institution on an annual basis for the various intervention lines available to your institution. You may view availability of funds and the status of current interventions availble to your institution.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush