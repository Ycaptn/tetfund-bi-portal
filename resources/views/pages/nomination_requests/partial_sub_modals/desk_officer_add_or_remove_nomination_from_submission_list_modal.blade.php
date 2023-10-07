<div class="modal fade" id="add_and_remove_nomination_from_submission_list_modal" tabindex="-1" role="dialog" aria-labelledby="creator-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-100vh">
        <div class="modal-content modal-content-95vh">
            <div class="modal-header">
                <h4 class="modal-title" id="creator-modal-label"> Preview Nomination Request Details </h4>
                <button id="creator-modal-close-button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                    <div class="col-lg-9" id='nomination_request_details'>
                        <div id="user_info_section" class="form-group row col-sm-12">
                            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.partials_request_nomination.preview_all_fields')
                            <hr>
                            <div class="col-sm-12">
                                <div class="col-sm-12 text-center" style="border-bottom: 1px solid lightgray;">
                                    <strong>ATTACHMENTS</strong>
                                </div>
                                <div id="add_or_remove_nomination_request_attachments" class="row col-sm-12"></div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-3">
                        <form id="form_add_and_remove_nomination_from_submission_list_modal" class="form-horizontal" role="form" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" id="add_or_remove_nomination_request_id" value="0">
                            <input type="hidden" id="add_or_remove_nomination_type" value="">
                            
                            <div class="offline-flag"><span class="offline-nomination_request">You are currently offline</span></div>

                            <div class="col-sm-12 mb-3 mt-3 text-justify" id="add_or_remove_date_details_submitted"></div>
                            
                            <hr>

                            <div id="div_add_and_remove_nomination_from_submission_list_modal_error" class="alert alert-danger" role="alert"></div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12">
                                <center>
                                    <button id="button-modal-close-preview" type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal" aria-label="Close">
                                        <div id="spinner-add_and_remove_nomination_to_submission" style="color: white; display:none;" class="spinner-add_and_remove_nomination_to_submission">
                                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                                            </div>
                                            <span class="">Loading...</span><hr>
                                        </div>
                                        Close Preview
                                    </button>
                                </center>    
                            </div>
                        </div>
                    </div>

                </div>

            </div>
          <div class="text-left col-xs-12 panel" >
             <br><br>
          </div>

      </div>
  </div>
</div>



@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $('.offline-nomination_request').hide();

            //Show Modal for Nomination preview details by head of institution
            $(document).on('click', ".btn-nomination-request-preview-modal", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div_add_and_remove_nomination_from_submission_list_modal_error').hide();
                $('#add_and_remove_nomination_from_submission_list_modal').modal('show');
                $('#form_add_and_remove_nomination_from_submission_list_modal').trigger("reset");

                $(".spinner-add_and_remove_nomination_to_submission").show();
                $("#button-modal-close-preview").attr('disabled', true);

                $("#button-modal-close-preview").show();

                let itemId = $(this).attr('data-val');
                $('#add_or_remove_nomination_request_id').val(itemId);
            
                $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {
                    let nomination_type_lower_case = response.data.nomination_request_type.toLowerCase();
                    if (nomination_type_lower_case == 'tp') {
                        $('#preview_tp_details').show();
                        $('#preview_ca_details').hide();
                        $('#preview_tsas_details').hide();
                    } else if (nomination_type_lower_case == 'ca') {
                        $('#preview_ca_details').show();
                        $('#preview_tp_details').hide();
                        $('#preview_tsas_details').hide();
                    } else if (nomination_type_lower_case == 'tsas') {
                        $('#preview_tsas_details').show();
                        $('#preview_ca_details').hide();
                        $('#preview_tp_details').hide();
                    } else {
                        $('#preview_tp_details').hide();
                        $('#preview_ca_details').hide();
                        $('#preview_tsas_details').hide();
                    }
                    
                    let middle_name = (response.data.nominee.middle_name) ? response.data.nominee.middle_name : '';
                    
                    @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.partials_request_nomination.js_preview_all_fields')
                    
                    $('#add_or_remove_nomination_type').val(response.data.nomination_request_type);
                    
                    // attachments
                    let = attachments_html = '';
                    $.each(response.data.attachments, function(key, attachment){
                        link = window.location.origin +'/attachment/'+attachment.id;
                        attachments_html += "<div class='col-sm-4'><small><a href='"+ link +"' target='__blank'>"+ attachment.label +"</a><br><i>"+ attachment.description +"</i></small></div>";
                    });
                    $('#add_or_remove_nomination_request_attachments').html(attachments_html);
                    $('#add_or_remove_date_details_submitted').text('This ' + response.data.nomination_request_type.toUpperCase() + ' Nomination Request Details was submitted on ' + new Date(response.data.nominee.created_at).toDateString());

                    $(".spinner-add_and_remove_nomination_to_submission").hide();
                    $("#button-modal-close-preview").attr('disabled', false);
               });
            });


            //Select or Unselect nomination request from approved list meant for submission
            $(document).on('click', ".btn-add-or-remove-nomination-approval", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                let itemId = $(this).attr('data-val');
                let itemActionType = $(this).attr('data-val-type');

                let swal_confirm_mgs = '';
                let swal_spinner_mgs = '';
                let swal_success_mgs = '';
                let swal_success_mgs_details = '';
                
                if(itemActionType=='select') {
                    swal_success_mgs_details = 'Nomination request selected and added to submission list successfully';
                    swal_confirm_mgs = 'You are about to select and add this nomination request to submission list?';
                    swal_spinner_mgs = 'Adding up nomination request to submission list';
                    swal_success_mgs = 'Selected & Added';
                } else if(itemActionType=='unselect') {
                    swal_success_mgs_details = 'Nomination request removed from submission list successfully';
                    swal_confirm_mgs = 'You are about to remove this nomination request from submission list?';
                    swal_spinner_mgs = 'Removing nomination request from submission list';
                    swal_success_mgs = 'Removed';
                }

                swal({
                    title: swal_confirm_mgs ,
                    text: "Please confirm to proceed.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {

                        let endPointUrl = "{{ route('tf-bi-portal-api.remove_or_select_nomination_for_submission','') }}/"+itemId;

                        swal({
                            title: '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                            text: swal_spinner_mgs + '<br><br> Do not refresh this page! ',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            html: true
                        });

                        let formData = new FormData();
                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('itemActionType', itemActionType);
                        formData.append('_method', 'PUT');
                        
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
                                    console.log(result.errors);
                                    swal("Error", "Oops an error occurred. Please try again.", "error");
                                }else{
                                    swal({
                                        title: swal_success_mgs,
                                        text: swal_success_mgs_details,
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    });
                                    location.reload(true);
                                }
                            }, error: function(errors) {
                                console.log(errors);
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
