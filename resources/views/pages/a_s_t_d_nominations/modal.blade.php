

<div class="modal fade" id="mdl-aSTDNomination-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-aSTDNomination-modal-title" class="modal-title"><span id="prefix_info"></span> ASTD Nomination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-aSTDNomination-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-aSTDNomination-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row m-3">
                        <div class="col-sm-12">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-a_s_t_d_nominations">You are currently offline</span></div>

                            <input type="hidden" id="txt-aSTDNomination-primary-id" value="0" />
                            <div id="div-show-txt-aSTDNomination-primary-id">
                                <div class="row">
                                    <div class="col-sm-12">
                                    @include('tf-bi-portal::pages.a_s_t_d_nominations.show_fields')
                                    </div>
                                </div>
                            </div>
                            <div id="div-edit-txt-aSTDNomination-primary-id">
                                <div class="row col-sm-12">
                                    @include('tf-bi-portal::pages.a_s_t_d_nominations.fields')
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-aSTDNomination-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-aSTDNomination-modal" value="add">
                <div id="spinner-a_s_t_d_nominations" style="color: white;">
                    <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                    </div>
                    <span class="">Loading...</span><hr>
                </div>
                Save ASTD Nomination
                </button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-a_s_t_d_nominations').hide();

    //Show Modal for New Entry
    $(document).on('click', ".{{ $nomination_type_str }}-nomination-form", function(e) {
        $('#div-aSTDNomination-modal-error').hide();
        $('#frm-aSTDNomination-modal').trigger("reset");
        $('#txt-aSTDNomination-primary-id').val(0);
        $('#prefix_info').text("New");

        /*get all beneficiary institutions*/
        $.get( "{{ route('tf-bi-portal-api.beneficiaries.index') }}").done(function( response ) {
            if (response.data && response.data != null) {
                let html_options = "<option value=''>-- None selected --</option>";
                $.each(response.data, function( index, value ) {
                    /*determining user beneficiary selected*/
                    if (value.id == '{{ $nominationRequest->beneficiary_id}}') {
                        html_options += "<option selected='selected' value='" + value.id + "'>" + value.full_name + ' (' + value.short_name + ')' + "</option>";
                    } else {
                        html_options += "<option value='" + value.id + "'>" + value.full_name + ' (' + value.short_name + ')' + "</option>";
                        }
                });
                $('#beneficiary_institution_id_select').html(html_options);
            }
        });

        /*get all institutions*/
        $.get( "{{ route('tf-bi-portal-api.institutions.index') }}").done(function( response ) {
            if (response.data && response.data != null) {
                let html_options = "<option value=''>-- None selected --</option>";
                $.each(response.data, function( index, value ) {
                    html_options += "<option value='" + value.id + "'>" + value.name + "</option>";
                });
                $('#institution_id_select').html(html_options);
            }
        });

        /*get all countries*/
        $.get( "{{ route('tf-bi-portal-api.countries.index') }}").done(function( response ) {
            if (response.data && response.data != null) {
                let html_options = "<option value=''>-- None selected --</option>";
                $.each(response.data, function( index, value ) {
                    html_options += "<option value='" + value.id + "'>" + value.name + ' (' + value.country_code + ')' + "</option>";
                });
                $('#country_id_select').html(html_options);
            }
        });

        $('#mdl-aSTDNomination-modal').modal('show');

        $('#div-show-txt-aSTDNomination-primary-id').hide();
        $('#div-edit-txt-aSTDNomination-primary-id').show();

        $("#spinner-a_s_t_d_nominations").hide();
        $("#btn-save-mdl-aSTDNomination-modal").attr('disabled', false);
    });

    //Show Modal for View
    $(document).on('click', ".btn-show-{{$nominationRequest->type}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-a_s_t_d_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-a_s_t_d_nominations').fadeOut(300);
        }

        $('#div-aSTDNomination-modal-error').hide();
        $('#mdl-aSTDNomination-modal').modal('show');
        $('#frm-aSTDNomination-modal').trigger("reset");

        $("#spinner-a_s_t_d_nominations").show();
        $("#btn-save-mdl-aSTDNomination-modal").attr('disabled', true);

        $('#div-show-txt-aSTDNomination-primary-id').show();
        $('#div-edit-txt-aSTDNomination-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.a_s_t_d_nominations.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-aSTDNomination-primary-id').val(response.data.id);
            $('#spn_aSTDNomination_email').html(response.data.email);
    		$('#spn_aSTDNomination_telephone').html(response.data.telephone);
    		$('#spn_aSTDNomination_gender').html(response.data.gender);
    		$('#spn_aSTDNomination_name_title').html(response.data.name_title);
    		$('#spn_aSTDNomination_first_name').html(response.data.first_name);
    		$('#spn_aSTDNomination_middle_name').html(response.data.middle_name);
    		$('#spn_aSTDNomination_last_name').html(response.data.last_name);
    		$('#spn_aSTDNomination_name_suffix').html(response.data.name_suffix);
    		$('#spn_aSTDNomination_bank_account_name').html(response.data.bank_account_name);
    		$('#spn_aSTDNomination_bank_account_number').html(response.data.bank_account_number);
    		$('#spn_aSTDNomination_bank_name').html(response.data.bank_name);
    		$('#spn_aSTDNomination_bank_sort_code').html(response.data.bank_sort_code);
    		$('#spn_aSTDNomination_intl_passport_number').html(response.data.intl_passport_number);
    		$('#spn_aSTDNomination_bank_verification_number').html(response.data.bank_verification_number);
    		$('#spn_aSTDNomination_national_id_number').html(response.data.national_id_number);
    		$('#spn_aSTDNomination_degree_type').html(response.data.degree_type);
    		$('#spn_aSTDNomination_program_title').html(response.data.program_title);
    		$('#spn_aSTDNomination_program_type').html(response.data.program_type);
    		$('#spn_aSTDNomination_fee_amount').html(response.data.fee_amount);
    		$('#spn_aSTDNomination_tuition_amount').html(response.data.tuition_amount);
    		$('#spn_aSTDNomination_upgrade_fee_amount').html(response.data.upgrade_fee_amount);
    		$('#spn_aSTDNomination_stipend_amount').html(response.data.stipend_amount);
    		$('#spn_aSTDNomination_passage_amount').html(response.data.passage_amount);
    		$('#spn_aSTDNomination_medical_amount').html(response.data.medical_amount);
    		$('#spn_aSTDNomination_warm_clothing_amount').html(response.data.warm_clothing_amount);
    		$('#spn_aSTDNomination_study_tours_amount').html(response.data.study_tours_amount);
    		$('#spn_aSTDNomination_education_materials_amount').html(response.data.education_materials_amount);
    		$('#spn_aSTDNomination_thesis_research_amount').html(response.data.thesis_research_amount);
    		$('#spn_aSTDNomination_final_remarks').html(response.data.final_remarks);
    		$('#spn_aSTDNomination_total_requested_amount').html(response.data.total_requested_amount);
    		$('#spn_aSTDNomination_total_approved_amount').html(response.data.total_approved_amount);
            $('#spn_beneficiary_institution_name').html(response.data.beneficiary.full_name);

            initially_selected_institution_id= response.data.tf_iterum_portal_institution_id;
            initially_selected_country_id = response.data.tf_iterum_portal_country_id;

            /*get all institutions*/
            $.get( "{{ route('tf-bi-portal-api.institutions.index') }}").done(function( response ) {
                if (response.data && response.data != null) {
                    $.each(response.data, function( index, value ) {
                        if (initially_selected_institution_id == value.id) {
                            $('#spn_institution_name').html(value.name);
                        }
                    });
                }
            });

             /*all countries*/
            $.get( "{{ route('tf-bi-portal-api.countries.index') }}").done(function( response ) {
                if (response.data && response.data != null) {
                    $.each(response.data, function( index, value ) {
                        if (initially_selected_country_id == value.id) {
                            $('#spn_aSTDNomination_country_name').html(value.name + ' (' + value
                            .country_code + ')');
                        }
                    });
                }
            });

            $("#spinner-a_s_t_d_nominations").hide();
            $("#div-save-mdl-aSTDNomination-modal").hide();
            $("#btn-save-mdl-aSTDNomination-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-{{$nominationRequest->type}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-aSTDNomination-modal-error').hide();
        $('#frm-aSTDNomination-modal').trigger("reset");
        $('#prefix_info').text("Edit");

        $("#spinner-a_s_t_d_nominations").show();
        $("#btn-save-mdl-aSTDNomination-modal").attr('disabled', true);

        $('#div-show-txt-aSTDNomination-primary-id').hide();
        $('#div-edit-txt-aSTDNomination-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.a_s_t_d_nominations.show','') }}/"+itemId+"?_method=GET").done(function( response ) {     

			$('#txt-aSTDNomination-primary-id').val(response.data.id);
            $('#email').val(response.data.email);
    		$('#telephone').val(response.data.telephone);
    		$('#gender').val(response.data.gender);
    		$('#name_title').val(response.data.name_title);
    		$('#first_name').val(response.data.first_name);
    		$('#middle_name').val(response.data.middle_name);
    		$('#last_name').val(response.data.last_name);
    		$('#name_suffix').val(response.data.name_suffix);
    		$('#bank_account_name').val(response.data.bank_account_name);
    		$('#bank_account_number').val(response.data.bank_account_number);
    		$('#bank_name').val(response.data.bank_name);
    		$('#bank_sort_code').val(response.data.bank_sort_code);
    		$('#intl_passport_number').val(response.data.intl_passport_number);
    		$('#bank_verification_number').val(response.data.bank_verification_number);
    		$('#national_id_number').val(response.data.national_id_number);
    		$('#degree_type').val(response.data.degree_type);
    		$('#program_title').val(response.data.program_title);
    		$('#program_type').val(response.data.program_type);
    		$('#fee_amount').val(response.data.fee_amount);
    		$('#tuition_amount').val(response.data.tuition_amount);
    		$('#upgrade_fee_amount').val(response.data.upgrade_fee_amount);
    		$('#stipend_amount').val(response.data.stipend_amount);
    		$('#passage_amount').val(response.data.passage_amount);
    		$('#medical_amount').val(response.data.medical_amount);
    		$('#warm_clothing_amount').val(response.data.warm_clothing_amount);
    		$('#study_tours_amount').val(response.data.study_tours_amount);
    		$('#education_materials_amount').val(response.data.education_materials_amount);
    		$('#thesis_research_amount').val(response.data.thesis_research_amount);
    		$('#final_remarks').val(response.data.final_remarks);
    		$('#total_requested_amount').val(response.data.total_requested_amount);
    		$('#total_approved_amount').val(response.data.total_approved_amount);
            $('#is_science_program').val(response.data.is_science_program ? '1' : '0');

            var program_start_date = new Date(response.data.program_start_date).toISOString().slice(0, 10);
            $('#program_start_date').val(program_start_date);

            var program_end_date = new Date(response.data.program_end_date).toISOString().slice(0, 10);
            $('#program_end_date').val(program_end_date);

            initially_selected_beneficiary_institution_id = response.data.beneficiary_institution_id;
            initially_selected_institution_id= response.data.tf_iterum_portal_institution_id;
            initially_selected_country_id = response.data.tf_iterum_portal_country_id;

            /*get all beneficiary institutions*/
            $.get( "{{ route('tf-bi-portal-api.beneficiaries.index') }}").done(function( response ) {
                if (response.data && response.data != null) {
                    let html_options = "<option value=''>-- None selected --</option>";
                    $.each(response.data, function( index, value ) {
                        /*determining selected*/
                        if (initially_selected_beneficiary_institution_id == value.id) {
                            html_options += "<option selected='selected' value='" + value.id + "'>" + value.full_name + ' (' + value.short_name + ')' + "</option>";
                        } else {
                            html_options += "<option value='" + value.id + "'>" + value.full_name + ' (' + value.short_name + ')' + "</option>";
                        }
                    });
                    $('#beneficiary_institution_id_select').html(html_options);
                }
            });

            /*get all institutions*/
            $.get( "{{ route('tf-bi-portal-api.institutions.index') }}").done(function( response ) {
                if (response.data && response.data != null) {
                    let html_options = "<option value=''>-- None selected --</option>";
                    $.each(response.data, function( index, value ) {
                        /*determining selected*/
                        if (initially_selected_institution_id == value.id) {
                            html_options += "<option selected='selected' value='" + value.id + "'>" + value.name + "</option>";
                        } else {
                            html_options += "<option value='" + value.id + "'>" + value.name + "</option>";
                        }
                    });
                    $('#institution_id_select').html(html_options);
                }
            });

             /*all countries*/
            $.get( "{{ route('tf-bi-portal-api.countries.index') }}").done(function( response ) {
                if (response.data && response.data != null) {
                    let html_options = "<option value=''>-- None selected --</option>";
                    $.each(response.data, function( index, value ) {
                        /*determining selected*/
                        if (initially_selected_country_id == value.id) {
                            html_options += "<option selected='selected' value='" + value.id + "'>" + value.name + ' (' + value.country_code + ')' + "</option>";
                        } else {
                            html_options += "<option value='" + value.id + "'>" + value.name + ' (' + value.country_code + ')' + "</option>";
                        }
                    });
                    $('#country_id_select').html(html_options);
                }
            });

            $('#mdl-aSTDNomination-modal').modal('show');

            $("#spinner-a_s_t_d_nominations").hide();
            $("#btn-save-mdl-aSTDNomination-modal").attr('disabled', false);

        });
    });

    //Delete action
    $(document).on('click', ".btn-delete-{{$nominationRequest->type}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-a_s_t_d_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-a_s_t_d_nominations').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this ASTDNomination?",
                text: "You will not be able to recover this ASTDNomination if deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    let endPointUrl = "{{ route('tf-bi-portal-api.a_s_t_d_nominations.destroy','') }}/"+itemId;

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
                                    text: "ASTDNomination deleted successfully",
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
    $('#btn-save-mdl-aSTDNomination-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-a_s_t_d_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-a_s_t_d_nominations').fadeOut(300);
        }

        $("#spinner-a_s_t_d_nominations").show();
        $("#btn-save-mdl-aSTDNomination-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('tf-bi-portal-api.a_s_t_d_nominations.store') }}";
        let primaryId = $('#txt-aSTDNomination-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('tf-bi-portal-api.a_s_t_d_nominations.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($nominationRequest->user->organization_id) && $nominationRequest->user->organization_id!=null)
            formData.append('organization_id', '{{ $nominationRequest->user->organization_id }}');
            formData.append('user_id', '{{ $nominationRequest->user->id }}');
            formData.append('nomination_request_id', '{{ $nominationRequest->id }}');
        @endif

        if ($('#email').length){	formData.append('email',$('#email').val());	}
		if ($('#telephone').length){	formData.append('telephone',$('#telephone').val());	}
		if ($('#beneficiary_institution_id_select').length){	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select').val());	}
        if ($('#institution_id_select').length){   formData.append('tf_iterum_portal_institution_id',$('#institution_id_select').val());   }
        if ($('#country_id_select').length){   formData.append('tf_iterum_portal_country_id',$('#country_id_select').val());   }
        if ($('#gender').length){   formData.append('gender',$('#gender').val());   }
		if ($('#name_title').length){	formData.append('name_title',$('#name_title').val());	}
		if ($('#first_name').length){	formData.append('first_name',$('#first_name').val());	}
		if ($('#middle_name').length){	formData.append('middle_name',$('#middle_name').val());	}
		if ($('#last_name').length){	formData.append('last_name',$('#last_name').val());	}
		if ($('#name_suffix').length){	formData.append('name_suffix',$('#name_suffix').val());	}
		if ($('#bank_account_name').length){	formData.append('bank_account_name',$('#bank_account_name').val());	}
		if ($('#bank_account_number').length){	formData.append('bank_account_number',$('#bank_account_number').val());	}
		if ($('#bank_name').length){	formData.append('bank_name',$('#bank_name').val());	}
		if ($('#bank_sort_code').length){	formData.append('bank_sort_code',$('#bank_sort_code').val());	}
		if ($('#intl_passport_number').length){	formData.append('intl_passport_number',$('#intl_passport_number').val());	}
		if ($('#bank_verification_number').length){	formData.append('bank_verification_number',$('#bank_verification_number').val());	}
		if ($('#national_id_number').length){	formData.append('national_id_number',$('#national_id_number').val());	}
		if ($('#degree_type').length){	formData.append('degree_type',$('#degree_type').val());	}
		if ($('#program_title').length){	formData.append('program_title',$('#program_title').val());	}
		if ($('#program_type').length){	formData.append('program_type',$('#program_type').val());	}
        if ($('#is_science_program').length){ formData.append('is_science_program',$('#is_science_program').val());   }
        if ($('#program_start_date').length){ formData.append('program_start_date',$('#program_start_date').val());   }
        if ($('#program_end_date').length){ formData.append('program_end_date',$('#program_end_date').val());   }
		if ($('#fee_amount').length){	formData.append('fee_amount',$('#fee_amount').val());	}
		if ($('#tuition_amount').length){	formData.append('tuition_amount',$('#tuition_amount').val());	}
		if ($('#upgrade_fee_amount').length){	formData.append('upgrade_fee_amount',$('#upgrade_fee_amount').val());	}
		if ($('#stipend_amount').length){	formData.append('stipend_amount',$('#stipend_amount').val());	}
		if ($('#passage_amount').length){	formData.append('passage_amount',$('#passage_amount').val());	}
		if ($('#medical_amount').length){	formData.append('medical_amount',$('#medical_amount').val());	}
		if ($('#warm_clothing_amount').length){	formData.append('warm_clothing_amount',$('#warm_clothing_amount').val());	}
		if ($('#study_tours_amount').length){	formData.append('study_tours_amount',$('#study_tours_amount').val());	}
		if ($('#education_materials_amount').length){	formData.append('education_materials_amount',$('#education_materials_amount').val());	}
		if ($('#thesis_research_amount').length){	formData.append('thesis_research_amount',$('#thesis_research_amount').val());	}
		if ($('#final_remarks').length){	formData.append('final_remarks',$('#final_remarks').val());	}
		if ($('#total_requested_amount').length){	formData.append('total_requested_amount',$('#total_requested_amount').val());	}
		if ($('#total_approved_amount').length){	formData.append('total_approved_amount',$('#total_approved_amount').val());	}


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
					$('#div-aSTDNomination-modal-error').html('');
					$('#div-aSTDNomination-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-aSTDNomination-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-aSTDNomination-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-aSTDNomination-modal-error').hide();
                        console.log(result.message);
                        swal({
                            title: "Saved",
                            text: "ASTDNomination saved successfully",
                            type: "success"
                        });
                        location.reload(true);
                    },20);
                }

                $("#spinner-a_s_t_d_nominations").hide();
                $("#btn-save-mdl-aSTDNomination-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-a_s_t_d_nominations").hide();
                $("#btn-save-mdl-aSTDNomination-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
