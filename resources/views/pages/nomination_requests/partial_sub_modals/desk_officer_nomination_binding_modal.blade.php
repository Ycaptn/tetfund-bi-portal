<div class="modal fade" id="desk_officer_nomination_binding_modal" tabindex="-1" role="dialog" aria-labelledby="creator-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-100vh">
        <div class="modal-content modal-content-95vh">
            <div class="modal-header">
                <h4 class="modal-title" id="creator-modal-label"> Preview And Bind Nomination Details To An Existing Submission </h4>
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
                        <div class="col-sm-12 mb-3 mt-3 text-justify" id="date_details_submitted"></div>
                        <div class="col-sm-12 mb-3 mt-3 text-justify" id="submission_binded_details"></div>
                        <form id="form_desk_officer_nomination_binding_modal" class="form-horizontal" role="form" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" id="nomination_request_id" value="0">
                            <input type="hidden" id="nomination_type" value="">
                            
                            <div class="offline-flag"><span class="offline-nomination_request">You are currently offline</span></div>

                            <div id="div_desk_officer_nomination_binding_modal_errors" class="alert alert-danger" role="alert"></div>
                            <hr>

                            <div id="binding_fields">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label"><strong>SELECT BINDING SUBMISSION</strong></label>
                                    <div class="col-xs-9">
                                        <select class="form-select form-control" id="binding_submission_data">
                                            <option value="">-- None Selected --</option>
                                            @if(isset($all_existing_submissions) && count($all_existing_submissions) > 0)
                                                @foreach($all_existing_submissions as $submission)
                                                    @php
                                                        $years = [];
                                                        $years_str = $submission->intervention_year1;

                                                        //intervention years merge
                                                        if ($submission->intervention_year1 != 0) {
                                                            array_push($years, $submission->intervention_year1);
                                                        }
                                                        if ($submission->intervention_year2 != 0) {
                                                            array_push($years, $submission->intervention_year2);
                                                        }
                                                        if ($submission->intervention_year3 != 0) {
                                                            array_push($years, $submission->intervention_year3);
                                                        }
                                                        if ($submission->intervention_year4 != 0) {
                                                            array_push($years, $submission->intervention_year4);
                                                        }

                                                        // merged years, unique years & sorted years
                                                        if (isset($years) && count($years) > 1) {
                                                            $years_detail = array_values(array_unique($years));
                                                            rsort($years_detail);
                                                            if (count($years_detail) > 1) {
                                                                $years_detail[count($years_detail) - 1] = ' and ' . $years_detail[count($years_detail) - 1];
                                                                $years_str = implode(", ", $years_detail);
                                                                $years_str = substr($years_str, 0,strrpos($years_str,",")) . $years_detail[count($years_detail) - 1];
                                                            }
                                                        }
                                                        
                                                        $selection_detail = ucwords($submission->title) . ' ('. $years_str .')';
                                                    @endphp
                                                    <option value="{{ $submission->id }}">{{ $selection_detail }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div><hr>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12">
                                <center>
                                    <button id="btn-binding-to-submission" class="btn btn-sm btn-primary">
                                        <div id="spinner-binding-to-submission" style="color: white; display: none;">
                                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                                            </div>
                                            <span class="">Loading...</span><hr>
                                        </div>

                                        <i class="fa fa-chain" style="opacity:80%"></i>
                                        Bind Nomination to Selected Submission
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

            //Show Modal for Nomination preview and binding by Desk Officer
            $(document).on('click', ".btn-show-preview-binding", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div_desk_officer_nomination_binding_modal_errors').hide();
                $('#desk_officer_nomination_binding_modal').modal('show');
                $('#form_desk_officer_nomination_binding_modal').trigger("reset");

                $("#spinner-binding-to-submission").show();
                $("#submission_binded_details").hide();
                $("#btn-binding-to-submission").attr('disabled', true);

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

                    if (response.data.submission_request && response.data.submission_request != null) {
                        let years_arr = [];
                        let year_1 = response.data.submission_request.intervention_year1;
                        let year_2 = response.data.submission_request.intervention_year2;
                        let year_3 = response.data.submission_request.intervention_year3;
                        let year_4 = response.data.submission_request.intervention_year4;
                        if (year_1 != '0' && years_arr.includes(year_1) == false) {
                            years_arr.push(year_1);
                        }

                        if (year_2 != '0' && years_arr.includes(year_2) == false) {
                            years_arr.push(year_2);
                        }

                        if (year_3 != '0' && years_arr.includes(year_3) == false) {
                            years_arr.push(year_3);
                        }

                        if (year_4 != '0' && years_arr.includes(year_4) == false) {
                            years_arr.push(year_4);
                        }
                        years_arr.sort();
                        let year_str = '';
                        if(years_arr.length > 1) {
                            year_str = years_arr.join(', ');
                            let lastComma = year_str.lastIndexOf(',');
                            year_str = year_str.split('');
                            year_str[lastComma] = ' & ';
                            year_str = year_str.join('');
                        } else {
                            year_str = years_arr[0];
                        }

                        let submission_request_binded = response.data.submission_request.title + ' (' + year_str + ')';
                        $('#submission_binded_details').html('This ' + response.data.nomination_request_type.toUpperCase() + ' Nomination Request is currently binding to <b>' + submission_request_binded + '</b> Submission');
                        $("#submission_binded_details").show();
                    }
                    
                    $("#spinner-binding-to-submission").hide();
                    $("#btn-binding-to-submission").attr('disabled', false);
               });
            });


            //process desk-officer bindng process
            $(document).on('click', "#btn-binding-to-submission", function(e) {
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
                
                swal({
                    title: "Are you sure you want to bind this "+ itemType +" request to the selected Submission?",
                    text: "You will be able to change binding status so long Submission is yet to be sent to TETfund registry desk.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes bind",
                    cancelButtonText: "No don't bind",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        $("#spinner-binding-to-submission").show();
                        $("#btn-binding-to-submission").attr('disabled', true);

                        let endPointUrl = "{{ route('tf-bi-portal-api.nomination_requests.process_nomination_binding_to_submission','') }}/"+itemId;
                        
                        let formData = new FormData();
                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('_method', 'POST');
                        formData.append('id', itemId);
                        formData.append('submission_id', $('#binding_submission_data').val());
                        
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
                                    $('#div_desk_officer_nomination_binding_modal_errors').html('');
                                    $('#div_desk_officer_nomination_binding_modal_errors').show();
                                    
                                    let response_arr = (result.errors) ? result.errors : result.response;
                                    $.each(response_arr, function(key, value){
                                        $('#div_desk_officer_nomination_binding_modal_errors').append('<li class="">'+value+'</li>');
                                    });

                                    $("#spinner-binding-to-submission").hide();
                                    $("#btn-binding-to-submission").attr('disabled', false);
                                }else{
                                    swal({
                                        title: "Processed",
                                        text: "Binding Submission for " + itemType + " request saved successfully",
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

                                $("#spinner-binding-to-submission").hide();
                                $("#btn-binding-to-submission").attr('disabled', false);

                            }
                        });
                    }

                });

            });

        });
    </script>
@endpush
