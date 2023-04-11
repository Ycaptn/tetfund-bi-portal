@php
	// begin monitoring request submission request
	$tf_iterum_intervention_line_key_id = $submissionRequest->tf_iterum_intervention_line_key_id;
	$request_tranche = "Monitoring Request";
	$title = str_replace('Request for AIP', $request_tranche, $parentAIPSubmissionRequest->title ?? $submissionRequest->getServerSideAIPRequest()->title ?? '');
	$title = str_replace('1st Tranche Payment', $request_tranche, $title);
	$intervention_year1 = $submissionRequest->intervention_year1;
	$intervention_year2 = $submissionRequest->intervention_year2;
	$intervention_year3 = $submissionRequest->intervention_year3;
	$intervention_year4 = $submissionRequest->intervention_year4;
	$parent_id = $submissionRequest->id;
	$amount_requested = $submissionRequest->amount_requested;
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