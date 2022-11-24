
<div class='btn-group' role="group">
    <a data-toggle="tooltip" 
        title="Preview ASTD Nomination details" 
        data-val='{{$id}}' 
        class="btn-show-{{$type}}" href="#">
        <i class="fa fa-eye text-primary" style="opacity:80%"></i>
    </a> &nbsp; &nbsp;

    {{-- appears for desk officer only --}}
    @if($is_desk_officer_check == 0 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Forward  ASTD Nomination details to ASTD Committee" 
            data-val='{{$id}}$is_desk_officer_check' 
            class="btn-forward-{{$type}}" href="#">
            <i class="fa fa-paper-plane text-danger" style="opacity:80%"></i>
        </a> &nbsp; &nbsp;
    @endif

    {{-- appears for all astd committee members only --}}
    @if($is_desk_officer_check == 1 && auth()->user()->hasAnyRole(['bi-astd-commitee-head', 'bi-astd-commitee-member']))
        <a data-toggle="tooltip" 
            title="ASTD Nomination Committee Vote Approval Zone" 
            data-val='{{$id}}' 
            class="btn-committee-vote-modal" href="#">
            <i class="fa fa-vote-yea text-danger" style="opacity:80%"></i>
        </a> &nbsp; &nbsp;
    @endif

    {{-- appears for desk-officer after committee approved --}}
    @if($is_head_commitee_members_check==1 && $is_desk_officer_check_after_head_commitee_members==0 && auth()->user()->hasAnyRole(['bi-desk-officer']))
        <a data-toggle="tooltip" 
            title="Forward  ASTD Nomination details to HOI for Approval" 
            data-val='{{$id}}$is_desk_officer_check_after_head_commitee_members' 
            class="btn-forward-{{$type}}" href="#">
            <i class="fa fa-paper-plane text-danger" style="opacity:80%"></i>
        </a> &nbsp; &nbsp;
    @endif

    {{-- appears for all hoi approval only --}}
    @if($is_desk_officer_check_after_head_commitee_members == 1 && $is_head_of_institution_check == 0 && auth()->user()->hasAnyRole(['bi-hoi']))
        <a data-toggle="tooltip" 
            title="Head of Institution Approval for ASTD Nomination Request" 
            data-val='{{$id}}'
            class="btn-hoi-approval" href="#">
            <i class="fa fa-check-square text-danger" style="opacity:80%"></i>
        </a> &nbsp; &nbsp;
    @endif

</div>