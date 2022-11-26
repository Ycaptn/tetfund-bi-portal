
<div class='btn-group' role="group">
    
    {{-- appears for desk officer only when request need to be forwarded to committee --}}
    @if($is_desk_officer_check == 0 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Preview ASTD Nomination details" 
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
            <i class="fa fa-paper-plane" style="opacity:80%"></i> <span>Consideration</span>
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

    {{-- appears for desk-officer after committee approved --}}
    @if($is_average_commitee_members_check==1 && $is_desk_officer_check_after_average_commitee_members_checked==0 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Forward  ASTD Nomination details to HOI for Approval" 
            data-val='{{$id}}$is_desk_officer_check_after_average_commitee_members_checked' 
            class="btn-forward-{{$type}}" href="#">
            <i class="fa fa-paper-plane text-danger" style="opacity:80%"></i>
        </a> &nbsp; &nbsp;
    @endif

    {{-- appears for all hoi approval only --}}
    @if($is_desk_officer_check_after_average_commitee_members_checked == 1 && $is_head_of_institution_check == 0 && auth()->user()->hasAnyRole(['bi-hoi']))
        <a data-toggle="tooltip" 
            title="Head of Institution Approval for ASTD Nomination Request" 
            data-val='{{$id}}'
            class="btn-hoi-approval" href="#">
            <i class="fa fa-check-square text-danger" style="opacity:80%"></i>
        </a> &nbsp; &nbsp;
    @endif

</div>