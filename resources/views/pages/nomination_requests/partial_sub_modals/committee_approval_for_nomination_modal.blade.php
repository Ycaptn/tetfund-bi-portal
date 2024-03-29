<div class="modal fade" id="committee_approval_for_nomination_modal" tabindex="-1" role="dialog" aria-labelledby="creator-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-100vh">
        <div class="modal-content modal-content-95vh">
            <div class="modal-header">
                <h4 class="modal-title" id="creator-modal-label"> Committee Member Nomination Recommendation <span id="title_toggle"></span></h4>
                <button id="creator-modal-close-button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                    <div class="col-lg-9" id='nomination_request_details'>
                        <div id="user_info_section" class="form-group row col-sm-12">
                            @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.partials_request_nomination.preview_all_fields')
                            <hr>
                            <div class="col-sm-12">
                                <div class="col-sm-12 text-center" style="border-bottom: 1px solid lightgray;">
                                    <strong>ATTACHMENTS</strong>
                                </div>
                                <div id="nomination_request_attachments" class="row col-sm-12"></div>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-3">
                        <form id="form_committee_approval_for_nomination_modal" class="form-horizontal" role="form" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" id="nomination_request_id" value="0">
                            <input type="hidden" id="nomination_type" value="">
                            
                            <div class="offline-flag"><span class="offline-nomination_request">You are currently offline</span></div>

                            <div class="col-sm-12 mb-3 mt-3 text-justify" id="date_details_submitted"></div>
                            
                            <hr>

                            <div id="div_committee_approval_for_nomination_modal_error" class="alert alert-danger" role="alert"></div>

                            <div id="decision_fields">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label"><strong>DECISION</strong></label>
                                    <div class="col-xs-9">
                                        <div class="checkbox">
                                            &nbsp; <input id='committee_member_decision_1' name='committee_member_decision' type="radio" value="approved" />
                                             &nbsp; <label class="form-label" for="committee_member_decision_1">Recommended</label> <br/>

                                            &nbsp; <input id='committee_member_decision_2' name='committee_member_decision' type="radio" value="declined" /> 
                                            &nbsp; <label class="form-label" for="committee_member_decision_2">Not Recommended</label> <br/>
                                        </div>
                                    </div>
                                </div><hr>

                                <div class="form-group">
                                    <label class="col-xs-3 form-label" for="decision_notes"><strong>DECISION COMMENT</strong></label>
                                    <textarea id="decision_notes" class="form-control" rows="6"></textarea>
                                </div><br>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12">
                                <center>
                                    <button id="btn-committee_consideration" class="btn btn-sm btn-primary">
                                        <div id="spinner-committee_consideration" style="color: white; display:none;" class="spinner-committee_consideration">
                                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                                            </div>
                                            <span class="">Loading...</span><hr>
                                        </div>

                                        <i class="fa fa-paper-plane" style="opacity:80%"></i>
                                        Process & Save Decision
                                    </button>
                                    <button id="button-modal-close-preview" type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal" aria-label="Close">
                                        <div id="spinner-committee_consideration" style="color: white; display:none;" class="spinner-committee_consideration">
                                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                                            </div>
                                            <span class="">Loading...</span><hr>
                                        </div>
                                        Close Preview
                                    </button>
                                </center>    
                            </div>
                        </div>
                    </div>

                </div>

            </div>
          <div class="text-left col-xs-12 panel" >
             <br><br>
          </div>

      </div>
  </div>
</div>



@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $('.offline-nomination_request').hide();

            //Show Modal for Nomination preview details by Committee Members
            $(document).on('click', ".btn-committee-preview-modal", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div_committee_approval_for_nomination_modal_error').hide();
                $('#title_toggle').text('Preview');
                $('#committee_approval_for_nomination_modal').modal('show');
                $('#form_committee_approval_for_nomination_modal').trigger("reset");

                $(".spinner-committee_consideration").show();
                $("#button-modal-close-preview").attr('disabled', true);

                $("#decision_fields").hide();
                $("#btn-committee_consideration").hide();
                $("#button-modal-close-preview").show();

                let itemId = $(this).attr('data-val');
                $('#nomination_request_id').val(itemId);
            
                $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {
                                        
                    let nomination_type_lower_case = response.data.nomination_request_type.toLowerCase();
                    if (nomination_type_lower_case == 'tp') {
                        $('#preview_tp_details').show();
                        $('#preview_ca_details').hide();
                        $('#preview_tsas_details').hide();
                    } else if (nomination_type_lower_case == 'ca') {
                        $('#preview_ca_details').show();
                        $('#preview_tp_details').hide();
                        $('#preview_tsas_details').hide();
                    } else if (nomination_type_lower_case == 'tsas') {
                        $('#preview_tsas_details').show();
                        $('#preview_ca_details').hide();
                        $('#preview_tp_details').hide();
                    } else {
                        $('#preview_tp_details').hide();
                        $('#preview_ca_details').hide();
                        $('#preview_tsas_details').hide();
                    }
                    
                    let middle_name = (response.data.nominee.middle_name) ? response.data.nominee.middle_name : '';
                    
                    @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.partials_request_nomination.js_preview_all_fields')
                    
                    $('#nomination_type').val(response.data.nomination_request_type);
                    
                    // attachments
                    let = attachments_html = '';
                    $.each(response.data.attachments, function(key, attachment){
                        link = window.location.origin +'/attachment/'+attachment.id;
                        attachments_html += "<div class='col-sm-4'><small><a href='"+ link +"' target='__blank'>"+ attachment.label +"</a><br><i>"+ attachment.description +"</i></small></div>";
                    });
                    $('#nomination_request_attachments').html(attachments_html);
                    $('#date_details_submitted').text('This ' + response.data.nomination_request_type.toUpperCase() + ' Nomination Request Details was submitted on ' + new Date(response.data.nominee.created_at).toDateString());

                    $(".spinner-committee_consideration").hide();
                    $("#button-modal-close-preview").attr('disabled', false);
               });
            });


            //Show Modal for Nomination preview and approval decision by Committee Members
            $(document).on('click', ".btn-committee-vote-modal", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div_committee_approval_for_nomination_modal_error').hide();
                $('#title_toggle').text('Zone');
                $('#committee_approval_for_nomination_modal').modal('show');
                $('#form_committee_approval_for_nomination_modal').trigger("reset");

                $(".spinner-committee_consideration").show();
                $("#btn-committee_consideration").attr('disabled', true);

                $("#button-modal-close-preview").hide();
                $("#decision_fields").show();
                $("#btn-committee_consideration").show();

                let itemId = $(this).attr('data-val');
                $('#nomination_request_id').val(itemId);
            
                $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {
                                        
                    let nomination_type_lower_case = response.data.nomination_request_type.toLowerCase();
                    if (nomination_type_lower_case == 'tp') {
                        $('#preview_tp_details').show();
                        $('#preview_ca_details').hide();
                        $('#preview_tsas_details').hide();
                    } else if (nomination_type_lower_case == 'ca') {
                        $('#preview_ca_details').show();
                        $('#preview_tp_details').hide();
                        $('#preview_tsas_details').hide();
                    } else if (nomination_type_lower_case == 'tsas') {
                        $('#preview_tsas_details').show();
                        $('#preview_ca_details').hide();
                        $('#preview_tp_details').hide();
                    } else {
                        $('#preview_tp_details').hide();
                        $('#preview_ca_details').hide();
                        $('#preview_tsas_details').hide();
                    }
                    
                    let middle_name = (response.data.nominee.middle_name) ? response.data.nominee.middle_name : '';
                    
                    @include('tf-bi-portal::pages.nomination_requests.partial_sub_modals.partials_request_nomination.js_preview_all_fields')
                    
                    $('#nomination_type').val(response.data.nomination_request_type);

                    // attachments
                    let = attachments_html = '';
                    $.each(response.data.attachments, function(key, attachment){
                        link = window.location.origin +'/attachment/'+attachment.id;
                        attachments_html += "<div class='col-sm-4'><small><a href='"+ link +"' target='__blank'>"+ attachment.label +"</a><br><i>"+ attachment.description +"</i></small></div>";
                    });
                    $('#nomination_request_attachments').html(attachments_html);
                    $('#date_details_submitted').text('This ' + response.data.nomination_request_type.toUpperCase() + ' Nomination Request Details was submitted on ' + new Date(response.data.nominee.created_at).toDateString());

                    $(".spinner-committee_consideration").hide();
                    $("#btn-committee_consideration").attr('disabled', false);
               });
            });


            //process committee member nomination consideraion decision
            $(document).on('click', "#btn-committee_consideration", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-nomination_request').fadeIn(300);
                    return;
                }else{
                    $('.offline-nomination_request').fadeOut(300);
                }

                let itemId = $('#nomination_request_id').val();
                let itemType = $('#nomination_type').val().toUpperCase()+'Nomination';

                swal({
                    title: "Are you sure you want to save your decision?",
                    text: "You will not be able to revert this decision once completed.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes decide",
                    cancelButtonText: "No don't decide",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        $(".spinner-committee_consideration").show();
                        $("#btn-committee_consideration").attr('disabled', true);

                        let endPointUrl = "{{ route('tf-bi-portal-api.nomination_requests.process_committee_member_vote','') }}/"+itemId;
                        
                        let formData = new FormData();
                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('_method', 'POST');
                        formData.append('id', itemId);
                        formData.append('decision', $('input[name=committee_member_decision]:checked').val());
                        formData.append('comment', $('#decision_notes').val());
                        
                        $.ajax({
                            url:endPointUrl,
                            type: "POST",
                            data: formData,
                            cache: false,
                            processData:false,
                            contentType: false,
                            dataType: 'json',
                            success: function(result){                                
                                if(result.errors || (result.status && result.status == 'fail' && result.response)) {
                                    $('#div_committee_approval_for_nomination_modal_error').html('');
                                    $('#div_committee_approval_for_nomination_modal_error').show();
                                    
                                    let response_arr = (result.errors) ? result.errors : result.response;
                                    $.each(response_arr, function(key, value){
                                        $('#div_committee_approval_for_nomination_modal_error').append('<li class="">'+value+'</li>');
                                    });

                                    $(".spinner-committee_consideration").hide();
                                    $("#btn-committee_consideration").attr('disabled', false);
                                }else{
                                    swal({
                                        title: "Processed",
                                        text: "Decision for " + itemType + " request saved successfully",
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    });
                                    location.reload(true);
                                }
                            }, error: function(data){
                                console.log(data);
                                swal("Error", "Oops an error occurred. Please try again.", "error");

                                $(".spinner-committee_consideration").hide();
                                $("#btn-committee_consideration").attr('disabled', false);

                            }
                        });
                    }
                });

            });

        });
    </script>
@endpush
