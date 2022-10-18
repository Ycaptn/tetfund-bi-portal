@php
    $control_id = $cdv_data_response->control_id;
    $data_set_group_list = [];
    $data_set_enable_search = true;
    $search_placeholder_text = 'Search submissions by Year of Intervention ';
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
    $base_index_url = url('tf-bi-portal/submissionRequests/');
@endphp

@extends('layouts.app')

@section('app_css')
    @include('tf-bi-portal::pages.cardview.card-view-css')
@stop

@section('title_postfix')
TETFund
@stop

@section('page_title')
TETFund
@stop

@section('page_title_suffix')
Submissions
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop

@section('page_title_buttons')
<a id="btn-new-mdl-submissionRequest-modal" class="btn btn-sm btn-primary btn-new-mdl-submissionRequest-modal" href="{{ route("tf-bi-portal.submissionRequests.create") }}">
    <i class="bx bx-book-add mr-1"></i>New Submission
</a>
@stop

@section('content')
    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 hidden-sm hidden-xs">
                {{-- Summary Row --}}
            </div>
            
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body">
                    @include('tf-bi-portal::pages.cardview.index')
                </div>
            </div>
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">TETFund Submissions</h5></div>
        <p class="small">
            Process and track your submissions to TETFund from this window. You may begin new submissions by clicking the new submission button.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    @include('tf-bi-portal::pages.cardview.card-view-js')
@endpush