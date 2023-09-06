

<div class="modal fade" id="mdl-tPNomination-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-tPNomination-modal-title" class="modal-title"><span id="prefix_info"></span> TP Nomination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-tPNomination-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-tPNomination-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row m-3">
                        <div class="col-sm-12">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-t_p_nominations">You are currently offline</span></div>

                            <input type="hidden" id="txt-tPNomination-primary-id" value="0" />
                            <div id="div-show-txt-tPNomination-primary-id">
                                <div class="row col-sm-12">
                                    @include('tf-bi-portal::pages.t_p_nominations.show_fields')
                                </div>
                            </div>
                            <div id="div-edit-txt-tPNomination-primary-id">
                                <div class="row col-sm-12">
                                    @include('tf-bi-portal::pages.t_p_nominations.fields')
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-tPNomination-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-tPNomination-modal" value="add">
                <div id="spinner-t_p_nominations" style="color: white;">
                    <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                    </div>
                    <span class="">Loading...</span><hr>
                </div>
                Save TP Nomination
                </button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-t_p_nominations').hide();

    //Show Modal for New Entry
    $(document).on('click', ".{{ $nomination_type_str ?? 'tp' }}-nomination-form", function(e) {
        $('#div-tPNomination-modal-error').hide();
        $('#frm-tPNomination-modal').trigger("reset");
        $('#txt-tPNomination-primary-id').val(0);
        $('#prefix_info').text("New");
        $("#attachments_info_tp").hide();

        $('#mdl-tPNomination-modal').modal('show');

        $('#div-show-txt-tPNomination-primary-id').hide();
        $('#div-edit-txt-tPNomination-primary-id').show();

        $("#spinner-t_p_nominations").hide();
        $("#btn-save-mdl-tPNomination-modal").attr('disabled', false);
    });

    //Show Modal for View
    $(document).on('click', ".btn-show-{{$nominationRequest->type ?? 'tp'}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
        $('#prefix_info').text("Preview");

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-t_p_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-t_p_nominations').fadeOut(300);
        }

        $('#div-tPNomination-modal-error').hide();
        $('#mdl-tPNomination-modal').modal('show');
        $('#frm-tPNomination-modal').trigger("reset");

        $("#spinner-t_p_nominations").show();
        $("#btn-save-mdl-tPNomination-modal").attr('disabled', true);

        $('#div-show-txt-tPNomination-primary-id').show();
        $('#div-edit-txt-tPNomination-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.t_p_nominations.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-tPNomination-primary-id').val(response.data.id);
            $('#spn_tPNomination_email').html(response.data.email);
    		$('#spn_tPNomination_telephone').html(response.data.telephone);
    		$('#spn_tPNomination_gender').html(response.data.gender);
    		$('#spn_tPNomination_rank_gl_equivalent').html('GL ' + response.data.rank_gl_equivalent);
    		$('#spn_tPNomination_first_name').html(response.data.first_name);
    		$('#spn_tPNomination_middle_name').html(response.data.middle_name);
    		$('#spn_tPNomination_last_name').html(response.data.last_name);
    		$('#spn_tPNomination_name_suffix').html(response.data.name_suffix);
    		$('#spn_tPNomination_bank_account_name').html(response.data.bank_account_name);
    		$('#spn_tPNomination_bank_account_number').html(response.data.bank_account_number);
    		$('#spn_tPNomination_bank_name').html(response.data.bank_name);
    		$('#spn_tPNomination_bank_sort_code').html(response.data.bank_sort_code);
    		$('#spn_tPNomination_intl_passport_number').html(response.data.intl_passport_number);
    		$('#spn_tPNomination_bank_verification_number').html(response.data.bank_verification_number);
    		$('#spn_tPNomination_national_id_number').html(response.data.national_id_number);
    		$('#spn_tPNomination_program_start_date').html(response.data.program_start_date);
    		$('#spn_tPNomination_program_end_date').html(response.data.program_end_date);
            $('#spn_tPNomination_beneficiary_institution_name').html(response.data.beneficiary.full_name);
            $('#spn_tPNomination_institution_name').html(response.data.institution_name); 
            $('#spn_tPNomination_institution_state').html(response.data.intitution_state); 
            $('#spn_tPNomination_institution_address').html(response.data.institution_address); 
            $('#spn_tPNomination_total_requested_amount').html(response.data.total_requested_amount); 
            // $('#spn_tPNomination_country_name').html(response.data.country.name + ' (' + response.data.country.country_code + ')');

            $("#spinner-t_p_nominations").hide();
            $("#div-save-mdl-tPNomination-modal").hide();
            $("#btn-save-mdl-tPNomination-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-{{$nominationRequest->type ?? 'tp'}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-tPNomination-modal-error').hide();
        $('#frm-tPNomination-modal').trigger("reset");
        $('#prefix_info').text("Edit");

        $("#spinner-t_p_nominations").show();
        $("#attachments_info_tp").show();
        $("#btn-save-mdl-tPNomination-modal").attr('disabled', true);

        $('#div-show-txt-tPNomination-primary-id').hide();
        $('#div-edit-txt-tPNomination-primary-id').show();
        let itemId = $(this).attr('data-val');

        $('#mdl-tPNomination-modal').modal('show');
        $.get( "{{ route('tf-bi-portal-api.t_p_nominations.show','') }}/"+itemId+"?_method=GET").done(function( response ) {     

			$('#txt-tPNomination-primary-id').val(response.data.id);
            $('#email_tp').val(response.data.email);
    		$('#telephone_tp').val(response.data.telephone);
    		$('#gender_tp').val(response.data.gender);
    		$('#rank_gl_equivalent_tp').val(response.data.rank_gl_equivalent);
    		$('#first_name_tp').val(response.data.first_name);
    		$('#middle_name_tp').val(response.data.middle_name);
    		$('#last_name_tp').val(response.data.last_name);
    		$('#name_suffix_tp').val(response.data.name_suffix);
    		$('#bank_account_name_tp').val(response.data.bank_account_name);
    		$('#bank_account_number_tp').val(response.data.bank_account_number);
    		$('#bank_name_tp').val(response.data.bank_name);
    		$('#bank_sort_code_tp').val(response.data.bank_sort_code);
    		$('#intl_passport_number_tp').val(response.data.intl_passport_number);
    		$('#bank_verification_number_tp').val(response.data.bank_verification_number);
    		$('#national_id_number_tp').val(response.data.national_id_number);
    		$('#degree_type_tp').val(response.data.degree_type);
    		$('#program_title_tp').val(response.data.program_title);
    		$('#program_type_tp').val(response.data.program_type);
    		$('#is_science_program_tp').val(response.data.is_science_program ? '1' : '0');
            $('#institution_name_tp').val(response.data.institution_name);
            $('#institution_state_tp').val(response.data.intitution_state);
            $('#institution_address_tp').val(response.data.institution_address);


            let program_start_date = new Date(response.data.program_start_date);
            let local_program_start_date = new Date(program_start_date.getTime() - (program_start_date.getTimezoneOffset() * 60000)).toISOString().slice(0, 10);

            let program_end_date = new Date(response.data.program_end_date);
            let local_program_end_date = new Date(program_end_date.getTime() - (program_end_date.getTimezoneOffset() * 60000)).toISOString().slice(0, 10);

            $('#program_start_date_tp').val(local_program_start_date);
            $('#program_end_date_tp').val(local_program_end_date);

            // $('#institution_id_select_tp option[value="' + response.data.tf_iterum_portal_institution_id + '"]').prop('selected', 'selected');
            $('#country_id_select_tp option[value="' + response.data.tf_iterum_portal_country_id + '"]').prop('selected', 'selected');
           
            $("#spinner-t_p_nominations").hide();
            $("#div-save-mdl-tPNomination-modal").show();
            $("#btn-save-mdl-tPNomination-modal").attr('disabled', false);

        });
        
    });

    //Delete action
    $(document).on('click', ".btn-delete-{{$nominationRequest->type ?? 'tp'}}", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-t_p_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-t_p_nominations').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this TPNomination?",
                text: "You will not be able to recover this TPNomination if deleted.",
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
                        text: 'Deleting TPNomination Details <br><br> Do not refresh this page! ',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });

                    let endPointUrl = "{{ route('tf-bi-portal-api.t_p_nominations.destroy','') }}/"+itemId;

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
                                    text: "TPNomination deleted successfully",
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
    $('#btn-save-mdl-tPNomination-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-t_p_nominations').fadeIn(300);
            return;
        }else{
            $('.offline-t_p_nominations').fadeOut(300);
        }

        $("#spinner-t_p_nominations").show();
        $("#btn-save-mdl-tPNomination-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('tf-bi-portal-api.t_p_nominations.store') }}";
        let primaryId = $('#txt-tPNomination-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('tf-bi-portal-api.t_p_nominations.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($nominationRequest->user->organization_id) && $nominationRequest->user->organization_id!=null)
            formData.append('organization_id', '{{ $nominationRequest->user->organization_id }}');
            formData.append('user_id', '{{ $nominationRequest->user->id }}');
            formData.append('nomination_request_id', '{{ $nominationRequest->id }}');
        @endif

        if ($('#email_tp').length){	formData.append('email',$('#email_tp').val());	}
		if ($('#telephone_tp').length){	formData.append('telephone',$('#telephone_tp').val());	}
		if ($('#beneficiary_institution_id_select_tp').length){	formData.append('beneficiary_institution_id',$('#beneficiary_institution_id_select_tp').val());	}
        // if ($('#institution_id_select_tp').length){   formData.append('tf_iterum_portal_institution_id',$('#institution_id_select_tp').val());   }
        if ($('#institution_name_tp').length){   formData.append('institution_name',$('#institution_name_tp').val());   }
        if ($('#institution_state_tp').length){   formData.append('intitution_state',$('#institution_state_tp').val());   }
        if ($('#institution_address_tp').length){   formData.append('institution_address',$('#institution_address_tp').val());   }
        if ($('#country_id_select_tp').length){   formData.append('tf_iterum_portal_country_id',$('#country_id_select_tp').val());   }
        if ($('#gender_tp').length){   formData.append('gender',$('#gender_tp').val());   }
		if ($('#rank_gl_equivalent_tp').length){	formData.append('rank_gl_equivalent',$('#rank_gl_equivalent_tp').val());	}
		if ($('#first_name_tp').length){	formData.append('first_name',$('#first_name_tp').val());	}
		if ($('#middle_name_tp').length){	formData.append('middle_name',$('#middle_name_tp').val());	}
		if ($('#last_name_tp').length){	formData.append('last_name',$('#last_name_tp').val());	}
		if ($('#name_suffix_tp').length){	formData.append('name_suffix',$('#name_suffix_tp').val());	}
		if ($('#bank_account_name_tp').length){	formData.append('bank_account_name',$('#bank_account_name_tp').val());	}
		if ($('#bank_account_number_tp').length){	formData.append('bank_account_number',$('#bank_account_number_tp').val());	}
		if ($('#bank_name_tp').length){	formData.append('bank_name',$('#bank_name_tp').val());	formData.append('bank_sort_code',$('#bank_name_tp').attr('data-val-code'));}
		/* if ($('#bank_sort_code_tp').length){	formData.append('bank_sort_code',$('#bank_sort_code_tp').val());	} */
		if ($('#bank_verification_number_tp').length){	formData.append('bank_verification_number',$('#bank_verification_number_tp').val());	}
		if ($('#national_id_number_tp').length){	formData.append('national_id_number',$('#national_id_number_tp').val());	}		
        if ($('#is_science_program_tp').length){ formData.append('is_science_program',$('#is_science_program_tp').val());   }
        if ($('#program_start_date_tp').length){ formData.append('program_start_date',$('#program_start_date_tp').val());   }
        if ($('#program_end_date_tp').length){ formData.append('program_end_date',$('#program_end_date_tp').val());   }

        if($('#passport_photo_tp').get(0).files.length != 0){
            formData.append('passport_photo', $('#passport_photo_tp')[0].files[0]);
        }

        if($('#invitation_letter_tp').get(0).files.length != 0){
            formData.append('invitation_letter', $('#invitation_letter_tp')[0].files[0]);      
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
					$('#div-tPNomination-modal-error').html('');
					$('#div-tPNomination-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-tPNomination-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-tPNomination-modal-error').hide();
                    console.log(result.message);
                    swal({
                        title: "Saved",
                        text: "TPNomination saved successfully",
                        type: "success"
                    });
                    location.reload(true);
                }

                $("#spinner-t_p_nominations").hide();
                $("#btn-save-mdl-tPNomination-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-t_p_nominations").hide();
                $("#btn-save-mdl-tPNomination-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
