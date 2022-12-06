@extends('layouts.app')

@section('title_postfix')
TSAS
@stop

@section('page_title')
TSAS
@stop

@section('page_title_suffix')
Nominations
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
        <div><h5 class="card-title">TSAS Nominations</h5></div>
        <p class="small">
            Staff from your institutions may request to be nominated for TSAS programs. Use this window to select staff from your institutions that will partipate in TSAS projects.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush