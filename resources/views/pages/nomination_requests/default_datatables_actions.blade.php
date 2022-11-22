{{-- determining action buttons view to be rendered --}}

@if ($type == strtolower('astd'))

    @include('tf-bi-portal::pages.a_s_t_d_nominations.datatables_actions')

@elseif ($type == strtolower('tp')) 

    @include('tf-bi-portal::pages.t_p_nominations.datatables_actions')

@elseif ($type == strtolower('ca'))

    @include('tf-bi-portal::pages.c_a_nominations.datatables_actions')

@elseif ($type == strtolower('tsas'))
    
    @include('tf-bi-portal::pages.t_s_a_s_nominations.datatables_actions')

@endif