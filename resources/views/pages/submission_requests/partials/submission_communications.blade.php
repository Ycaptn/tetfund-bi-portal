@php
	$array_of_contents = array();
@endphp

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

<div class="col-sm-12">
    <table class="table table-striped  well well-sm">
    	<thead>
    		<tr>
    			<th style="width:5%;">S/N</th>
    			<th style="width:40%;">Title</th>
    			<th style="width:20%;">Label</th>
    			<th style="width:15%;">Date Released</th>
    			<th style="width:20%;">Action</th>
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
	    				<td>{{ date('jS-M-Y', strtotime($bi_request_communications->communication->updated_at ?? ''))}}</td>
	    				<td>
	    					<div class='btn-group' role="group">
							    <a data-toggle="tooltip" 
							        title="Preview Communication Content" 
							        data-val='{{$loop->iteration}}' 
							        class="btn-show-submission-communication-content" href="#">
							        Preview content
							    </a>
							</div>
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
				let itemId = $(this).attr('data-val');
				let html_encoded_content = JSON.parse(json_array_of_contents.replace(/[\r\n]+/gm, ''))[itemId];

				let html_decoded_content = $('<textarea />').html(html_encoded_content).text();
				
		        $('#display-submission-communication-content').html(html_decoded_content);
		        $('#mdl-submission-communication-content').modal('show');
		    }); 
		});
	</script>
@endpush


