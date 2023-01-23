@php
    $control_id = $cdv_data_response->control_id;
    $data_set_group_list = [];
    $data_set_enable_search = true;
    $search_placeholder_text = 'Search TPNomination by first or last name';
    $data_set_enable_pagination = $cdv_data_response->paginate ? true : false;
    $page_number = $cdv_data_response->page_number;
    $pages_total = $cdv_data_response->pages_total;
    $result_count = $cdv_data_response->result_count;
        // +"pages_total": 2
        // +"paginate": true
        // +"page_number": 1
        // +"pages_total": 2
        // +"search_term": null
        // +"result_count": 4
        // +"control_id": "cdv_1665697478"
        // +"cards_html": """
        // +"page_limit": 2
    $div_id_name = $control_id.'-div-card-view';
    $base_index_url = url('tf-bi-portal/t_p_nominations/');
@endphp

@extends('layouts.app')

@section('app_css')
    @include('tf-bi-portal::pages.cardview.card-view-css')
@stop

@section('title_postfix')
TP Nominations
@stop

@section('page_title')
TP Nominations
@stop

@section('page_title_suffix')
All TP Nominations
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop

@section('page_title_buttons')
<a id="btn-new-mdl-aTNomination-modal" class="btn btn-sm btn-primary btn-new-mdl-aTNomination-modal pt-2">
    <i class="bx bx-book-add me-1"></i>New TP Nomination
</a>
{{-- @if (Auth()->user()->hasAnyRole(['','admin']))
    @include('tf-bi-portal::pages.t_p_nominations.bulk-upload-modal')
@endif --}}
@stop

@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 hidden-sm hidden-xs">
        {{-- Summary Row --}}
    </div>
    
    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body">
            @include('tf-bi-portal::pages.cardview.index')
        </div>
    </div>

    @include('tf-bi-portal::pages.t_p_nominations.modal')
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">TP Nominations</h5></div>
        <p class="small">
            Staff from your institutions may request to be nominated for TP programs. Use this window to select staff from your institutions that will partipate in TP projects.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    @include('tf-bi-portal::pages.cardview.card-view-js')
@endpush