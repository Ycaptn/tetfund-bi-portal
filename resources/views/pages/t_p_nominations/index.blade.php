@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
All T P Nomination
@stop

@section('page_title')
All T P Nomination
@stop

@section('page_title_suffix')
    @if(isset(request()->view_type))
        @if(request()->view_type == 'committee_approved')
            Committee Approved
        @elseif(request()->view_type == 'hoi_approved')
            HOI Approved
        @elseif(request()->view_type == 'final_nominations')
            Binded Nomination
        @else
            Newly Submitted
        @endif
    @else
        Newly Submitted
    @endif
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a> 
@stop

@section('page_title_buttons')
    {{-- @if ($current_user->hasAnyRole(['bi-desk-officer']))
        <a id="btn-new-mdl-tPNomination-modal" class="btn btn-sm btn-primary btn-new-mdl-tPNomination-modal">
            <i class="bx bx-book-add mr-1"></i>New T P Nomination
        </a>
    @endif --}}

    {{-- @include('tf-bi-portal::pages.t_p_nominations.bulk-upload-modal') --}}
@stop


@section('content')
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 hidden-sm hidden-xs">
        {{-- Summary Row --}}
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <p>
                    <div class="col-sm-12">
                        @if ($current_user->hasAnyRole(['bi-desk-officer', 'bi-head-of-institution', 'bi-tp-committee-head', 'bi-tp-committee-member']))

                            <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}"
                                class="btn btn-sm btn-primary"
                                title="Preview newly submitted nomination details by scholars" >
                                Newly Submitted {{($current_user->hasAnyRole(['bi-tp-committee-head', 'bi-tp-committee-member'])) ? '|| Approval Zone' : ''}}
                            </a>

                            @if ($current_user->hasAnyRole(['bi-desk-officer', 'bi-tp-committee-head', 'bi-tp-committee-member']))
                                <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}?view_type=committee_approved"
                                    class="btn btn-sm btn-primary me-2"
                                    title="Preview TP Nomination(s) that have been Considered by TP Committee" >
                                    Committee Approved
                                </a>
                            @endif

                            @if ($current_user->hasAnyRole(['bi-desk-officer', 'bi-head-of-institution']))
                                <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}?view_type=hoi_approved"
                                    class="btn btn-sm btn-primary me-2"
                                    title="Preview TP Nomination(s) that have been Considered by Head of Institution" >
                                    HOI Approved
                                </a>
                            @endif
                        @endif
                    </div>
                </p>
                @include('tf-bi-portal::pages.t_p_nominations.table')
                
            </div>
        
        </div>
    </div>

    @include('tf-bi-portal::pages.t_p_nominations.modal')
    
    @if(auth()->user()->hasRole('bi-desk-officer'))
        @if(!isset(request()->view_type))
            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.desk_officer_nomination_to_committee_modal')
        @endif
        
        @if(isset(request()->view_type) && request()->view_type == 'committee_approved')
            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.desk_officer_nomination_to_hoi_modal')
        @endif
        
    @endif

    {{-- include approval by voting if user is an tp committee menber --}}
    @if (auth()->user()->hasRole('bi-tp-committee-member'))
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.committee_approval_for_nomination_modal')
    @endif

    {{-- include astd commitee head to check committee menber --}}
    @if (auth()->user()->hasRole('bi-astd-committee-head'))
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.head_committee_to_members_vote_modal')
    @endif

    {{-- include approval for Head of Institution --}}
    @if (auth()->user()->hasRole('bi-head-of-institution'))
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.hoi_approval_for_nomination_modal')
    @endif

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