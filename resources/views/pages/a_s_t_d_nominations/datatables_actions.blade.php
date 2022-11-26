
<div class='btn-group' role="group">
    
    {{-- appears for desk officer only when request need to be forwarded to committee --}}
    @if($is_desk_officer_check == 0 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Preview and forward Nomination details to ASTDNomination Committee" 
            data-val='{{$id}}'
            class="btn-show-preview-nomination text-primary" href="#">
            <i class="fa fa-paper-plane" style="opacity:80%"></i> <span>Manage</span>
        </a>
    @endif

    {{-- appears to all astd committee members when they need to make their individual decisions --}}
    @if($is_desk_officer_check==1 && $is_average_commitee_members_check==0 && auth()->user()->hasAnyRole(['bi-astd-commitee-head', 'bi-astd-commitee-member']))
        <a data-toggle="tooltip" 
            title="ASTDNomination Committee Consideration Approval Zone" 
            data-val='{{$id}}'
            class="btn-committee-vote-modal text-primary" href="#">
            <i class="fa fa-check-square style="opacity:80%"></i> <span>Consideration</span>
        </a>
    @endif

     {{-- appears to all astd committee members when approval is accomplished for preview--}}
    @if($is_average_commitee_members_check==1 && $is_desk_officer_check_after_average_commitee_members_checked==0 && auth()->user()->hasAnyRole(['bi-astd-commitee-head', 'bi-astd-commitee-member']))
        <a data-toggle="tooltip" 
            title="Preview ASTDNomination approved but pending action by Desk-Officer" 
            data-val='{{$id}}'
            class="btn-committee-preview-modal text-primary" href="#">
            <i class="fa fa-eye" style="opacity:80%"></i> <span>Preview</span>
        </a>
    @endif

    {{-- appears for desk-officer after committee approved and set to forward to HOI --}}
    @if($is_average_commitee_members_check==1 && $is_desk_officer_check_after_average_commitee_members_checked==0 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Preview and Forward ASTD Nomination details to Head of Institution" 
            data-val='{{$id}}'
            class="btn-show-preview-nomination_to_hoi text-primary" href="#">
            <i class="fa fa-paper-plane" style="opacity:80%"></i> <span>Manage</span>
        </a>
    @endif

    {{-- appears for all hoi approval after forwarded by desk officer --}}
    @if($is_desk_officer_check_after_average_commitee_members_checked == 1 && $is_head_of_institution_check == 0 && auth()->user()->hasAnyRole(['bi-hoi']))
        <a data-toggle="tooltip" 
            title="Head of Institution Approval for ASTD Nomination Request" 
            data-val='{{$id}}'
            class="btn-hoi-approval-modal text-primary" href="#">
            <i class="fa fa-check-square" style="opacity:80%"></i> <span>Approval</span>
        </a>
    @endif

</div>