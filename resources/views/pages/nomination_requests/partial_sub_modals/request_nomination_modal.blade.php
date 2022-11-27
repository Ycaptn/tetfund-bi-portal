<div class="modal fade" id="mdl-requestNomination-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-requestNomination-modal-title" class="modal-title">Request Nomination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-requestNomination-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-requestNomination-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row">
                        <div class="col-sm-12 m-3">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-request_nomination">You are currently offline</span></div>

                            <input type="hidden" id="txt-requestNomination-primary-id" value="0" />

                            <div id="div-edit-txt-requestNomination-primary-id">
                                <div class="row col-sm-12">
                                    @include('pages.nomination_requests.partial_sub_modals.partials_request_nomination.fields')
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-requestNomination-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-requestNomination-modal" value="add">
                    <div id="spinner-request_nomination" class="spinner-border text-info" role="status"> 
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Submit Nomination Request
                </button>
            </div>

        </div>
    </div>
</div>



@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-request_nomination').hide();

    //Show Modal for New Nomination Invitation Entry
    $(document).on('click', ".btn-new-mdl-request_nomination-modal", function(e) {
        $('#div-requestNomination-modal-error').hide();
        $('#mdl-requestNomination-modal').modal('show');
        $('#frm-requestNomination-modal').trigger("reset");
        $('#txt-requestNomination-primary-id').val(0);

        $('#div-show-txt-requestNomination-primary-id').hide();
        $('#div-edit-txt-requestNomination-primary-id').show();

        $("#spinner-request_nomination").hide();
        $("#btn-save-mdl-requestNomination-modal").attr('disabled', true);
    });

    //toggle different nomination forms on changing type
    $(document).on('change', "#nomination_type", function(e) {
        $("#spinner-request_nomination").show();
        $("#btn-save-mdl-requestNomination-modal").attr('disabled', true);
        let nomination_type = $('#nomination_type').val();
        $('#div-requestNomination-modal-error').hide();

        if (nomination_type == 'astd') {
            $('#frm-requestNomination-modal').trigger("reset");
            $('#astd_nomination_form').show();
            $('#ca_nomination_form').hide();
            $('#tp_nomination_form').hide();
            $('#tsas_nomination_form').hide();
            $("#spinner-request_nomination").hide();
            $("#btn-save-mdl-requestNomination-modal").attr('disabled', false);
        } else if (nomination_type == 'ca') {
            $('#frm-requestNomination-modal').trigger("reset");
            $('#ca_nomination_form').show();
            $('#astd_nomination_form').hide();
            $('#tp_nomination_form').hide();
            $('#tsas_nomination_form').hide();
            $("#spinner-request_nomination").hide();
            $("#btn-save-mdl-requestNomination-modal").attr('disabled', false);
        } else if (nomination_type == 'tp') {
            $('#frm-requestNomination-modal').trigger("reset");
            $('#tp_nomination_form').show();
            $('#astd_nomination_form').hide();
            $('#ca_nomination_form').hide();
            $('#tsas_nomination_form').hide();
            $("#spinner-request_nomination").hide();
            $("#btn-save-mdl-requestNomination-modal").attr('disabled', false);
        } else if (nomination_type == 'tsas') {
            $('#frm-requestNomination-modal').trigger("reset");
            $('#tsas_nomination_form').show();
            $('#astd_nomination_form').hide();
            $('#ca_nomination_form').hide();
            $('#tp_nomination_form').hide();
            $("#spinner-request_nomination").hide();
            $("#btn-save-mdl-requestNomination-modal").attr('disabled', false);
        } else {
            $('#frm-requestNomination-modal').trigger("reset");
            $('#astd_nomination_form').hide();
            $('#ca_nomination_form').hide();
            $('#tp_nomination_form').hide();
            $('#tsas_nomination_form').hide();
            $("#spinner-request_nomination").hide();
            $("#btn-save-mdl-requestNomination-modal").attr('disabled', true);
        }

        $('#nomination_type').val(nomination_type);

    });

    /*//Show Modal for View
    $(document).on('click', ".btn-show-mdl-requestNomination-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-request_nomination').fadeIn(300);
            return;
        }else{
            $('.offline-request_nomination').fadeOut(300);
        }

        $('#div-requestNomination-modal-error').hide();
        $('#mdl-requestNomination-modal').modal('show');
        $('#frm-requestNomination-modal').trigger("reset");

        $("#spinner-request_nomination").show();
        $("#btn-save-mdl-requestNomination-modal").attr('disabled', true);

        $('#div-show-txt-requestNomination-primary-id').show();
        $('#div-edit-txt-requestNomination-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {
            
            $('#txt-requestNomination-primary-id').val(response.data.id);
                    $('#spn_requestNomination_title').html(response.data.title);
        $('#spn_requestNomination_status').html(response.data.status);
        $('#spn_requestNomination_type').html(response.data.type);
        $('#spn_requestNomination_tf_iterum_portal_request_status').html(response.data.tf_iterum_portal_request_status);


            $("#spinner-request_nomination").hide();
            $("#btn-save-mdl-requestNomination-modal").attr('disabled', false);
        });
    });*/

    //Show Modal for Edit
    /*$(document).on('click', ".btn-edit-mdl-requestNomination-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-requestNomination-modal-error').hide();
        $('#mdl-requestNomination-modal').modal('show');
        $('#frm-requestNomination-modal').trigger("reset");

        $("#spinner-request_nomination").show();
        $("#btn-save-mdl-requestNomination-modal").attr('disabled', true);

        $('#div-show-txt-requestNomination-primary-id').hide();
        $('#div-edit-txt-requestNomination-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {     

            $('#txt-requestNomination-primary-id').val(response.data.id);
                    $('#title').val(response.data.title);
        $('#status').val(response.data.status);
        $('#type').val(response.data.type);
        $('#tf_iterum_portal_request_status').val(response.data.tf_iterum_portal_request_status);


            $("#spinner-request_nomination").hide();
            $("#btn-save-mdl-requestNomination-modal").attr('disabled', false);
        });
    });*/

    //Delete action
    /*$(document).on('click', ".btn-delete-mdl-requestNomination-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-request_nomination').fadeIn(300);
            return;
        }else{
            $('.offline-request_nomination').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this requestNomination?",
                text: "You will not be able to recover this requestNomination if deleted.",
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
                                        text: "requestNomination deleted successfully",
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
    $('#btn-save-mdl-requestNomination-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-request_nomination').fadeIn(300);
            return;
        }else{
            $('.offline-request_nomination').fadeOut(300);
        }

        $("#spinner-request_nomination").show();
        $("#btn-save-mdl-requestNomination-modal").attr('disabled', true);

        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());
        formData.append('_method', 'POST');
        formData.append('nomination_request_and_submission', '1');
        if ($('#nomination_type').length){ formData.append('nomination_type',$('#nomination_type').val()); }

        //nomination request data appended
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif

       
        // astd nomination request data appended
        @include('pages.nomination_requests.partial_sub_modals.partials_request_nomination.js_append_astd_form_data')

        // tp nomination request data appended
        @include('pages.nomination_requests.partial_sub_modals.partials_request_nomination.js_append_tp_form_data')

        $.ajax({
            url: "{{ route('tf-bi-portal-api.nomination_requests.store_nomination_request_and_details') }}",
            type: "POST",
            data: formData,
            cache: false,
            processData:false,
            contentType: false,
            dataType: 'json',
            success: function(result){
                if(result.errors){
                    $('#div-requestNomination-modal-error').html('');
                    $('#div-requestNomination-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-requestNomination-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-requestNomination-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-requestNomination-modal-error').hide();

                        swal({
                            title: "Saved",
                            text: result.message,
                            type: "success"
                        },function(){
                            location.reload(true);
                        });

                    },20);
                }

                $("#spinner-request_nomination").hide();
                $("#btn-save-mdl-requestNomination-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-request_nomination").hide();
                $("#btn-save-mdl-requestNomination-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush