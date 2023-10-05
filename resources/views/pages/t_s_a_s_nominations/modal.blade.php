@php
    $country_nigeria_index = array_search('Nigeria', array_column($countries ?? [], 'name'));
    $country_nigeria_id = ($country_nigeria_index !== false) ? optional($countries[$country_nigeria_index])->id : null;
@endphp

<div class="modal fade" id="mdl-tSASNomination-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-tSASNomination-modal-title" class="modal-title"><span id="prefix_info"></span> TSAS Nomination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-tSASNomination-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-tSASNomination-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row m-3">
                        <div class="col-sm-12">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-t_s_a_s_nominations">You are currently offline</span></div>

                            <input type="hidden" id="txt-tSASNomination-primary-id" value="0" />
                            <div id="div-show-txt-tSASNomination-primary-id">
                                <div class="row col-sm-12">
                                    @include('tf-bi-portal::pages.t_s_a_s_nominations.show_fields')
                                </div>
                            </div>
                            <div id="div-edit-txt-tSASNomination-primary-id">
                                <div class="row col-sm-12">
                                    @include('tf-bi-portal::pages.t_s_a_s_nominations.fields')
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-tSASNomination-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-tSASNomination-modal" value="add">
                <div id="spinner-t_s_a_s_nominations" style="color: white;">
                    <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                    </div>
                    <span class="">Loading...</span><hr>
                </div>
                Save TSAS Nomination
                </button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-t_s_a_s_nominations').hide();

    let institutions = '{!! json_encode($institutions ?? []) !!}';
    let country_nigeria_id = '{!! $country_nigeria_id !!}';
    
    // toggle TSAS different input fields based on the selected country
    $(document).on('change', "#country_id_select_tsas", function(e) {
        let selected_country = $('#country_id_select_tsas').val();
        let institutions_filtered = "<option value=''>-- None selected --</option>";
        let todayDate = "{{ date('Y-m-d') }}";
        let sixMonthsAhead = "{{ date('Y-m-d', strtotime(date('Y-m-d') . ' +6 months')) }}";
        
        // actions if Nigeria is selected
        if (selected_country == country_nigeria_id || selected_country == '') {
            $('#div-institution_state_tsas').show();
            $('#intl_passport_number_tsas').val('');
            $('#div-intl_passport_number_tsas').hide();
            $('#div-international_passport_bio_page_tsas').hide();
            $('#is_science_program_tsas').val('');
            $('#tsas_program_start_date_notice').hide();
            // $("#program_start_date_tsas").attr("min", todayDate);
            $("#program_start_date_tsas").removeAttr("min");
            // $("#program_end_date_tsas").attr("min", todayDate);
            $("#program_end_date_tsas").removeAttr("min");

            if(selected_country == '') {
                $('#div-is_science_program_tsas').hide();
            } else {
                $('#div-is_science_program_tsas').show();
            }
        } else {
            $('#institution_state_tsas').val('');
            $('#div-institution_state_tsas').hide();
            $('#div-intl_passport_number_tsas').show();
            $('#is_science_program_tsas').val('1');
            $('#div-is_science_program_tsas').hide();
            $('#tsas_program_start_date_notice').show();
            $("#program_start_date_tsas").attr("min", sixMonthsAhead);
            $("#program_end_date_tsas").attr("min", sixMonthsAhead);
        }

        // institutions based on the selected country
        // $.each(JSON.parse(institutions), function(key, institution) {
        //     if (institution.country_id == selected_country) {
        //         institutions_filtered += "<option value='"+ institution.id +"'>"+ institution.name +"</option>";
        //     }
        // });
        // $('#institution_id_select_tsas').html(institutions_filtered);
    });

    //Show Modal for New Entry
    $(document).on('click', ".{{ $nomination_type_str ?? 'tsas' }}-nomination-form", function(e) {
        $('#div-tSASNomination-modal-error').hide();
        $('#frm-tSASNomination-modal').trigger("reset");
        $('#txt-tSASNomination-primary-id').val(0);
        $("#attachments_info_tsas").hide();
        $('#prefix_info').text("New");

        $('#mdl-tSASNomination-modal').modal('show');

        $('#div-show-txt-tSASNomination-primary-id').hide();
        $('#div-edit-txt-tSASNomination-primary-id').show();

        $("#spinner-t_s_a_s_nominations").hide();
        $("#btn-save-mdl-tSASNomination-modal").attr('disabled', false);
    });

    // toggle TSAS international passport attachement input filed
    $('#intl_passport_number_tsas').on('keyup', function() {
        let intl_passport_number_set_tsas = $(this).val();
        if (intl_passport_number_set_tsas != '' && intl_passport_number_set_tsas.length >= 1) {
            $('#div-international_passport_bio_page_tsas').show();
        } else if (intl_passport_number_set_tsas == '' || intl_passport_number_set_tsas.length == 0) {
            $('#div-international_passport_bio_page_tsas').hide();
        }
    });

    //Show Modal for View
    $(document).on('click', ".btn-show-{{$nominationRequest->type ?? 'tsas'}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
        $('#prefix_info').text("Preview");

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-t_s_a_s_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-t_s_a_s_nominations').fadeOut(300);
        }

        $('#div-tSASNomination-modal-error').hide();
        $('#mdl-tSASNomination-modal').modal('show');
        $('#frm-tSASNomination-modal').trigger("reset");

        $("#spinner-t_s_a_s_nominations").show();
        $("#btn-save-mdl-tSASNomination-modal").attr('disabled', true);

        $('#div-show-txt-tSASNomination-primary-id').show();
        $('#div-edit-txt-tSASNomination-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.t_s_a_s_nominations.show','') }}/"+itemId).done(function( response ) {
			console.log(response.data);

			$('#txt-tSASNomination-primary-id').val(response.data.id);
            $('#spn_tSASNomination_email').html(response.data.email);
    		$('#spn_tSASNomination_telephone').html(response.data.telephone);
    		$('#spn_tSASNomination_gender').html(response.data.gender);
    		$('#spn_tSASNomination_name_title').html(response.data.name_title);
    		$('#spn_tSASNomination_first_name').html(response.data.first_name);
    		$('#spn_tSASNomination_middle_name').html(response.data.middle_name);
    		$('#spn_tSASNomination_last_name').html(response.data.last_name);
    		$('#spn_tSASNomination_name_suffix').html(response.data.name_suffix);
    		$('#spn_tSASNomination_bank_account_name').html(response.data.bank_account_name);
    		$('#spn_tSASNomination_bank_account_number').html(response.data.bank_account_number);
    		$('#spn_tSASNomination_bank_name').html(response.data.bank_name);
    		$('#spn_tSASNomination_bank_sort_code').html(response.data.bank_sort_code);
    		$('#spn_tSASNomination_intl_passport_number').html(response.data.intl_passport_number);
    		$('#spn_tSASNomination_bank_verification_number').html(response.data.bank_verification_number);
    		$('#spn_tSASNomination_national_id_number').html(response.data.national_id_number);
    		$('#spn_tSASNomination_degree_type').html(response.data.degree_type);
    		$('#spn_tSASNomination_program_title').html(response.data.program_title);
    		$('#spn_tSASNomination_is_science_program').html((response.data.is_science_program == true) ? 'Yes' : 'No');
    		$('#spn_tSASNomination_program_start_date').html(response.data.program_start_date);
            $('#spn_tSASNomination_program_end_date').html(response.data.program_end_date);
    		
    		$('#spn_tSASNomination_final_remarks').html(response.data.final_remarks);
    		$('#spn_tSASNomination_total_requested_amount').html(response.data.total_requested_amount);
    		$('#spn_tSASNomination_total_approved_amount').html(response.data.total_approved_amount);
            $('#spn_tSASNomination_beneficiary_institution_name').html(response.data.beneficiary.full_name);
            $('#spn_tSASNomination_institution_name').html(response.data.institution_name);
            $('#spn_tSASNomination_institution_state').html(response.data.intitution_state);
            $('#spn_tSASNomination_country_name').html(response.data.country.name + ' (' + response.data.country.country_code + ')');

            $("#spinner-t_s_a_s_nominations").hide();
            $("#div-save-mdl-tSASNomination-modal").hide();
            $("#btn-save-mdl-tSASNomination-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-{{$nominationRequest->type ?? 'tsas'}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-tSASNomination-modal-error').hide();
        $('#frm-tSASNomination-modal').trigger("reset");
        $('#prefix_info').text("Edit");

        $("#spinner-t_s_a_s_nominations").show();
        $("#attachments_info_tsas").show();
        $("#btn-save-mdl-tSASNomination-modal").attr('disabled', true);

        $('#div-show-txt-tSASNomination-primary-id').hide();
        $('#div-edit-txt-tSASNomination-primary-id').show();
        let itemId = $(this).attr('data-val');

        $('#mdl-tSASNomination-modal').modal('show');
        $.get( "{{ route('tf-bi-portal-api.t_s_a_s_nominations.show','') }}/"+itemId+"?_method=GET").done(function( response ) {     

			$('#txt-tSASNomination-primary-id').val(response.data.id);
            $('#email_tsas').val(response.data.email);
    		$('#telephone_tsas').val(response.data.telephone);
    		$('#gender_tsas').val(response.data.gender);
    		$('#name_title_tsas').val(response.data.name_title);
    		$('#first_name_tsas').val(response.data.first_name);
    		$('#middle_name_tsas').val(response.data.middle_name);
    		$('#last_name_tsas').val(response.data.last_name);
    		$('#name_suffix_tsas').val(response.data.name_suffix);
    		$('#bank_account_name_tsas').val(response.data.bank_account_name);
    		$('#bank_account_number_tsas').val(response.data.bank_account_number);
    		$('#bank_name_tsas').val(response.data.bank_name);
    		$('#intl_passport_number_tsas').val(response.data.intl_passport_number);
            $('#institution_name_tsas').val(response.data.institution_name);
            $('#institution_state_tsas').val(response.data.intitution_state);
            
            if (response.data.intl_passport_number!=null && response.data.intl_passport_number.length > 0) {
                $('#institution_state_tsas').val('');
                $('#div-institution_state_tsas').hide();
                $('#div-intl_passport_number_tsas').show();
                $('#div-international_passport_bio_page_tsas').show();
                $('#is_science_program_tsas').val('');
                $('#div-is_science_program_tsas').hide();
            } else {
                $('#div-institution_state_tsas').show();
                $('#intl_passport_number_tsas').val('');
                $('#div-intl_passport_number_tsas').hide();
                $('#div-international_passport_bio_page_tsas').hide();
                $('#div-is_science_program_tsas').show();
            }

    		$('#bank_verification_number_tsas').val(response.data.bank_verification_number);
    		$('#national_id_number_tsas').val(response.data.national_id_number);
    		$('#degree_type_tsas').val(response.data.degree_type);
    		$('#program_title_tsas').val(response.data.program_title);
            $('#is_science_program_tsas').val(response.data.is_science_program ? '1' : '0');
            // $('#div-international_passport_bio_page_tsas').show();

            let program_start_date = new Date(response.data.program_start_date);
            let local_program_start_date = new Date(program_start_date.getTime() - (program_start_date.getTimezoneOffset() * 60000)).toISOString().slice(0, 10);

            let program_end_date = new Date(response.data.program_end_date);
            let local_program_end_date = new Date(program_end_date.getTime() - (program_end_date.getTimezoneOffset() * 60000)).toISOString().slice(0, 10);

            $('#program_start_date_tsas').val(local_program_start_date);
            $('#program_end_date_tsas').val(local_program_end_date);
            $('#country_id_select_tsas option[value="' + response.data.tf_iterum_portal_country_id + '"]').prop('selected', 'selected');
            
            // let institutions_filtered = "<option value=''>-- None selected --</option>";
            // $.each(JSON.parse(institutions), function(key, institution) {
            //     if (institution.country_id == response.data.tf_iterum_portal_country_id && response.data.tf_iterum_portal_institution_id == institution.id) {
            //         institutions_filtered += "<option selected='selected' value='"+ institution.id +"'>"+ institution.name +"</option>";
            //     } else if (institution.country_id == response.data.tf_iterum_portal_country_id) {
            //         institutions_filtered += "<option value='"+ institution.id +"'>"+ institution.name +"</option>";
            //     }
            // });

            // $('#institution_id_select_tsas').html(institutions_filtered);
            
            $("#spinner-t_s_a_s_nominations").hide();
            $("#div-save-mdl-tSASNomination-modal").show();
            $("#btn-save-mdl-tSASNomination-modal").attr('disabled', false);

        });
        
    });

    //Delete action
    $(document).on('click', ".btn-delete-{{$nominationRequest->type ?? 'tsas'}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-t_s_a_s_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-t_s_a_s_nominations').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this TSASNomination?",
                text: "You will not be able to recover this TSASNomination if deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    let endPointUrl = "{{ route('tf-bi-portal-api.t_s_a_s_nominations.destroy','') }}/"+itemId;

                    swal({
                        title: '<div id="spinner-beneficiaries" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                        text: 'Deleting TSASNomination Details <br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });

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
                                    text: "TSASNomination deleted successfully",
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

    //Save details
    $('#btn-save-mdl-tSASNomination-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-t_s_a_s_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-t_s_a_s_nominations').fadeOut(300);
        }

        $("#spinner-t_s_a_s_nominations").show();
        $("#btn-save-mdl-tSASNomination-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('tf-bi-portal-api.t_s_a_s_nominations.store') }}";
        let primaryId = $('#txt-tSASNomination-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('tf-bi-portal-api.t_s_a_s_nominations.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($nominationRequest->user->organization_id) && $nominationRequest->user->organization_id!=null)
            formData.append('organization_id', '{{ $nominationRequest->user->organization_id }}');
            formData.append('user_id', '{{ $nominationRequest->user->id }}');
            formData.append('nomination_request_id', '{{ $nominationRequest->id }}');
            formData.append('country_nigeria_id', '{{$country_nigeria_id}}');
        @endif

        if ($('#email_tsas').length){	formData.append('email',$('#email_tsas').val());	}
		if ($('#telephone_tsas').length){	formData.append('telephone',$('#telephone_tsas').val());	}
		if ($('#beneficiary_institution_id_select_tsas').length){	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select_tsas').val());	}
        if ($('#institution_name_tsas').length){   formData.append('institution_name',$('#institution_name_tsas').val());   }
        if ($('#institution_state_tsas').length){   formData.append('intitution_state',$('#institution_state_tsas').val());   }
        if ($('#country_id_select_tsas').length){   formData.append('tf_iterum_portal_country_id',$('#country_id_select_tsas').val());   }
        if ($('#gender_tsas').length){   formData.append('gender',$('#gender_tsas').val());   }
		if ($('#name_title_tsas').length){	formData.append('name_title',$('#name_title_tsas').val());	}
		if ($('#first_name_tsas').length){	formData.append('first_name',$('#first_name_tsas').val());	}
		if ($('#middle_name_tsas').length){	formData.append('middle_name',$('#middle_name_tsas').val());	}
		if ($('#last_name_tsas').length){	formData.append('last_name',$('#last_name_tsas').val());	}
		if ($('#name_suffix_tsas').length){	formData.append('name_suffix',$('#name_suffix_tsas').val());	}
		if ($('#bank_account_name_tsas').length){	formData.append('bank_account_name',$('#bank_account_name_tsas').val());	}
		if ($('#bank_account_number_tsas').length){	formData.append('bank_account_number',$('#bank_account_number_tsas').val());	}
		if ($('#bank_name_tsas').length){	formData.append('bank_name',$('#bank_name_tsas').val()); formData.append('bank_sort_code',$('#bank_name_tsas').attr('data-val-code'));	}
		/* if ($('#bank_sort_code_tsas').length){	formData.append('bank_sort_code',$('#bank_sort_code_tsas').val());	} */
		if ($('#intl_passport_number_tsas').length){	formData.append('intl_passport_number',$('#intl_passport_number_tsas').val());	}
		if ($('#bank_verification_number_tsas').length){	formData.append('bank_verification_number',$('#bank_verification_number_tsas').val());	}
		if ($('#national_id_number_tsas').length){	formData.append('national_id_number',$('#national_id_number_tsas').val());	}
		if ($('#degree_type_tsas').length){	formData.append('degree_type',$('#degree_type_tsas').val());	}
		if ($('#program_title_tsas').length){	formData.append('program_title',$('#program_title_tsas').val());	}
		if ($('#program_type_tsas').length){	formData.append('program_type',$('#program_type_tsas').val());	}
        if ($('#is_science_program_tsas').length){ formData.append('is_science_program',$('#is_science_program_tsas').val());   }
        if ($('#program_start_date_tsas').length){ formData.append('program_start_date',$('#program_start_date_tsas').val());   }
        if ($('#program_end_date_tsas').length){ formData.append('program_end_date',$('#program_end_date_tsas').val());   }

        if($('#passport_photo_tsas').get(0).files.length != 0){
            formData.append('passport_photo', $('#passport_photo_tsas')[0].files[0]);
        }
        if($('#admission_letter_tsas').get(0).files.length != 0){
            formData.append('admission_letter', $('#admission_letter_tsas')[0].files[0]);      
        }
        if($('#health_report_tsas').get(0).files.length != 0){
            formData.append('health_report', $('#health_report_tsas')[0].files[0]);  
        }
        if($('#curriculum_vitae_tsas').get(0).files.length != 0){
            formData.append('curriculum_vitae', $('#curriculum_vitae_tsas')[0].files[0]);  
        }
        if($('#signed_bond_with_beneficiary_tsas').get(0).files.length != 0){
            formData.append('signed_bond_with_beneficiary', $('#signed_bond_with_beneficiary_tsas')[0].files[0]);  
        }
        if($('#international_passport_bio_page_tsas').get(0).files.length != 0){
            formData.append('international_passport_bio_page', $('#international_passport_bio_page_tsas')[0].files[0]);  
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
					$('#div-tSASNomination-modal-error').html('');
					$('#div-tSASNomination-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-tSASNomination-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-tSASNomination-modal-error').hide();
                    console.log(result.message);
                    swal({
                        title: "Saved",
                        text: "TSASNomination saved successfully",
                        type: "success"
                    });
                    location.reload(true);
                }

                $("#spinner-t_s_a_s_nominations").hide();
                $("#btn-save-mdl-tSASNomination-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-t_s_a_s_nominations").hide();
                $("#btn-save-mdl-tSASNomination-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
