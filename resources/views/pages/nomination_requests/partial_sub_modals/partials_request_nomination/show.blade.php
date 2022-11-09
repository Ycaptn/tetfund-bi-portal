    @if($nominationRequest->status == 'pending')
        {{-- pending nomination request --}}
        
        <div class="row container alert alert-warning">
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
        
        <div class="row container alert alert-danger">
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
        
        <div class="row container alert alert-warning">
            <div class="col-md-8">
                <i class="icon fa fa-warning"></i> &nbsp; &nbsp;
                <strong>{{ $nomination_type_str }} NOMINATION REQUEST:</strong> 
                <ul>
                    <li>This request was completed on
                        <strong>
                            {{ \Carbon\Carbon::parse($nominationRequest->created_at)->format('l jS F Y') }}.
                        </strong>
                    </li>
                    <li>This <strong>nomination request</strong> has a <strong>deffered </strong>! </li>
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
         
        <div class="row container alert alert-success">
            <div class="col-md-8">
                <i class="icon fa fa-warning"></i> &nbsp; &nbsp;
                <strong>{{ $nomination_type_str }} NOMINATION REQUEST:</strong> 
                <ul>
                    <li>The submission was completed on
                        <strong>
                            {{ \Carbon\Carbon::parse($nominationRequest->created_at)->format('l jS F Y') }}.
                        </strong>
                    </li>
                    <li>This <strong>nomination request</strong> has been <strong>successfully approved!</strong></li>
                    @if($nominationRequest->astd_submission != null)
                        <li><strong>{{ $nomination_type_str }}</strong> Nomination form data <strong>completed!</strong></li>
                    @else
                        <li>Submit your <strong>Nomination Details</strong> by clicking on the button <strong> '{{ $nomination_type_str }} Nomination Form'</strong> to complete this process!</li>
                    @endif
                </ul>
            </div>
            <div class="col-md-4">
                <div class="col-sm-12 text-success">
                    <span class="pull-right">
                        
                        <span class="fa fa-check-square"></span>
                        <strong>
                            REQUEST APPROVED
                        </strong>   <br>

                        @if($nominationRequest->astd_submission != null)
                            <span class="fa fa-check-square"></span>
                            <strong>
                                SUBMITTED NOMINATION DATA
                            </strong>
                        @endif

                    </span>
                </div>
                <br>
                <div class="row col-sm-12 pull-right">
                @if($nominationRequest->astd_submission != null)
                    <form action="" method="post">
                        @csrf
                    </form>

                    <button title="Preview"
                            data-val='{{$nominationRequest->astd_submission->id}}'
                            href='#'
                            class="col-sm-12 col-md-4 mr-3 text-primary btn-show-{{$nominationRequest->type}}" href="#">
                            <i class="fa fa-eye"></i>View
                    </button>
                    <button title="Edit"
                            data-val='{{$nominationRequest->astd_submission->id}}'
                            href='#'
                            class="col-sm-12 col-md-4 mr-3 text-primary btn-edit-{{$nominationRequest->type}}" href="#">
                           <i class="fa fa-pencil-square-o"></i>Edit
                    </button>
                    <button title="Delete" 
                            data-val='{{$nominationRequest->astd_submission->id}}' 
                            href='#'
                            class="col-sm-12 col-md-4 mr-3 text-danger btn-delete-{{$nominationRequest->type}}" href="#">
                            <i class="fa fa-trash"></i>Delete
                    </button>
                @else
                    <button title="completed and submit {{ $nomination_type_str }} nomination form" 
                            class="btn btn-sm btn-danger pt-2 pull-right {{ $nomination_type_str }}-nomination-form">
                            <i class="fa fa-pencil-square-o"></i> {{ $nomination_type_str }} Nomination Form 
                    </button>
                @endif
            </div>
        </div>
    </div>

        @if($nominationRequest->type == 'astd')
            @include('tf-bi-portal::pages.a_s_t_d_nominations.modal')
        @elseif($nominationRequest->type == 'tp')
            @include('tf-bi-portal::pages.t_p_nominations.modal')
        @endif


    @endif