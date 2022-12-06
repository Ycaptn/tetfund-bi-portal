



@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-beneficiaries').hide();
    
    //Proceed to process synchronization
    $(document).on('click', ".btn-sync-mdl-beneficiary-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-beneficiaries').fadeIn(300);
            return;
        }else{
            $('.offline-beneficiaries').fadeOut(300);
        }
        swal({
            title: "Are you sure you want to synchronize beneficiary list with newly updated records?",
            text: "You will not be able to undo this process once initiated.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, proceed",
            cancelButtonText: "No, don't proceed",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
                let endPointUrl = "{{ route('tf-bi-portal-api.synchronize_beneficiary_list') }}";                
                
                swal({
                    title: '<div id="spinner-beneficiaries" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                    text: 'Synchronizing Beneficiary List ! <br><br> Do not refresh this page! ',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html: true
                })

                $.ajax({
                    url:endPointUrl,
                    type: "GET",
                    cache: false,
                    processData:false,
                    contentType: false,
                    dataType: 'json',
                    success: function(result){
                        if(result.success && result.success == true){
                            swal({
                                title: "Synchronized",
                                text: result.message,
                                type: "success",
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            });
                            location.reload(true);
                        }else{
                            console.log(result)
                            swal("Error", "Oops an error occurred. Please try again.", "error");
                        }
                    }, error: function(data){
                        console.log(data);
                        swal("Error", "Oops an error occurred. Please try again.", "error");
                    },
                });
            }
        });
    });

    //Show Modal for New Entry
    $(document).on('click', ".btn-new-mdl-beneficiary-modal", function(e) {
        $('#div-beneficiary-modal-error').hide();
        $('#mdl-beneficiary-modal').modal('show');
        $('#frm-beneficiary-modal').trigger("reset");
        $('#txt-beneficiary-primary-id').val(0);

        $('#div-show-txt-beneficiary-primary-id').hide();
        $('#div-edit-txt-beneficiary-primary-id').show();

        $("#spinner-beneficiaries").hide();
        $("#div-save-mdl-beneficiary-modal").attr('disabled', false);
    });


    //Show Modal for View
    $(document).on('click', ".btn-show-mdl-beneficiary-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-beneficiaries').fadeIn(300);
            return;
        }else{
            $('.offline-beneficiaries').fadeOut(300);
        }

        $('#div-beneficiary-modal-error').hide();
        $('#mdl-beneficiary-modal').modal('show');
        $('#frm-beneficiary-modal').trigger("reset");

        $("#spinner-beneficiaries").show();
        $("#div-save-mdl-beneficiary-modal").attr('disabled', true);

        $('#div-show-txt-beneficiary-primary-id').show();
        $('#div-edit-txt-beneficiary-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.beneficiaries.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-beneficiary-primary-id').val(response.data.id);
            		$('#spn_beneficiary_email').html(response.data.email);
		$('#spn_beneficiary_full_name').html(response.data.full_name);
		$('#spn_beneficiary_short_name').html(response.data.short_name);
		$('#spn_beneficiary_official_email').html(response.data.official_email);
		$('#spn_beneficiary_official_website').html(response.data.official_website);
		$('#spn_beneficiary_official_phone').html(response.data.official_phone);
		$('#spn_beneficiary_address_street').html(response.data.address_street);
		$('#spn_beneficiary_address_town').html(response.data.address_town);
		$('#spn_beneficiary_address_state').html(response.data.address_state);
		$('#spn_beneficiary_head_of_institution_title').html(response.data.head_of_institution_title);
		$('#spn_beneficiary_geo_zone').html(response.data.geo_zone);
		$('#spn_beneficiary_owner_agency_type').html(response.data.owner_agency_type);
		$('#spn_beneficiary_tf_iterum_portal_beneficiary_status').html(response.data.tf_iterum_portal_beneficiary_status);


            $("#spinner-beneficiaries").hide();
            $("#div-save-mdl-beneficiary-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-mdl-beneficiary-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-beneficiary-modal-error').hide();
        $('#mdl-beneficiary-modal').modal('show');
        $('#frm-beneficiary-modal').trigger("reset");

        $("#spinner-beneficiaries").show();
        $("#div-save-mdl-beneficiary-modal").attr('disabled', true);

        $('#div-show-txt-beneficiary-primary-id').hide();
        $('#div-edit-txt-beneficiary-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.beneficiaries.show','') }}/"+itemId).done(function( response ) {     

			$('#txt-beneficiary-primary-id').val(response.data.id);
            		$('#email').val(response.data.email);
		$('#full_name').val(response.data.full_name);
		$('#short_name').val(response.data.short_name);
		$('#official_email').val(response.data.official_email);
		$('#official_website').val(response.data.official_website);
		$('#official_phone').val(response.data.official_phone);
		$('#address_street').val(response.data.address_street);
		$('#address_town').val(response.data.address_town);
		$('#address_state').val(response.data.address_state);
		$('#head_of_institution_title').val(response.data.head_of_institution_title);
		$('#geo_zone').val(response.data.geo_zone);
		$('#owner_agency_type').val(response.data.owner_agency_type);
		$('#tf_iterum_portal_beneficiary_status').val(response.data.tf_iterum_portal_beneficiary_status);


            $("#spinner-beneficiaries").hide();
            $("#div-save-mdl-beneficiary-modal").attr('disabled', false);
        });
    });

    //Delete action
    $(document).on('click', ".btn-delete-mdl-beneficiary-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-beneficiaries').fadeIn(300);
            return;
        }else{
            $('.offline-beneficiaries').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this Beneficiary?",
                text: "You will not be able to recover this Beneficiary if deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    let endPointUrl = "{{ route('tf-bi-portal-api.beneficiaries.destroy','') }}/"+itemId;

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
                                console.log(result.errors)
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }else{
                                swal({
                                        title: "Deleted",
                                        text: "Beneficiary deleted successfully",
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    },function(){
                                        location.reload(true);
                                });
                            }
                        },
                    });
                }
            });

    });

    //Save details
    $('#btn-save-mdl-beneficiary-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-beneficiaries').fadeIn(300);
            return;
        }else{
            $('.offline-beneficiaries').fadeOut(300);
        }

        $("#spinner-beneficiaries").show();
        $("#div-save-mdl-beneficiary-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('tf-bi-portal-api.beneficiaries.store') }}";
        let primaryId = $('#txt-beneficiary-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('tf-bi-portal-api.beneficiaries.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif
        // formData.append('', $('#').val());
        		if ($('#email').length){	formData.append('email',$('#email').val());	}
		if ($('#full_name').length){	formData.append('full_name',$('#full_name').val());	}
		if ($('#short_name').length){	formData.append('short_name',$('#short_name').val());	}
		if ($('#official_email').length){	formData.append('official_email',$('#official_email').val());	}
		if ($('#official_website').length){	formData.append('official_website',$('#official_website').val());	}
		if ($('#official_phone').length){	formData.append('official_phone',$('#official_phone').val());	}
		if ($('#address_street').length){	formData.append('address_street',$('#address_street').val());	}
		if ($('#address_town').length){	formData.append('address_town',$('#address_town').val());	}
		if ($('#address_state').length){	formData.append('address_state',$('#address_state').val());	}
		if ($('#head_of_institution_title').length){	formData.append('head_of_institution_title',$('#head_of_institution_title').val());	}
		if ($('#geo_zone').length){	formData.append('geo_zone',$('#geo_zone').val());	}
		if ($('#owner_agency_type').length){	formData.append('owner_agency_type',$('#owner_agency_type').val());	}
		if ($('#tf_iterum_portal_beneficiary_status').length){	formData.append('tf_iterum_portal_beneficiary_status',$('#tf_iterum_portal_beneficiary_status').val());	}


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
					$('#div-beneficiary-modal-error').html('');
					$('#div-beneficiary-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-beneficiary-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-beneficiary-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-beneficiary-modal-error').hide();

                        swal({
                                title: "Saved",
                                text: "Beneficiary saved successfully",
                                type: "success"
                            },function(){
                                location.reload(true);
                        });

                    },20);
                }

                $("#spinner-beneficiaries").hide();
                $("#div-save-mdl-beneficiary-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-beneficiaries").hide();
                $("#div-save-mdl-beneficiary-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
