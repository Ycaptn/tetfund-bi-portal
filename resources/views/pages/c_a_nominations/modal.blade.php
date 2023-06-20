
@php
    $country_nigeria_index = array_search('Nigeria', array_column($countries ?? [], 'name'));
    $country_nigeria_id = ($country_nigeria_index !== false) ? optional($countries[$country_nigeria_index])->id : null;
@endphp

<div class="modal fade" id="mdl-cANomination-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-cANomination-modal-title" class="modal-title"><span id="prefix_info"></span> CA Nomination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-cANomination-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-cANomination-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row m-3">
                        <div class="col-sm-12">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-c_a_nominations">You are currently offline</span></div>

                            <input type="hidden" id="txt-cANomination-primary-id" value="0" />
                            <div id="div-show-txt-cANomination-primary-id">
                                <div class="row col-sm-12">
                                    @include('tf-bi-portal::pages.c_a_nominations.show_fields')
                                </div>
                            </div>
                            <div id="div-edit-txt-cANomination-primary-id">
                                <div class="row col-sm-12">
                                    @include('tf-bi-portal::pages.c_a_nominations.fields')
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-cANomination-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-cANomination-modal" value="add">
                    <div id="spinner-c_a_nominations" style="color: white;">
                        <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                        </div>
                        <span class="">Loading...</span><hr>
                    </div>
                    Save CA Nomination
                </button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-c_a_nominations').hide();

    let conferences = '{!! json_encode($conferences ?? []) !!}';
    let country_nigeria_id = '{!! $country_nigeria_id !!}';
    
    // CA DIgit amoutn input 
    $('#conference_fee_amount_local_ca').keyup(function(event) {
        $('#conference_fee_amount_local_ca').digits();
    });

    // toggle CA presention paper attachement input filed on is_conference_workshop_ca
    $('#is_conference_workshop_ca').on('change', function() {
        let is_conference_workshop = $(this).val();
        if (is_conference_workshop != '' && is_conference_workshop == '1') {
            $('#div-paper_presentation_ca').hide();
            $('#accepted_paper_title_ca').val('');
            $('#div-accepted_paper_title_ca').hide();
        } else if (is_conference_workshop == '' || is_conference_workshop == 0) {
            $('#div-paper_presentation_ca').show();
            $('#div-accepted_paper_title_ca').show();
        }
    });

    // toggle CA international passport attachement input filed
    $('#intl_passport_number_ca').on('keyup', function() {
        let intl_passport_number_set_ca = $(this).val();
        if (intl_passport_number_set_ca != '' && intl_passport_number_set_ca.length >= 1) {
            $('#div-international_passport_bio_page_ca').show();
        } else if (intl_passport_number_set_ca == '' || intl_passport_number_set_ca.length == 0) {
            $('#div-international_passport_bio_page_ca').hide();
        }
    });

    //toggle different conferences based on the selected country for CA and International Passport
    $(document).on('change', "#country_id_select_ca", function(e) {
        let selected_country = $('#country_id_select_ca').val();
        let conferences_filtered = "<option value=''>-- None selected --</option>";
        
        // actions on intl passport input filed if Nigeria is selected
        if (selected_country == country_nigeria_id || selected_country == '') {
            $('#intl_passport_number_ca').val('');
            $('#div-intl_passport_number_ca').hide();
            $('#div-international_passport_bio_page_ca').hide();
        } else {
            $('#div-intl_passport_number_ca').show();
        }

        // toggle conference state input selection field if Nigerial is selected
        if (selected_country == country_nigeria_id && selected_country != '') {
            $('#conference_state_select_ca').val('');
            $('#div-conference_state_select_ca').show();
        } else {
            $('#div-conference_state_select_ca').hide();
        }

        // conferences based on country selected
        // $.each(JSON.parse(conferences), function(key, conference) {
        //     if (conference.country_id == selected_country) {
        //         conferences_filtered += "<option value='"+ conference.id +"'>"+ conference.name +"</option>";
        //     }
        // });
        // $('#conference_id_select_ca').html(conferences_filtered);
    });

    //Show Modal for New Entry
    $(document).on('click', ".{{ $nomination_type_str ?? 'ca' }}-nomination-form", function(e) {
        $('#div-cANomination-modal-error').hide();
        $('#frm-cANomination-modal').trigger("reset");
        $('#txt-cANomination-primary-id').val(0);
        $("#attachments_info_ca").hide();
        $('#prefix_info').text("New");

        $('#mdl-cANomination-modal').modal('show');
        
        $('#div-show-txt-cANomination-primary-id').hide();
        $('#div-edit-txt-cANomination-primary-id').show();
        
        $("#spinner-c_a_nominations").hide();
        $("#btn-save-mdl-cANomination-modal").attr('disabled', false);

    });


    //Show Modal for View
    $(document).on('click', ".btn-show-{{$nominationRequest->type ?? 'ca'}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-c_a_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-c_a_nominations').fadeOut(300);
        }

        $('#prefix_info').text("Preview");
        $('#div-cANomination-modal-error').hide();
        $('#mdl-cANomination-modal').modal('show');
        $('#frm-cANomination-modal').trigger("reset");

        $("#spinner-c_a_nominations").show();
        $("#btn-save-mdl-cANomination-modal").attr('disabled', true);

        $('#div-show-txt-cANomination-primary-id').show();
        $('#div-edit-txt-cANomination-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.c_a_nominations.show','') }}/"+itemId).done(function( response ) {
			$('#txt-cANomination-primary-id').val(response.data.id);
            $('#spn_cANomination_email').html(response.data.email);
    		$('#spn_cANomination_telephone').html(response.data.telephone);
    		$('#spn_cANomination_gender').html(response.data.gender);
    		$('#spn_cANomination_name_title').html(response.data.name_title);
    		$('#spn_cANomination_first_name').html(response.data.first_name);
    		$('#spn_cANomination_middle_name').html(response.data.middle_name);
    		$('#spn_cANomination_last_name').html(response.data.last_name);
    		$('#spn_cANomination_name_suffix').html(response.data.name_suffix);
    		$('#spn_cANomination_bank_account_name').html(response.data.bank_account_name);
    		$('#spn_cANomination_bank_account_number').html(response.data.bank_account_number);
    		$('#spn_cANomination_bank_name').html(response.data.bank_name);
    		$('#spn_cANomination_bank_sort_code').html(response.data.bank_sort_code);
    		$('#spn_cANomination_intl_passport_number').html(response.data.intl_passport_number);
    		$('#spn_cANomination_bank_verification_number').html(response.data.bank_verification_number);
    		$('#spn_cANomination_national_id_number').html(response.data.national_id_number);
    		$('#spn_cANomination_organizer_name').html(response.data.organizer_name);
    		$('#spn_cANomination_conference_theme').html(response.data.conference_theme);
    		$('#spn_cANomination_accepted_paper_title').html(response.data.accepted_paper_title);
    		$('#spn_cANomination_attendee_department_name').html(response.data.attendee_department_name);
    		$('#spn_cANomination_attendee_grade_level').html(response.data.attendee_grade_level);
            $('#spn_cANomination_has_paper_presentation').html((response.data.has_paper_presentation == true) ? 'Yes' : 'No');
            $('#spn_cANomination_is_academic_staff').html((response.data.is_academic_staff == true) ? 'Yes' : 'No');
            $('#spn_cANomination_conference_start_date').html(response.data.conference_start_date);
            $('#spn_cANomination_conference_end_date').html(response.data.conference_end_date);

            $('#spn_cANomination_beneficiary_institution_name').html(response.data.beneficiary.full_name);
            $('#spn_cANomination_country_name').html(response.data.country.name + ' (' + response.data.country.country_code + ')');
            $('#spn_cANomination_conference_name').html(response.data.conference_title); 

            /*$('#spn_cANomination_conference_name').html(response.data.conference_fee_amount_local); 
            $('#spn_cANomination_conference_name').html(response.data.local_runs_amount); 
            $('#spn_cANomination_conference_name').html(response.data.passage_amount); 
            $('#spn_cANomination_conference_name').html(response.data.dta_amount); 
            $('#spn_cANomination_conference_name').html(response.data.paper_presentation_fee); */

            $('#spn_cANomination_total_requested_amount').html(response.data.total_requested_amount); 
            $("#spinner-c_a_nominations").hide();
            $("#div-save-mdl-cANomination-modal").hide();
            $("#btn-save-mdl-cANomination-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-{{$nominationRequest->type ?? 'ca'}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
        $('#div-cANomination-modal-error').hide();
        $('#prefix_info').text("Edit");
        $('#frm-cANomination-modal').trigger("reset");

        $("#spinner-c_a_nominations").show();
        $("#attachments_info_ca").show();
        $("#btn-save-mdl-cANomination-modal").attr('disabled', true);


        $('#div-show-txt-cANomination-primary-id').hide();
        $('#div-edit-txt-cANomination-primary-id').show();
        $("#div-save-mdl-cANomination-modal").show();
        let itemId = $(this).attr('data-val');

        $('#mdl-cANomination-modal').modal('show');
        $.get( "{{ route('tf-bi-portal-api.c_a_nominations.show','') }}/"+itemId).done(function( response ) {   
        console.log(response.data);  
    		$('#txt-cANomination-primary-id').val(response.data.id);
            $('#email_ca').val(response.data.email);
    		$('#telephone_ca').val(response.data.telephone);
    		$('#gender_ca').val(response.data.gender);
    		$('#name_title_ca').val(response.data.name_title);
    		$('#first_name_ca').val(response.data.first_name);
    		$('#middle_name_ca').val(response.data.middle_name);
    		$('#last_name_ca').val(response.data.last_name);
    		$('#name_suffix_ca').val(response.data.name_suffix);
    		$('#bank_account_name_ca').val(response.data.bank_account_name);
    		$('#bank_account_number_ca').val(response.data.bank_account_number);
    		$('#bank_name_ca').val(response.data.bank_name);
    		$('#bank_sort_code_ca').val(response.data.bank_sort_code);
            
            if (response.data.intl_passport_number != null && response.data.intl_passport_number.length > 0) {
                $('#conference_state_select_ca').val('');
                $('#div-conference_state_select_ca').hide();
                $('#div-intl_passport_number_ca').show();
                $('#div-international_passport_bio_page_ca').show();
    		    $('#intl_passport_number_ca').val(response.data.intl_passport_number);
            }  else {
                $('#div-conference_state_select_ca').show();
                $('#intl_passport_number_ca').val('');
                $('#div-intl_passport_number_ca').hide();
                $('#div-international_passport_bio_page_ca').hide();
            }

    		$('#bank_verification_number_ca').val(response.data.bank_verification_number);
    		$('#national_id_number_ca').val(response.data.national_id_number);
            $('#conference_title_ca').val(response.data.conference_title);
            $('#conference_state_select_ca').val(response.data.conference_state);
    		$('#organizer_name_ca').val(response.data.organizer_name);
    		$('#conference_theme_ca').val(response.data.conference_theme);
            $('#conference_address_ca').val(response.data.conference_address);
            $('#conference_passage_type_ca').val(response.data.conference_passage_type);
    		$('#accepted_paper_title_ca').val(response.data.accepted_paper_title);
    		$('#attendee_department_name_ca').val(response.data.attendee_department_name);
    		$('#attendee_grade_level_ca').val(response.data.attendee_grade_level);
            $('#conference_fee_amount_local_ca').val(response.data.conference_fee_amount_local);
            
            if (response.data.has_paper_presentation) {
                $('#div-accepted_paper_title_ca').show();
                $('#div-paper_presentation_ca').show();
                $('#is_conference_workshop_ca').val('0');
            } else {
                $('#accepted_paper_title_ca').val('');
                $('#div-accepted_paper_title_ca').hide();
                $('#div-paper_presentation_ca').hide();
                $('#is_conference_workshop_ca').val('1');
            }

            initially_selected_beneficiary_institution_id = response.data.beneficiary_institution_id;
            initially_selected_conference_id = response.data.conference_id;
            initially_selected_country_id = response.data.country_id;

            let conference_start_date = new Date(response.data.conference_start_date);
            let local_conference_start_date = new Date(conference_start_date.getTime() - (conference_start_date.getTimezoneOffset() * 60000)).toISOString().slice(0, 10);

            let conference_end_date = new Date(response.data.conference_end_date);
            let local_conference_end_date = new Date(conference_end_date.getTime() - (conference_end_date.getTimezoneOffset() * 60000)).toISOString().slice(0, 10);

            $('#conference_start_date_ca').val(local_conference_start_date);

            $('#conference_end_date_ca').val(local_conference_end_date);

            $('#country_id_select_ca option[value="' + response.data.tf_iterum_portal_country_id + '"]').prop('selected', 'selected');

            // let conferences_filtered = "<option value=''>-- None selected --</option>";
            // $.each(JSON.parse(conferences), function(key, conference) {
            //     if (conference.country_id == response.data.tf_iterum_portal_country_id && response.data.tf_iterum_portal_conference_id == conference.id) {
            //         conferences_filtered += "<option selected='selected' value='"+ conference.id +"'>"+ conference.name +"</option>";
            //     } else if (conference.country_id == response.data.tf_iterum_portal_country_id) {
            //         conferences_filtered += "<option value='"+ conference.id +"'>"+ conference.name +"</option>";
            //     }
            // });

            // $('#conference_id_select_ca').html(conferences_filtered);

            $("#spinner-c_a_nominations").hide();
            $("#div-save-mdl-cANomination-modal").show();
            $("#btn-save-mdl-cANomination-modal").attr('disabled', false);
        });
    });

    //Delete action
    $(document).on('click', ".btn-delete-{{$nominationRequest->type ?? 'ca'}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-c_a_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-c_a_nominations').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this CANomination?",
                text: "You will not be able to recover this CANomination if deleted.",
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
                        title: '<div id="spinner-beneficiaries" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                        text: 'Deleting CANomination Details <br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });

                    let endPointUrl = "{{ route('tf-bi-portal-api.c_a_nominations.destroy','') }}/"+itemId;

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
                                console.log(result.errors);
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }else{
                                swal({
                                    title: "Deleted",
                                    text: "CANomination deleted successfully",
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
    $('#btn-save-mdl-cANomination-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#conference_fee_amount_local_ca').keyup(function(event) {
            $('#conference_fee_amount_local_ca').digits();
        });

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-c_a_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-c_a_nominations').fadeOut(300);
        }

        $("#spinner-c_a_nominations").show();
        $("#btn-save-mdl-cANomination-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('tf-bi-portal-api.c_a_nominations.store') }}";
        let primaryId = $('#txt-cANomination-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('tf-bi-portal-api.c_a_nominations.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($nominationRequest->user->organization_id) && $nominationRequest->user->organization_id!=null)
            formData.append('organization_id', '{{ $nominationRequest->user->organization_id }}');
            formData.append('user_id', '{{ $nominationRequest->user->id }}');
            formData.append('nomination_request_id', '{{ $nominationRequest->id }}');
            formData.append('country_nigeria_id', '{{$country_nigeria_id}}');
        @endif

        if ($('#email_ca').length && $('#email_ca').val().trim().length > 0){   formData.append('email',$('#email_ca').val());  }
        if ($('#telephone_ca').length && $('#telephone_ca').val().trim().length > 0){   formData.append('telephone',$('#telephone_ca').val());  }
        if ($('#beneficiary_institution_id_select_ca').length && $('#beneficiary_institution_id_select_ca').val().trim().length > 0){   
            formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select_ca').val()); 
        }
        
        if ($('#is_conference_workshop_ca').length && $('#is_conference_workshop_ca').val().trim().length > 0){
            formData.append('is_conference_workshop',$('#is_conference_workshop_ca').val());
        }
        if ($('#country_id_select_ca').length && $('#country_id_select_ca').val().trim().length > 0){
            formData.append('tf_iterum_portal_country_id',$('#country_id_select_ca').val());
        }
        if ($('#conference_title_ca').length && $('#conference_title_ca').val().trim().length > 0){  
            formData.append('conference_title',$('#conference_title_ca').val());  
        }
        formData.append('conference_state',$('#conference_state_select_ca').val());
        if ($('#gender_ca').length && $('#gender_ca').val().trim().length > 0){   formData.append('gender',$('#gender_ca').val());   }
        if ($('#name_title_ca').length && $('#name_title_ca').val().trim().length > 0){ formData.append('name_title',$('#name_title_ca').val());    }
        if ($('#first_name_ca').length && $('#first_name_ca').val().trim().length > 0){ formData.append('first_name',$('#first_name_ca').val());    }
        if ($('#middle_name_ca').length && $('#middle_name_ca').val().trim().length > 0){   formData.append('middle_name',$('#middle_name_ca').val());  }
        if ($('#last_name_ca').length && $('#last_name_ca').val().trim().length > 0){   formData.append('last_name',$('#last_name_ca').val());  }
        if ($('#name_suffix_ca').length && $('#name_suffix_ca').val().trim().length > 0){   formData.append('name_suffix',$('#name_suffix_ca').val());  }
        if ($('#bank_account_name_ca').length && $('#bank_account_name_ca').val().trim().length > 0){   formData.append('bank_account_name',$('#bank_account_name_ca').val());  }
        if ($('#bank_account_number_ca').length && $('#bank_account_number_ca').val().trim().length > 0){   formData.append('bank_account_number',$('#bank_account_number_ca').val());  }
        if ($('#bank_name_ca').length && $('#bank_name_ca').val().trim().length > 0){   formData.append('bank_name',$('#bank_name_ca').val());  }
        if ($('#bank_sort_code_ca').length && $('#bank_sort_code_ca').val().trim().length > 0){ formData.append('bank_sort_code',$('#bank_sort_code_ca').val());    }
        formData.append('intl_passport_number',$('#intl_passport_number_ca').val());
        if ($('#bank_verification_number_ca').length && $('#bank_verification_number_ca').val().trim().length > 0){
            formData.append('bank_verification_number',$('#bank_verification_number_ca').val());
        }
        if ($('#national_id_number_ca').length && $('#national_id_number_ca').val().trim().length > 0){ formData.append('national_id_number',$('#national_id_number_ca').val());    }
        if ($('#organizer_name_ca').length){    formData.append('organizer_name',$('#organizer_name_ca').val());    }
        if ($('#conference_theme_ca').length){  formData.append('conference_theme',$('#conference_theme_ca').val());    }
        if ($('#conference_address_ca').length){  formData.append('conference_address',$('#conference_address_ca').val());    }
        if ($('#conference_passage_type_ca').length && $('#conference_passage_type_ca').val().trim().length > 0){   formData.append('conference_passage_type',$('#conference_passage_type_ca').val());  }
        if ($('#accepted_paper_title_ca').length){  formData.append('accepted_paper_title',$('#accepted_paper_title_ca').val());    }
        if ($('#attendee_department_name_ca').length){  formData.append('attendee_department_name',$('#attendee_department_name_ca').val());    }
        if ($('#attendee_grade_level_ca').length){  formData.append('attendee_grade_level',$('#attendee_grade_level_ca').val());    }
        if ($('#has_paper_presentation_ca').length){ formData.append('has_paper_presentation',$('#has_paper_presentation_ca').val());   }
        if ($('#is_academic_staff_ca').length){ formData.append('is_academic_staff',$('#is_academic_staff_ca').val());   }

        {{-- determining paper presentation --}}
        if ($('#is_academic_staff_ca').length){
            if ($('#is_academic_staff_ca').val()=='1' || ($('#is_academic_staff_ca').val()=='0' && $('#is_conference_workshop').val()=='0') ) {
                formData.append('has_paper_presentation', '1');
            } else {
                formData.append('has_paper_presentation', '0');
            }
        }
        
        if ($('#conference_start_date_ca').length){ formData.append('conference_start_date',$('#conference_start_date_ca').val());   }
        if ($('#conference_end_date_ca').length){ formData.append('conference_end_date',$('#conference_end_date_ca').val());   }

        /* amounts */
        if ($('#conference_fee_amount_local_ca').length){ formData.append('conference_fee_amount_local',$('#conference_fee_amount_local_ca').val().replace(/,/g,""));   }
        /* amounts */

        if($('#passport_photo_ca').get(0).files.length != 0){
            formData.append('passport_photo', $('#passport_photo_ca')[0].files[0]);
        }
        if($('#conference_attendance_flyer_ca').get(0).files.length != 0){
            formData.append('conference_attendance_flyer', $('#conference_attendance_flyer_ca')[0].files[0]);      
        }
        if($('#paper_presentation_ca').get(0).files.length != 0){
            formData.append('paper_presentation', $('#paper_presentation_ca')[0].files[0]);  
        }
        if($('#international_passport_bio_page_ca').get(0).files.length != 0){
            formData.append('international_passport_bio_page', $('#international_passport_bio_page_ca')[0].files[0]);  
        }

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
					$('#div-cANomination-modal-error').html('');
					$('#div-cANomination-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-cANomination-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-cANomination-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-cANomination-modal-error').hide();

                        swal({
                            title: "Saved",
                            text: "CANomination saved successfully",
                            type: "success"
                        });
                        location.reload(true);

                    },20);
                }

                $("#spinner-c_a_nominations").hide();
                $("#btn-save-mdl-cANomination-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-c_a_nominations").hide();
                $("#btn-save-mdl-cANomination-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
