@extends('layouts.app')

@section('title_postfix')
Fund Availability Status
@stop

@section('page_title')
Fund Availability Status
@stop

@section('page_title_suffix')
{{ $selected_year }}
@stop

@section('app_css')
@stop

@section('page_title_buttons')
@if (count($funding) > 0)
    {{-- <a target="_blank" href="#" class="pull-right"> --}}
        <button disabled='disabled' type="button" class="pull-right btn btn-primary btn-sm">
            Allocation Letter
        </button>
    {{-- </a> --}}
@endif
@stop

@section('page_title_subtext')
@stop

@section('content')
    
    <div class="card radius-5 border-top border-0 border-3 border-success">
        <div class="card-body">
            <div class="col-sm-12">
                {{-- <span style="margin:20px;font-weight:bold">
                    Please consult the official Fund Allocation Letter from TETFund to confirm your fund availability.
                </span> --}}
            </div>
            <div class="row col-sm-12">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        @php
                            $start_yr = date('Y');
                            $end_yr = date('Y')-5;
                        @endphp

                        @for($i=$start_yr;$i>=$end_yr;$i--)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{($selected_year==$i)?'active':''}}" href="{{ route("tf-bi-portal.fund-availability")."?year=".$i }}" >
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon">
                                            <i class="bx bx-calendar font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">{{$i}}</div>
                                    </div>
                                </a>
                            </li>
                        @endfor
                    </ul>
                </div>
                                
                <div class="col-sm-12 well well-sm mt-3">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Intervention Line</th>
                                    <th>Funding Description</th>
                                    {{-- <th>Allocation Code</th> --}}
                                    <th>Allocated Amount</th>
                                    {{-- <th>Disbursed Amount</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($funding) > 0)
                                @foreach ($funding as $detail)
                                    <tr>
                                        <td name='cdp_li_type_{{$detail->id}}' id='cdp_li_type_{{$detail->id}}'>
                                            {{ ucwords($detail->type) }}
                                        </td>

                                        <td name='cdp_li_line_{{$detail->id}}' id='cdp_li_line_{{$detail->id}}'>
                                            {{ ucwords(optional($detail->intervention_beneficiary_type)->name) }}
                                        </td>

                                        <td name='cdp_li_desc_{{$detail->id}}' id='cdp_li_desc_{{$detail->id}}'>
                                            {{ $detail->funding_description }}
                                        </td>

                                        {{-- <td name='cdp_li_code_{{$detail->id}}' id='cdp_li_code_{{$detail->id}}'>
                                            {{ $detail->allocation_code }}
                                        </td> --}}

                                        <td> ₦
                                            <span name='cdp_li_amt_{{$detail->id}}' id='cdp_li_amt_{{$detail->id}}'>
                                                {{number_format($detail->allocated_amount,2) }}
                                            </span>
                                        </td>

                                        {{-- <td> ₦
                                            <span name='cdp_li_dis_{{$detail->id}}' id='cdp_li_dis_{{$detail->id}}'>
                                                {{number_format(optional($detail)->disbursed_amount,2) }}
                                            </span>
                                        </td> --}}
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center text-danger">Funding allocation not available online for the selected year.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop


@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">Fund Availability</h5></div>
        <p class="small text-justify">
            Allocations are issued to your institution on an annual basis for the various intervention lines available to your institution. You may view availability of funds and the status of current interventions availble to your institution.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush