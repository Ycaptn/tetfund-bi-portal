@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
All T S A S Nomination
@stop

@section('page_title')
All T S A S Nomination
@stop

@section('page_title_suffix')
    @if(isset(request()->view_type))
        @if(request()->view_type == 'committee_approved')
            @if($current_user->hasRole('bi-desk-officer'))
                Committee Considered Nomination
            @else
                Committee Approved
            @endif
        @elseif(request()->view_type == 'hoi_approved')
            @if($current_user->hasAnyRole(['bi-desk-officer', 'bi-head-of-institution' ]))
                Approved Nomination
            @endif
        @elseif(request()->view_type == 'committee_head_consideration')
            @if($current_user->hasRole('bi-tsas-committee-head'))
                Considered Nomination
            @endif
        @endif
    @else
        @if($current_user->hasRole('bi-desk-officer'))
            Newly Submitted
        @elseif($current_user->hasRole('bi-head-of-institution'))
            Nomination Approval
        @elseif($current_user->hasRole('bi-tsas-committee-member'))
            Nomination Consideration
        @endif
    @endif
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a> 
@stop

@section('page_title_buttons')
    {{-- @if ($current_user->hasAnyRole(['bi-desk-officer']))
        <a id="btn-new-mdl-tSASNomination-modal" class="btn btn-sm btn-primary btn-new-mdl-tSASNomination-modal">
            <i class="bx bx-book-add mr-1"></i>New T S A S Nomination
        </a>
    @endif --}}

    {{-- @include('tf-bi-portal::pages.t_s_a_s_nominations.bulk-upload-modal') --}}
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
                        @if($current_user->hasRole('bi-desk-officer'))

                        {{-- appears for desk officer to preview newly submitted nomination --}}
                            <a  href="{{ route('tf-bi-portal.t_s_a_s_nominations.index') }}"
                                class="btn btn-sm btn-primary"
                                title="Preview newly submitted nomination details by Scholars" >
                                Newly Submitted
                            </a>

                        {{-- appears for desk officer to preview committee considered nomination --}}
                            <a  href="{{ route('tf-bi-portal.t_s_a_s_nominations.index') }}?view_type=committee_approved"
                                class="btn btn-sm btn-primary"
                                title="Preview TSAS Nomination(s) that have been Considered by TSAS Committee" >
                                Committee Considered Nomination
                            </a>

                        {{-- desk officer and HOI to preview finally approved nomination by HOI --}}
                            <a  href="{{ route('tf-bi-portal.t_s_a_s_nominations.index') }}?view_type=hoi_approved"
                                class="btn btn-sm btn-primary"
                                title="Preview TSAS Nomination(s) that have been considered approved by Head of Institution" >
                                Approved Nomination
                            </a>
                        @endif

                        @if ($current_user->hasAnyRole(['bi-tsas-committee-member', 'bi-tsas-committee-head']))
                            {{-- appears for all tsas commitee me to preview newly submitted nomination --}}
                            <a  href="{{ route('tf-bi-portal.t_s_a_s_nominations.index') }}"
                                class="btn btn-sm btn-primary"
                                title="Preview TSAS Nomination(s) forwarded by Desk-Officer and provide your consideration feedback to TSASNomination committee head" >
                                Nomination Consideration
                            </a> 
                        @endif

                        @if ($current_user->hasRole('bi-tsas-committee-head'))
                            {{-- appears for all tsas commitee head and decide final consideration state --}}
                            <a  href="{{ route('tf-bi-portal.t_s_a_s_nominations.index') }}?view_type=committee_head_consideration"
                                class="btn btn-sm btn-primary"
                                title="Preview TSAS Nomination(s) forwarded by Desk-Officer and take find decision on behalf of TSASNomination committee" >
                                Considered Nomination
                            </a> 
                        @endif

                        @if($current_user->hasRole('bi-head-of-institution'))
                            {{-- appeear so HOI can approve nomnations --}}
                            <a  href="{{ route('tf-bi-portal.t_s_a_s_nominations.index') }}"
                                class="btn btn-sm btn-primary"
                                title="Preview and manage Nomination request pending Head of Institution approval" >
                                Nomination Approval
                            </a>

                            {{-- appears to show all approved nominations --}}
                             <a  href="{{ route('tf-bi-portal.t_s_a_s_nominations.index') }}?view_type=hoi_approved"
                                class="btn btn-sm btn-primary"
                                title="Preview TSAS Nomination(s) that have been considered approved by Head of Institution" >
                                Approved Nomination
                            </a>
                        @endif
                    </div>
                </p>
                @include('tf-bi-portal::pages.t_s_a_s_nominations.table')
                
            </div>
        
        </div>
    </div>

    @include('tf-bi-portal::pages.t_s_a_s_nominations.modal')
    
    @if($current_user->hasRole('bi-desk-officer'))
        @if(!isset(request()->view_type))
            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.desk_officer_nomination_to_committee_modal')
        @endif
        
        @if(isset(request()->view_type) && request()->view_type == 'committee_approved')
            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.desk_officer_nomination_to_hoi_modal')
        @endif

        @if(isset(request()->view_type) && request()->view_type == 'hoi_approved')
            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.hoi_approval_for_nomination_modal')
        @endif
        
    @endif

    {{-- include approval by voting if user is an tsas committee menber --}}
    @if ($current_user->hasAnyRole(['bi-tsas-committee-head', 'bi-tsas-committee-member']))
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.committee_approval_for_nomination_modal')
    @endif

    {{-- include tsas commitee head to check committee menber --}}
    @if ($current_user->hasRole('bi-tsas-committee-head'))
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.head_committee_to_members_vote_modal')
    @endif

    {{-- include approval for Head of Institution --}}
    @if ($current_user->hasRole('bi-head-of-institution'))
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