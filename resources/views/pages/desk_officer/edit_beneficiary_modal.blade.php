<div class="modal fade" id="mdl-edit_beneficiary-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-edit_beneficiary-modal-title" class="modal-title">
                    <span class="fa fa-school"></span> &nbsp;
                    MODIFY BENEFICIARY INSTITUTION DETAILS
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-edit_beneficiary-modal-error" class="alert alert-danger" role="alert"></div>
            
                <form class="form-horizontal" id="frm-edit_beneficiary-modal" role="form" method="POST" action="">
                    <div class="row m-2">
                        <div class="col-sm-12">
                            @csrf
                            <div class="offline-flag">
                                <span class="offline-request-for-monitoring-evaluation">
                                    You are currently offline
                                </span>
                            </div>

                            <div class="col-sm-12">
                                <div class="row">
                                    @include('tf-bi-portal::pages.beneficiaries.fields')
                                </div>                             
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        
            <div class="modal-footer" id="div-save-mdl-edit_beneficiary-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-edit_beneficiary">
                    <span class="fa fa-edit"></span> Update Beneficiary Details
                </button>
            </div>

        </div>
    </div>
</div>


@push('page_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.offline-request-for-monitoring-evaluation').hide();

        //Show Modal for Request of related tranche
        $(document).on('click', "#btn-edit-beneficiary", function(e) {
            $('#div-edit_beneficiary-modal-error').hide();
            $('#mdl-edit_beneficiary-modal').modal('show');

            $("#btn-save-mdl-edit_beneficiary").attr('disabled', false);
        });

        //Save beneficiary member details
        $('#btn-save-mdl-edit_beneficiary').click(function(e) {
            e.preventDefault();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            //check for internet status 
            if (!window.navigator.onLine) {
                $('.offline-request-for-monitoring-evaluation').fadeIn(300);
                return;
            }else{
                $('.offline-request-for-monitoring-evaluation').fadeOut(300);
            }

            swal({
                title: "Are you sure you want update details of beneficiary institution?",
                text: "Your newly provided data will be updated immediately.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, update",
                cancelButtonText: "No, don't update",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: '<div id="spinner-beneficiary-member" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Loading...  </span> </div> <br><br> Please wait...',
                        text: "Currently Updating Beneficiary Data! <br><br> Do not refresh this page! ",
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    });

                    $("#btn-save-mdl-edit_beneficiary").attr('disabled', true);

                    let formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('id', '{{ $beneficiary->id }}');
                    
                    @if (isset($organization) && $organization!=null)
                        formData.append('organization_id', '{{$organization->id}}');
                    @endif

                    if ($('#bi_type').length){ formData.append('type', $('#bi_type').val()); }
                    if ($('#bi_email').length){ formData.append('email', $('#bi_email').val()); }
                    if ($('#bi_official_email').length){ formData.append('official_email', $('#bi_official_email').val()); }
                    if ($('#bi_short_name').length){ formData.append('short_name', $('#bi_short_name').val()); }
                    if ($('#bi_official_website').length){ formData.append('official_website', $('#bi_official_website').val()); }
                    if ($('#bi_official_phone').length){ formData.append('official_phone', $('#bi_official_phone').val()); }
                    if ($('#bi_address_street').length){ formData.append('address_street', $('#bi_address_street').val()); }
                    if ($('#bi_address_town').length){ formData.append('address_town', $('#bi_address_town').val()); }
                    if ($('#bi_address_state').length){ formData.append('address_state', $('#bi_address_state').val()); }
                    if ($('#bi_head_of_institution_title').length){ formData.append('head_of_institution_title', $('#bi_head_of_institution_title').val()); }
                    if ($('#bi_geo_zone').length){ formData.append('geo_zone',$('#bi_geo_zone').val()); }
                    if ($('#bi_owner_agency_type').length){ formData.append('owner_agency_type',$('#bi_owner_agency_type').val()); }

                    $.ajax({
                        url: "{{ route('tf-bi-portal-api.beneficiaries.update', $beneficiary->id) }}",
                        type: "POST",
                        data: formData,
                        cache: false,
                        processData:false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result) {
                            if(result.errors) {
                                $('#div-edit_beneficiary-modal-error').html('');
                                $('#div-edit_beneficiary-modal-error').show();
                                
                                $.each(result.errors, function(key, value) {
                                    $('#div-edit_beneficiary-modal-error').append('<li class="">'+value+'</li>');
                                });
                                swal.close();
                            } else {
                                $('#div-edit_beneficiary-modal-error').hide();
                                swal({
                                    title: "Updated",
                                    text: result.message,
                                    type: "success"
                                });
                                location.reload(true);
                            }

                            $("#btn-save-mdl-edit_beneficiary").attr('disabled', false);
                            
                        }, error: function(data) {
                            console.log(data);
                            swal("Error", "Oops an error occurred. Please try again.", "error");

                            $("#btn-save-mdl-edit_beneficiary").attr('disabled', false);
                        }
                    });
                };
            });
        });
    });
</script>
@endpush
