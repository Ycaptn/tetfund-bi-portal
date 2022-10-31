

@if(auth()->user()->hasAnyRole(['bi-desk-officer', 'bi-hoi']))

    @include('pages.nomination_requests.partial_sub_modals.nomination_invitation_modal')

@endif

@if(auth()->user()->hasAnyRole(['bi-staff']))

    @include('pages.nomination_requests.partial_sub_modals.request_nomination_modal')
    
@endif
