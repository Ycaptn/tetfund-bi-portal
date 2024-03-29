@extends('layouts.app')

@section('app_css')
    {!! $cdv_beneficiaries->render_css() !!}
@stop

@section('title_postfix')
Beneficiaries
@stop

@section('page_title')
Beneficiary
@stop

@section('page_title_suffix')
Institutions
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop

@section('page_title_buttons')
<button id="btn-sync-mdl-beneficiary-modal" class="btn btn-sm btn-primary  btn-sync-mdl-beneficiary-modal">
    <i class="bx bx-book-add me-1"></i>Synchronize Beneficiary List
</button>
@stop

@section('content')
    
    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body">
            {{ $cdv_beneficiaries->render() }}
        </div>
    </div>

    @include('tf-bi-portal::pages.beneficiaries.modal')

@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small text-justify">
            This page provides a central location for managing all beneficiary institutions and their user accounts. From here, you can preview detail belonging to beneficiaries as well as create, edit, and delete beneficiary users, and also view a list of all existing beneficiaries & users.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    {!! $cdv_beneficiaries->render_js() !!}
@endpush