{{-- reset beneficiary user password --}}
<div class="modal fade" id="mdl-beneficiary-member-reset-password-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-beneficiary-reset-password-modal-title" class="modal-title">Reset Beneficiary User Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="col-sm-12 alert alert-info">
                    <i class=""><strong>NOTE:</strong> <span id='opposite_creating'> The selected <strong>{{ ucwords(strtolower($beneficiary->full_name)); }}</strong></i> beneficiary user login password will be changed after completing this action.
                </div>
                <div id="div-beneficiary-member-reset-password-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="form-beneficiary-member-reset-password" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row">
                        <div class="col-sm-12 m-3">
                            @csrf                        
                            <div class="offline-flag"><span class="offline-beneficiary-member-reset-password">You are currently offline</span></div>

                            <input type="hidden" id="txt-beneficiary-member-reset-password-primary-id" value="0" />

                            <div class="row col-sm-12 form-group mb-3">
                                <label class="col-sm-12 control-label" for="bi_staff_new_password">Beneficiary User New Password</label>
                                <div class="col-sm-12 input-group">
                                    <input type="password" name="bi_staff_new_password" value="" placeholder="User New Password" class="form-control" id="bi_staff_new_password">
                                    <span class="input-group-text"><span class="fa fa-lock"></span></span>
                                </div>
                            </div>

                            <div class="row col-sm-12 form-group mb-3">
                                <label class="col-sm-12 control-label" for="bi_staff_confirm_password">Confirm User New Password</label>
                                <div class="col-sm-12 input-group">
                                    <input type="password" name="bi_staff_confirm_password" value="" placeholder="User Email Address" class="form-control" id="bi_staff_confirm_password">
                                    <span class="input-group-text"><span class="fa fa-lock"></span></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-beneficiary-member-reset-password-modal">
                <button type="button" class="btn btn-primary" id="btn-save-beneficiary-member-reset-password-modal" value="add">
                    <div id="spinner-beneficiary-member-reset-password" class="spinner-border text-info" role="status"> 
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Process & Reset</span>
                </button>
            </div>

        </div>
    </div>
</div>


{{-- new beneficiary member modal --}}
<div class="modal fade" id="mdl-beneficiary-member-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-beneficiary-modal-title" class="modal-title"><span class="opposite_create">Create</span> Beneficiary User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="col-sm-12 alert alert-info">
                    <i class=""><strong>NOTE:</strong> <span id='opposite_creating'>You will be creating a new</span> beneficiary user for <strong>{{ ucwords(strtolower($beneficiary->full_name)); }}</strong></i>
                </div>
                <div id="div-beneficiary-member-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="form-beneficiary-member-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row">
                        <div class="col-sm-12 m-3" id="div_beneficiary_member_fields">
                            @include('tf-bi-portal::pages.beneficiaries.partials.beneficiary_member_fields')
                        </div>

                        <div class="col-sm-12 m-3" id="div_beneficiary_member_show_fields">
                            @include('tf-bi-portal::pages.beneficiaries.partials.beneficiary_member_show_fields')
                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-beneficiary-member-modal">
                <button type="button" class="btn btn-primary" id="btn-save-beneficiary-member-modal" value="add">
                    <div id="spinner-beneficiary-member" class="spinner-border text-info" role="status"> 
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Process & <span class="opposite_create">Create</span>
                </button>
                <button type="button" class="btn btn-primary" id="btn-dismiss-beneficiary-member-preview-modal" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>

        </div>
    </div>
</div>

{{-- Bulk upload modal bulk-new-beneficiary-members modal --}}
<div class="modal fade" id="mdl-bulk-new-beneficiary-members-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="lbl-beneficiary-modal-title" class="modal-title"><span class="opposite_create">Create</span> Bulk Beneficiary Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="offline-flag"><span class="offline-bulk-beneficiary-users">You are currently offline</span></div>

            <div class="modal-body">
                <div class="col-sm-12 alert alert-info">
                    <i class=""><strong>NOTE:</strong> <span id='opposite_creating'>You will be creating new</span> beneficiary users for <strong>{{ ucwords(strtolower($beneficiary->full_name)); }}</strong></i>
                </div>
                
                <div id="div-bulk-new-beneficiary-members-modal-error" class="alert alert-danger" role="alert"></div>

                <form id="form-bulk-new-beneficiary-members-modal" class="form-bulk-new-beneficiary-members-modal">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="div-bulk_user" class="form-group">
                                <label class="col-sm-12">
                                    <b>Please upload a csv file</b>
                                </label>

                                <div class="col-sm-9">
                                    {!! Form::file('bulk_users', ['class' => 'form-control', 'id' => 'bulk_users']) !!}
                                </div>
                            </div>

                            <div class=" m-2">
                                <a id="format_csv_file" src="" class="btn btn-sm btn-danger small"
                                    data-toggle="tootip" title="Users csv file format" href="{{ asset('csv/beneficiary_staffs_upload_cvs_format.csv') }}">
                                    <i class="fa fa-download"></i> <small>Download</small>
                                </a>
                                
                                <span class="badge-secondary mb-5 ml-30">
                                    Users csv file format:
                                </span>                                
                            </div>
                            
                        </div>
                    </div>

                </form>
            </div>
        
            <div class="modal-footer" id="div-save-bulk-new-beneficiary-members-modal">
                <button type="button" class="btn btn-primary " id="btn-save-mdl-bulk-beneficiary-users-modal" value="add">
                    <div id="spinner-bulk-new-beneficiary-members" class="spinner-border text-info" role="status"> 
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Process & Create</span>
                </button>
            </div>
        </div>
    </div>
</div>


@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.offline-beneficiary-member').hide();
        
            //Show Modal for New beneficiary members Entry
            $(document).on('click', ".btn-new-beneficiary-member", function(e) {
                e.preventDefault();
                $('#div-beneficiary-member-modal-error').hide();
                $('#bi_staff_email').attr('disabled', false);
                $('.opposite_create').text("Create");
                $('#opposite_creating').text("You will be creating a new");
                $('#mdl-beneficiary-member-modal').modal('show');

                $('#div_beneficiary_member_show_fields').hide();
                $('#div_beneficiary_member_fields').show();
                $('#btn-dismiss-beneficiary-member-preview-modal').hide();
                $('#btn-save-beneficiary-member-modal').show();

                $('#form-beneficiary-member-modal').trigger("reset");
                $('#txt-beneficiary-member-primary-id').val(0);

                $("#spinner-beneficiary-member").hide();
                $("#btn-new-beneficiary-member").attr('disabled', false);
            });

            //Show Modal for Bulk New beneficiary members Entry
            $(document).on('click', "#btn-bulk-new-beneficiary-members", function(e) {
                e.preventDefault();
                $('#div-bulk-new-beneficiary-members-modal-error').hide();
                $('#mdl-bulk-new-beneficiary-members-modal').modal('show');
                $('#form-bulk-new-beneficiary-members-modal').trigger("reset");
                $("#spinner-bulk-new-beneficiary-members").hide();
            });

            //Show Modal for beneficiary member password reset
            $(document).on('click', ".btn-reset-password-beneficiary-member", function(e) {
                e.preventDefault();
                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-beneficiary-member-reset-password').fadeIn(300);
                    return;
                }else{
                    $('.offline-beneficiary-member-reset-password').fadeOut(300);
                }

                $('#div-beneficiary-member-reset-password-modal-error').hide();
                $('#mdl-beneficiary-member-reset-password-modal').modal('show');
                $('#btn-save-beneficiary-member-reset-password-modal').show();

                $('#form-beneficiary-member-reset-password').trigger("reset");

                let itemId = $(this).attr('data-val');
                $('#txt-beneficiary-member-reset-password-primary-id').val(itemId);

                $("#spinner-beneficiary-member-reset-password").hide();
            });

            //process beneficiary user password reset action
            $('#btn-save-beneficiary-member-reset-password-modal').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-beneficiary-member-reset-password').fadeIn(300);
                    return;
                }else{
                    $('.offline-beneficiary-member-reset-password').fadeOut(300);
                }

                $("#spinner-beneficiary-member-reset-password").show();
                $("#btn-save-beneficiary-member-reset-password-modal").attr('disabled', true);

                let itemId = $('#txt-beneficiary-member-reset-password-primary-id').val();
                
                let formData = new FormData();
                formData.append('_token', $('input[name="_token"]').val());
                
                formData.append('_method', "PUT");
                @if (isset($organization) && $organization!=null)
                    formData.append('organization_id', '{{$organization->id}}');
                @endif

                if ($('#bi_staff_new_password').length){ formData.append('password',$('#bi_staff_new_password').val()); }
                if ($('#bi_staff_confirm_password').length){ formData.append('confirm_password',$('#bi_staff_confirm_password').val()); }

                $.ajax({
                    url: "{{ route('tf-bi-portal-api.reset_password_beneficiary_member','') }}/"+itemId,
                    type: "POST",
                    data: formData,
                    cache: false,
                    processData:false,
                    contentType: false,
                    dataType: 'json',
                    success: function(result){
                        if(result.errors){
                            $('#div-beneficiary-member-reset-password-modal-error').html('');
                            $('#div-beneficiary-member-reset-password-modal-error').show();
                            
                            $.each(result.errors, function(key, value){
                                $('#div-beneficiary-member-reset-password-modal-error').append('<li class="">'+value+'</li>');
                            });
                        }else{
                            $('#div-beneficiary-member-reset-password-modal-error').hide();
                            window.setTimeout( function(){

                                $('#div-beneficiary-member-reset-password-modal-error').hide();

                                swal({
                                    title: "Saved",
                                    text: result.message,
                                    type: "success"
                                });
                                
                                location.reload(true);

                            },20);
                        }

                        $("#spinner-beneficiary-member-reset-password").hide();
                        $("#btn-save-beneficiary-member-reset-password-modal").attr('disabled', false);
                        
                    }, error: function(data){
                        console.log(data);
                        swal("Error", "Oops an error occurred. Please try again.", "error");

                        $("#spinner-beneficiary-member-reset-password").hide();
                        $("#btn-save-beneficiary-member-reset-password-modal").attr('disabled', false);

                    }
                });
            });

            //Show Modal to preview beneficiary member details
            $(document).on('click', ".btn-preview-beneficiary-member", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div-beneficiary-member-modal-error').hide();
                $('.opposite_create').text("Preview");
                $('#opposite_creating').text("You are previewing details of an existing");
                $('#mdl-beneficiary-member-modal').modal('show');

                $('#div_beneficiary_member_fields').hide();
                $('#div_beneficiary_member_show_fields').show();
                $('#btn-save-beneficiary-member-modal').hide();
                $('#btn-dismiss-beneficiary-member-preview-modal').show();

                $('#form-beneficiary-member-modal').trigger("reset");

                $("#spinner-beneficiary-member").show();
                $("#btn-new-beneficiary-member").attr('disabled', true);

                let itemId = $(this).attr('data-val');
                $('#txt-beneficiary-member-primary-id').val(itemId);

                $.get( "{{ route('tf-bi-portal-api.show_beneficiary_member','') }}/"+itemId).done(function( response ) {     
                    console.log(response);
                    $('#bi_staff_email_preview').text((response.data.email != null) ? response.data.email.toLowerCase() : 'N/A');
                    $('#bi_staff_account_status_preview').text((response.data.is_disabled == 0) ? 'Enabled' : 'Disabled');
                    $('#bi_staff_fname_preview').text((response.data.first_name != null) ? response.data.first_name.toUpperCase() : 'N/A');
                    $('#bi_staff_lname_preview').text((response.data.last_name != null) ? response.data.last_name.toUpperCase() : 'N/A');
                    $('#bi_telephone_preview').text((response.data.telephone != null) ? response.data.telephone.toUpperCase() : 'N/A');
                    $('#bi_staff_gender_preview').text((response.data.gender != null) ? response.data.gender.toUpperCase() : 'N/A');
                    $('#bi_staff_grade_level_preview').text((response.data.grade_level != null) ? "GL-"+response.data.grade_level: 'N/A');
                    $('#bi_staff_member_type_preview').text((response.data.member_type != null) ? response.data.member_type.toUpperCase() : 'N/A');
                    // handling data for role(s)
                    $('#bi_staff_userRoles').text('N/A');
                    if(response.data.user_roles != '') {
                        let roles =  ''
                        $.each(response.data.user_roles, function(key, value){
                            roles += "<div class='form-group col-sm-4'> <label class='form-label' for='userRole_"+ value +"'>"+ value.toUpperCase() +"</label>  </div>";
                        });
                        $('#bi_staff_userRoles').html(roles);
                    }

                    $("#spinner-beneficiary-member").hide();
                    $("#div-save-mdl-beneficiary-member-modal").attr('disabled', false);
                });
            });

            //process disable & enable beneficiary member
            $(document).on('click', ".btn-enable-disable-beneficiary-member", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                let itemId = $(this).attr('data-val');
                let itemFlag = itemId.slice(-1);
                let itemName = (itemFlag == '0') ? 'Disabl' : 'Enabl';
                swal({
                    title: "Are you sure you want to " + itemName.toLowerCase() + "e this beneficiary user?",
                    text: 'This action will be applied to selected beneficiary user only!',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, " + itemName.toLowerCase() + 'e',
                    cancelButtonText: "No, don't " + itemName.toLowerCase() + 'e',
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: '<div id="spinner-beneficiary-member" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                            text: itemName + "ing beneficiary user! <br><br> Do not refresh this page! ",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            html: true
                        })
                        
                        let endPointUrl = "{{ route('tf-bi-portal-api.enable_disable_beneficiary_member','') }}/"+itemId;                

                        let formData = new FormData();
                        
                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('_method', "GET");

                        @if (isset($organization) && $organization!=null)
                            formData.append('organization_id', '{{$organization->id}}');
                        @endif
                        formData.append('user_id', itemId);
                        
                        $.ajax({
                            url:endPointUrl,
                            type: "GET",
                            cache: false,
                            data: formData,
                            processData:false,
                            contentType: false,
                            dataType: 'json',
                            success: function(result){
                                if(result.success && result.success == true){
                                    swal({
                                        title: itemName + 'ed',
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
                            },
                        });
                    }
                });
            });


            //Delete action for beneficiary user
            $(document).on('click', ".btn-delete-beneficiary-member", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-beneficiary-member').fadeIn(300);
                    return;
                }else{
                    $('.offline-beneficiary-member').fadeOut(300);
                }

                let itemId = $(this).attr('data-val');
                swal({
                    title: "Are you sure you want to delete this beneficiary user?",
                    text: "You will not be able to recover beneficiary user data if deleted.",
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
                            title: '<div id="spinner-beneficiary-member" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                            text: "Deleting beneficiary user! <br><br> Do not refresh this page! ",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            html: true
                        })

                        let endPointUrl = "{{ route('tf-bi-portal-api.delete_beneficiary_member','') }}/"+itemId;

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
                                        text: "Beneficiary user deleted successfully",
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


            //Show Modal for beneficiary member Edit
            $(document).on('click', ".btn-edit-beneficiary-member", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div-beneficiary-member-modal-error').hide();
                $('.opposite_create').text("Modify");
                $('#opposite_creating').text("You will be modifying an existing");
                $('#mdl-beneficiary-member-modal').modal('show');

                $('#div_beneficiary_member_show_fields').hide();
                $('#div_beneficiary_member_fields').show();
                $('#btn-dismiss-beneficiary-member-preview-modal').hide();
                $('#btn-save-beneficiary-member-modal').show();

                $('#form-beneficiary-member-modal').trigger("reset");

                $("#spinner-beneficiary-member").show();
                $("#btn-new-beneficiary-member").attr('disabled', true);

                let itemId = $(this).attr('data-val');
                $('#txt-beneficiary-member-primary-id').val(itemId);

                $.get( "{{ route('tf-bi-portal-api.show_beneficiary_member','') }}/"+itemId).done(function( response ) {     
                  //  console.log(response);
                    $('#bi_staff_email').val(response.data.email);
                    $('#bi_staff_fname').val(response.data.first_name);
                    $('#bi_staff_lname').val(response.data.last_name);
                    $('#bi_telephone').val(response.data.telephone);
                    $('#bi_staff_gender').val((response.data.gender != null) ? response.data.gender.toLowerCase() : '');
                    $('#bi_grade_level').val((response.data.grade_level != null) ? response.data.grade_level : '');
                    $('#bi_member_type').val((response.data.member_type != null) ? response.data.member_type : '');
                    // handling data for role(s)
                    if(response.data.user_roles != '') {
                        $.each(response.data.user_roles, function(key, value){
                            $('#userRole_' + value).prop('checked', true);
                        });
                    }

                    $("#spinner-beneficiary-member").hide();
                    $("#div-save-mdl-beneficiary-member-modal").attr('disabled', false);
                });
            });

        //Save beneficiary member details
            $('#btn-save-beneficiary-member-modal').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-beneficiary-member').fadeIn(300);
                    return;
                }else{
                    $('.offline-beneficiary-member').fadeOut(300);
                }

                $("#spinner-beneficiary-member").show();
                $("#btn-save-beneficiary-member-modal").attr('disabled', true);

                let actionType = "POST";
                let endPointUrl = "{{ route('tf-bi-portal-api.store_beneficiary_member') }}";
                let primaryId = $('#txt-beneficiary-member-primary-id').val();
                
                let formData = new FormData();
                formData.append('_token', $('input[name="_token"]').val());

                if (primaryId != "0"){
                    actionType = "PUT";
                    endPointUrl = "{{ route('tf-bi-portal-api.update_beneficiary_member','') }}/"+primaryId;
                    formData.append('id', primaryId);
                }
                
                formData.append('_method', actionType);
                @if (isset($organization) && $organization!=null)
                    formData.append('organization_id', '{{$organization->id}}');
                @endif

                @if (isset($beneficiary->id) && $beneficiary->id!=null)
                    formData.append('beneficiary_id', '{{$beneficiary->id}}');
                @endif

                if ($('#bi_staff_email').length){ formData.append('bi_staff_email',$('#bi_staff_email').val()); }
                if ($('#bi_staff_fname').length){ formData.append('bi_staff_fname',$('#bi_staff_fname').val()); }
                if ($('#bi_staff_lname').length){ formData.append('bi_staff_lname',$('#bi_staff_lname').val()); }
                if ($('#bi_telephone').length){ formData.append('bi_telephone',$('#bi_telephone').val());   }               
                if ($('#bi_staff_gender').length){ formData.append('bi_staff_gender',$('#bi_staff_gender').val()); }
                if ($('#bi_member_type').length){ formData.append('bi_member_type',$('#bi_member_type').val()); }
                if ($('#bi_grade_level').length){ formData.append('bi_grade_level',$('#bi_grade_level').val()); }
                // handling data for role(s)
                @if(isset($roles) && count($roles) > 0)
                    @foreach($roles as $role)
                        formData.append('userRole_{{$role}}', ($('input[name="userRole_{{$role}}"]').is(':checked')) ? 'on' : 'off' );
                    @endforeach
                @endif

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
                            $('#div-beneficiary-member-modal-error').html('');
                            $('#div-beneficiary-member-modal-error').show();
                            
                            $.each(result.errors, function(key, value){
                                $('#div-beneficiary-member-modal-error').append('<li class="">'+value+'</li>');
                            });
                        }else{
                            $('#div-beneficiary-member-modal-error').hide();
                            window.setTimeout( function(){

                                $('#div-beneficiary-member-modal-error').hide();

                                swal({
                                    title: "Saved",
                                    text: result.message,
                                    type: "success"
                                });
                                
                                location.reload(true);

                            },20);
                        }

                        $("#spinner-beneficiary-member").hide();
                        $("#btn-save-beneficiary-member-modal").attr('disabled', false);
                        
                    }, error: function(data) {
                        console.log(data);
                        swal("Error", "Oops an error occurred. Please try again.", "error");

                        $("#spinner-beneficiary-member").hide();
                        $("#btn-save-beneficiary-member-modal").attr('disabled', false);

                    }
                });
            });

            // process and save bulk beneficiary users upload
            $(document).on('click', '#btn-save-mdl-bulk-beneficiary-users-modal', function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-beneficiary-member').fadeIn(300);
                    return;
                }else{
                    $('.offline-beneficiary-member').fadeOut(300);
                }

                $("#spinner-bulk-new-beneficiary-members").show();
                $("#btn-save-mdl-bulk-beneficiary-users-modal").attr('disabled', true);

                let formData = new FormData();
                formData.append('_method', "POST");
                @if (isset($organization) && $organization != null)
                    formData.append('organization_id', '{{ $organization->id }}');
                @endif
                formData.append('_token', $('input[name="_token"]').val());

                if ($('#bulk_users')[0].files.length > 0) {
                    formData.append('bulk_users_file', $('#bulk_users')[0].files[0]);
                }

                $.ajax({
                    url: "{{ route('tf-bi-portal-api.process_bulk_beneficiary_users_upload','') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(result) {
                        console.log(result);
                        
                        if (result.errors) {
                            $('#div-bulk-new-beneficiary-members-modal-error').html('');
                            $('#div-bulk-new-beneficiary-members-modal-error').show();
                            
                            $.each(result.errors, function(key, value){
                                $('#div-bulk-new-beneficiary-members-modal-error').append('<li class="">'+value+'</li>');
                            });
                        } else {
                            $('#div-bulk-user-modal-error').hide();
                            window.setTimeout(function() {
                                $('#div-bulk-new-beneficiary-members-modal-error').hide();
                                swal({
                                    title: "Bulk Upload Completed",
                                    text: result.message,
                                    type: "success"
                                });
                                location.reload(true);
                            }, 20);
                        }

                        $("#spinner-bulk-new-beneficiary-members").hide();
                        $("#btn-save-mdl-bulk-beneficiary-users-modal").attr('disabled', false);

                    }, error: function(data) {
                        console.log(data);
                        swal("Error", "Oops an error occurred. Please try again.", "error");

                        $("#spinner-bulk-new-beneficiary-members").hide();
                        $("#btn-save-mdl-bulk-beneficiary-users-modal").attr('disabled', false);

                    }
                });
            });
        });
    </script>
@endpush