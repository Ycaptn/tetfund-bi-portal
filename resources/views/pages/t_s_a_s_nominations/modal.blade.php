

<div class="modal fade" id="mdl-tSASNomination-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                                <div class="row">
                                    <div class="col-sm-12">
                                    @include('tf-bi-portal::pages.t_s_a_s_nominations.show_fields')
                                    </div>
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
    		$('#spn_tSASNomination_program_type').html(response.data.program_type);
    		/*$('#spn_tSASNomination_fee_amount').html(response.data.fee_amount);
    		$('#spn_tSASNomination_tuition_amount').html(response.data.tuition_amount);
    		$('#spn_tSASNomination_upgrade_fee_amount').html(response.data.upgrade_fee_amount);
    		$('#spn_tSASNomination_stipend_amount').html(response.data.stipend_amount);
    		$('#spn_tSASNomination_passage_amount').html(response.data.passage_amount);
    		$('#spn_tSASNomination_medical_amount').html(response.data.medical_amount);
    		$('#spn_tSASNomination_warm_clothing_amount').html(response.data.warm_clothing_amount);
    		$('#spn_tSASNomination_study_tours_amount').html(response.data.study_tours_amount);
    		$('#spn_tSASNomination_education_materials_amount').html(response.data.education_materials_amount);
    		$('#spn_tSASNomination_thesis_research_amount').html(response.data.thesis_research_amount);
    		$('#spn_tSASNomination_final_remarks').html(response.data.final_remarks);
    		$('#spn_tSASNomination_total_requested_amount').html(response.data.total_requested_amount);
    		$('#spn_tSASNomination_total_approved_amount').html(response.data.total_approved_amount);*/
            $('#spn_tSASNomination_beneficiary_institution_name').html(response.data.beneficiary.full_name);
            $('#spn_tSASNomination_institution_name').html(response.data.institution.name); 
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
    		$('#bank_sort_code_tsas').val(response.data.bank_sort_code);
    		$('#intl_passport_number_tsas').val(response.data.intl_passport_number);
    		$('#bank_verification_number_tsas').val(response.data.bank_verification_number);
    		$('#national_id_number_tsas').val(response.data.national_id_number);
    		$('#degree_type_tsas').val(response.data.degree_type);
    		$('#program_title_tsas').val(response.data.program_title);
    		$('#program_type_tsas').val(response.data.program_type);
    		/*$('#fee_amount_tsas').val(response.data.fee_amount);
    		$('#tuition_amount_tsas').val(response.data.tuition_amount);
    		$('#upgrade_fee_amount_tsas').val(response.data.upgrade_fee_amount);
    		$('#stipend_amount_tsas').val(response.data.stipend_amount);
    		$('#passage_amount_tsas').val(response.data.passage_amount);
    		$('#medical_amount_tsas').val(response.data.medical_amount);
    		$('#warm_clothing_amount_tsas').val(response.data.warm_clothing_amount);
    		$('#study_tours_amount_tsas').val(response.data.study_tours_amount);
    		$('#education_materials_amount_tsas').val(response.data.education_materials_amount);
    		$('#thesis_research_amount_tsas').val(response.data.thesis_research_amount);
    		$('#final_remarks_tsas').val(response.data.final_remarks);
    		$('#total_requested_amount_tsas').val(response.data.total_requested_amount);
    		$('#total_approved_amount_tsas').val(response.data.total_approved_amount);*/
            $('#is_science_program_tsas').val(response.data.is_science_program ? '1' : '0');

            var program_start_date_tsas = new Date(response.data.program_start_date).toISOString().slice(0, 10);
            $('#program_start_date_tsas').val(program_start_date_tsas);

            var program_end_date_tsas = new Date(response.data.program_end_date).toISOString().slice(0, 10);
            $('#program_end_date_tsas').val(program_end_date_tsas);

            $('#institution_id_select_tsas option[value="' + response.data.tf_iterum_portal_institution_id + '"]').prop('selected', 'selected');
            $('#country_id_select_tsas option[value="' + response.data.tf_iterum_portal_country_id + '"]').prop('selected', 'selected');
           
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
                        },
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
        @endif

        if ($('#email_tsas').length){	formData.append('email',$('#email_tsas').val());	}
		if ($('#telephone_tsas').length){	formData.append('telephone',$('#telephone_tsas').val());	}
		if ($('#beneficiary_institution_id_select_tsas').length){	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select_tsas').val());	}
        if ($('#institution_id_select_tsas').length){   formData.append('tf_iterum_portal_institution_id',$('#institution_id_select_tsas').val());   }
        if ($('#country_id_select_tsas').length){   formData.append('tf_iterum_portal_country_id',$('#country_id_select_tsas').val());   }
        if ($('#gender_tsas').length){   formData.append('gender',$('#gender_tsas').val());   }
		if ($('#name_title_tsas').length){	formData.append('name_title',$('#name_title_tsas').val());	}
		if ($('#first_name_tsas').length){	formData.append('first_name',$('#first_name_tsas').val());	}
		if ($('#middle_name_tsas').length){	formData.append('middle_name',$('#middle_name_tsas').val());	}
		if ($('#last_name_tsas').length){	formData.append('last_name',$('#last_name_tsas').val());	}
		if ($('#name_suffix_tsas').length){	formData.append('name_suffix',$('#name_suffix_tsas').val());	}
		if ($('#bank_account_name_tsas').length){	formData.append('bank_account_name',$('#bank_account_name_tsas').val());	}
		if ($('#bank_account_number_tsas').length){	formData.append('bank_account_number',$('#bank_account_number_tsas').val());	}
		if ($('#bank_name_tsas').length){	formData.append('bank_name',$('#bank_name_tsas').val());	}
		if ($('#bank_sort_code_tsas').length){	formData.append('bank_sort_code',$('#bank_sort_code_tsas').val());	}
		if ($('#intl_passport_number_tsas').length){	formData.append('intl_passport_number',$('#intl_passport_number_tsas').val());	}
		if ($('#bank_verification_number_tsas').length){	formData.append('bank_verification_number',$('#bank_verification_number_tsas').val());	}
		if ($('#national_id_number_tsas').length){	formData.append('national_id_number',$('#national_id_number_tsas').val());	}
		if ($('#degree_type_tsas').length){	formData.append('degree_type',$('#degree_type_tsas').val());	}
		if ($('#program_title_tsas').length){	formData.append('program_title',$('#program_title_tsas').val());	}
		if ($('#program_type_tsas').length){	formData.append('program_type',$('#program_type_tsas').val());	}
        if ($('#is_science_program_tsas').length){ formData.append('is_science_program',$('#is_science_program_tsas').val());   }
        if ($('#program_start_date_tsas').length){ formData.append('program_start_date',$('#program_start_date_tsas').val());   }
        if ($('#program_end_date_tsas').length){ formData.append('program_end_date',$('#program_end_date_tsas').val());   }
        
        formData.append('passport_photo', $('#passport_photo_tsas')[0].files[0]);
        formData.append('admission_letter', $('#admission_letter_tsas')[0].files[0]);          
        formData.append('health_report', $('#health_report_tsas')[0].files[0]);  
        formData.append('international_passport_bio_page', $('#international_passport_bio_page_tsas')[0].files[0]);  
        formData.append('conference_attendence_letter', $('#conference_attendence_letter_tsas')[0].files[0]);  
                
		/*if ($('#fee_amount_tsas').length){	formData.append('fee_amount',$('#fee_amount_tsas').val());	}
		if ($('#tuition_amount_tsas').length){	formData.append('tuition_amount',$('#tuition_amount_tsas').val());	}
		if ($('#upgrade_fee_amount_tsas').length){	formData.append('upgrade_fee_amount',$('#upgrade_fee_amount_tsas').val());	}
		if ($('#stipend_amount_tsas').length){	formData.append('stipend_amount',$('#stipend_amount_tsas').val());	}
		if ($('#passage_amount_tsas').length){	formData.append('passage_amount',$('#passage_amount_tsas').val());	}
		if ($('#medical_amount_tsas').length){	formData.append('medical_amount',$('#medical_amount_tsas').val());	}
		if ($('#warm_clothing_amount_tsas').length){	formData.append('warm_clothing_amount',$('#warm_clothing_amount_tsas').val());	}
		if ($('#study_tours_amount_tsas').length){	formData.append('study_tours_amount',$('#study_tours_amount_tsas').val());	}
		if ($('#education_materials_amount_tsas').length){	formData.append('education_materials_amount',$('#education_materials_amount_tsas').val());	}
		if ($('#thesis_research_amount_tsas').length){	formData.append('thesis_research_amount',$('#thesis_research_amount_tsas').val());	}
		if ($('#final_remarks_tsas').length){	formData.append('final_remarks',$('#final_remarks_tsas').val());	}
		if ($('#total_requested_amount_tsas').length){	formData.append('total_requested_amount',$('#total_requested_amount_tsas').val());	}
		if ($('#total_approved_amount_tsas').length){	formData.append('total_approved_amount',$('#total_approved_amount_tsas').val());	}*/


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
                    window.setTimeout( function(){

                        $('#div-tSASNomination-modal-error').hide();
                        console.log(result.message);
                        swal({
                            title: "Saved",
                            text: "TSASNomination saved successfully",
                            type: "success"
                        });
                        location.reload(true);
                    },20);
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
