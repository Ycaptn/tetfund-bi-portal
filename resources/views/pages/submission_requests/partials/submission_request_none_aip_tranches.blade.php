@php
	$should_button_request_next_display = false;
	
	// begin aip to first tranche submission request
	if($submissionRequest->is_aip_request==true && $submissionRequest->first_tranche_intervention_percentage($intervention->name) != null && $submissionRequest->getFirstTrancheSubmissionRequest()==null) {

		// $submissionRequest_aip_request = $submissionRequest->getParentAIPSubmissionRequest();
		$submitted_aip_request = $submitted_request_data->submitted_aip_request;
		$submitted_aip_request_final_amount = $submitted_aip_request->request_final_amount ?? 0;
		$tranche_amount_percentage = str_replace('%', '', $submissionRequest->first_tranche_intervention_percentage($intervention->name));
		$first_tranche_amount_requested = (floatval($tranche_amount_percentage) * $submitted_aip_request_final_amount) / 100;

		$tf_iterum_intervention_line_key_id = $submissionRequest->tf_iterum_intervention_line_key_id;
		$title = $submissionRequest->title;
		$intervention_year1 = $submissionRequest->intervention_year1;
		$intervention_year2 = $submissionRequest->intervention_year2;
		$intervention_year3 = $submissionRequest->intervention_year3;
		$intervention_year4 = $submissionRequest->intervention_year4;
		$amount_requested = $first_tranche_amount_requested;
		$parent_id = $submissionRequest->id;
		$request_tranche = "1st Tranche Payment";
		$is_first_tranche_request = true;
		$should_button_request_next_display = true;	// show button for next tranche
	}

	// begin first to second tranche submission request
	if($submissionRequest->is_first_tranche_request==true && $submissionRequest->second_tranche_intervention_percentage($intervention->name) != null && $submissionRequest->getSecondTrancheSubmissionRequest()==null) {

		$submissionRequest_aip_request = $submissionRequest->getParentAIPSubmissionRequest();
		$submitted_aip_request = $submitted_request_data->submitted_aip_request;
		$submitted_aip_request_final_amount = $submitted_aip_request->request_final_amount ?? 0;
		$tranche_amount_percentage = str_replace('%', '', $submissionRequest->second_tranche_intervention_percentage($intervention->name));
		$first_tranche_amount_requested = (floatval($tranche_amount_percentage) * $submitted_aip_request_final_amount) / 100;

		$tf_iterum_intervention_line_key_id = $submissionRequest_aip_request->tf_iterum_intervention_line_key_id;
		$title = $submissionRequest_aip_request->title;
		$intervention_year1 = $submissionRequest_aip_request->intervention_year1;
		$intervention_year2 = $submissionRequest_aip_request->intervention_year2;
		$intervention_year3 = $submissionRequest_aip_request->intervention_year3;
		$intervention_year4 = $submissionRequest_aip_request->intervention_year4;
		$amount_requested = $first_tranche_amount_requested;
		$parent_id = $submissionRequest_aip_request->id;
		$request_tranche = "2nd Tranche Payment";
		$is_second_tranche_request = true;
		$should_button_request_next_display = true;	// show button for next tranche
	}

	// begin second to final tranche submission request
	if($submissionRequest->is_second_tranche_request==true && $submissionRequest->final_tranche_intervention_percentage($intervention->name) != null && $submissionRequest->getFinalTrancheSubmissionRequest()==null) {

		$submissionRequest_aip_request = $submissionRequest->getParentAIPSubmissionRequest();
		$submitted_aip_request = $submitted_request_data->submitted_aip_request;
		$submitted_aip_request_final_amount = $submitted_aip_request->request_final_amount ?? 0;
		$tranche_amount_percentage = str_replace('%', '', $submissionRequest->final_tranche_intervention_percentage($intervention->name));
		$first_tranche_amount_requested = (floatval($tranche_amount_percentage) * $submitted_aip_request_final_amount) / 100;

		$tf_iterum_intervention_line_key_id = $submissionRequest_aip_request->tf_iterum_intervention_line_key_id;
		$title = $submissionRequest_aip_request->title;
		$intervention_year1 = $submissionRequest_aip_request->intervention_year1;
		$intervention_year2 = $submissionRequest_aip_request->intervention_year2;
		$intervention_year3 = $submissionRequest_aip_request->intervention_year3;
		$intervention_year4 = $submissionRequest_aip_request->intervention_year4;
		$amount_requested = $first_tranche_amount_requested;
		$parent_id = $submissionRequest_aip_request->id;
		$request_tranche = "Final Tranche Payment";
		$is_final_tranche_request = true;
		$should_button_request_next_display = true;	// show button for next tranche
	}

		
	// begin first to final tranche submission request
	if($submissionRequest->is_first_tranche_request==true && $submissionRequest->final_tranche_intervention_percentage($intervention->name) != null && $submissionRequest->getFinalTrancheSubmissionRequest()==null && $submissionRequest->second_tranche_intervention_percentage($intervention->name) == null) {

		$submissionRequest_aip_request = $submissionRequest->getParentAIPSubmissionRequest();
		$submitted_aip_request = $submitted_request_data->submitted_aip_request;
		$submitted_aip_request_final_amount = $submitted_aip_request->request_final_amount ?? 0;
		$tranche_amount_percentage = str_replace('%', '', $submissionRequest->final_tranche_intervention_percentage($intervention->name));
		$first_tranche_amount_requested = (floatval($tranche_amount_percentage) * $submitted_aip_request_final_amount) / 100;

		$tf_iterum_intervention_line_key_id = $submissionRequest_aip_request->tf_iterum_intervention_line_key_id;
		$title = $submissionRequest_aip_request->title;
		$intervention_year1 = $submissionRequest_aip_request->intervention_year1;
		$intervention_year2 = $submissionRequest_aip_request->intervention_year2;
		$intervention_year3 = $submissionRequest_aip_request->intervention_year3;
		$intervention_year4 = $submissionRequest_aip_request->intervention_year4;
		$amount_requested = $first_tranche_amount_requested;
		$parent_id = $submissionRequest_aip_request->id;
		$request_tranche = "Final Tranche Payment";
		$is_final_tranche_request = true;
		$should_button_request_next_display = true;	// show button for next tranche request
	}
@endphp

@if($should_button_request_next_display==true)
	<div class="modal fade" id="mdl-request-related-tranche-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
	    <div class="modal-dialog modal-lg" role="document">
	        <div class="modal-content">

	            <div class="modal-header">
	                <h5 id="lbl-request-related-tranche-modal-title" class="modal-title"><span id="prefix_info"></span> Request For {{$request_tranche}}</h5>
	                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	            </div>

	            <div class="modal-body">
	                <div id="div-request-related-tranche-modal-error" class="alert alert-danger" role="alert"></div>
	            
	                <form class="form-horizontal" id="frm-request-related-tranche-modal" role="form" method="POST" action="">
	                    <div class="row m-3">
	                        <div class="col-sm-12">
	                            @csrf
	                            <div class="offline-flag">
	                            	<span class="offline-request-for-related-tranche">
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
                                    </div>
                                   
                                    <div class="col-sm-12">
                                        <i class="fa fa-crosshairs fa-fw"></i>
                                        @php
                                        	$intervention_years = $submissionRequest->getInterventionYears();
                                        @endphp
                                        @if(count($intervention_years) == 1)
                                        	{{ $intervention_years[0] ?? ''}}
                                        @elseif(count($intervention_years) > 1)
                                        	@php
                                        		$intervention_years[(count($intervention_years)-1)] = 'and '.end($intervention_years);
                                        	@endphp
                                        	{{implode(' ', $intervention_years)}}
                                        @endif
                                    </div>
                                   
                                    <div class="col-lg-12">
                                        <i class="fa fa-money"></i>
                                    	<strong>Total AIP Amount Approved:</strong>
                                    	&#8358;{{ number_format($submitted_aip_request_final_amount, 2)}}
                                    </div>

                                    <div class="col-sm-12">
                                        <i class="fa fa-money"></i>
                                    	<strong>Total Available Amount:</strong>
                                    	&#8358;{{number_format(($fund_available ?? 0), 2)}}
                                    	{{-- <strong>Allocated Funds Available:</strong>  ₦{{ number_format($allocated_fund_available,2) }} --}}
                                    </div>

                                    <div class="form-group" style="margin-top:10px;">
                                        <label class="col-sm-12 control-label">Project Title</label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <textarea readonly class="form-control" name="project_title" id="project_title" rows="2">{{ $submissionRequest->title }} - {{ $request_tranche }} Request</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top:10px;">
                                		<label class="col-sm-12 control-label">
                                       		{{$request_tranche}} Request Amount<small class="text-red"> - {{ $tranche_amount_percentage }}% of AIP Amount Approved</small>
                                        </label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <input readonly type='text' class="form-control" name="amount_requested" id="amount_requested" value=" &#8358; {{ number_format($amount_requested, 2) }}" />
                                            </div>
                                        </div>
                                    </div>

				                </div>
	                        </div>
	                    </div>
	                </form>
	            </div>
	        
	            <div class="modal-footer" id="div-save-mdl-request-related-tranche-modal">
	                <button type="button" class="btn btn-primary btn-save-mdl-request-related-tranche" id="btn-save-mdl-request-related-tranche" value="add">
	                <div id="spinner-request-related-tranche" style="color: white;">
	                    <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
	                    </div>
	                    <span class="">Loading...</span><hr>
	                </div>
	                Save {{$request_tranche}} Request
	                </button>
	            </div>

	        </div>
	    </div>
	</div>

<div class="col-sm-4 p-2">
	<button class="col-sm-12 btn btn-danger btn-sm btn-modal-request-for-related-tranche">
		<small>
			<span class="fa fa-paper-plane"></span>
			Request {{$request_tranche}}
		</small>
	</button>
</div>

	@push('page_scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			
			$('.offline-request-for-related-tranche').hide();

		    //Show Modal for Request of related tranche
		    $(document).on('click', ".btn-modal-request-for-related-tranche", function(e) {
		        $('#div-request-related-tranche-modal-error').hide();
		        $('#frm-request-related-tranche-modal').trigger("reset");

		        $('#mdl-request-related-tranche-modal').modal('show');

		        $("#spinner-request-related-tranche").hide();
		        $("#btn-save-mdl-request-related-tranche").attr('disabled', false);
		    });

			// save new tranche submission request
			$('#btn-save-mdl-request-related-tranche').click(function(e) {
				e.preventDefault();
	        	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

	        	//check for internet status 
		        if (!window.navigator.onLine) {
		            $('.offline-request-for-related-tranche').fadeIn(300);
		            return;
		        }else{
		            $('.offline-request-for-related-tranche').fadeOut(300);
		        }


		        swal({
	                title: "Please confirm the initiation of your request for {{$request_tranche}}",
	                text: "You will be redirected to provide all neccessary attachments before final submission to TETFund.",
	                type: "warning",
	                showCancelButton: true,
	                confirmButtonClass: "btn-danger",
	                confirmButtonText: "Yes proceed",
	                cancelButtonText: "No don't proceed",
	                closeOnConfirm: false,
	                closeOnCancel: true

	            }, function(isConfirm) {

	            	if (isConfirm) {
	            		swal({
	                        title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Processing...',
	                        text: 'Please wait while {{$request_tranche}} request is being initiated <br><br> Do not refresh this page! ',
	                        showConfirmButton: false,
	                        allowOutsideClick: false,
	                        html: true
	                    })

			        	let formData = new FormData();
			        	formData.append('_token', $('input[name="_token"]').val());
				        formData.append('_method', 'POST');

				        if ('{{$tf_iterum_intervention_line_key_id}}'.length){ formData.append('tf_iterum_intervention_line_key_id', '{{$tf_iterum_intervention_line_key_id}}'); }
				        if ('{{$title}}'.length){ formData.append('title', '{{$title}}'); }
				        if ('{{$intervention_year1}}'.length){ formData.append('intervention_year1', '{{$intervention_year1}}'); }
				        if ('{{$intervention_year2}}'.length){ formData.append('intervention_year2', '{{$intervention_year2}}'); }
				        if ('{{$intervention_year3}}'.length){ formData.append('intervention_year3', '{{$intervention_year3}}'); }
				        if ('{{$intervention_year4}}'.length){ formData.append('intervention_year4', '{{$intervention_year4}}'); }
				        if ('{{$amount_requested}}'.length){ formData.append('amount_requested', '{{$amount_requested}}'); }
				        if ('{{$parent_id}}'.length){ formData.append('parent_id', '{{$parent_id}}'); }
				        if ('{{$request_tranche}}'.length){ formData.append('request_tranche', '{{$request_tranche}}'); }
				        
				        @if(isset($is_first_tranche_request))
				        	if ('{{$is_first_tranche_request}}'.length){ formData.append('is_first_tranche_request', '{{$is_first_tranche_request}}'); }
				        @endif

				        @if(isset($is_second_tranche_request))
				        	if ('{{$is_second_tranche_request}}'.length){ formData.append('is_second_tranche_request', '{{$is_second_tranche_request}}'); }
				        @endif

				        @if(isset($is_final_tranche_request))
					        if ('{{$is_final_tranche_request}}'.length){ formData.append('is_final_tranche_request', '{{$is_final_tranche_request}}'); }
					    @endif

				        @if(isset($is_monitoring_request))
				        	if ('{{$is_monitoring_request}}'.length){ formData.append('is_monitoring_request', '{{$is_monitoring_request}}'); }
				        @endif

				        $.ajax({
				            url: "{{ route('tf-bi-portal-api.submission_requests.store') }}",
				            type: "POST",
				            data: formData,
				            cache: false,
				            processData:false,
				            contentType: false,
				            dataType: 'json',
				            success: function(result) {
				                if(result.errors) {
				                	swal.close();
									$('#div-request-related-tranche-modal-error').html('');
									$('#div-request-related-tranche-modal-error').show();
				                    
				                    $.each(result.errors, function(key, value){
				                        $('#div-request-related-tranche-modal-error').append('<li class="">'+value+'</li>');
				                    });
				                } else {
				                    $('#div-request-related-tranche-modal-error').hide();
				                    console.log(result);
				                    let redirect_link = window.location.origin +'/tf-bi-portal/submissionRequests/'+ result.data.id;
				                    swal({
				                        title: "Submission Request Saved",
				                        text: "{{$request_tranche}} Submission Request Saved Successfully!",
				                        type: "success"
				                    });
				                    window.location.href = redirect_link;
				                }

				                $("#spinner-request-related-tranche").hide();
				                $("#btn-save-mdl-request-related-tranche").attr('disabled', false);
				                
				            }, error: function(data) {
				                console.log(data);
				                swal("Error", "Oops an error occurred. Please try again.", "error");

				                $("#spinner-request-related-tranche").hide();
				                $("#btn-save-mdl-request-related-tranche").attr('disabled', false);

				            }
				        });
				    }

	            });


			});
		});
	</script>
	@endpush
@endif







