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


{{-- modal to show uploaded Committee minutes of meetings --}}
<div class="modal fade" id="modal_uploaded_committee_minutes_meetings" tabindex="-1" role="dialog" aria-labelledby="creator-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-100vh">
        <div class="modal-content modal-content-95vh">
            <div class="modal-header">
                <h4 class="modal-title" id="creator-modal-label"> <small>Most recently uploaded <span class="nomination_type_name">committee</span> minutes of meeting</small></h4>
                <button id="creator-modal-close-button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12 row">

                    <div class="offline-minutes-meeting-uploaded"><span class="offline">You are currently offline</span></div>
                    <div id="div_uploaded_committee_minutes_meetings_errs" class="alert alert-danger" role="alert"></div>
                    
                    <form id="form_uploaded_committee_minutes_meetings_modal" class="form-horizontal" role="form" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" id="minute_uploaded_nomination_type" value="">
                        <input type="hidden" id="minute_uploaded_primary_id" value="0">
                        
                        <div class="col-sm-12">
                            <div class="col-sm-12 text-justify text-danger">
                                <i>
                                    <strong>Note:</strong> Only the most-recently uploaded minutes of meeting which has been uploaded by the <span class="nomination_type_name">nomination committee</span> and has <strong>not been used in any of your Submission(s)</strong> is display. It will be considered <strong>used</strong> once the submission request is completed and successfully <strong>forwarded to TETFund</strong>. 
                                </i>                                
                            </div>    
                        </div>
                        <hr>

                        <div class="row col-sm-12">
                            <div class="col-sm-5">
                                <strong>
                                    Recently uploaded minutes of meeting: &nbsp; &nbsp;
                                </strong>
                            </div>
                            <div class="col-sm-7">
                                <i>
                                    <small class="text-danger" id="attachment_html_show">
                                        No document available!
                                    </small>
                                </i>
                            </div>
                        </div>
                        <hr>
                        
                        <br>
                        <div class="form-group col-sm-10">
                            <label class="form-label" for="bind_uploaded_minutes_to_checklist">
                                <strong>
                                    Select and attach the uploaded minutes of meeting to a checklist item.
                                </strong>
                            </label>
                            
                            <select class="form-select" name="bind_uploaded_minutes_to_checklist" id="bind_uploaded_minutes_to_checklist">
                                <option value="">-- No checklist selected --</option>
                                @if(isset($checklist_items) && count($checklist_items) > 0)
                                    @foreach($checklist_items as $checklist_item)
                                        <option value="{{$checklist_item->item_label}}">{{$checklist_item->item_label}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <br>
                        </div>                  

                    </form>
                </div>
            </div>
            <div class="modal-footer text-left col-sm-12" >
                <div class="form-group">
                    <button type="button" name="btn_process_uploaded_minutes_of_meeting" class="btn btn-sm btn-primary" id="btn_process_uploaded_minutes_of_meeting">
                        <div id="spinner-committee-minutes-of-meeting" style="color: white; display:none;" class="spinner-committee-minutes-of-meeting">
                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                            </div>
                            <span class="">Loading...</span><hr>
                        </div>

                        <i class="fa fa-chain" style="opacity:80%"></i>

                        Use uploaded minutes of meeting as selected checklist item.
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-submission_requests').hide();
    $('.offline-minutes-meeting-uploaded').hide();

    //Show Modal for uploaded committee minutes of meetings
    $(document).on('click', ".btn-committee-last-minute-of-meeting-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div_uploaded_committee_minutes_meetings_errs').hide();
        $('#modal_uploaded_committee_minutes_meetings').modal('show');
        $('#form_uploaded_committee_minutes_meetings_modal').trigger("reset");

        $(".spinner-committee-minutes-of-meeting").show();
        $("#btn_process_uploaded_minutes_of_meeting").attr('disabled', true);
        
        let nomination_type = $(this).attr('data-val');
        $('#minute_uploaded_nomination_type').val(nomination_type);

        $.get( "{{ route('tf-bi-portal-api.committee_meeting_minutes.show', '') }}/"+nomination_type).done(function( response ) {
                    
            let attachment = (response.data.attachables[0]) ? response.data.attachables[0].attachment : null;

            if(attachment != null) {
                let attachment_link = window.location.origin +'/attachment/'+attachment.id;
                let attachment_html = "<u class='text-primary'><a href='"+ attachment_link +"' target='__blank'>"+ attachment.label +"</a></u><br>"+ attachment.description +"<br><b>MODIFIED: </b>" + new Date(response.data.updated_at).toDateString()+ '.' + "<br><b>BY: </b>" + response.data.user.full_name +'.';
                
                console.log(attachment_html);
                $('#minute_uploaded_primary_id').val(attachment.id);
                $('#attachment_html_show').html(attachment_html);
                
                $("#btn_process_uploaded_minutes_of_meeting").attr('disabled', false);
            }

            $(".spinner-committee-minutes-of-meeting").hide();
        });

    });

    //process the uploaded committee minutes of meeting binding to checklist
    $(document).on('click', "#btn_process_uploaded_minutes_of_meeting", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-minutes-meeting-upload').fadeIn(300);
            return;
        }else{
            $('.offline-minutes-meeting-upload').fadeOut(300);
        }

        let minute_uploaded_nomination_type = $('#minute_uploaded_nomination_type').val()
        let itemType = minute_uploaded_nomination_type.toUpperCase();

        swal({
            title: "You are about to tie the uploaded committee minutes of meeting to the selected " + itemType + "-Nomination checklist item !",
            text: "This action can be modified subsequently. Modification is no longer possible once this Submission request is completed and forwarded to TETFund by institution Desk-Officer.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, use this file",
            cancelButtonText: "No, don't use this file",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
                $(".spinner-committee-minutes-of-meeting").show();
                $("#btn_process_uploaded_minutes_of_meeting").attr('disabled', true);

                let endPointUrl = "{{ route('tf-bi-portal-api.committee_meeting_minutes.update', '') }}/" + '{{$submissionRequest->id ?? '0'}}';

                let formData = new FormData();
                formData.append('_token', $('input[name="_token"]').val());
                formData.append('_method', 'PUT');
                formData.append('nomination_type', minute_uploaded_nomination_type);
                formData.append('checklist_item_name', $('#bind_uploaded_minutes_to_checklist').val());
                formData.append('minute_uploaded_primary_id', $('#minute_uploaded_primary_id').val());
                formData.append('submissioin_request_primary_id', '{{$submissionRequest->id ?? '0'}}');
                
                $.ajax({
                    url:endPointUrl,
                    type: "POST",
                    data: formData,
                    cache: false,
                    processData:false,
                    contentType: false,
                    dataType: 'json',
                    success: function(result){                                
                        if(result.errors || (result.status && result.status == 'fail' && result.response)) {
                            $('#div_uploaded_committee_minutes_meetings_errs').html('');
                            $('#div_uploaded_committee_minutes_meetings_errs').show();
                            
                            let response_arr = (result.errors) ? result.errors : result.response;
                            $.each(response_arr, function(key, value){
                                $('#div_uploaded_committee_minutes_meetings_errs').append('<li class="">'+value+'</li>');
                            });

                            $(".spinner-committee-minutes-of-meeting").hide();
                            $("#btn_process_uploaded_minutes_of_meeting").attr('disabled', false);
                        }else{
                            swal({
                                title: "Successful",
                                text: itemType + "-Nomination Committee minutes of meeting has been tied to selected checklist item and saved successfully",
                                type: "success",
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            });
                            location.reload(true);
                        }
                    }, error: function(data){
                        console.log(data);
                        swal("Error", "Oops an error occurred. Please try again.", "error");

                        $(".spinner-committee-minutes-of-meeting").hide();
                        $("#btn_process_uploaded_minutes_of_meeting").attr('disabled', false);

                    }
                });
            }
        });

    });

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
                    swal({
                        title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Deleting...',
                        text: 'Please wait while SubmissionRequest is being deleted <br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });

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
                    swal({
                        title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Deleting...',
                        text: 'Please wait while SubmissionRequest Attachment is being deleted <br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });

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
