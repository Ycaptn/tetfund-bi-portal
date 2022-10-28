@extends('layouts.app')

@section('title_postfix')
Fund Availability
@stop

@section('page_title')
Fund Availability
@stop

@section('page_title_suffix')
{{ $selected_year }} Fund Availability Status
@stop

@section('app_css')
@stop

@section('page_title_buttons')
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
                <div class="col-sm-9">
                    <ul class="nav">
                        @php
                            $start_yr = date('Y');
                            $end_yr = date('Y')-5;
                        @endphp

                        @for($i=$start_yr;$i>=$end_yr;$i--)
                            <li role="presentation" class="{{ ($selected_year==$i)?'active':'' }}" style="margin-right: 5px; margin-top: 2px;">
                                <a class="btn btn-md btn-primary" href='{{ route("tf-bi-portal.fund-availability")."?year=".$i }}'>
                                    {{$i}}
                                </a>
                            </li>
                        @endfor
                    </ul>

                </div>
                
                <div class="col-sm-3">
                    @if (count($funding) > 0)
                        {{-- <a target="_blank" href="#" class="pull-right"> --}}
                            <button disabled='disabled' type="button" class="pull-right btn btn-primary btn-xs">
                                Allocation Letter
                            </button>
                        {{-- </a> --}}
                    @endif
                </div>
                
                <div class="col-sm-12 well well-sm">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <hr>
                                <h4>Allocation Year : {{$selected_year}}</h4>
                            <hr>
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Funding Description</th>
                                    <th>Allocation Code</th>
                                    <th>Allocated Amount</th>
                                    <th>Disbursed Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($funding as $detail)
                                    <tr>
                                        <td name='cdp_li_type_{{$detail->id}}' id='cdp_li_type_{{$detail->id}}'>
                                            {{ ucwords($detail->type) }}
                                        </td>

                                        <td name='cdp_li_desc_{{$detail->id}}' id='cdp_li_desc_{{$detail->id}}'>
                                            {{ $detail->funding_description }}
                                        </td>

                                        <td name='cdp_li_code_{{$detail->id}}' id='cdp_li_code_{{$detail->id}}'>
                                            {{ $detail->allocation_code }}
                                        </td>

                                        <td> ₦
                                            <span name='cdp_li_amt_{{$detail->id}}' id='cdp_li_amt_{{$detail->id}}'>
                                                {{number_format($detail->allocated_amount,2) }}
                                            </span>
                                        </td>

                                        <td> ₦
                                            <span name='cdp_li_dis_{{$detail->id}}' id='cdp_li_dis_{{$detail->id}}'>
                                                {{number_format(optional($detail)->disbursed_amount,2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
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
        <p class="small">
            Allocations are issued to your institution on an annual basis for the various intervention lines available to your institution. You may view availability of funds and the status of current interventions availble to your institution.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
@endpush