@if(isset($submitted_request_data->beneficiary_request_queries) && count($submitted_request_data->beneficiary_request_queries) > 0)
	@php
		$sn_counter = 0;
		$array_clarification_queries = array();
	@endphp

	{{-- clarification modal --}}
	<div class="modal fade" id="mdl-submission-clarification-response" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
	    <div class="modal-dialog modal-lg" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 id="lbl-submission-clarification-response-title" class="modal-title"><span id="prefix_info"></span> Responding to Clarification Query Message</h5>
	                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	            </div>

	            <div class="modal-body">
	            	<div id="div-submission-clarification-response-modal-error" class="alert alert-danger" role="alert"></div>
	            
            		<form class="form-horizontal" id="frm-submission-clarification-response-modal" role="form" method="POST" enctype="multipart/form-data" action="">
	                    <div class="row col-sm-12">		                            
        				    @csrf
                            <input type="hidden" id="txt-submission-clarification-response-primary-id" value="0" />
                        	
                            <div class="offline-flag mb-3">
                            	<span class="offline-submission-clarification-response">You are currently offline</span>
                            </div>

                        	<div class="form-group mb-3">
			            		<label for="txt-submission-clarification-message" class="form-label text-danger">
			            			<strong>Clarification/Query Message</strong>
			            		</label>
			            		<textarea class="form-control" id="txt-submission-clarification-message" rows="3" disabled='disabled'></textarea>
			            	</div>
                            
                           		                            
                        	<div class="form-group mb-3">
                        		<label for="text_clarificarion_response" class="form-label">
                        			<strong>Clarification/Query Response</strong>
                        		</label>
                        		<textarea class="form-control" id="text_clarificarion_response" rows="4"></textarea>
                        	</div>

                        	<div class="form-group mb-3">
                        		<label for="attachments_clarification_response" class="form-label">
                        			<strong>Attachments (Optional)</strong>
                        		</label>
                        		<input multiple="multiple" type='file' class="form-control" name="attachments_clarification_response[]" id="attachments_clarification_response" />

                        		<small class="text-danger"><i>
                        			Max file Size 50M (Optional)<br>
                        			You may select multiple files for upload where neccessary by holding down <b>Ctrl key</b> then click to select the desired files to be uploaded.
                        		</i></small>
                        	</div>		                                  
	                    </div>
	                </form>
		        </div>
		            
	            <div class="modal-footer" id="div-save-mdl-submission-clarification-response">
	                <button type="button" class="btn btn-primary" id="btn-save-submission-clarification-response" value="add">
	            		<span class="fa fa-paper-plane"></span> Send Response
	                </button>
	            </div>
            </div>
	   
        </div>
    </div>

	{{-- all clarification queries --}}
	<div class="col-sm-12">
		<div class="col-sm-12">
			<strong>SUBMISSION REQUEST CLARIFICATION QUERIES</strong>
		</div>
	    <table class="table table-striped well well-sm">
	    	<thead>
	    		<tr>
	    			<th style="width:5%;"><small>S/N</small></th>
	    			<th style="width:55%;"><small>Message (MSG)</small></th>
	    			<th style="width:20%;" class="text-center"><small>MSG. Date</small></th>
	    			<th style="width:20%;" class="text-center"><small>Action</small></th>
	    		</tr>
	    	</thead>
	    	<tbody>
		    	@foreach($submitted_request_data->beneficiary_request_queries as $beneficiary_request_query)

		    		@php
		    			if($beneficiary_request_query->has_responded==true) {
		    				continue;
		    			}
		    			$array_clarification_queries[$beneficiary_request_query->id] = htmlentities($beneficiary_request_query->message);
		    		@endphp

		    		<tr>
			    		<td>
			    			<small>{{ $sn_counter+=1 }}.</small>
			    		</td>

			    		<td>
			    			<small>{{ $beneficiary_request_query->message }}</small>
			    		</td>

			    		<td class="text-center">
			    			<small>{{ date('jS M Y', strtotime($beneficiary_request_query->created_at ?? '')) }}</small>
			    		</td>

			    		<td class="text-center">
			    			<div class='btn-group' role="group">
							    <button title="Respond to this Query" data-val='{{$beneficiary_request_query->id}}' class="btn btn-sm text-primary btn-show-submission-clarification-response">
							    	<u>Respond</u>
							    </button>
							</div>
			    		</td>
		    		</tr>
		    	@endforeach
	    	</tbody>
	    </table>
	</div>
	@php
		$array_clarification_queries_str = str_replace('\'','\\\'',json_encode($array_clarification_queries));	
	@endphp
	@push('page_scripts')
		<script type="text/javascript">
			$(document).ready(function() {
				$('.offline-submission-clarification-response').hide();
				
				let array_clarification_queries = '{!! $array_clarification_queries_str !!}';
				let array_clarification_queries_decoded = JSON.parse(array_clarification_queries.replace(/[\r\n]+/gm, ''));

				// Show Modal to reply clarification query
			    $(document).on('click', ".btn-show-submission-clarification-response", function(e) {
					let itemId = $(this).attr('data-val');
			        $('#frm-submission-clarification-response-modal').trigger("reset");
			        $('#txt-submission-clarification-response-primary-id').val(itemId);
					
			        $('#div-submission-clarification-response-modal-error').hide();
			        $('#txt-submission-clarification-message').text(array_clarification_queries_decoded[itemId]);
			        $('#mdl-submission-clarification-response').modal('show');
			    });

			    // process and send claridicarion query response
			    $('#btn-save-submission-clarification-response').click(function(e) {
			        e.preventDefault();
			        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

			        //check for internet status 
			        if (!window.navigator.onLine) {
			            $('.offline-submission-clarification-response').fadeIn(300);
			            return;
			        }else{
			            $('.offline-submission-clarification-response').fadeOut(300);
			        }

			        swal({
		                title: "You are about to submit a response for this clarification query message",
		                text: "This is an irreversible action, the clarification request leaves your dashborad once your response is submitted successfully.",
		                type: "warning",
		                showCancelButton: true,
		                confirmButtonClass: "btn-danger",
		                confirmButtonText: "Yes respond",
		                cancelButtonText: "No don't respond",
		                closeOnConfirm: false,
		                closeOnCancel: true

		            }, function(isConfirm) {

		            	if (isConfirm) {
		            		swal({
		                        title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Processing...',
		                        text: 'Please wait, your response submission is being processed <br><br> Do not refresh this page! ',
		                        showConfirmButton: false,
		                        allowOutsideClick: false,
		                        html: true
		                    });

				        	let formData = new FormData();
					        let primaryId = $('#txt-submission-clarification-response-primary-id').val()

					        formData.append('_method', "POST");
				        	formData.append('_token', $('input[name="_token"]').val());
					        formData.append('id', primaryId);
					        formData.append('submission_request_id', '{{$submissionRequest->id}}');

					        if ($('#text_clarificarion_response').length) {
					        	formData.append('text_clarificarion_response', $('#text_clarificarion_response').val());
					    	}

					    	$.each($('#attachments_clarification_response')[0].files, function(i, file) {
			                    formData.append('attachments_clarification_response[]', file);
			                });

					        $.ajax({
					            url: "{{ route('tf-bi-portal-api.submission_requests.clarification_response') }}",
					            type: "POST",
					            data: formData,
					            cache: false,
					            processData:false,
					            contentType: false,
					            dataType: 'json',
					            success: function(result) {
					                if(result.errors) {
					                	swal.close();
										$('#div-submission-clarification-response-modal-error').html('');
										$('#div-submission-clarification-response-modal-error').show();
					                    
					                    $.each(result.errors, function(key, value){
					                        $('#div-submission-clarification-response-modal-error').append('<li class="">'+value+'</li>');
					                    });
					                } else {
					                    $('#div-submission-clarification-response-modal-error').hide();
					                    
					                    swal({
					                        title: "Clarification Response Sent",
					                        text: result.message,
					                        type: "success"
					                    });
					                    
					                    window.location.reload(true);
					                }
					                
					            }, error: function(data) {
					                console.log(data);
					                swal("Error", "Oops an error occurred. Please try again.", "error");
					            }
					        });
					    }

		            });
			    });

			});
				
		</script>
	@endpush

@endif


