<div class="modal fade" id="mdl-request-monitoring-evaluation-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-request-monitoring-evaluation-modal-title" class="modal-title">
                    <span id="prefix_info"></span><span id='new_or_old_m_r'></span> Monitoring Request 
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-request-monitoring-evaluation-modal-error" class="alert alert-danger" role="alert"></div>
            
                <form class="form-horizontal" id="frm-request-monitoring-evaluation-modal" role="form" method="POST" action="">
                    <div class="row m-3">
                        <div class="col-sm-12">
                            @csrf
                            <div class="offline-flag">
                                <span class="offline-request-for-monitoring-evaluation">
                                    You are currently offline
                                </span>
                            </div>

                            <input type="hidden" id="m_r_primary_id" value="0">

                            <div class="row col-sm-12">                             
                                <div class="form-group" style="margin-top:10px;">
                                    <label class="col-sm-12 control-label" for="project_title">
                                        Project Title:
                                    </label>
                                    <div class="col-sm-12">
                                        <div class="input-group"> 
                                            <input class="form-control" disabled="disabled" id="project_title" value="{{$submissionRequest->title??''}}"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top:10px;">
                                    <label class="col-sm-12 control-label" for="type_of_monitoring_request">
                                        Type of Monitoring Request:
                                    </label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <select class="form-select" id="type_of_monitoring_request">
                                                <option value="">-- none selected --</option>
                                                <option value="Invitation for Bid Opening">Invitation for Bid Opening</option>
                                                <option value="Project Monitoring">Project Monitoring</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top:10px;">
                                    <label class="col-sm-12 control-label" for="proposed_monitoring_date">
                                        Proposed Monitoring Date:
                                    </label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input type='date' class="form-control" id="proposed_monitoring_date" value="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top:10px;">
                                    <label class="col-sm-12 control-label" for="optional_attachment">
                                        Attachment (Optional):<br>
                                        <small>
                                            <span id="editing_old_m_r_notice" class="text-danger">
                                                <i><strong>NOTE:</strong> 
                                                Selecting a new attachment will authomatically replace any existing attachment.</i>
                                            </span>
                                        </small>
                                    </label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input type='file' class="form-control" id="optional_attachment" />
                                        </div>
                                        <small><i class="text-danger">Max file Size 50M</i></small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        
            <div class="modal-footer" id="div-save-mdl-request-monitoring-evaluation-modal">
                <button type="button" class="btn btn-primary btn-save-mdl-request-monitoring-evaluation" id="btn-save-mdl-request-monitoring-evaluation" value="add">
                <div id="spinner-request-monitoring-evaluation" style="color: white;">
                    <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                    </div>
                    <span class="">Loading...</span><hr>
                </div>
                <span class="fa fa-save"></span> Save Monitoring Request
                </button>
            </div>

        </div>
    </div>
</div>


@push('page_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.offline-request-for-monitoring-evaluation').hide();

         //Show Modal for Request of related tranche
        $(document).on('click', ".btn-modal-request-for-monitoring-evaluation", function(e) {
            $('#div-request-monitoring-evaluation-modal-error').hide();
            $('#frm-request-monitoring-evaluation-modal').trigger("reset");
            $('#new_or_old_m_r').text("New");
            $('#editing_old_m_r_notice').hide();
            $('#mdl-request-monitoring-evaluation-modal').modal('show');

            $("#spinner-request-monitoring-evaluation").hide();
            $("#btn-save-mdl-request-monitoring-evaluation").attr('disabled', false);
        });


        // submit monitoring request to TETFund
        $(document).on('click', ".btn-submit-m-r", function(e) {
            e.preventDefault();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            let itemId = $(this).attr('data-val');
            swal({
                title: "You are about to process the final submission of this Monitoring Request to TETFund?",
                text: "You will not be able to undo this process once completed.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, submit",
                cancelButtonText: "No, don't submit",
                closeOnConfirm: false,
                closeOnCancel: true

            }, function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: '<div id="spinner-request-monitoring" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Processing...',
                        text: 'Please wait, validating requirements and submitting this monitoring request. <br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });

                    let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.process_m_r_to_tetfund','') }}/"+itemId;

                    let formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('_method', 'POST');
                    formData.append('id', itemId);
                    
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
                                    title: "Submission Completed",
                                    text: "Monitoring request submitted successfully",
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false
                                });
                                location.reload(true);
                            }
                        }, error: function(data) {
                            console.log(data);
                            swal("Error", "Oops an error occurred. Please try agaisssssn.", "error");
                        }
                    });
                }
            });

        });

        // Show Modal for Edit
        $(document).on('click', ".btn-edit-m-r", function(e) {
            e.preventDefault();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            //check for internet status 
            if (!window.navigator.onLine) {
                $('.offline-request-for-monitoring-evaluation').fadeIn(300);
                return;
            }else{
                $('.offline-request-for-monitoring-evaluation').fadeOut(300);
            }

            $('#div-request-monitoring-evaluation-modal-error').hide();
            $('#frm-request-monitoring-evaluation-modal').trigger("reset");
            $('#new_or_old_m_r').text("Edit");
            $('#mdl-request-monitoring-evaluation-modal').modal('show');

            $("#spinner-request-monitoring-evaluation").show();
            $("#btn-save-mdl-request-monitoring-evaluation").attr('disabled', true);

            let itemId = $(this).attr('data-val');

            $.get( "{{ route('tf-bi-portal-api.submission_requests.show','') }}/"+itemId).done(function( response ) {
                let proposed_monitoring_date= new Date(response.data.proposed_request_date).toISOString().slice(0, 10);
                $('#m_r_primary_id').val(response.data.id);
                $('#project_title').val(response.data.title);
                $('#type_of_monitoring_request').val(response.data.type);
                $('#proposed_monitoring_date').val(proposed_monitoring_date);

                $('#editing_old_m_r_notice').show();
                $("#spinner-request-monitoring-evaluation").hide();
                $("#btn-save-mdl-request-monitoring-evaluation").attr('disabled', false);
            });
        });

        // Delete action for monitoring request
        $(document).on('click', ".btn-delete-m-r", function(e) {
            e.preventDefault();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            let itemId = $(this).attr('data-val');
            swal({
                title: "Are you sure you want to delete this Monitoring Request?",
                text: "You will not be able to recover this request once deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete",
                cancelButtonText: "No, don't delete",
                closeOnConfirm: false,
                closeOnCancel: true

            }, function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: '<div id="spinner-request-monitoring" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Processing...',
                        text: 'Please wait, deleting this monitoring request <br><br> Do not refresh this page! ',
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
                                    text: "Monitoring request deleted successfully",
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false
                                });
                                @if(isset($delete_from_monitoring_details_to_card_list))
                                    location.href = "{{ route('tf-bi-portal.monitoring') }}";
                                @else
                                    location.reload(true);
                                @endif
                            }
                        },
                    });
                }
            });

        });


        // save monitoring submission request
        $('#btn-save-mdl-request-monitoring-evaluation').click(function(e) {
            e.preventDefault();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            //check for internet status 
            if (!window.navigator.onLine) {
                $('.offline-request-for-monitoring-evaluation').fadeIn(300);
                return;
            }else{
                $('.offline-request-for-monitoring-evaluation').fadeOut(300);
            }


            swal({
                title: "Please confirm the request for Monitoring and Evaluation",
                text: "Your request for Minitoring and Evaluation will be processed once comfirmed.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes request",
                cancelButtonText: "No don't request",
                closeOnConfirm: false,
                closeOnCancel: true

            }, function(isConfirm) {

                if (isConfirm) {
                    swal({
                        title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Processing...',
                        text: 'Please wait while request for Monitoring and Evaluation is being processed <br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });

                    let formData = new FormData();
                    let primaryId = $('#m_r_primary_id').val();
                    let actionTypeAlert = 'Saved';  
                    let actionType = "POST";
                    let endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.store') }}";

                    if (primaryId != "0"){
                        actionType = "PUT";
                        endPointUrl = "{{ route('tf-bi-portal-api.submission_requests.update','') }}/"+primaryId;
                        formData.append('id', primaryId);
                        actionTypeAlert = 'Updated';
                    }

                    formData.append('_method', actionType);
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('is_monitoring_request', 1);

                    if ($('#type_of_monitoring_request').length){ formData.append('type', $('#type_of_monitoring_request').val()); }
                    if ($('#proposed_monitoring_date').length){ formData.append('proposed_request_date', $('#proposed_monitoring_date').val()); }
                    
                    @if(isset($can_request_monitoring) && $can_request_monitoring==true)
                        if(primaryId=='0') {
                            if ('{{$title}}'.length){ formData.append('title', '{{$title}}'); }
                            if ('{{$tf_iterum_intervention_line_key_id}}'.length){ formData.append('tf_iterum_intervention_line_key_id', '{{$tf_iterum_intervention_line_key_id}}'); }
                            if ('{{$intervention_year1}}'.length){ formData.append('intervention_year1', '{{$intervention_year1}}'); }
                            if ('{{$intervention_year2}}'.length){ formData.append('intervention_year2', '{{$intervention_year2}}'); }
                            if ('{{$intervention_year3}}'.length){ formData.append('intervention_year3', '{{$intervention_year3}}'); }
                            if ('{{$intervention_year4}}'.length){ formData.append('intervention_year4', '{{$intervention_year4}}'); }
                            if ('{{$parent_id}}'.length){ formData.append('parent_id', '{{$parent_id}}'); }
                            if ('{{$amount_requested}}'.length){ formData.append('amount_requested', '{{$amount_requested}}'); }
                        }
                    @endif

                    if($('#optional_attachment').get(0).files.length != 0){
                        formData.append('optional_attachment', $('#optional_attachment')[0].files[0]);
                    }

                    $.ajax({
                        url: endPointUrl,
                        type: "POST",
                        data: formData,
                        cache: false,
                        processData:false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result) {
                            if(result.errors) {
                                swal.close();
                                $('#div-request-monitoring-evaluation-modal-error').html('');
                                $('#div-request-monitoring-evaluation-modal-error').show();
                                
                                $.each(result.errors, function(key, value){
                                    $('#div-request-monitoring-evaluation-modal-error').append('<li class="">'+value+'</li>');
                                });
                            } else {
                                $('#div-request-monitoring-evaluation-modal-error').hide();
                                
                                swal({
                                    title: "Submission Request Saved",
                                    text: "Monitoring Request Submission "+ actionTypeAlert +" Successfully!",
                                    type: "success"
                                });
                                
                                @if(isset($redired_to_details_page_on_edit_from_card_list))
                                    location.href = "{{ route('tf-bi-portal.showMonitoring', '') }}/"+primaryId;
                                @else
                                    window.location.reload(true);
                                @endif
                            }

                            $("#spinner-request-monitoring-evaluation").hide();
                            $("#btn-save-mdl-request-monitoring-evaluation").attr('disabled', false);
                            
                        }, error: function(data) {
                            console.log(data);
                            swal("Error", "Oops an error occurred. Please try again.", "error");

                            $("#spinner-request-monitoring-evaluation").hide();
                            $("#btn-save-mdl-request-monitoring-evaluation").attr('disabled', false);

                        }
                    });
                }

            });


        });
    });
</script>
@endpush
