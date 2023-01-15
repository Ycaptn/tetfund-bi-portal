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

    @php

        if (array_key_exists('tp_nomination_sent_to', $astd_settings)) {
            $tp_nomination_sent_to = $astd_settings['tp_nomination_sent_to']->value;
        } else {
            $tp_nomination_sent_to = 'BI-desk-officer';
        }

        if (array_key_exists('tp_committee_considered_sent_to', $astd_settings)) {
            $tp_committee_considered_sent_to = $astd_settings['tp_committee_considered_sent_to']->value;
        } else {
            $tp_committee_considered_sent_to = 'BI-desk-officer';
        }

        if (array_key_exists('tp_approved_nomination_sent_to', $astd_settings)) {
            $tp_approved_nomination_sent_to = [$astd_settings['tp_approved_nomination_sent_to']->value];
        } else {
            $tp_approved_nomination_sent_to = ['BI-desk-officer', 'BI-head-of-institution'];
        }
        
        
    @endphp
    @if(isset(request()->view_type))
        @if(request()->view_type == 'committee_approved')
            @if($current_user->hasRole($tp_committee_considered_sent_to))
                Committee Considered Nomination
            @else
                Committee Approved
            @endif
        @elseif(request()->view_type == 'hoi_approved')
            @if($current_user->hasAnyRole($tp_approved_nomination_sent_to))
                Approved Nomination
            @endif
        @elseif(request()->view_type == 'committee_head_consideration')
            @if($current_user->hasRole('BI-TP-committee-head'))
                Considered Nomination
            @endif
        @endif
    @else
        @if($current_user->hasRole($tp_nomination_sent_to))
            Newly Submitted
        @elseif($current_user->hasRole('BI-head-of-institution'))
            Nomination Approval
        @elseif($current_user->hasAnyRole(['BI-TP-committee-member', 'BI-TP-committee-head']))
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
    @if ($current_user->hasAnyRole(['BI-TP-committee-head']) && isset(request()->view_type) && request()->view_type == 'committee_head_consideration')
        <a  class="btn btn-sm btn-primary btn_upload_committee_minutes_meetings"
            id="" 
            data-val="tp"
            title="Upload and avail to Desk-Officer a copy of TPNomination Committee most-recent minutes of Meetings" >
            <span class="fa fa-upload"></span> TPNomination Committee Minutes of Meeting
        </a>
    @endif
    {{-- @if ($current_user->hasAnyRole(['BI-desk-officer']))
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
                        @if($current_user->hasRole($tp_nomination_sent_to))
                        
                            {{-- appears for desk officer to preview newly submitted nomination --}}
                            <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}"
                                class="mb-3 btn btn-sm {{ (!isset(request()->view_type)) ? 'btn-primary' : 'btn-secondary'}}"
                                title="Preview newly submitted nomination details by scholars" ><i class="fas fa-bell"></i><b><sup class="fa-layers-counter text-danger" style="background-color:white; border-radius: 20%;">{{number_format($count_array_returned['desk_officer_newly_submitted'] ?? 0)}}</sup></b>
                                Newly Submitted
                            </a>
                        @endif

                        @if ($current_user->hasRole($tp_committee_considered_sent_to))
                            {{-- appears for desk officer to preview committee considered nomination --}}
                            <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}?view_type=committee_approved"
                                class="mb-3 btn btn-sm {{ (isset(request()->view_type) && request()->view_type == 'committee_approved') ? 'btn-primary' : 'btn-secondary'}}"
                                title="Preview TP Nomination(s) that have been Considered by TP Committee" ><i class="fas fa-bell"></i><b><sup class="fa-layers-counter text-danger" style="background-color:white; border-radius: 20%;">{{number_format($count_array_returned['desk_officer_committee_considered'] ?? 0)}}</sup></b>
                                Committee Considered Nomination
                            </a>
                        @endif

                        @if ($current_user->hasAnyRole($tp_approved_nomination_sent_to))

                            {{-- desk officer and HOI to preview finally approved nomination by HOI --}}
                            <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}?view_type=hoi_approved"
                            class="mb-3 btn btn-sm {{ (isset(request()->view_type) && request()->view_type == 'hoi_approved') ? 'btn-primary' : 'btn-secondary'}}"
                            title="Preview TP Nomination(s) that have been considered approved by Head of Institution" ><i class="fas fa-bell"></i><b><sup class="fa-layers-counter text-danger" style="background-color:white; border-radius: 20%;">{{number_format($count_array_returned['desk_officer_hoi_approved'] ?? 0)}}</sup></b>
                                Approved Nomination
                            </a>
                            
                        @endif

                            

                            
                        

                        @if ($current_user->hasAnyRole(['BI-TP-committee-member', 'BI-TP-committee-head']))
                            {{-- appears for all tp commitee me to preview newly submitted nomination --}}
                            <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}"
                                class="btn btn-sm {{ !isset(request()->view_type) ? 'btn-primary' : 'btn-secondary'}}"
                                title="Preview TP Nomination(s) forwarded by Desk-Officer and provide your consideration feedback to TPNomination committee head" ><i class="fas fa-bell"></i><b><sup class="fa-layers-counter text-danger" style="background-color:white; border-radius: 20%;">{{number_format($count_array_returned['committee_members_newly_submitted'] ?? 0)}}</sup></b>
                                Nomination Consideration
                            </a> 
                        @endif

                        @if ($current_user->hasRole('BI-TP-committee-head'))
                            {{-- appears for all tp commitee head and decide final consideration state --}}
                            <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}?view_type=committee_head_consideration&nomination_type=tp"
                                class="btn btn-sm {{ (isset(request()->view_type) && request()->view_type == 'committee_head_consideration') ? 'btn-primary' : 'btn-secondary'}}"
                                title="Preview TP Nomination(s) forwarded by Desk-Officer and take find decision on behalf of TPNomination committee" ><i class="fas fa-bell"></i><b><sup class="fa-layers-counter text-danger" style="background-color:white; border-radius: 20%;">{{number_format($count_array_returned['committee_members_considered_nomination'] ?? 0)}}</sup></b>
                                Considered Nomination
                            </a> 
                        @endif

                        @if($current_user->hasRole('BI-head-of-institution'))
                            {{-- appeear so HOI can approve nomnations --}}
                            <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}"
                                class="btn btn-sm {{ !isset(request()->view_type) ? 'btn-primary' : 'btn-secondary'}}"
                                title="Preview and manage Nomination request pending Head of Institution approval" ><i class="fas fa-bell"></i><b><sup class="fa-layers-counter text-danger" style="background-color:white; border-radius: 20%;">{{number_format($count_array_returned['hoi_nomination_approval'] ?? 0)}}</sup></b>
                                Nomination Approval
                            </a>

                            {{-- appears to show all approved nominations --}}
                             {{-- <a  href="{{ route('tf-bi-portal.t_p_nominations.index') }}?view_type=hoi_approved"
                                class="btn btn-sm {{ (isset(request()->view_type) && request()->view_type == 'hoi_approved') ? 'btn-primary' : 'btn-secondary'}}"
                                title="Preview TP Nomination(s) that have been considered approved by Head of Institution" ><i class="fas fa-bell"></i><b><sup class="fa-layers-counter text-danger" style="background-color:white; border-radius: 20%;">{{number_format($count_array_returned['hoi_approved_nominations'] ?? 0)}}</sup></b>
                                Approved Nomination
                            </a> --}}

                        @endif

                        @if($current_user->hasRole($tp_nomination_sent_to) && !isset(request()->view_type))
                            {{-- appears to desk-officer to forward all tp nominations to committee --}}
                            <a  class="mb-3 btn btn-sm btn-danger pull-right move_all_for_consideration text-white"
                                data-val="tp"
                                title="Move all TP Nomination(s) to TPNomination Committee for Consideration" >
                                <span class="fa fa-paper-plane"></span><sup>*</sup>
                                Move All for Consideration
                            </a>
                        @endif

                        @if($current_user->hasRole($tp_committee_considered_sent_to) && isset(request()->view_type) && request()->view_type == 'committee_approved')
                            {{-- appears for desk-officer to forward all tp nominations for approval --}}
                            <a  class="mb-3 btn btn-sm btn-danger pull-right move_all_for_approval text-white"
                                data-val="tp"
                                title="Move all TP Nomination(s) to HOI for Approval" >
                                <span class="fa fa-paper-plane"></span><sup>*</sup>
                                Move All for Approval
                            </a>
                        @endif
                        
                    </div>
                </p>
                @include('tf-bi-portal::pages.t_p_nominations.table')
                
            </div>
        
        </div>
    </div>

    @include('tf-bi-portal::pages.t_p_nominations.modal')
    
    @if($current_user->hasRole($tp_nomination_sent_to))
        @if(!isset(request()->view_type))
            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.desk_officer_nomination_to_committee_modal')
        @endif
    @endif
        
    @if($tp_committee_considered_sent_to && isset(request()->view_type) && request()->view_type == 'committee_approved')
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.desk_officer_nomination_to_hoi_modal')
    @endif
    
    @if($tp_approved_nomination_sent_to && isset(request()->view_type) && request()->view_type == 'hoi_approved')
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.hoi_approval_for_nomination_modal')
    @endif
    

    {{-- include approval by voting if user is an tp committee menber --}}
    @if ($current_user->hasAnyRole(['BI-TP-committee-head', 'BI-TP-committee-member']))
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.committee_approval_for_nomination_modal')
    @endif

    {{-- include tp commitee head to check committee menber --}}
    @if ($current_user->hasRole('BI-TP-committee-head'))
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.head_committee_to_members_vote_modal')
    @endif

    {{-- include approval for Head of Institution --}}
    {{-- @if ($current_user->hasRole('BI-head-of-institution'))
        @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.hoi_approval_for_nomination_modal')
    @endif --}}

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