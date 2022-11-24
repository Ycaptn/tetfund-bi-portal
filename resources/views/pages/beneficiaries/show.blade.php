@extends('layouts.app')

@section('app_css')
    <style type="text/css">
        /* Style the tab */
        .tab {
          overflow: hidden;
          border: 1px solid #ccc;
          background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        .tab button {
          background-color: inherit;
          float: left;
          border: none;
          outline: none;
          cursor: pointer;
          padding: 14px 16px;
          transition: 0.3s;
          font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
          background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
          background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
          display: none;
          padding: 6px 12px;
          border: 1px solid #ccc;
          border-top: none;
        }
    </style>
@stop

@section('title_postfix')
Beneficiary
@stop

@section('page_title')
Beneficiary
@stop

@section('page_title_suffix')
{{$beneficiary->title}}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.beneficiaries.index') }}">
    <i class="fa fa-angle-double-left"></i> Back to Beneficiary List
</a>
@stop

@section('page_title_buttons')

    {{-- <a data-toggle="tooltip" 
        title="New" 
        data-val='{{$beneficiary->id}}' 
        class="btn btn-sm btn-primary btn-new-mdl-beneficiary-member-modal" href="#">
        <i class="fa fa-eye"></i> New
    </a>

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$beneficiary->id}}' 
        class="btn btn-sm btn-primary btn-edit-mdl-beneficiary-member-modal" href="#">
        <i class="fa fa-pencil-square-o"></i> Edit
    </a>

    @if (Auth()->user()->hasAnyRole(['','admin']))
        @include('tf-bi-portal::pages.beneficiaries.bulk-upload-modal')
    @endif --}}
@stop



@section('content')
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="row col-sm-12">
                @include('tf-bi-portal::pages.beneficiaries.modal') 
                @include('tf-bi-portal::pages.beneficiaries.show_fields')
            </div>
            <div class="col-lg-12">
                <div class="tab pb-2 mt-3" style="border-top: thin solid lightgray; border-bottom: thin solid lightgray;">
                    <ul class="nav">
                        <li class="mt-3" style="margin-right: 3px;">
                            <a href="#?beneficiary_members=true" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event,'beneficiary_details')" id="defaultOpen">
                                Members
                            </a>                        
                        </li>
                        <li class="mt-3" style="margin-right: 3px;">                            
                            <a href="#?submissions=submissions" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event, 'submissions')">
                                Submissions
                            </a>
                        </li>
                        <li class="mt-3" style="margin-right: 3px;">                                
                            <a href="#?nominations=nominations" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event, 'nominations')">
                                Nominations
                            </a>
                        </li>
                    </ul>
                </div>


            {{-- sub menu contents details--}}
                <div id="beneficiary_details" class="tabcontent">
                    <div class="col-sm-12 panel panel-default card-view">
                        <h5 class="pt-2"> 
                            <strong>
                                Beneficiary Members 
                            </strong>
                             <a title="Create New Beneficiary Member" class="btn btn-primary btn-sm pull-right btn-new-beneficiary-member" href="#">
                                <span class="fa fa-plus"></span> <small>New Member</small>
                            </a>
                        </h5>
                        @include('tf-bi-portal::pages.beneficiaries.table')
                        @include('tf-bi-portal::pages.beneficiaries.partials.beneficiary_member_modal')
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small">
            This is the help message.
            This is the help message.
            This is the help message.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    <script type="text/javascript">
        
        // function handling menu toggle items
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }


        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();



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
                    console.log(response);
                    $('#bi_staff_email').val(response.data.email);
                    $('#bi_staff_fname').val(response.data.first_name);
                    $('#bi_staff_lname').val(response.data.last_name);
                    $('#bi_telephone').val(response.data.telephone);
                    $('#bi_staff_gender').val((response.data.gender != null) ? response.data.gender.toLowerCase() : '');

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
                        
                    }, error: function(data){
                        console.log(data);
                        swal("Error", "Oops an error occurred. Please try again.", "error");

                        $("#spinner-beneficiary-member").hide();
                        $("#btn-save-beneficiary-member-modal").attr('disabled', false);

                    }
                });
            });


        });
    </script>
@endpush