@php
	$array_of_contents = array();
@endphp

{{-- modal to preview communucation content --}}
<div class="modal fade" id="mdl-submission-communication-content" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="lbl-submission-communication-content-title" class="modal-title"><span id="prefix_info"></span> Preview Communication Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
               <div class="row m-3">
                    <div class="col-sm-12" id="display-submission-communication-content">

                    </div>
                </div>
            </div>
        
            <div class="modal-footer" id="div-save-mdl-submission-communication-content">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">
                	Close Preview
                </button>
            </div>

        </div>
    </div>
</div>

{{-- modal responding to communication --}}
<div class="modal fade" id="mdl-submission-communication-response" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="lbl-submission-communication-response-title" class="modal-title"><span id="prefix_info"></span>Provide Communication Response</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
               <div class="row m-3">
               		<div class="col-sm-12">

               			<form id="form-communication-modal" method="POST" action="">	
	                        @csrf
	                        <div class="offline-flag">
	                        	<span class="offline-request-for-submission-communication-response-title">
	                        		You are currently offline
	                        	</span>
	                        </div>
	                        <div class="row col-sm-12">

	                        	<div id="div-communication-modal-error" class="alert alert-danger" role="alert"></div>

	                       		<div class="col-sm-12">
	                                <span class="fa fa-institution"></span>
	                                {{$beneficiary->full_name}}
	                            </div><hr>

	                            <div class="col-sm-12 text-justify text-danger">
	                            	<small>
	                            		<i>
	                            			<strong>Note:</strong> This interface will enable you comply to this communication by providing response contaning neccessary attachments.                               			
	                            		</i>
	                            	</small>
	                            </div><hr>

	                            <input type="hidden" id="communication-primary-id" value="0">
	                            <input type="hidden" id="communication-table-type" value="0">

			                    <div class="col-sm-12 mb-3">
			                    	<div class="row form-group">
				                        <label class="col-md-3 control-label"><strong>Communication Comment</strong></label>
				                        <div class="col-md-9">
				                            <textarea  class="form-control" id="communication_comment" rows="5"></textarea>
				                        </div>
			                    	</div>
			                    </div>

			                    <div class="col-sm-12 mb-3">
			                    	<div class="row form-group">
				                        <label class="col-md-3 control-label"><strong>Attachments</strong></label>
				                        <div class="col-md-9">
				                            <input multiple="multiple" type='file' class="form-control" name="communication_attachments[]" id="communication_attachments" />

				                            <em>
				                            	<small class="text-danger">
				                            		Max file Size 100MB.
				                            	</small>
				                            	
				                            	<small class="text-danger pull-right">
				                            		You may select multiple files for upload where neccessary by holding down <b>Ctrl key</b> then click to select the desired files to be uploaded.<br>
				                            		Each file must be a PDF document.
				                            	</small>
				                            </em>
				                        </div>			                    		
			                    	</div>
			                    </div>
		                	</div>
               			</form>

                    </div>
                </div>
            </div>
        
            <div class="modal-footer" id="div-save-mdl-submission-communication-response">
                <button type="button" class="btn btn-primary" id="btn-submit-communication-response">
                	Process & Submit Response
                </button>
            </div>

        </div>
    </div>
</div>

{{-- communications list --}}
<div class="col-sm-12">
    <table class="table table-striped  well well-sm">
    	<thead>
    		<tr>
    			<th style="width:5%;">S/N</th>
    			<th style="width:40%;">Title</th>
    			<th style="width:20%;">Label</th>
    			<th style="width:15%;" class="text-center">Date Released</th>
    			<th style="width:20%;" class="text-center">Action</th>
    		</tr>
    	</thead>
    	<tbody>
	    	@if(isset($bi_request_released_communications) && count($bi_request_released_communications) > 0)
	    		@foreach($bi_request_released_communications as $bi_request_communications)
	    		@php
	    			$array_of_contents[$loop->iteration] = htmlentities($bi_request_communications->communication->content); 
	    		@endphp
	    			<tr>
	    				<td>{{$loop->iteration}}.</td>
	    				<td>{{ ucwords($bi_request_communications->communication->title ?? '') }}</td>
	    				<td>{{ ucwords($bi_request_communications->communication->destination_label ?? '') }}</td>
	    				<td class="text-center">
	    					{{ date('jS-M-Y', strtotime($bi_request_communications->communication->updated_at ?? ''))}}
	    				</td>
	    				<td class="text-center">
	    					<div class='btn-group' role="group">
							    <a data-toggle="tooltip" 
							        title="Preview Communication Content" 
							        data-val='{{$loop->iteration}}' 
							        class="btn btn-info btn-sm btn-show-submission-communication-content" href="#">
							        <small>Preview</small>
							        @if($bi_request_communications->communication->has_responded!=false)
								        <br>
								        <small class="text-white">
							        		<span class="fa fa-check-square"></span> Responded
								        </small>
							        @endif
							    </a>
							</div>
							
							@if($bi_request_communications->communication->has_responded==false)
								<div class='btn-group' role="group">
								    <a data-toggle="tooltip" 
								        title="Respond to Communication" 
								        data-val="{{$bi_request_communications->id}}"
								        data-val-communication-table-type="{{isset($bi_request_communications->beneficiary_request_id) ? 'tf_bip_ben_req_communications' : 'tf_bm_beneficiary_communications'}}"

								        class="btn btn-success btn-sm btn-show-submission-communication-response-mdl" href="#">
								        <small>Respond</small>
								    </a>
								</div>
							@endif
	    				</td>
	    			</tr>
	    		@endforeach
	    	@else
	    		<tr>
	    			<td class="text-center text-danger" colspan="5">
	    				<i>No Communication Available</i>
	    			</td>
	    		</tr>
	    	@endif
    	</tbody>
    </table>
</div>

@push('page_scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			let json_array_of_contents = '{!! json_encode($array_of_contents) !!}';

			// Show Modal to preview communication contents
		    $(document).on('click', ".btn-show-submission-communication-content", function(e) {
		    	e.preventDefault();
				let itemId = $(this).attr('data-val');
				let html_encoded_content = JSON.parse(json_array_of_contents.replace(/[\r\n]+/gm, ''))[itemId];

				let html_decoded_content = $('<textarea />').html(html_encoded_content).text();
				
		        $('#display-submission-communication-content').html(html_decoded_content);
		        $('#mdl-submission-communication-content').modal('show');
		    });

		    // Show Modal to respond to communication
            $(document).on('click', ".btn-show-submission-communication-response-mdl", function(e) {
                e.preventDefault();
                
                $('#div-communication-modal-error').hide();
                $('#form-communication-modal').trigger('reset');
                $('.offline-request-for-submission-communication-response-title').hide();
                $('#communication-primary-id').val($(this).attr('data-val'));
                $('#communication-table-type').val($(this).attr('data-val-communication-table-type'));

                $('#mdl-submission-communication-response').modal('show');
                
            });

            // process communication response
            $(document).on('click', "#btn-submit-communication-response", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
                
                //check for internet status 
		        if (!window.navigator.onLine) {
		            $('.offline-request-for-submission-communication-response-title').fadeIn(300);
		            return;
		        }else{
		            $('.offline-request-for-submission-communication-response-title').fadeOut(300);
		        }
                
                let primaryID = $('#communication-primary-id').val();
                let communicationTableType = $('#communication-table-type').val();


                swal({
		            title: "Please confirm you want to submit this communication response.",
		            text: "Your response will be sent to TETFund front desk once confirmed.",
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
		                    title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Responding...',
		                    text: 'Please wait while this communication response is being proccessed! <br><br> Do not refresh this page! ',
		                    showConfirmButton: false,
		                    allowOutsideClick: false,
		                    html: true
		                });

		                let formData = new FormData();
		                formData.append('_token', $('input[name="_token"]').val());
		                formData.append('_method', 'POST');
	                    formData.append('communication_parent_id', '{{ isset($submissionRequest->id) ? $submissionRequest->id : $beneficiary->id }}' );

		                if (primaryID.trim().length > 0) {
		                    formData.append('communication_primary_id', primaryID);
		                }

		                if (communicationTableType.trim().length > 0) {
		                    formData.append('communication_table_type', communicationTableType);
		                }

		                if ($('#communication_comment').val().trim().length > 0) {
		                    formData.append('communication_comment', $('#communication_comment').val());
		                }

		                $.each($('#communication_attachments')[0].files, function(i, file) {
		                    formData.append('communication_attachments[]', file);
		                });
		                
		                $.ajax({
		                    url: "{{ route('process-communication-response') }}",
		                    type: "POST",
		                    data: formData,
		                    cache: false,
		                    processData:false,
		                    contentType: false,
		                    dataType: 'json',
		                    success: function(result) {
		                        if(result.errors) {
		                            swal.close();
		                            $('#div-communication-modal-error').html('');
		                            $('#div-communication-modal-error').show();
		                            
		                            $.each(result.errors, function(key, value){
		                                $('#div-communication-modal-error').append('<li class="">'+value+'</li>');
		                            });
		                        } else {
		                            $('#div-communication-modal-error').hide();
		                            console.log(result);
		                            swal({
		                                title: "Communication Response Submitted",
		                                text: "The Communication Response Has Been Submitted Successfully!",
		                                type: "success"
		                            });
		                            
		                            location.reload();
		                        }

		                        $("#btn-submit-communication-response").attr('disabled', false);
		                        
		                    }, error: function(data) {
		                        console.log(data);
		                        swal("Error", "Oops an error occurred. Please try again.", "error");

		                        $("#btn-submit-communication-response").attr('disabled', false);
		                    }
		                });
		            }
		        });

            }); 
		});
	</script>
@endpush


	