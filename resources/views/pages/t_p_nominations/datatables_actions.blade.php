
<div class='btn-group' role="group">
    
    {{-- appears for desk officer only when request need to be forwarded to committee --}}
    @if($is_desk_officer_check == 0 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Preview and forward Nomination details to TPNomination Committee" 
            data-val='{{$id}}'
            class="btn-show-preview-nomination text-primary" href="#">
            <i class="fa fa-paper-plane" style="opacity:80%"></i> <span>Manage</span>
        </a>
    @endif

    {{-- appears to all tp committee members when they need to make their individual decisions --}}
    @if($is_desk_officer_check==1 && $is_average_commitee_members_check==0 && auth()->user()->hasAnyRole(['bi-tp-commitee-head', 'bi-tp-commitee-member']))
        <a data-toggle="tooltip" 
            title="TPNomination Committee Consideration Approval Zone" 
            data-val='{{$id}}'
            class="btn-committee-vote-modal text-primary" href="#">
            <i class="fa fa-check-square style="opacity:80%"></i> <span>Consideration</span>
        </a>
    @endif

     {{-- appears to all tp committee members when approval is accomplished for preview--}}
    @if($is_average_commitee_members_check==1 && $is_desk_officer_check_after_average_commitee_members_checked==0 && auth()->user()->hasAnyRole(['bi-tp-commitee-head', 'bi-tp-commitee-member']))
        <a data-toggle="tooltip" 
            title="Preview TPNomination approved but pending action by Desk-Officer" 
            data-val='{{$id}}'
            class="btn-committee-preview-modal text-primary" href="#">
            <i class="fa fa-eye" style="opacity:80%"></i> <span>Preview</span>
        </a>
    @endif

    {{-- appears for desk-officer after committee approved and set to forward to HOI --}}
    @if($is_average_commitee_members_check==1 && $is_desk_officer_check_after_average_commitee_members_checked==0 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Preview and forward TPNomination details to Head of Institution" 
            data-val='{{$id}}'
            class="btn-show-preview-nomination_to_hoi text-primary" href="#">
            <i class="fa fa-paper-plane" style="opacity:80%"></i> <span>Manage</span>
        </a>
    @endif

    {{-- appears for all hoi approval after forwarded by desk officer --}}
    @if($is_desk_officer_check_after_average_commitee_members_checked == 1 && $is_head_of_institution_check == 0 && auth()->user()->hasAnyRole(['bi-hoi']))
        <a data-toggle="tooltip" 
            title="Head of Institution Approval for TP Nomination Request" 
            data-val='{{$id}}'
            class="btn-hoi-approval-modal text-primary" href="#">
            <i class="fa fa-check-square" style="opacity:80%"></i> <span>Approval</span>
        </a>
    @endif

     {{-- appears to HOI when approval is accomplished for preview --}}
    @if($is_head_of_institution_check==1 && $head_of_institution_checked_status=='approved' && $is_set_for_final_submission==0 && auth()->user()->hasRole('bi-hoi'))
        <a data-toggle="tooltip" 
            title="Preview HOI TPNomination approved but pending action by Desk-Officer" 
            data-val='{{$id}}'
            class="btn-hoi-approval-preview-modal text-primary" href="#">
            <i class="fa fa-eye" style="opacity:80%"></i> <span>Preview</span>
        </a>
    @endif

    {{-- appears for desk officer only when request is approved by HOI and not yet binded --}}
    @if($is_head_of_institution_check==1 && $head_of_institution_checked_status=='approved' && $is_set_for_final_submission==0 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Preview and bind TPNomination details to a prepared Tetfund Submission" 
            data-val='{{$id}}'
            class="btn-show-preview-binding text-primary" href="#">
            <i class="fa fa-chain" style="opacity:80%"></i> <span>Binding</span>
        </a>
    @endif

    {{-- appears for desk officer only when request is approved by HOI and not yet binded --}}
    @if($is_head_of_institution_check==1 && $is_set_for_final_submission==1 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Preview and alter binding submission for selected TPNomination details" 
            data-val='{{$id}}'
            class="btn-show-preview-binding text-primary" href="#">
            <i class="fa fa-chain" style="opacity:80%"></i> <span>Alter Binding</span>
        </a>
    @endif

</div>