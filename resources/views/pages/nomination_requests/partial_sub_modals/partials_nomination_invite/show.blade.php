@if(!empty($nominationRequest->status))
    <div class="row col-sm-12 alert alert-warning">
        <div class="col-md-8">
            <i class="icon fa fa-warning"></i> &nbsp; &nbsp;
            <strong>{{ $nomination_type_str }} NOMINATION REQUEST:</strong> 
            <ul>
                <li>This nomination request was made on
                    <strong> {{ \Carbon\Carbon::parse($nominationRequest->created_at)->format('l jS F Y') }}. </strong>
                </li>

                @if($nominationRequest->status == 'pending')
                    
                    <li> The nomination request is <strong>pending</strong> approval </li>

                @elseif($nominationRequest->status == 'defered')

                    <li> This nomination request has been <strong>Defered</strong>  </li>

                @elseif($nominationRequest->status == 'declined')

                    <li> This nomination request has been <strong>Declined</strong>  </li>

                @elseif($nominationRequest->status == 'approved')

                    <li> The <strong>Nomination Request</strong> has been <strong>Approved</strong>  </li>
                
                @endif
                <li>
                    {!! (isset($nominationRequest->details_submitted) && $nominationRequest->details_submitted == true) ? 
                        "The Nomination Details has been <b>submitted</b> by the nominee" : 
                        "The Nomination Details has <b>not been submitted</b> by the nominee"
                    !!}
                </li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="col-sm-12 text-center">
                <span type="button" class="text-danger">
                    @if($nominationRequest->status != 'declined' && $nominationRequest->status != 'approved')
                       <strong>
                            REQUEST ACTIONS 
                       </strong>
                    @elseif($nominationRequest->status == 'approved')
                        <strong>
                            <span class="text-success">    REQUEST APPROVED   </span>
                        </strong>
                    @elseif($nominationRequest->status == 'declined')
                        <strong>
                            <span class="text-danger"> REQUEST DECLINED </span>
                        </strong>
                    @endif
                </span>
            </div>
            <div class="row col-sm-12">
                @if($nominationRequest->status != 'declined' && $nominationRequest->status != 'approved')
                    <form action="" method="post">
                        @csrf
                    </form>

                    <button title="Approve Request" 
                            class="btn btn-sm btn-success col-sm-12 col-md-4 btn-nomination-request-actions mr-3"
                            data-val='approve'
                            href="#">
                            <small>&nbsp;Approve</small>
                    </button>
                    <button title="Defer Request" 
                            data-val='defer'
                            {{ ($nominationRequest->status == 'defered') ?  "disabled='disabled'" : '' }}
                            class="btn btn-sm btn-primary col-sm-12 col-md-4 btn-nomination-request-actions mr-3" href="#">
                            <small>&nbsp;Defer</small>
                    </button>
                    <button title="Decline Request" 
                            data-val='decline' 
                            class="btn btn-sm btn-danger col-sm-12 col-md-4 btn-nomination-request-actions mr-3" href="#">
                            <small>&nbsp;Decline</small>
                    </button>
                @endif
              
            </div>
        </div>
    </div>
@endif


@push('page_scripts')
    <script type="text/javascript">
       $(document).ready(function() {
            $('.offline-nominationApprove').hide();

            //Proceed to process nomination request actions
            $(document).on('click', ".btn-nomination-request-actions", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
                
                let itemName = $(this).attr('data-val');
                let passed_word = '';
                let ing_word = '';
                let waiting_msg = '';

                if (itemName == 'approve') {
                    ing_word = 'Approving';
                    passed_word = 'Approved';
                    waiting_msg = 'You will not be able to revert approval status once completed.';
                } else if (itemName == 'defer') {
                    ing_word = 'Defering';
                    passed_word = 'Defered';
                    waiting_msg = 'You may decide to approve or decline this Nomination Request in due time.';
                } else if(itemName == 'decline') {
                    ing_word = 'Declining';
                    passed_word = 'Declined';
                    waiting_msg = 'You will not be able to execute any further action(s) once declined.';
                }

                let itemId = '{{$nominationRequest->id}}';
                swal({
                    title: "Are you sure you want to " + itemName + " this Nomination Request?",
                    text: waiting_msg,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, " + itemName,
                    cancelButtonText: "No, don't " + itemName,
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: '<div id="spinner-nomination-request" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                            text: ing_word + " Nomination Request! <br><br> Do not refresh this page! ",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            html: true
                        })
                        
                        let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.request_actions', '') }}/"+itemId;                
                        let actionType = "POST";

                        let formData = new FormData();
                        
                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('_method', actionType);

                        @if (isset($organization) && $organization!=null)
                            formData.append('organization_id', '{{$organization->id}}');
                        @endif
                        formData.append('id', itemId);
                        formData.append('actionTasked', itemName);
                        
                        $.ajax({
                            url:endPointUrl,
                            type: "POST",
                            cache: false,
                            data: formData,
                            processData:false,
                            contentType: false,
                            dataType: 'json',
                            success: function(result){
                                if(result.success && result.success == true){
                                    swal({
                                        title: passed_word,
                                        text: result.message,
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    });
                                    location.reload(true);
                                }else{
                                    console.log(result)
                                    swal("Error", "Oops an error occurred. Please try again.", "error");
                                }
                            },
                        });
                    }
                });
            });


        });
    </script>
@endpush