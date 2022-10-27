

<div class="modal fade" id="mdl-submissionRequest-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-submissionRequest-modal-title" class="modal-title">Submission Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-submissionRequest-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-submissionRequest-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row">
                        <div class="col-lg-12 m-3">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-submission_requests">You are currently offline</span></div>

                            <div id="spinner-submission_requests" class="spinner-border text-primary" role="status"> 
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            <input type="hidden" id="txt-submissionRequest-primary-id" value="0" />
                            <div id="div-show-txt-submissionRequest-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('pages.submission_requests.show_fields')
                                    </div>
                                </div>
                            </div>
                            <div id="div-edit-txt-submissionRequest-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('pages.submission_requests.fields')
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-submissionRequest-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-submissionRequest-modal" value="add">Save</button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-submission_requests').hide();

    /*//Show Modal for New Entry
    $(document).on('click', ".btn-new-mdl-submissionRequest-modal", function(e) {
        $('#div-submissionRequest-modal-error').hide();
        $('#mdl-submissionRequest-modal').modal('show');
        $('#frm-submissionRequest-modal').trigger("reset");
        $('#txt-submissionRequest-primary-id').val(0);

        $('#div-show-txt-submissionRequest-primary-id').hide();
        $('#div-edit-txt-submissionRequest-primary-id').show();

        $("#spinner-submission_requests").hide();
        $("#div-save-mdl-submissionRequest-modal").attr('disabled', false);
    });*/

    //Show Modal for View
    $(document).on('click', ".btn-show-mdl-submissionRequest-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-submission_requests').fadeIn(300);
            return;
        }else{
            $('.offline-submission_requests').fadeOut(300);
        }

        $('#div-submissionRequest-modal-error').hide();
        $('#mdl-submissionRequest-modal').modal('show');
        $('#frm-submissionRequest-modal').trigger("reset");

        $("#spinner-submission_requests").show();
        $("#div-save-mdl-submissionRequest-modal").attr('disabled', true);

        $('#div-show-txt-submissionRequest-primary-id').show();
        $('#div-edit-txt-submissionRequest-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.submission_requests.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-submissionRequest-primary-id').val(response.data.id);
            		$('#spn_submissionRequest_title').html(response.data.title);
		$('#spn_submissionRequest_status').html(response.data.status);
		$('#spn_submissionRequest_type').html(response.data.type);
		$('#spn_submissionRequest_tf_iterum_portal_request_status').html(response.data.tf_iterum_portal_request_status);


            $("#spinner-submission_requests").hide();
            $("#div-save-mdl-submissionRequest-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    /*$(document).on('click', ".btn-edit-mdl-submissionRequest-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-submissionRequest-modal-error').hide();
        $('#mdl-submissionRequest-modal').modal('show');
        $('#frm-submissionRequest-modal').trigger("reset");

        $("#spinner-submission_requests").show();
        $("#div-save-mdl-submissionRequest-modal").attr('disabled', true);

        $('#div-show-txt-submissionRequest-primary-id').hide();
        $('#div-edit-txt-submissionRequest-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.submission_requests.show','') }}/"+itemId).done(function( response ) {     

			$('#txt-submissionRequest-primary-id').val(response.data.id);
            		$('#title').val(response.data.title);
		$('#status').val(response.data.status);
		$('#type').val(response.data.type);
		$('#tf_iterum_portal_request_status').val(response.data.tf_iterum_portal_request_status);


            $("#spinner-submission_requests").hide();
            $("#div-save-mdl-submissionRequest-modal").attr('disabled', false);
        });
    });*/

    //Delete action
    $(document).on('click', ".btn-delete-mdl-submissionRequest-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-submission_requests').fadeIn(300);
            return;
        }else{
            $('.offline-submission_requests').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this SubmissionRequest?",
                text: "You will not be able to recover this SubmissionRequest if deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.destroy','') }}/"+itemId;

                    let formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('_method', 'DELETE');
                    
                    $.ajax({
                        url:endPointUrl,
                        type: "POST",
                        data: formData,
                        cache: false,
                        processData:false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result){
                            if(result.errors){
                                console.log(result.errors)
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }else{
                                swal({
                                        title: "Deleted",
                                        text: "SubmissionRequest deleted successfully",
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    },function(){
                                        location.reload(true);
                                });
                            }
                        },
                    });
                }
            });

    });

     //Delete action attachement
    $(document).on('click', ".btn-delete-mdl-submissionRequest-attachement", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-submission_requests').fadeIn(300);
            return;
        }else{
            $('.offline-submission_requests').fadeOut(300);
        }

        let submissionRequestId = "{{ isset($submissionRequest->id) ? $submissionRequest->id : '' }}";
        let attachment_label = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this SubmissionRequest Attachment?",
                text: "You will not be able to recover this Attachment if deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.destroy','') }}/"+submissionRequestId;

                    let formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('_method', 'DELETE');
                    formData.append('submissionRequestId', submissionRequestId);
                    formData.append('attachment_label', attachment_label);
                    
                    $.ajax({
                        url:endPointUrl,
                        type: "POST",
                        data: formData,
                        cache: false,
                        processData:false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result){
                            if(result.errors){
                                console.log(result.errors)
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }else{
                                swal({
                                    title: "Deleted",
                                    text: "SubmissionRequest Attachment deleted successfully",
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false
                                });
                                location.reload(true);
                            }
                        },
                    });
                }
            });

    });

    //Save details
    /*$('#btn-save-mdl-submissionRequest-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-submission_requests').fadeIn(300);
            return;
        }else{
            $('.offline-submission_requests').fadeOut(300);
        }

        $("#spinner-submission_requests").show();
        $("#div-save-mdl-submissionRequest-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.store') }}";
        let primaryId = $('#txt-submissionRequest-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif
        // formData.append('', $('#').val());
        		if ($('#title').length){	formData.append('title',$('#title').val());	}
		if ($('#status').length){	formData.append('status',$('#status').val());	}
		if ($('#type').length){	formData.append('type',$('#type').val());	}
		if ($('#tf_iterum_portal_request_status').length){	formData.append('tf_iterum_portal_request_status',$('#tf_iterum_portal_request_status').val());	}


        $.ajax({
            url:endPointUrl,
            type: "POST",
            data: formData,
            cache: false,
            processData:false,
            contentType: false,
            dataType: 'json',
            success: function(result){
                if(result.errors){
					$('#div-submissionRequest-modal-error').html('');
					$('#div-submissionRequest-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-submissionRequest-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-submissionRequest-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-submissionRequest-modal-error').hide();

                        swal({
                                title: "Saved",
                                text: "SubmissionRequest saved successfully",
                                type: "success"
                            },function(){
                                location.reload(true);
                        });

                    },20);
                }

                $("#spinner-submission_requests").hide();
                $("#div-save-mdl-submissionRequest-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-submission_requests").hide();
                $("#div-save-mdl-submissionRequest-modal").attr('disabled', false);

            }
        });
    });*/

});
</script>
@endpush
