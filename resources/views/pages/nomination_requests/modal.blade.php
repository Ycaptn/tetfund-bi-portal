{{-- include script if uers is deskofficer, HOI or committee head --}}

@if(auth()->user()->hasAnyRole(['BI-desk-officer','BI-astd-desk-officer', 'BI-head-of-institution']))

    @include('pages.nomination_requests.partial_sub_modals.nomination_invitation_modal')

@endif

@if(auth()->user()->hasAnyRole(['BI-staff']))

    @include('pages.nomination_requests.partial_sub_modals.request_nomination_modal')
    
@endif
