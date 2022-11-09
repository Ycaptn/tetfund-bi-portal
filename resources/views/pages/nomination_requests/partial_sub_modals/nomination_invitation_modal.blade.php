<div class="modal fade" id="mdl-nominationInvitation-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-nominationInvitation-modal-title" class="modal-title"> Staff Nomination Invitation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-nominationInvitation-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-nominationInvitation-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row">
                        <div class="col-sm-12 m-3">
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-nomination_invitation">You are currently offline</span></div>

                            <input type="hidden" id="txt-nominationInvitation-primary-id" value="0" />

                            <div id="div-edit-txt-nominationInvitation-primary-id">
                                <div class="row col-sm-12">
                                    <div class="row col-sm-12 mb-3 text-justify">
                                        <small>
                                            <i class="text-danger">
                                                <strong>Note: </strong> Beneficiary staff email provided here must be a registered and active account on this portal. However if this is not the case, the system will attempt creating a new user with the email provided.
                                            </i>
                                        </small>
                                    </div><hr>
                                    @include('pages.nomination_requests.partial_sub_modals.partials_nomination_invite.fields')
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-nominationInvitation-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-nominationInvitation-modal" value="add">
                    <div id="spinner-nomination_invitation" class="spinner-border text-info" role="status"> 
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Process & send Invitation
                </button>
            </div>

        </div>
    </div>
</div>





@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-nomination_invitation').hide();

    //Show Modal for New Nomination Invitation Entry
    $(document).on('click', ".btn-new-mdl-nominationInvitation-modal", function(e) {
        $('#div-nominationInvitation-modal-error').hide();
        $('#mdl-nominationInvitation-modal').modal('show');
        $('#frm-nominationInvitation-modal').trigger("reset");
        $('#txt-nominationInvitation-primary-id').val(0);

        $('#div-show-txt-nominationInvitation-primary-id').hide();
        $('#div-edit-txt-nominationInvitation-primary-id').show();

        $("#spinner-nomination_invitation").hide();
        $("#btn-save-mdl-nominationInvitation-modal").attr('disabled', false);
    });

    /*//Show Modal for View
    $(document).on('click', ".btn-show-mdl-nominationInvitation-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-nomination_invitation').fadeIn(300);
            return;
        }else{
            $('.offline-nomination_invitation').fadeOut(300);
        }

        $('#div-nominationInvitation-modal-error').hide();
        $('#mdl-nominationInvitation-modal').modal('show');
        $('#frm-nominationInvitation-modal').trigger("reset");

        $("#spinner-nomination_invitation").show();
        $("#btn-save-mdl-nominationInvitation-modal").attr('disabled', true);

        $('#div-show-txt-nominationInvitation-primary-id').show();
        $('#div-edit-txt-nominationInvitation-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {
            
            $('#txt-nominationInvitation-primary-id').val(response.data.id);
                    $('#spn_nominationInvitation_title').html(response.data.title);
        $('#spn_nominationInvitation_status').html(response.data.status);
        $('#spn_nominationInvitation_type').html(response.data.type);
        $('#spn_nominationInvitation_tf_iterum_portal_request_status').html(response.data.tf_iterum_portal_request_status);


            $("#spinner-nomination_invitation").hide();
            $("#btn-save-mdl-nominationInvitation-modal").attr('disabled', false);
        });
    });*/

    //Show Modal for Edit
    /*$(document).on('click', ".btn-edit-mdl-nominationInvitation-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-nominationInvitation-modal-error').hide();
        $('#mdl-nominationInvitation-modal').modal('show');
        $('#frm-nominationInvitation-modal').trigger("reset");

        $("#spinner-nomination_invitation").show();
        $("#btn-save-mdl-nominationInvitation-modal").attr('disabled', true);

        $('#div-show-txt-nominationInvitation-primary-id').hide();
        $('#div-edit-txt-nominationInvitation-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {     

            $('#txt-nominationInvitation-primary-id').val(response.data.id);
                    $('#title').val(response.data.title);
        $('#status').val(response.data.status);
        $('#type').val(response.data.type);
        $('#tf_iterum_portal_request_status').val(response.data.tf_iterum_portal_request_status);


            $("#spinner-nomination_invitation").hide();
            $("#btn-save-mdl-nominationInvitation-modal").attr('disabled', false);
        });
    });*/

    //Delete action
    /*$(document).on('click', ".btn-delete-mdl-nominationInvitation-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-nomination_invitation').fadeIn(300);
            return;
        }else{
            $('.offline-nomination_invitation').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this nominationInvitation?",
                text: "You will not be able to recover this nominationInvitation if deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    let endPointUrl = "{{ route('tf-bi-portal-api.nomination_requests.destroy','') }}/"+itemId;

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
                                        text: "nominationInvitation deleted successfully",
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

    });*/


    //Save details
    $('#btn-save-mdl-nominationInvitation-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-nomination_invitation').fadeIn(300);
            return;
        }else{
            $('.offline-nomination_invitation').fadeOut(300);
        }

        $("#spinner-nomination_invitation").show();
        $("#btn-save-mdl-nominationInvitation-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('tf-bi-portal-api.nomination_requests.store') }}";
        let primaryId = $('#txt-nominationInvitation-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('tf-bi-portal-api.nomination_requests.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif

        // formData.append('', $('#').val());
        if ($('#bi_staff_email').length){ formData.append('bi_staff_email',$('#bi_staff_email').val()); }

        if ($('#bi_staff_fname').length){ formData.append('bi_staff_fname',$('#bi_staff_fname').val()); }

        if ($('#bi_staff_lname').length){ formData.append('bi_staff_lname',$('#bi_staff_lname').val()); }
        
        if ($('#bi_telephone').length){ formData.append('bi_telephone',$('#bi_telephone').val());   }
        
        if ($('#bi_staff_gender').length){ formData.append('bi_staff_gender',$('#bi_staff_gender').val()); }

        if ($('#nomination_type').length){ formData.append('nomination_type',$('#nomination_type').val()); }
        
        if ($('#bind_nomination_to_submission').length){ formData.append('bi_submission_request_id',$('#bind_nomination_to_submission').val()); }

        $.ajax({
            url:endPointUrl,
            type: "POST",
            data: formData,
            cache: false,
            processData:false,
            contentType: false,
            dataType: 'json',
            success: function(result){
                if (result.errors) {
                    $('#div-nominationInvitation-modal-error').html('');
                    $('#div-nominationInvitation-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-nominationInvitation-modal-error').append('<li class="">'+value+'</li>');
                    });
                } else {
                    $('#div-nominationInvitation-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-nominationInvitation-modal-error').hide();

                        swal({
                            title: "Saved",
                            text: result.message,
                            type: "success"
                        },function(){
                            location.reload(true);
                        });

                    },20);
                }

                $("#spinner-nomination_invitation").hide();
                $("#btn-save-mdl-nominationInvitation-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-nomination_invitation").hide();
                $("#btn-save-mdl-nominationInvitation-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush