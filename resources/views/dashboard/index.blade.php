@extends('layouts.app')

@section('title_postfix')
Beneficiary Dashboard
@stop

@section('page_title')
Beneficiary Dashboard
@stop

@section('page_title_suffix')
{{ \Auth::user()->full_name }}
@stop

@section('app_css')
@stop

@section('page_title_buttons')
@stop

@section('page_title_subtext')
@stop

@section('content')
    
    {{-- Load all available dashboards --}}
    @foreach(\FoundationCore::get_dashboards($organization) as $idx=>$dashboard)
        @include($dashboard->value)
    @endforeach

    <div class="card radius-5 border-top border-0 border-3 border-success">
        <div class="card-body">
            <div class="accordion" id="acc_main">
                <div class="accordion-item">
                    <h4 class="accordion-header" id="acc_platforms">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_platforms_div" aria-expanded="false" aria-controls="acc_platforms_div">
                            TETFund Official Communications
                        </button>
                    </h4>
                    <div id="acc_platforms_div" class="accordion-collapse collapse" aria-labelledby="acc_platforms" data-bs-parent="#acc_main" style="">
                        <div class="accordion-body">
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h4 class="accordion-header" id="acc_org_sites">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_org_sites_div" aria-expanded="false" aria-controls="acc_org_sites_div">
                            Upcoming Monitoring
                        </button>
                    </h4>
                    <div id="acc_org_sites_div" class="accordion-collapse collapse" aria-labelledby="acc_org_sites" data-bs-parent="#acc_main" style="">
                        <div class="accordion-body">
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h4 class="accordion-header" id="acc_ess">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_ess_div" aria-expanded="false" aria-controls="acc_ess_div">
                            Active Submissions
                        </button>
                    </h4>
                    <div id="acc_ess_div" class="accordion-collapse collapse" aria-labelledby="acc_ess_div" data-bs-parent="#acc_main" style="">
                        <div class="accordion-body">
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


@stop


@section('side-panel')
@stop

@push('page_scripts')
@endpush