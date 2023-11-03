    @php
        $nominationRequestParentSubmission = $nominationRequest->submission_request;
    @endphp

    @if($nominationRequest->status == 'pending')
        {{-- pending nomination request --}}
        
        <div class="row alert alert-warning">
            <div class="col-md-8">
                <i class="icon fa fa-warning"></i> &nbsp; &nbsp;
                <strong>{{ $nomination_type_str }} NOMINATION REQUEST :</strong> 
                <ul>
                    <li>This request was completed on
                        <strong>
                            {{ \Carbon\Carbon::parse($nominationRequest->created_at)->format('l jS F Y') }}.
                        </strong>
                    </li>
                    <li>This <strong>nomination request</strong> has a <strong>pending approval </strong> status! </li>
                    <li>
                        {!! (isset($nominationRequest->details_submitted) && $nominationRequest->details_submitted == true) ? 
                            "The Nomination Details has been <b>submitted</b> by the nominee" : 
                            "The Nomination Details has <b>not been submitted</b> by the nominee"
                        !!}
                    </li>
                </ul>
            </div>
            <div class="col-md-4 text-center">
                <span class="text-danger">
                    <strong>
                        REQUEST PENDING APPROVAL 
                    </strong>
                </span><br>
            </div>
        </div>

    @elseif($nominationRequest->status == 'declined')
        {{-- declined nomination request --}}
        
        <div class="row alert alert-danger">
            <div class="col-md-8">
                <i class="icon fa fa-warning"></i> &nbsp; &nbsp;
                <strong>{{ $nomination_type_str }} NOMINATION REQUEST:</strong> 
                <ul>
                    <li>This request was completed on
                        <strong>
                            {{ \Carbon\Carbon::parse($nominationRequest->created_at)->format('l jS F Y') }}.
                        </strong>
                    </li>
                    <li>This <strong>nomination request</strong> has been <strong> declined </strong>! </li>
                </ul>
            </div>
            <div class="col-md-4 text-center">
                <span class="text-danger">
                    <span class="fa fa-times"></span>
                    <strong>
                        REQUEST DECLINED 
                    </strong>
                </span><br>
            </div>
        </div>

    @elseif($nominationRequest->status == 'defered')
        {{-- deffered nomination request --}}
        
        <div class="row alert alert-warning">
            <div class="col-md-8">
                <i class="icon fa fa-warning"></i> &nbsp; &nbsp;
                <strong>{{ $nomination_type_str }} NOMINATION REQUEST:</strong> 
                <ul>
                    <li>This request was completed on
                        <strong>
                            {{ \Carbon\Carbon::parse($nominationRequest->created_at)->format('l jS F Y') }}.
                        </strong>
                    </li>
                    <li>This <strong>nomination request</strong> has a <strong>defered </strong>! </li>
                    <li>This <strong>nomination request</strong> is not totally declined, it <strong>may be approved</strong> in due time! </li>
                </ul>
            </div>
            <div class="col-md-4 text-center">
                <span class="text-primary">
                    <span class="fa fa-arrow-up"></span>
                    <strong>
                        REQUEST DEFERED 
                    </strong>
                </span><br>
            </div>
        </div>
    
    @elseif($nominationRequest->status == 'approved')
        {{-- approved nmination request --}}
         
        <div class="row alert alert-success">
            <div class="col-md-8">
                <i class="icon fa fa-warning"></i> &nbsp; &nbsp;
                <strong>{{ $nomination_type_str }} NOMINATION REQUEST:</strong> 
                <ul>
                    <li>The submission was completed on
                        <strong>
                            {{ \Carbon\Carbon::parse($nominationRequest->created_at)->format('l jS F Y') }}.
                        </strong>
                    </li>
                    @if($nominationRequest->tsas_submission != null || $nominationRequest->ca_submission != null || $nominationRequest->tp_submission != null)
                        <li><strong>{{ $nomination_type_str }}</strong> Nomination form data <strong>completed!</strong></li>
                    @else
                        <li>Submit your <strong>Nomination Details</strong> by clicking on the button <strong> '{{ $nomination_type_str }} Nomination Form'</strong> to complete this process!</li>
                    @endif
                </ul>
            </div>
            <div class="col-md-4">
                <div class="col-sm-12">
                    <span class="pull-right">
                        @if($nominationRequest->tsas_submission != null || $nominationRequest->ca_submission != null || $nominationRequest->tp_submission != null)
                            <strong class="text-success">
                                <span class="fa fa-check-square"></span>
                                Submitted Nomination Data
                            </strong><br>
                        @endif

                        @if($nominationRequest->status == 'approved' && $nominationRequest->head_of_institution_checked_status == 'approved')
                            <strong class="text-success">
                                <span class="fa fa-check-square"></span>
                                Considered For Submission
                            </strong><br>
                        @elseif($nominationRequest->status == 'declined' || $nominationRequest->head_of_institution_checked_status == 'declined')
                            <strong class="text-danger">
                                <span class="fa fa-times"></span>
                                Nomination Suspended or Rejected
                            </strong><br>
                        @else
                            <span class="fa fa-calendar-o text-danger"></span>
                            <strong class="text-danger">
                                Pending Consideration
                            </strong><br>
                        @endif

                        @if(!empty($nominationRequest->bi_submission_request_id))

                            @if(!empty($nominationRequestParentSubmission) && $nominationRequestParentSubmission->status == 'recalled' )
                                <strong class="">
                                    <span class="fa fa-info-circle"></span>
                                    Recalled From TETFund
                                </strong><br>
                            @else
                                <strong class="text-success">
                                    <span class="fa fa-check-square"></span>
                                    Submitted to TETFund
                                </strong><br>
                            @endif              
                        @endif              
                    </span>
                </div>
                <br>
                <div class="row col-sm-12 pull-right">
                @if($nominationRequest->tsas_submission != null || $nominationRequest->ca_submission != null || $nominationRequest->tp_submission != null)
                    <form action="" method="post">
                        @csrf
                    </form>
                    <button title="Preview Nomination Details"
                            @if($nominationRequest->type == 'tp')
                                data-val='{{$nominationRequest->tp_submission->id}}'
                            @elseif($nominationRequest->type == 'ca')
                                data-val='{{$nominationRequest->ca_submission->id}}'
                            @elseif($nominationRequest->type == 'tsas')
                                data-val='{{$nominationRequest->tsas_submission->id}}'
                            @endif
                            href='#'
                            class="col-sm-12 col-md-4 btn btn-info btn-sm m-2 btn-show-{{$nominationRequest->type}}" href="#">
                            <i class="fa fa-eye"></i>View
                    </button>
                    {{-- @if($nominationRequest->is_desk_officer_check == 0) --}}
                    @if(empty($nominationRequest->bi_submission_request_id) || (!empty($nominationRequestParentSubmission) && $nominationRequestParentSubmission->status == 'recalled' ))
                        <button title="Edit Nomination Details"
                                @if($nominationRequest->type == 'tp')
                                    data-val='{{$nominationRequest->tp_submission->id}}'
                                @elseif($nominationRequest->type == 'ca')
                                    data-val='{{$nominationRequest->ca_submission->id}}'
                                @elseif($nominationRequest->type == 'tsas')
                                    data-val='{{$nominationRequest->tsas_submission->id}}'
                                @endif
                                href='#'
                                class="col-sm-12 col-md-4 btn btn-primary btn-sm m-2 btn-edit-{{$nominationRequest->type}}" href="#">
                               <i class="fa fa-pencil-square-o"></i>Edit
                        </button>
                        {{-- <button title="Delete Nomination Details"
                                @if($nominationRequest->type == 'tp')
                                    data-val='{{$nominationRequest->tp_submission->id}}'
                                @elseif($nominationRequest->type == 'ca')
                                    data-val='{{$nominationRequest->ca_submission->id}}'
                                @elseif($nominationRequest->type == 'tsas')
                                    data-val='{{$nominationRequest->tsas_submission->id}}'
                                @endif
                                href='#'
                                class="col-sm-12 col-md-4 mr-3 text-danger btn-delete-{{$nominationRequest->type}}" href="#">
                                <i class="fa fa-trash"></i>Delete
                        </button> --}}
                    @endif
                @else
                    <button title="completed and submit {{ $nomination_type_str }} nomination form" 
                            class="btn btn-sm btn-danger pt-2 pull-right {{ $nomination_type_str }}-nomination-form">
                            <i class="fa fa-pencil-square-o"></i> {{ $nomination_type_str }} Nomination Form
                    </button>
                @endif
            </div>
        </div>
    </div>

        @if($nominationRequest->type == 'tp')
            @include('tf-bi-portal::pages.t_p_nominations.modal')
        @elseif($nominationRequest->type == 'ca')
            @include('tf-bi-portal::pages.c_a_nominations.modal')
        @elseif($nominationRequest->type == 'tsas')
            @include('tf-bi-portal::pages.t_s_a_s_nominations.modal')
        @endif


    @endif