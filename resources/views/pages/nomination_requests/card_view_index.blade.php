@extends('layouts.app')

@section('app_css')
    {!! $cdv_nomination_requests->render_css() !!}
@stop

@section('title_postfix')
TETFund
@stop

@section('page_title')
TETFund
@stop

@section('page_title_suffix')
Nomination Requests
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a>
@stop

@section('page_title_buttons')
    @if (auth()->user()->hasAnyRole(['bi-desk-officer','bi-hoi']))
        <a data-toggle="tooltip" 
            title="Nomination Invitation" 
            class="btn btn-sm btn-primary  btn-new-mdl-nominationInvitation-modal me-1" href="#">
            <i class="bx bx-user mr-1"></i> Nomination Invite
        </a>
        <a data-toggle="tooltip" 
            title="Bulk Nomination Invitation" 
            class="btn btn-sm btn-primary  btn-bulk-new-mdl-nominationInvitation-modal me-1" href="#">
            <i class="fas fa-users mr-1"></i> Bulk Invitation
        </a>
    @endif
    
    @if (auth()->user()->hasAnyRole(['bi-staff']))
        <a data-toggle="tooltip" 
            title="Request Nomination" 
            class="btn btn-sm btn-primary  btn-new-mdl-request_nomination-modal me-1" href="#">
            <i class="bx bx-book-add mr-1"></i> Request Nomination
        </a>
    @endif
@stop

@section('content')
    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body">
            {{ $cdv_nomination_requests->render() }}

            @include('tf-bi-portal::pages.nomination_requests.modal')
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">TETFund Nomination Request</h5></div>
        @if (auth()->user()->hasAnyRole(['bi-desk-officer','bi-hoi']))
            <p class="small text-justify">
                Invite, receive and track nomination requests from this window. You may invite staff for new nomination by clicking the <strong>Nomination Invite</strong> button.
            </p>
        @endif
    
        @if (auth()->user()->hasAnyRole(['bi-staff']))
            <p class="small text-justify">
                Submit and track nomination requests from this window. You may begin new nomination request by clicking the <strong>Request Nomination</strong> button.
            </p>
        @endif
    </div>
</div>
@stop

@push('page_scripts')
    {!! $cdv_nomination_requests->render_js() !!}
@endpush