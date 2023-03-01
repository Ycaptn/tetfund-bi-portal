@php
	// begin monitoring request submission request
	$submissionRequest_aip_request = $submissionRequest->getParentAIPSubmissionRequest();
	$tf_iterum_intervention_line_key_id = $submissionRequest_aip_request->tf_iterum_intervention_line_key_id;
	$title = $submissionRequest_aip_request->title;
	$intervention_year1 = $submissionRequest_aip_request->intervention_year1;
	$intervention_year2 = $submissionRequest_aip_request->intervention_year2;
	$intervention_year3 = $submissionRequest_aip_request->intervention_year3;
	$intervention_year4 = $submissionRequest_aip_request->intervention_year4;
	$parent_id = $submissionRequest_aip_request->id;
	$amount_requested = $submissionRequest_aip_request->amount_requested;
	$can_request_monitoring = true;

@endphp

<div class="{{ !empty($get_all_related_requests) ? 'col-sm-6' : 'col-sm-4' }} p-2">
	<button class="col-sm-12 btn btn-info btn-sm btn-modal-request-for-monitoring-evaluation">
		<small>
			<span class="fa fa-camera"></span>
			Request Monitoring
		</small>
	</button>
</div>

@include('tf-bi-portal::pages.monitoring.modal')