@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
All A S T D Nomination
@stop

@section('page_title')
All A S T D Nomination
@stop

@section('page_title_suffix')
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('dashboard') }}">
    <i class="bx bx-chevron-left"></i> Back to Dashboard
</a> 
@stop

@section('page_title_buttons')
    {{-- @if ($current_user->hasAnyRole(['bi-desk-officer']))
        <a id="btn-new-mdl-aSTDNomination-modal" class="btn btn-sm btn-primary btn-new-mdl-aSTDNomination-modal">
            <i class="bx bx-book-add mr-1"></i>New A S T D Nomination
        </a>
    @endif --}}

    {{-- @include('tf-bi-portal::pages.a_s_t_d_nominations.bulk-upload-modal') --}}
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
                        @if ($current_user->hasAnyRole(['bi-desk-officer', 'bi-hoi', 'bi-astd-commitee-head', 'bi-astd-commitee-member']))

                            <a  href="{{ route('tf-bi-portal.a_s_t_d_nominations.index') }}"
                                class="btn btn-sm btn-primary"
                                title="Preview newly submitted nomination details by scholars" >
                                Newly Submitted {{($current_user->hasAnyRole(['bi-astd-commitee-head', 'bi-astd-commitee-member'])) ? '|| Approval Zone' : ''}}
                            </a>

                            @if ($current_user->hasAnyRole(['bi-desk-officer', 'bi-astd-commitee-head', 'bi-astd-commitee-member']))
                                <a  href="{{ route('tf-bi-portal.a_s_t_d_nominations.index') }}?view_type=commitee_approved"
                                    class="btn btn-sm btn-primary me-2"
                                    title="Preview ASTD Nomination(s) that have been Considered by ASTD Commitee" >
                                    Commitee Approved
                                </a>
                            @endif

                            @if ($current_user->hasAnyRole(['bi-desk-officer', 'bi-hoi']))
                                <a  href="{{ route('tf-bi-portal.a_s_t_d_nominations.index') }}?view_type=hoi_approved"
                                    class="btn btn-sm btn-primary me-2"
                                    title="Preview ASTD Nomination(s) that have been Considered by Head of Institution" >
                                    HOI Approved
                                </a>
                            @endif

                            @if ($current_user->hasAnyRole(['bi-desk-officer']))
                                <a  href="{{ route('tf-bi-portal.a_s_t_d_nominations.index') }}?view_type=final_nominations"
                                    class="btn btn-sm btn-primary"
                                    title="Preview final nomination set to be binded to an existing Submission Request" >
                                    Final Nominations
                                </a>
                            @endif
                        @endif
                    </div>
                </p>
                @include('tf-bi-portal::pages.a_s_t_d_nominations.table')
                
            </div>
        
        </div>
    </div>

    {{-- @include('tf-bi-portal::pages.a_s_t_d_nominations.modal')  --}}

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