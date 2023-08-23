{{-- modal to show forms for ongoing submission request --}}
<div class="modal fade" id="mdl-ongoing-submission-modal" tabindex="-1" role="dialog" aria-labelledby="creator-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-100vh">
        <div class="modal-content modal-content-95vh">
            <div class="modal-header">
                <h4 class="modal-title" id="creator-modal-label"> ONGOING SUBMISSIONS REQUEST </h4>
                <button id="creator-modal-close-button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12 row m-2">

                    <div class="offline-ongoing-submission"><span class="offline">You are currently offline</span></div>
                    <div id="div-ongoing-submission-modal-error" class="alert alert-danger" role="alert"></div>
                    
                    <form id="frm-ongoing-submission-modal" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}            
                        <div class="col-sm-12">
                            <div class="col-sm-12 text-justify text-danger">
                                <i>
                                    <strong>NOTE:</strong> Select from options such as First Tranche, Second Tranche, Final Tranche, Monitoring Request, Audit Clearance, etc.<br>

                                    Once your selection is validated and processed, you will be redirected and required to provide <strong>all checkilist items</strong> before final submission to the Fund.
                                </i>                                
                            </div>    
                        </div>
                        <hr>

                        <div class="row col-sm-12">
                            <div class="form-group col-sm-12">
                                <label class="form-label" for="ongoing-submission-stage">
                                    <b>SELECT ONGOING SUBMISSION REQUEST STAGE</b>
                                </label>

                                <select class="form-select form-control" id="ongoing-submission-stage">
                                    <option value="">
                                        -- None Selected --
                                    </option>

                                    <option value="1st_Tranche_Payment">
                                        First Tranche Payment
                                    </option>

                                    <option value="2nd_Tranche_Payment">
                                        Second Tranche Payment
                                    </option>

                                    <option value="Final_Tranche_Payment">
                                        Final Tranche Payment
                                    </option>

                                    <option value="Monitoring_Request">
                                        Monitoring Request
                                    </option>
                                </select>
                            </div>

                            {{-- div to display ongoing submission request input fields --}}
                            <div class="col-sm-12 mt-3" id="append_form_input_fields" style="display:none;">
                            </div>
                        </div>          

                    </form>
                </div>
            </div>
            <div class="modal-footer text-left col-sm-12" >
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-primary" id="btn-save-mdl-ongoing-submission">
                        <div id="spinner-ongoing-submission" style="color: white; display:none;" class="spinner-ongoing-submission">
                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                            </div>
                            <span class="">Loading...</span><hr>
                        </div>

                        <i class="fa fa-save" style="opacity:80%"></i>
                        Save Ongoing Submission request.
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">

$(document).ready(function() {
    let all_astd_interventions_id = [];
    let all_none_astd_interventions_id = [];
    let all_intervention_records = '{!! json_encode($intervention_types??[]) !!}';

    $('.offline-ongoing-submission').hide();

    // amount to readable digits
    $(document).on('keyup', "#amount_requested", function(e) {
        $('#amount_requested').digits();
    });

    // Show modal ongoing submission request
    $(document).on('click', "#btn-ongoing-submission", function(e) {
        e.preventDefault();
        $("#append_form_input_fields").hide();
        $("#append_form_input_fields").html('');
        $('#div-ongoing-submission-modal-error').hide();
        $('#frm-ongoing-submission-modal').trigger("reset");
        $('#mdl-ongoing-submission-modal').modal('show');

        $("#spinner-ongoing-submission").hide();
        $("#btn-save-mdl-ongoing-submission").attr('disabled', true);
    });

    // fetch form fields on changing Ongoing submission selection stage
    $(document).on('change', "#ongoing-submission-stage", function(e) {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-ongoing-submission').fadeIn(300);
            return;
        }else{
            $('.offline-ongoing-submission').fadeOut(300);
        }

        let ongoing_submission_stage = $(this).val();
        $("#spinner-ongoing-submission").show();
        $("#btn-save-mdl-ongoing-submission").attr('disabled', true);

        if(ongoing_submission_stage.length > 0) {
            $.get( "{{ route('tf-bi-portal-api.submission_requests.ongoing-submission', '') }}/"+ongoing_submission_stage).done(function( response ) {

                if(response.data && response.data!=null && response.data.length > 0) {
                    console.log(response.data);                    
                    $("#append_form_input_fields").show();
                    $("#append_form_input_fields").html(response.data);
                    $("#btn-save-mdl-ongoing-submission").attr('disabled', false);
                    hide_3_intervention_years();    // hiding 3 intervention years byh default
                } else {
                    $("#append_form_input_fields").hide();
                    $("#append_form_input_fields").html('');
                    $("#btn-save-mdl-ongoing-submission").attr('disabled', true);
                }
                
                $("#spinner-ongoing-submission").hide();
            });
        } else {
            $("#append_form_input_fields").hide();
            $("#spinner-ongoing-submission").hide();
            $('#frm-ongoing-submission-modal').trigger("reset");
            $("#btn-save-mdl-ongoing-submission").attr('disabled', true);
        }
    }); 

    // triggered on changing intervention type
    $(document).on('change', "#intervention_type", function(e) {
        let selected_intevention_type = $(this).val();
        let intervention_line_html = "<option value=''>Select AIP Intervention Line</option>";

        if (selected_intevention_type != '' && all_intervention_records != null) {
            all_astd_interventions_id.length = 0; /* resetting array to empty */
            all_none_astd_interventions_id.length = 0; /* resetting array to empty */
                    @if(auth()->user()->hasRole('BI-desk-officer','BI-head-of-institution'))
                        $.each(JSON.parse(all_intervention_records), function(key, intervention) {
            
                            // appending intervention lines options
                            if (intervention.type == selected_intevention_type) {
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                            }

                            // setting all astd interventions into the array
                            if (intervention.name.includes('Teaching Practice') || intervention.name.includes('Conference Attendance') || intervention.name.includes('TETFund Scholarship')) {
                                all_astd_interventions_id[intervention.id] = intervention.name;
                            } else {
                                all_none_astd_interventions_id[intervention.id] = intervention.name;
                            }
                        });
                    @else

                        $.each(JSON.parse(all_intervention_records), function(key, intervention) {
                            // appending intervention lines options
                            console.log(selected_intevention_type)
                            console.log(intervention.type);
                            let role_string = roles.join(',')
                            if(intervention.name == "ICT Support" && role_string.includes("BI-ict") && intervention.type == selected_intevention_type){
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                                all_none_astd_interventions_id[intervention.id] = intervention.name;
                              
                            } else if(intervention.name == "Library Development" && role_string.includes("BI-librarian") && intervention.type == selected_intevention_type){
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                                all_none_astd_interventions_id[intervention.id] = intervention.name;
                             
                            }else if(intervention.name == "Physical Infrastructure // Program Upgrade" && role_string.includes("BI-works") && intervention.type == selected_intevention_type ){
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                                all_none_astd_interventions_id[intervention.id] = intervention.name;
                              
                            }else if(role_string.includes("BI-astd-desk-officer") && (intervention.name.includes('Teaching Practice') || intervention.name.includes('Conference Attendance') || intervention.name.includes('TETFund Scholarship')) && intervention.type == selected_intevention_type){
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                                all_astd_interventions_id[intervention.id] = intervention.name;
                               
                            }

                         
                            
                        });
                
                     @endif
        }

        let astd_inteventions_keys = Object.keys(all_astd_interventions_id);
        $('#intervention_line').html(intervention_line_html);
        $('#astd_interventions_ids').val(astd_inteventions_keys.join(','));
    });

    // triggered on changing intervention line
    $(document).on('change', "#intervention_line", function(e) {
        let selected_intervention_line = $(this).val();
        if (selected_intervention_line != '' && selected_intervention_line in all_astd_interventions_id) {
            hide_3_intervention_years(); 
        } else {
            show_3_intervention_years();
        }

        // settings to formulate intervention_title
        let confirmed_selected_line = all_none_astd_interventions_id[selected_intervention_line]!=null ?
                all_none_astd_interventions_id[selected_intervention_line] : 
                all_astd_interventions_id[selected_intervention_line];
      
        $('#intervention_title').val(confirmed_selected_line);
    });

    // saving ongoing submission request
    $('#btn-save-mdl-ongoing-submission').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-request-for-related-tranche').fadeIn(300);
            return;
        }else{
            $('.offline-request-for-related-tranche').fadeOut(300);
        }

        swal({
            title: "Please confirm to proceed with this ongoing submission request.",
            text: "You will be redirected to provide all neccessary attachments before final submission to TETFund.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes proceed",
            cancelButtonText: "No don't proceed",
            closeOnConfirm: false,
            closeOnCancel: true

        }, function(isConfirm) {

            if (isConfirm) {
                swal({
                    title: '<div id="spinner-request-related" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br>Processing...',
                    text: 'Please wait while this ongoing submission request is being initiated <br><br> Do not refresh this page! ',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html: true
                })

                let formData = new FormData();
                formData.append('_token', $('input[name="_token"]').val());
                formData.append('_method', 'POST');

                if ($('#intervention_year1').val().trim().length > 0) {
                    formData.append('intervention_year1', $('#intervention_year1').val());
                }

                if ($('#intervention_year2').val().trim().length > 0) {
                    formData.append('intervention_year2', $('#intervention_year2').val());
                }

                if ($('#intervention_year3').val().trim().length > 0) {
                    formData.append('intervention_year3', $('#intervention_year3').val());
                }

                if ($('#intervention_year4').val().trim().length > 0) {
                    formData.append('intervention_year4', $('#intervention_year4').val());
                }

                if ($('#amount_requested').val().trim().length > 0) {
                    formData.append('amount_requested', $('#amount_requested').val().replace(/,/g,''));
                }                

                if ($('#ongoing-submission-stage').val().trim().length > 0) {
                    formData.append('ongoing_submission_stage', $('#ongoing-submission-stage').val());
                }

                if ($('#intervention_title').val().trim().length > 0) {
                    formData.append('title', $('#intervention_title').val());
                }

                if ($('#intervention_line').val().trim().length > 0) {
                    formData.append('tf_iterum_intervention_line_key_id', $('#intervention_line').val());
                }

                $.each($('#file_attachments')[0].files, function(i, file) {
                    formData.append('file_attachments[]', file);
                });
                
                $.ajax({
                    url: "{{ route('tf-bi-portal-api.submission_requests.process-ongoing-submission') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    processData:false,
                    contentType: false,
                    dataType: 'json',
                    success: function(result) {
                        if(result.errors) {
                            swal.close();
                            $('#div-ongoing-submission-modal-error').html('');
                            $('#div-ongoing-submission-modal-error').show();
                            
                            $.each(result.errors, function(key, value){
                                $('#div-ongoing-submission-modal-error').append('<li class="">'+value+'</li>');
                            });
                        } else {
                            $('#div-ongoing-submission-modal-error').hide();
                            console.log(result);
                            let redirect_link = window.location.origin +'/tf-bi-portal/submissionRequests';
                            if(result.data.type == 'Monitoring Request') {
                                redirect_link = window.location.origin +'/tf-bi-portal/showMonitoring/'+ result.data.id;
                            } else {
                                redirect_link = window.location.origin +'/tf-bi-portal/submissionRequests/'+ result.data.id;
                            }
                            swal({
                                title: "Ongoing Submission Request Saved",
                                text: "The Ongoing Submission Request Has Been Saved Successfully!",
                                type: "success"
                            });
                            window.location.href = redirect_link;
                        }

                        $("#btn-save-mdl-ongoing-submission").attr('disabled', false);
                        
                    }, error: function(data) {
                        console.log(data);
                        swal("Error", "Oops an error occurred. Please try again.", "error");

                        $("#btn-save-mdl-ongoing-submission").attr('disabled', false);
                    }
                });
            }
        });
    });

});


// hiding 3 intervention years
    function hide_3_intervention_years() {
        $("#intervention_year2").val('');
        $("#intervention_year2").attr('disabled', true);
        $("#intervention_year3").val('');
        $("#intervention_year3").attr('disabled', true);
        $("#intervention_year4").val('');
        $("#intervention_year4").attr('disabled', true);
        $("#year_plural").hide();
    }

// showing 3 intervention years
    function show_3_intervention_years() {
        $("#intervention_year2").val('');
        $("#intervention_year2").attr('disabled', false);
        $("#intervention_year3").val('');
        $("#intervention_year3").attr('disabled', false);
        $("#intervention_year4").val('');
        $("#intervention_year4").attr('disabled', false);
        $("#year_plural").show();
    }
</script>
@endpush
