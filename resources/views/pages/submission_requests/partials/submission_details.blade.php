@php
    $years_str = $submissionRequest->intervention_year1;
    // merged years, unique years & sorted years
    if (isset($years) && count($years) > 1) {
        $years_detail = array_values(array_unique($years));
        rsort($years_detail);
        if (count($years_detail) > 1) {
            $years_detail[count($years_detail) - 1] = ' and ' . $years_detail[count($years_detail) - 1];
            $years_str = implode(", ", $years_detail);
            $years_str = substr($years_str, 0,strrpos($years_str,",")) . $years_detail[count($years_detail) - 1];
        }
    }
@endphp


{{-- allocation preview modal --}}
<div class="modal fade" id="mdl-submissionRequestAllocationAmount-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-submissionRequestAllocationAmount-modal-title" class="modal-title">Preview Allocation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="col-sm-12">
                    <strong>SUBMISSION INTERVENTION YEAR(s) : </strong> [ {{ $years_str }} ] <hr>
                </div>
                <div class="row col-sm-12 m-3">
                    <div class="row table-responsive">
                        <table class="col-sm-12 table table-bordered">
                            <thead>
                                <tr>
                                    <th> S/N </th>
                                    <th> Year </th>
                                    <th> Type </th>
                                    <th> Allocation Code </th>
                                    <th> Allocated Amount </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sn_counter = 0;
                                @endphp

                                @if(isset($submission_allocations) && count($submission_allocations) > 0)
                                    @foreach($submission_allocations as $sub_allocation)
                                        <tr>
                                            <td> {{ $sn_counter += 1 }}. </td>
                                            <td> {{ $sub_allocation->year }} </td>
                                            <td>    
                                                {{ ucwords($sub_allocation->type) }} - 
                                                {{ $sub_allocation->allocation_type }} 
                                            </td>
                                            <td> {{ $sub_allocation->allocation_code }} </td>
                                            <td> &#8358; 
                                                {{ number_format((isset($sub_allocation->allocated_amount) ? $sub_allocation->allocated_amount : 0), 2) }} 
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                <tr>
                                    <td class="text-right" colspan="4" style="text-align: right;"><strong>Allocation(s) Sum Total <span class="fa fa-arrow-right"></span> </strong></td>
                                    <td><strong>&#8358; {{ number_format((isset($fund_available) ? $fund_available : 0), 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-submissionRequestAllocationAmount-modal">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>

        </div>
    </div>
</div>


<div class="col-sm-12">
    <div class="container">
        {{-- <i class="fa fa-briefcase fa-fw"></i> <b>File_Number:</b> &nbsp; ______ {{ optional($submissionRequest)->file_number }} <br/> --}}
        <i class="fa fa-calendar-o fa-fw"></i> <strong>Created on </strong> {{ \Carbon\Carbon::parse($submissionRequest->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($submissionRequest->created_at)->diffForHumans() !!} </span> <br />

        <i class="fa fa-bank fa-fw"></i> <b>{{ ucwords($intervention->type) }} Intervention &nbsp; - &nbsp; </b> &nbsp; {{ $intervention->name }} <br/>
        <i class="fa fa-crosshairs fa-fw"></i> <b>Intervention Year(s) &nbsp; - &nbsp; </b> &nbsp; {{ $years_str }} <br/>
        <i class="fa fa-money fa-fw"></i> <b>Total Allocated Amount &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format((isset($fund_available) ? $fund_available : 0), 2) }} <br/>
        <i class="fa fa-money fa-fw"></i> <b>Amount Requested &nbsp; - &nbsp; </b> &nbsp; &#8358; {{ number_format($submissionRequest->amount_requested, 2) }} <br/>
        <i class="fa fa-thumbs-up fa-fw"> </i><b>Current Stage &nbsp; - &nbsp; </b> &nbsp; {{ strtoupper($submissionRequest->status) }}<br/><br/>

        {{-- @include('tf-bi-portal::pages.submission_requests.show_fields') --}}
    </div>
</div>