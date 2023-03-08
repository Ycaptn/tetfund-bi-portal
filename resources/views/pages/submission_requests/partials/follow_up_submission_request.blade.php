<div class="modal fade" id="mdl-request-follow-up-submission-request-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-request-follow-up-submission-request-modal-title" class="modal-title">
                	Follow-Up {{ $submissionRequest->type }} Submission
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-request-follow-up-submission-request-modal-error" class="alert alert-danger" role="alert"></div>
           
                <form class="form-horizontal" id="frm-request-follow-up-submission-request-modal" role="form" method="POST">
                    <div class="row m-3">
                        <div class="col-sm-12">
                            @csrf
                            <div class="offline-flag">
                            	<span class="offline-request-for-follow-up-submission-request">
                            		You are currently offline
                            	</span>
                            </div>
                            <div class="row col-sm-12">

                           		<div class="col-sm-12">
                                    <span class="fa fa-institution"></span>
                                    {{$beneficiary->full_name}}
                                </div>
                               
                                <div class="col-sm-12">
                                    <span class="fa fa-folder-open"></span>
                                    {{ ucfirst($submitted_request_data->intervention_beneficiary_type->intervention->type ?? '') }}
                                </div>
                               
                                <div class="col-sm-12">
                                   	<span class="fa fa-archive"></span>
                                    {{ $submitted_request_data->intervention_beneficiary_type->intervention->name ?? '' }}
                                </div><hr>

                                <div class="col-sm-12 text-justify text-danger">
                                	<small>
                                		<i>
                                			<strong>Note:</strong> This interface will enable you follow-up on this <b>{{ $submissionRequest->type }}</b> submission currrently submitted to <b>TETFund</b>.                               			
                                		</i>
                                	</small>
                                </div><hr>

			                    <div class="col-sm-12 mb-3">
			                    	<div class="row form-group">
				                        <label class="col-md-3 control-label"><strong>Comment</strong></label>
				                        <div class="col-md-9">
				                            <textarea  class="form-control" id="follow_up_submission_comment" rows="5" ></textarea>
				                        </div>
			                    	</div>
			                    </div>

			                    <div class="col-sm-12 mb-3">
			                    	<div class="row form-group">
				                        <label class="col-md-3 control-label"><strong>Attachment</strong></label>
				                        <div class="col-md-9">
				                            <input type='file' class="form-control" id="follow_up_submission_attachment" />
				                            <em><small class="text-danger"> Max file Size 100MB </small></em>
				                        </div>			                    		
			                    	</div>
			                    </div>

			                </div>
                        </div>
                    </div>
                </form>
            </div>
        
            <div class="modal-footer" id="div-save-mdl-request-follow-up-submission-request-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-request-follow-up-submission-request" value="add">
	                <div id="spinner-request-follow-up-submission-request" style="color: white;">
	                    <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
	                    </div>
	                    <span class="">Loading...</span><hr>
	                </div>
	                <span class="fa fa-refresh"></span> Follow-up Now
                </button>
            </div>

        </div>
    </div>
</div>


<div class="{{ !empty($get_all_related_requests) ? 'col-sm-6' : 'col-sm-4' }} p-2">
	<button class="col-sm-12 btn btn-info btn-sm btn-modal-follow-up-submission-request">
		<small>
			<span class="fa fa-check-square"></span> &nbsp
			FollowUP Submission
		</small>
	</button>
</div>



{{-- JS scripts --}}
@push('page_scripts')
<script type="text/javascript">
	$(document).ready(function() {
		
		$('.offline-request-for-follow-up-submission-request').hide();

		$('#reprioritize_amount_requested').keyup(function(event){
            $('#reprioritize_amount_requested').digits();
        });

		 //Show Modal for reprioritization of submission request
	    $(document).on('click', ".btn-modal-follow-up-submission-request", function(e) {
	        $('#div-request-follow-up-submission-request-modal-error').hide();
	        $('#frm-request-follow-up-submission-request-modal').trigger("reset");
	        $('#mdl-request-follow-up-submission-request-modal').modal('show');

	        $("#spinner-request-follow-up-submission-request").hide();
	        $("#btn-save-mdl-request-follow-up-submission-request").attr('disabled', false);
	    });

	    // save reprioritized submission request
		$('#btn-save-mdl-request-follow-up-submission-request').click(function(e) {
			e.preventDefault();
        	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        	//check for internet status 
	        if (!window.navigator.onLine) {
	            $('.offline-request-for-follow-up-submission-request').fadeIn(300);
	            return;
	        }else{
	            $('.offline-request-for-follow-up-submission-request').fadeOut(300);
	        }

	        swal({
                title: "Please confirm the processing status of this submission request follow-up.",
                text: "The comment & file provided for this follow-up will be forward to TETFund once confirmed below.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes follow-up",
                cancelButtonText: "No don't follow-up",
                closeOnConfirm: false,
                closeOnCancel: true

            }, function(isConfirm) {
            	if (isConfirm) {
            		$("#spinner-request-follow-up-submission-request").show();
            		$("#btn-save-mdl-request-follow-up-submission-request").attr('disabled', true);
            		swal({
                        title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Processing...',
                        text: 'Please wait while submission follow-up is being processed and sent.<br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });
				
			        let primaryId = "{{ $submissionRequest->id }}";
		        	let formData = new FormData();

			        formData.append('id', primaryId);
			        formData.append('_method', 'POST');
		        	formData.append('_token', $('input[name="_token"]').val());

			        if ($('#follow_up_submission_comment').length){
			        	formData.append('follow_up_submission_comment', $('#follow_up_submission_comment').val());
			        }

			        if($('#follow_up_submission_attachment').get(0).files.length != 0){
						formData.append('follow_up_submission_attachment', $('#follow_up_submission_attachment')[0].files[0]);
					}
			        
			        
			        $.ajax({
			            url: "{{ route('tf-bi-portal-api.submission_requests.follow_up_submission','') }}/"+primaryId,
			            type: "POST",
			            data: formData,
			            cache: false,
			            processData:false,
			            contentType: false,
			            dataType: 'json',
			            success: function(result) {
			                if(result.errors) {
			                	swal.close();
								$('#div-request-follow-up-submission-request-modal-error').html('');
								$('#div-request-follow-up-submission-request-modal-error').show();
			                    
			                    $.each(result.errors, function(key, value){
			                        $('#div-request-follow-up-submission-request-modal-error').append('<li class="">'+value+'</li>');
			                    });
			                } else {
			                    $('#div-request-follow-up-submission-request-modal-error').hide();
			                    
			                    swal({
			                        title: "Follow-up Processed.",
			                        text: "Submission Request Follow-up Processed Successfully!",
			                        type: "success"
			                    });
			                    
			                    window.location.reload(true);
			                }

			                $("#spinner-request-follow-up-submission-request").hide();
			                $("#btn-save-mdl-request-follow-up-submission-request").attr('disabled', false);
			                
			            }, error: function(data) {
			                console.log(data);
			                swal("Error", "Oops an error occurred. Please try again.", "error");

			                $("#spinner-request-follow-up-submission-request").hide();
			                $("#btn-save-mdl-request-follow-up-submission-request").attr('disabled', false);

			            }
			        });
				}
            });
		});

	});
</script>
@endpush
{{-- end of scripts --}}