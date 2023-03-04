@php
	// begin monitoring request submission request
	$tf_iterum_intervention_line_key_id = $parentAIPSubmissionRequest->tf_iterum_intervention_line_key_id;
	$title = $parentAIPSubmissionRequest->title;
	$intervention_year1 = $parentAIPSubmissionRequest->intervention_year1;
	$intervention_year2 = $parentAIPSubmissionRequest->intervention_year2;
	$intervention_year3 = $parentAIPSubmissionRequest->intervention_year3;
	$intervention_year4 = $parentAIPSubmissionRequest->intervention_year4;
	$parent_id = $parentAIPSubmissionRequest->id;
	$amount_requested = $parentAIPSubmissionRequest->amount_requested;

    $years = [];
    for ($i=0; $i < 6; $i++) { 
        array_push($years, date("Y")-$i);
	}

@endphp

<div class="modal fade" id="mdl-request-submission-reprioritization-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-request-submission-reprioritization-modal-title" class="modal-title">Reprioritizing Submission <span class="text-success">({{$submissionRequest->title}} - AIP Request)</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-request-submission-reprioritization-modal-error" class="alert alert-danger" role="alert"></div>
           
                <form class="form-horizontal" id="frm-request-submission-reprioritization-modal" role="form" method="POST" action="">
                    <div class="row m-3">
                        <div class="col-sm-12">
                            @csrf
                            <div class="offline-flag">
                            	<span class="offline-request-for-submission-reprioritization">
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
                                			<strong>Note:</strong> By reprioritizing this AIP submission request, this will enable you to make & resubmit minor alterations for the input fields below.                               			
                                		</i>
                                	</small>
                                </div><hr>

			                    <div class="col-sm-12 mb-3">
			                    	<div class="row form-group">
				                        <label class="col-md-3 control-label"><strong>Requested Amount (₦)</strong></label>
				                        <div class="col-md-9">			                              
				                            <input type='text' class="form-control" id="reprioritize_amount_requested" value="{{old('reprioritize_amount_requested') ?? number_format($amount_requested, 2) }}" />
				                        </div>
			                    	</div>
			                    </div>
			                    
			                    <div class="col-sm-12 mb-3">
			                    	<div class="row form-group">

				                        <label class="col-md-3 control-label"><strong>Intervention Years</strong></label>
				                        <div class="col-md-9">
				                        	<div class="row">				                        		
					                         	<div class="col-md-3">
					                                <select id="reprioritize_intervention_year1" class="form-select">
					                                    <option value="">N/A</option>
					                                    @if(isset($years))
								                            @foreach($years as $idx=>$year)
								                                <option {{ (old('intervention_year1') == $year || (isset($intervention_year1) && $intervention_year1==$year)) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
								                            @endforeach
								                        @endif
					                                </select>
					                            </div>

					                            <div class="col-md-3">
					                                <select id="reprioritize_intervention_year2" class="form-select">
					                                    <option value="">N/A</option>
					                                    @if(isset($years))
								                            @foreach($years as $idx=>$year)
								                                <option {{ (old('intervention_year2') == $year || (isset($intervention_year2) && $intervention_year2==$year)) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
								                            @endforeach
								                        @endif
					                                </select>
					                            </div>

					                            <div class="col-md-3">
					                                <select id="reprioritize_intervention_year3" class="form-select">
					                                    <option value="">N/A</option>
					                                    @if(isset($years))
								                            @foreach($years as $idx=>$year)
								                                <option {{ (old('intervention_year3') == $year || (isset($intervention_year3) && $intervention_year3==$year)) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
								                            @endforeach
								                        @endif
					                                </select>
					                            </div>

					                            <div class="col-md-3 mb-3">
					                                <select id="reprioritize_intervention_year4" class="form-select">
					                                    <option value="">N/A</option>
					                                    @if(isset($years))
								                            @foreach($years as $idx=>$year)
								                                <option {{ (old('intervention_year4') == $year || (isset($intervention_year4) && $intervention_year4==$year)) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
								                            @endforeach
								                        @endif
					                                </select>
					                            </div>
				                        	</div>
				                        </div>

			                    	</div>
			                    </div>

			                    <div class="col-sm-12 mb-3">
			                    	<div class="row form-group">
				                        <label class="col-md-3 control-label"><strong>Comment</strong></label>
				                        <div class="col-md-9">
				                            <textarea  class="form-control" id="reprioritize_submission_comment" ></textarea>
				                        </div>
			                    	</div>
			                    </div>

			                    <div class="col-sm-12 mb-3">
			                    	<div class="row form-group">
				                        <label class="col-md-3 control-label"><strong>Attachment</strong></label>
				                        <div class="col-md-9">
				                            <input type='file' class="form-control" id="reprioritize_submission_attachment" />
				                            <em><small class="text-danger"> Max file Size 100MB </small></em>
				                        </div>			                    		
			                    	</div>
			                    </div>

			                </div>
                        </div>
                    </div>
                </form>
            </div>
        
            <div class="modal-footer" id="div-save-mdl-request-submission-reprioritization-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-request-submission-reprioritization" value="add">
	                <div id="spinner-request-submission-reprioritization" style="color: white;">
	                    <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
	                    </div>
	                    <span class="">Loading...</span><hr>
	                </div>
	                <span class="fa fa-refresh"></span> Reprioritize Request
                </button>
            </div>

        </div>
    </div>
</div>

<div class="col-sm-4 p-2">
	<button class="col-sm-12 btn btn-info btn-sm btn-modal-request-for-submission-reprioritization">
		<small>
			<span class="fa fa-refresh"></span>
			Reprioritize Submission
		</small>
	</button>
</div>


{{-- JS scripts --}}
@push('page_scripts')
<script type="text/javascript">
	$(document).ready(function() {
		
		$('.offline-request-for-submission-reprioritization').hide();

		$('#reprioritize_amount_requested').keyup(function(event){
            $('#reprioritize_amount_requested').digits();
        });

		 //Show Modal for reprioritization of submission request
	    $(document).on('click', ".btn-modal-request-for-submission-reprioritization", function(e) {
	        $('#div-request-submission-reprioritization-modal-error').hide();
	        $('#frm-request-submission-reprioritization-modal').trigger("reset");
	        $('#mdl-request-submission-reprioritization-modal').modal('show');

	        $("#spinner-request-submission-reprioritization").hide();
	        $("#btn-save-mdl-request-submission-reprioritization").attr('disabled', false);
	    });

	    // save reprioritized submission request
		$('#btn-save-mdl-request-submission-reprioritization').click(function(e) {
			e.preventDefault();
        	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        	//check for internet status 
	        if (!window.navigator.onLine) {
	            $('.offline-request-for-submission-reprioritization').fadeIn(300);
	            return;
	        }else{
	            $('.offline-request-for-submission-reprioritization').fadeOut(300);
	        }

	        swal({
                title: "Please confirm to complete the submission request Reprioritization.",
                text: "Your submission request will be altered and reprioritized with the data provided once comfirmed.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes reprioritize",
                cancelButtonText: "No don't reprioritize",
                closeOnConfirm: false,
                closeOnCancel: true

            }, function(isConfirm) {
            	if (isConfirm) {
            		$("#spinner-request-submission-reprioritization").show();
            		$("#btn-save-mdl-request-submission-reprioritization").attr('disabled', true);
            		swal({
                        title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Reprioritizing...',
                        text: 'Please wait while submission request is being processed and reprioritized<br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });
				
			        let primaryId = "{{ $submissionRequest->id }}";
		        	let formData = new FormData();

			        formData.append('id', primaryId);
			        formData.append('_method', 'POST');
		        	formData.append('_token', $('input[name="_token"]').val());

			        if ($('#reprioritize_amount_requested').length){
			        	formData.append('reprioritize_amount_requested', $('#reprioritize_amount_requested').val().replace(/,/g,""));
			        }

			        if ($('#reprioritize_intervention_year1').length){
			        	formData.append('reprioritize_intervention_year1', $('#reprioritize_intervention_year1').val());
			        }
			        if ($('#reprioritize_intervention_year2').length){
			        	formData.append('reprioritize_intervention_year2', $('#reprioritize_intervention_year2').val());
			        }
			        if ($('#reprioritize_intervention_year3').length){
			        	formData.append('reprioritize_intervention_year3', $('#reprioritize_intervention_year3').val());
			        }
			        if ($('#reprioritize_intervention_year4').length){
			        	formData.append('reprioritize_intervention_year4', $('#reprioritize_intervention_year4').val());
			        }
			        
			        if ($('#reprioritize_submission_comment').length){
			        	formData.append('reprioritize_submission_comment', $('#reprioritize_submission_comment').val());
			        }
			        if($('#reprioritize_submission_attachment').get(0).files.length != 0){
						formData.append('reprioritize_submission_attachment', $('#reprioritize_submission_attachment')[0].files[0]);
					}
			        
			        $.ajax({
			            url: "{{ route('tf-bi-portal-api.submission_requests.reprioritize','') }}/"+primaryId,
			            type: "POST",
			            data: formData,
			            cache: false,
			            processData:false,
			            contentType: false,
			            dataType: 'json',
			            success: function(result) {
			                if(result.errors) {
			                	swal.close();
								$('#div-request-submission-reprioritization-modal-error').html('');
								$('#div-request-submission-reprioritization-modal-error').show();
			                    
			                    $.each(result.errors, function(key, value){
			                        $('#div-request-submission-reprioritization-modal-error').append('<li class="">'+value+'</li>');
			                    });
			                } else {
			                    $('#div-request-submission-reprioritization-modal-error').hide();
			                    
			                    swal({
			                        title: "Submission Request Reprioritized.",
			                        text: "Submission Reprioritization Processed Successfully!",
			                        type: "success"
			                    });
			                    
			                    window.location.reload(true);
			                }

			                $("#spinner-request-submission-reprioritization").hide();
			                $("#btn-save-mdl-request-submission-reprioritization").attr('disabled', false);
			                
			            }, error: function(data) {
			                console.log(data);
			                swal("Error", "Oops an error occurred. Please try again.", "error");

			                $("#spinner-request-submission-reprioritization").hide();
			                $("#btn-save-mdl-request-submission-reprioritization").attr('disabled', false);

			            }
			        });
				}
            });
		});

	});
</script>
@endpush
{{-- end of scripts --}}
