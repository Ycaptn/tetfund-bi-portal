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
                    <li>Submit your <strong>Nomination Details</strong> by clicking on the button <strong> {{ $nomination_type_str }} Nomination Form</strong> to complete this process!</li>
                </ul>
            </div>
            <div class="col-md-4">
                <div class="col-sm-12 text-success">
                    <span class="pull-right">
                        <span class="fa fa-check-square"></span>
                        <strong>
                            REQUEST APPROVED
                        </strong>
                    </span>
                </div>
                <div class="col-sm-12">
                    <a data-toggle="tooltip" 
                        title="completed and submit {{ $nomination_type_str }} nomination form" 
                        data-val='{{$nominationRequest->id}}' 
                        class="btn btn-sm btn-danger pt-2 pull-right" href="#">
                        <i class="fa fa-pencil-square-o"></i> {{ $nomination_type_str }} Nomination Form 
                    </a>
                </div>
            </div>
        </div>
    @endif