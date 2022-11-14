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
        class="btn btn-sm btn-primary btn-new-mdl-beneficiary-modal" href="#">
        <i class="fa fa-eye"></i> New
    </a>

    <a data-toggle="tooltip" 
        title="Edit" 
        data-val='{{$beneficiary->id}}' 
        class="btn btn-sm btn-primary btn-edit-mdl-beneficiary-modal" href="#">
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
                            <a href="#?members=members" class="tablinks btn btn-primary btn-md shadow-none" onclick="openCity(event,'members')" id="defaultOpen">
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

                {{-- sub menu contents --}}
                <div id="members" class="tabcontent">
                    <h5 class="pt-2"> 
                        <strong>
                            Beneficiary Members 
                        </strong>
                         <a title="Create New Beneficiary Member" class="btn btn-primary btn-sm pull-right btn-new-beneficiary-member" href="#">
                            <span class="fa fa-plus"></span> <small>New Member</small>
                        </a>
                    </h5>
                    @include('tf-bi-portal::pages.beneficiaries.partials.beneficiary_members') 
                </div>

                <div id="submissions" class="tabcontent">
                    <h5 class="pt-2"> 
                        <strong>
                            Submissions Requests
                        </strong>
                    </h5>
                    {{-- include view path --}}
                </div>

                <div id="nominations" class="tabcontent">
                    <h5 class="pt-2"> 
                        <strong>
                            Nominations 
                        </strong>
                    </h5>
                    {{-- include view path --}}                    
                </div>   
                <div class="">
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
                $('#div-beneficiary-member-modal-error').hide();
                $('#mdl-beneficiary-member-modal').modal('show');
                $('#form-beneficiary-member-modal').trigger("reset");
                $('#txt-beneficiary-member-primary-id').val(0);

                $("#spinner-beneficiary-member").hide();
                $("#btn-new-beneficiary-member").attr('disabled', false);
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
                    endPointUrl = "{{ route('tf-bi-portal-api.beneficiaries.update','') }}/"+primaryId;
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