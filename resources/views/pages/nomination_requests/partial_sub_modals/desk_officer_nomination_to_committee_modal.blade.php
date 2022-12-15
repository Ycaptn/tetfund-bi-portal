<div class="modal fade" id="desk_officer_nomination_preview_modal" tabindex="-1" role="dialog" aria-labelledby="creator-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-100vh">
        <div class="modal-content modal-content-95vh">
            <div class="modal-header">
                <h4 class="modal-title" id="creator-modal-label"> Preview And Forward Nomination Details To Committee </h4>
                <button id="creator-modal-close-button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                    <div class="col-lg-9" id='nomination_request_details'>
                        <div id="user_info_section" class="form-group col-sm-12">
                            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.partials_request_nomination.preview_all_fields')
                            <hr>
                            <div class="col-sm-12">
                                <div class="col-sm-12 text-center" style="border-bottom: 1px solid lightgray;">
                                    <strong>ATTACHMENTS</strong>
                                </div>
                                <div id="nomination_request_attachments" class="row col-sm-12"></div>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-3">
                        <div id="div_desk_officer_nomination_preview_modal_error" class="alert alert-danger" role="alert">
                        </div>
                        <form id="form_desk_officer_nomination_preview_modal" class="form-horizontal" role="form" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" id="nomination_request_id" value="0">
                            <input type="hidden" id="nomination_type" value="">
                            
                            <div class="offline-flag"><span class="offline-nomination_request">You are currently offline</span></div>

                        </form>
                        <div class="row">
                            <div class="col-sm-12 mb-3 mt-3 text-justify" id="date_details_submitted">
                            </div>
                            <div class="col-sm-12">
                                <center>
                                    <button id="btn-forward-to-committee" class="btn btn-sm btn-primary">
                                        <div id="spinner-forward-to-committee" style="color: white; display: none;">
                                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                                            </div>
                                            <span class="">Loading...</span><hr>
                                        </div>

                                        <i class="fa fa-paper-plane" style="opacity:80%"></i>
                                        Forward to Committee
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

            //Show Modal for Nomination preview by Desk Officer
            $(document).on('click', ".btn-show-preview-nomination", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div_desk_officer_nomination_preview_modal_error').hide();
                $('#desk_officer_nomination_preview_modal').modal('show');
                $('#form_desk_officer_nomination_preview_modal').trigger("reset");

                $("#spinner-forward-to-committee").show();
                $("#btn-forward-to-committee").attr('disabled', true);

                let itemId = $(this).attr('data-val');
                $('#nomination_request_id').val(itemId);
            
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
                    
                    $('#nomination_type').val(response.data.nomination_request_type);

                    // attachments
                    let = attachments_html = '';
                    $.each(response.data.attachments, function(key, attachment){
                        link = window.location.origin +'/tf-bi-portal/preview-attachement/'+attachment.id;
                        attachments_html += "<div class='col-sm-4'><small><a href='"+ link +"' target='__blank'>"+ attachment.label +"</a><br><i>"+ attachment.description +"</i></small></div>";
                    });

                    $('#nomination_request_attachments').html(attachments_html);
                    $('#date_details_submitted').text('This ' + response.data.nomination_request_type.toUpperCase() + ' Nomination Request Details was submitted on ' + new Date(response.data.nominee.created_at).toDateString());

                    $("#spinner-forward-to-committee").hide();
                    $("#btn-forward-to-committee").attr('disabled', false);
               });
            });


            //process nomination forwarding to committee actions
            $(document).on('click', "#btn-forward-to-committee", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-nomination_request').fadeIn(300);
                    return;
                }else{
                    $('.offline-nomination_request').fadeOut(300);
                }

                let itemId = $('#nomination_request_id').val();
                let itemType = $('#nomination_type').val().toUpperCase()+'Nomination';
                let column_to_update = 'is_desk_officer_check';

                swal({
                    title: "Are you sure you want to forward this request to " + itemType + " committee for consideration?",
                    text: "You will not be able to revert this process once initiated.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes forward",
                    cancelButtonText: "No don't forward",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {

                        let endPointUrl = "{{ route('tf-bi-portal-api.nomination_requests.process_forward_details','') }}/"+itemId;
                        
                        swal({
                            title: '<div id="spinner-beneficiaries" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                            text: 'Forwarding request to ' + itemType + ' committee members! <br><br> Do not refresh this page! ',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            html: true
                        });

                        let formData = new FormData();
                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('_method', 'PUT');
                        formData.append('id', itemId);
                        formData.append('column_to_update', column_to_update);
                        
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
                                        title: "Forwarded",
                                        text: "Request forwarded to " + itemType + " committee for consideration",
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

        });
    </script>
@endpush
