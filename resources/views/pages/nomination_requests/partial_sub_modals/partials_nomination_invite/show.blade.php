@if(!empty($nominationRequest->status))
    <div class="row alert alert-warning">
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
                            class="btn btn-sm btn-success col-sm-12 col-md-4 btn-new-mdl-nominationApprove-modal mr-3" href="#">
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


                    {{-- modal for approving Nomination request --}}
                    <div class="modal fade" id="mdl-nominationApprove-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 id="lbl-nominationApprove-modal-title" class="modal-title"> Approving Staff Nomination Request</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div id="div-nominationApprove-modal-error" class="alert alert-danger" role="alert"></div>
                                    <form class="form-horizontal" id="frm-nominationApprove-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                                        <div class="row">
                                            <div class="col-sm-12 m-3">
                                                @csrf
                                                
                                                <div class="offline-flag"><span class="offline-nominationApprove">You are currently offline</span></div>

                                                <input type="hidden" id="txt-nominationApprove-primary-id" value="0" />

                                                <div id="div-edit-txt-nominationApprove-primary-id">
                                                    <div class="col-sm-12">
                                                        <div class="row col-sm-12 mb-3 text-justify alert alert-info container">
                                                            <small class="">
                                                                <strong>NOTE: </strong>
                                                                <ul>
                                                                    <li>
                                                                        <i>
                                                                            You should Bind this Nomination to one recent existing <strong>Submission</strong>
                                                                        </i>   
                                                                    </li>
                                                                    <li>
                                                                        <i>
                                                                            You will <strong>not</strong> be able to <strong>revert approval status</strong>  once completed.
                                                                        </i>   
                                                                    </li>
                                                                </ul>
                                                            </small>
                                                        </div><hr>
                                                        
                                                        <div class="row col-sm-12">
                                                            
                                                            <div class="col-sm-12 input-group">
                                                                <select name="bind_nomination_to_submission" id="bind_nomination_to_submission" class="form-select">
                                                                    <option value="">-- Bind Nomination to one Submission --</option> 
                                                                    @if (isset($bi_submission_requests) && $bi_submission_requests != null)
                                                                        @foreach($bi_submission_requests as $biSR)
                                                                            
                                                                            @php
                                                                                $years = array();

                                                                                if ($biSR->intervention_year1 != null) {
                                                                                    array_push($years, $biSR->intervention_year1);
                                                                                }

                                                                                if ($biSR->intervention_year2 != null) {
                                                                                    array_push($years, $biSR->intervention_year2);
                                                                                }

                                                                                if ($biSR->intervention_year3 != null) {
                                                                                    array_push($years, $biSR->intervention_year3);
                                                                                }

                                                                                if ($biSR->intervention_year4 != null) {
                                                                                    array_push($years, $biSR->intervention_year4);
                                                                                }

                                                                                $years_str = $biSR->intervention_year1; 
                                                                                // merged years, unique years & sorted years
                                                                                if (isset($years) && count($years) > 1) {
                                                                                    $years_detail = array_values(array_unique($years));
                                                                                    sort($years_detail);
                                                                                    if (count($years_detail) > 1) {
                                                                                        $years_detail[count($years_detail) - 1] = ' and ' . $years_detail[count($years_detail) - 1];
                                                                                        $years_str = implode(", ", $years_detail);
                                                                                        $years_str = substr($years_str, 0,strrpos($years_str,",")) . $years_detail[count($years_detail) - 1];
                                                                                    }
                                                                                } 
                                                                            @endphp

                                                                            <option value="{{ $biSR->id }}">{{ ucwords($biSR->title) }} &nbsp; ({{ $years_str }})</option>

                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="input-group-text"><span class="fa fa-check-square"></span></span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>

                            
                                <div class="modal-footer" id="div-save-mdl-nominationApprove-modal">
                                    <button title="Approve Request"
                                            type="button"
                                            data-val='approve'
                                            value="add"
                                            id="btn-nomination-request-approval"
                                            class="btn btn-primary col-sm-12 col-md-5 btn-nomination-request-approval mr-3" href="#">

                                            <div id="spinner-nominationApprove" class="spinner-border text-info" role="status"> 
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            
                                            Process Approval

                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
              
            </div>
        </div>
    </div>
@endif


@push('page_scripts')
    <script type="text/javascript">
       $(document).ready(function() {
            $('.offline-nominationApprove').hide();

            //Show Modal for Approving Nomination Request
            $(document).on('click', ".btn-new-mdl-nominationApprove-modal", function(e) {
                $('#div-nominationApprove-modal-error').hide();
                $('#mdl-nominationApprove-modal').modal('show');
                $('#frm-nominationApprove-modal').trigger("reset");
                $('#txt-nominationApprove-primary-id').val(0);

                $('#div-show-txt-nominationApprove-primary-id').hide();
                $('#div-edit-txt-nominationApprove-primary-id').show();

                $("#spinner-nominationApprove").hide();
                $("#btn-nomination-request-approval").attr('disabled', false);
            });
            
            //process approval button clicked
            $(document).on('click', ".btn-nomination-request-approval", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
                $("#spinner-nominationApprove").show();
                $("#btn-nomination-request-approval").attr('disabled', true);

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-nominationApprove').fadeIn(300);
                    return;
                }else{
                    $('.offline-nominationApprove').fadeOut(300);
                }
                
                let itemName = $(this).attr('data-val');
                let itemId = '{{$nominationRequest->id}}';                
                let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.request_actions', '') }}/"+itemId;          
                let actionType = "POST";
                let formData = new FormData();
                
                formData.append('_token', $('input[name="_token"]').val());
                formData.append('_method', actionType);
                
                if (itemName == 'approve') {
                    if ( $('#bind_nomination_to_submission').length ) {
                        formData.append('bi_submission_request_id',$('#bind_nomination_to_submission').val());
                    }
                }

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
                        if (result.message && result.message == 'errors' && result.response) {
                            $('#div-nominationApprove-modal-error').html('');
                            $('#div-nominationApprove-modal-error').show();
                            
                            $.each(result.response, function(key, value){
                                $('#div-nominationApprove-modal-error').append('<li class="">'+value+'</li>');
                            });
                        } else {
                            $('#div-nominationApprove-modal-error').hide();
                            window.setTimeout( function(){

                                $('#div-nominationApprove-modal-error').hide();

                                swal({
                                    title: "Approved",
                                    text: result.message,
                                    type: "success"
                                },function(){
                                    location.reload(true);
                                });

                            },20);
                        }

                        $("#spinner-nominationApprove").hide();
                        $("#btn-nomination-request-approval").attr('disabled', false);
                        
                    }, error: function(data){
                        console.log(data);
                        swal("Error", "Oops an error occurred. Please try again.", "error");

                        $("#spinner-nominationApprove").hide();
                        $("#btn-nomination-request-approval").attr('disabled', false);

                    }
                });
            });


            //Proceed to process nomination request actions
            $(document).on('click', ".btn-nomination-request-actions", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
                
                let itemName = $(this).attr('data-val');
                let passed_word = '';
                let ing_word = '';
                let waiting_msg = '';

                if (itemName == 'defer') {
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

                        /*swal({
                            title: "Please wait...",
                            text: ing_word + " Nomination Request!",
                            imageUrl: "{{asset('imgs/loading.gif')}}",
                            imageSize: '300x200',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });*/
                        
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