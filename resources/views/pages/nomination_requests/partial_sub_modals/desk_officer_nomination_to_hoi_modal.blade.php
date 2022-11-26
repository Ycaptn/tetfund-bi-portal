<div class="modal fade" id="desk_officer_nomination_preview_to_hoi_modal" tabindex="-1" role="dialog" aria-labelledby="creator-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-100vh">
        <div class="modal-content modal-content-95vh">
            <div class="modal-header">
                <h4 class="modal-title" id="creator-modal-label"> Preview And Forward Nomination Details To Head of Institution </h4>
                <button id="creator-modal-close-button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                    <div class="col-lg-9" id='nomination_request_details'>
                        <div id="user_info_section" class="form-group row col-sm-12">
                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Fullname:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="full_name_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Email:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="email_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Telephone:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="telephone_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Beneficiary Institution:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="beneficiary_institution_id_select_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Gender:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="gender_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Bank Account Name:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="bank_account_name_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Bank Account Number:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="bank_account_number_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Bank Name:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="bank_name_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Bank Sort Code:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="bank_sort_code_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Intl Passport Number:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="intl_passport_number_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Bank Verification Number:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="bank_verification_number_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>National ID Number:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="national_id_number_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Degree Type:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="degree_type_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Program Title:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="program_title_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Program Type:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="program_type_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Is Sscience Program ?</b></label>
                                <div class="col-sm-12 ">
                                    <i id="is_science_program_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Program Start Date:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="program_start_date_data"></i>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4 mb-3">
                                <label class="col-sm-12"><b>Program End Date:</b></label>
                                <div class="col-sm-12 ">
                                    <i id="program_end_date_data"></i>
                                </div>
                            </div>
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
                        <div id="div_desk_officer_nomination_preview_to_hoi_modal_error" class="alert alert-danger" role="alert">
                        </div>
                        <form id="form_desk_officer_nomination_preview_to_hoi_modal" class="form-horizontal" role="form" method="POST">
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
                                    <button id="btn-forward-to-hoi" class="btn btn-sm btn-primary">
                                        <div id="spinner-forward-to-hoi" style="color: white; display: none;">
                                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                                            </div>
                                            <span class="">Loading...</span><hr>
                                        </div>

                                        <i class="fa fa-paper-plane" style="opacity:80%"></i>
                                        Forward to H.O.I
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

            //Show Modal for Nomination preview by Head Of Institution
            $(document).on('click', ".btn-show-preview-nomination_to_hoi", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div_desk_officer_nomination_preview_to_hoi_modal_error').hide();
                $('#desk_officer_nomination_preview_to_hoi_modal').modal('show');
                $('#form_desk_officer_nomination_preview_to_hoi_modal').trigger("reset");

                $("#spinner-forward-to-hoi").show();
                $("#btn-forward-to-hoi").attr('disabled', true);

                let itemId = $(this).attr('data-val');
                $('#nomination_request_id').val(itemId);
            
                $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {
                    
                    $('#full_name_data').text(response.data.nominee.first_name +' '+ response.data.nominee.middle_name +' '+ response.data.nominee.last_name);
                    $('#email_data').text(response.data.nominee.email);
                    $('#telephone_data').text(response.data.nominee.telephone);
                    $('#beneficiary_institution_id_select_data').text(response.data.nominee_beneficiary.full_name +' ('+ response.data.nominee_beneficiary.short_name +')');
                    $('#gender_data').text(response.data.nominee.gender);
                    $('#bank_account_name_data').text(response.data.nominee.bank_account_name);
                    $('#bank_account_number_data').text(response.data.nominee.bank_account_number);
                    $('#bank_name_data').text(response.data.nominee.bank_name);
                    $('#bank_sort_code_data').text(response.data.nominee.bank_sort_code);
                    $('#intl_passport_number_data').text(response.data.nominee.intl_passport_number);
                    $('#bank_verification_number_data').text(response.data.nominee.bank_verification_number);
                    $('#national_id_number_data').text(response.data.nominee.national_id_number);
                    $('#degree_type_data').text(response.data.nominee.degree_type);
                    $('#program_title_data').text(response.data.nominee.program_title);
                    $('#program_type_data').text(response.data.nominee.program_type);
                    $('#is_science_program_data').text((response.data.nominee.is_science_program == 'true') ? 'Yes' : 'No');
                    $('#program_start_date_data').text(new Date(response.data.nominee.program_start_date).toDateString());
                    $('#program_end_date_data').text(new Date(response.data.nominee.program_end_date).toDateString());
                    $('#nomination_type').val(response.data.nomination_request_type);
                    
                    // attachments
                    let = attachments_html = '';
                    $.each(response.data.attachments, function(key, attachment){
                        let link = attachment.path;
                        link = link.replace('public/', '');
                        link = window.location.origin +'/'+ link;
                        attachments_html += "<div class='col-sm-4'><small><a href='"+ link +"' target='__blank'>"+ attachment.label +"</a><br><i>"+ attachment.description +"</i></small></div>";
                    });

                    $('#nomination_request_attachments').html(attachments_html);
                    $('#date_details_submitted').text('This ' + response.data.nomination_request_type.toUpperCase() + ' Nomination Request Details was submitted on ' + new Date(response.data.nominee.created_at).toDateString());

                    $("#spinner-forward-to-hoi").hide();
                    $("#btn-forward-to-hoi").attr('disabled', false);
               });
            });


            //process nomination forwarding to hoi actions
            $(document).on('click', "#btn-forward-to-hoi", function(e) {
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
                let column_to_update = 'is_desk_officer_check_after_average_commitee_members_checked';

                swal({
                    title: "Are you sure you want to forward this " + itemType + " request to H.O.I for approval?",
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
                            text: 'Forwarding '+ itemType +' request to H.O.I <br><br> Do not refresh this page! ',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            html: true
                        })

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
                                        text: itemType + " request forwarded to H.O.I for approval successfully",
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
