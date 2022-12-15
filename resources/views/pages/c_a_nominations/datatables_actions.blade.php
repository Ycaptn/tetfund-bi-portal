
<div class='btn-group' role="group">
    
    {{-- appears for desk officer only when request need to be forwarded to committee --}}
    @if($is_desk_officer_check == 0 && auth()->user()->hasAnyRole(['BI-desk-officer']))
        <a data-toggle="tooltip" 
            title="Preview and forward Nomination details to CANomination Committee" 
            data-val='{{$id}}'
            class="btn-show-preview-nomination btn btn-info btn-sm text-white" href="#">
            <i class="fa fa-paper-plane" style="opacity:80%"></i> <span>Move for Consideration</span>
        </a>
    @endif

    {{-- appears to all ca committee members when they need to make their individual decisions --}}
    @if($is_desk_officer_check==1 && $is_average_committee_members_check==0 && auth()->user()->hasAnyRole(['BI-CA-committee-head', 'BI-CA-committee-member']) && !isset(request()->view_type))
        <a data-toggle="tooltip" 
            title="CANomination Committee Consideration Approval Zone" 
            data-val='{{$id}}'
            class="btn-committee-vote-modal btn btn-info btn-sm text-white" href="#">
            <i class="fa fa-check-square style="opacity:80%"></i> <span>Consideration</span>
        </a> &nbsp; &nbsp;
    @endif

    {{-- appears for committee head only to finalize decision --}}
    @if($is_desk_officer_check==1 && $is_average_committee_members_check==0 && auth()->user()->hasRole('BI-CA-committee-head') && (isset(request()->view_type) && request()->view_type == 'committee_head_consideration' ))
        <a data-toggle="tooltip" 
            title="CANomination committee head finalizing consideration" 
            data-val='{{$id}}'
            class="btn-committee-head-modal btn btn-info btn-sm text-white" href="#">
            <i class="fa fa-paper-plane style="opacity:80%"></i> <span>Nomination Decision</span>
        </a>
    @endif

     {{-- appears to all ca committee members when approval is accomplished for preview--}}
    @if($is_average_committee_members_check==1 && $is_desk_officer_check_after_average_committee_members_checked==0 && auth()->user()->hasAnyRole(['BI-CA-committee-head', 'BI-CA-committee-member']))
        <a data-toggle="tooltip" 
            title="Preview CANomination considered but pending action by Desk-Officer" 
            data-val='{{$id}}'
            class="btn-committee-preview-modal btn btn-info btn-sm text-white" href="#">
            <i class="fa fa-eye" style="opacity:80%"></i> <span>Preview</span>
        </a>
    @endif

    {{-- appears for desk-officer after committee approved and set to forward to HOI --}}
    @if($is_average_committee_members_check==1 && $is_desk_officer_check_after_average_committee_members_checked==0 && auth()->user()->hasRole('BI-desk-officer'))
        <a data-toggle="tooltip" 
            title="Preview and forward CANomination details to Head of Institution" 
            data-val='{{$id}}'
            class="btn-show-preview-nomination_to_hoi btn btn-info btn-sm text-white" href="#">
            <i class="fa fa-paper-plane" style="opacity:80%"></i> <span>Move for Approval</span>
        </a>
    @endif

    {{-- appears for all hoi approval after forwarded by desk officer --}}
    @if($is_desk_officer_check_after_average_committee_members_checked == 1 && $is_head_of_institution_check == 0 && auth()->user()->hasRole('BI-head-of-institution'))
        <a data-toggle="tooltip" 
            title="Head of Institution Approval for CA Nomination Request" 
            data-val='{{$id}}'
            class="btn-hoi-approval-modal btn btn-info btn-sm text-white" href="#">
            <i class="fa fa-check-square" style="opacity:80%"></i> <span>Approval</span>
        </a>
    @endif

     {{-- appears to HOI when approval is accomplished for preview --}}
    @if($is_head_of_institution_check==1 && $head_of_institution_checked_status=='approved' && $is_set_for_final_submission==0 && auth()->user()->hasAnyRole(['BI-head-of-institution', 'BI-desk-officer']))
        <a data-toggle="tooltip" 
            title="Preview HOI CANomination approved but pending action by Desk-Officer" 
            data-val='{{$id}}'
            class="btn-hoi-approval-preview-modal btn btn-info btn-sm text-white" href="#">
            <i class="fa fa-eye" style="opacity:80%"></i> <span>Preview</span>
        </a>
    @endif

</div>