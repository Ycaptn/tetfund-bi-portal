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
<a id="btn-sync-mdl-beneficiary-modal" class="btn btn-sm btn-primary btn-sync-mdl-beneficiary-modal">
    <i class="bx bx-book-add me-1"></i>Synchronize Beneficiary List
</a>
@stop

@section('content')
    
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            {{ $cdv_beneficiaries->render() }}
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
    {!! $cdv_beneficiaries->render_js() !!}
@endpush