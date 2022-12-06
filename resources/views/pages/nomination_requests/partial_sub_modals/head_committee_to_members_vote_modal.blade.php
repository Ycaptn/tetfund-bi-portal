<div class="modal fade" id="head_committee_to_members_vote_modal" tabindex="-1" role="dialog" aria-labelledby="creator-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-100vh">
        <div class="modal-content modal-content-95vh">
            <div class="modal-header">
                <h4 class="modal-title" id="creator-modal-label"> Preview Committee Members Nomination Consideration </h4>
                <button id="creator-modal-close-button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12 row">
                    
                    <div class="col-lg-9">
                        <div class="row m-3">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="6">
                                                    <h6>Total Consideration(s) By <span id="committee_type"></span> Commitee Members <br> <b><span id="committee_ratio"></span></b> </h6>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Member Name</th>
                                                <th>Decision</th>
                                                <th>Comment</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>

                                        <tbody id="committee_table_body" class="committee_table_body"></tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <form id="form_head_committee_to_members_vote_modal" class="form-horizontal" role="form" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" id="nomination_request_id" value="0">
                            <input type="hidden" id="nomination_type" value="">
                            
                            <div class="col-sm-12 mb-3 mt-3 text-justify" id="date_details_submitted"></div>
                            
                            <hr>

                            <div id="div_head_committee_to_members_vote_modal_error" class="alert alert-danger" role="alert"></div>

                            <div id="">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label"><strong>COMMITTEE HEAD FINAL DECISION</strong></label>
                                    <div class="col-xs-9">
                                        <div class="checkbox">
                                            &nbsp; <input id='committee_head_1' name='committee_head' type="radio" value="approved" />
                                             &nbsp; <label class="form-label" for="committee_head_1">Considered</label> <br/>

                                            &nbsp; <input id='committee_head_2' name='committee_head' type="radio" value="declined" /> 
                                            &nbsp; <label class="form-label" for="committee_head_2">Not Considered</label> <br/>
                                        </div>
                                    </div>
                                </div><hr>

                                <div class="form-group">
                                    <label class="col-xs-3 form-label" for="approval_notes"><strong>COMMENT</strong></label>
                                    <textarea id="approval_notes" class="form-control" rows="6"></textarea>
                                </div><br>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12">
                                <center>
                                    <button id="btn-committee-head-final" class="btn btn-sm btn-primary">
                                        <div id="spinner-committee-head-final" style="color: white; display:none;" class="spinner-committee-head-final">
                                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                                            </div>
                                            <span class="">Loading...</span><hr>
                                        </div>

                                        <i class="fa fa-check-square" style="opacity:80%"></i>
                                        Process & Save Final Decision
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

            //Show Modal for Nomination votes committee head
            $(document).on('click', ".btn-committee-head-modal", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div_head_committee_to_members_vote_modal_error').hide();
                $('#head_committee_to_members_vote_modal').modal('show');
                $('#form_head_committee_to_members_vote_modal').trigger("reset");

                $("#spinner-committee-head-final").show();
                $("#btn-committee-head-final").attr('disabled', true);

                let itemId = $(this).attr('data-val');
                $('#nomination_request_id').val(itemId);
                
                $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {
                    
                    let table_body = '';
                    let count_commitee_voter = response.data.count_committee_votes;
                    let count_commitee_members = response.data.count_committee_members;

                    $('#committee_type').text(response.data.nomination_request_type.toUpperCase() + 'Nomination');
                    $('#committee_ratio').text('(RATIO: ' + count_commitee_voter + ' of ' + count_commitee_members +  ' ' + response.data.nomination_request_type.toUpperCase() + 'Nomination Committee Member)');
                    
                    console.log(response.data);

                    if (response.data.nomination_committee_voters) {
                        let counter = 1;
                        $.each(response.data.nomination_committee_voters, function(key, value){
                            var serverDate = new Date(value.created_at).toDateString();
                            let status = (value.approval_status == 1) ? 'Considered' : 'Not Considered';
                            table_body += "<tr> <td>"+ counter +"</td> <td>"+value.first_name+' '+value.last_name+"</td> <td>"+status+"</td> <td>"+value.approval_comment+"</td> <td>"+serverDate+"</td> </tr>";
                            counter += 1;
                        });
                    }

                    $("#committee_table_body").html(table_body);

                    $('#nomination_type').val(response.data.nomination_request_type);
                    
                    // attachments
                    let = attachments_html = '';
                    $.each(response.data.attachments, function(key, attachment){
                        link = window.location.origin +'/tf-bi-portal/preview-attachement/'+attachment.id;
                        attachments_html += "<div class='col-sm-4'><small><a href='"+ link +"' target='__blank'>"+ attachment.label +"</a><br><i>"+ attachment.description +"</i></small></div>";
                    });

                    $('#nomination_request_attachments').html(attachments_html);
                    $('#date_details_submitted').text('This ' + response.data.nomination_request_type.toUpperCase() + ' Nomination Request Details was submitted on ' + new Date(response.data.nominee.created_at).toDateString());

                    $("#spinner-committee-head-final").hide();
                    $("#btn-committee-head-final").attr('disabled', false);
               });
            });

            //process head committee final decision
            $(document).on('click', "#btn-committee-head-final", function(e) {
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
                    title: "Are you sure you want to save final decision in respect to this " + itemType + " request?",
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
                        $(".spinner-committee-head-final").show();
                        $("#btn-committee-head-final").attr('disabled', true);

                        let endPointUrl = "{{ route('tf-bi-portal-api.nomination_requests.process_committee_head_consideration','') }}/"+itemId;
                        
                        let formData = new FormData();
                        formData.append('_token', $('input[name="_token"]').val());
                        formData.append('_method', 'POST');
                        formData.append('id', itemId);
                        formData.append('decision', $('input[name=committee_head]:checked').val());
                        formData.append('comment', $('#approval_notes').val());
                        
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
                                    $('#div_head_committee_to_members_vote_modal_error').html('');
                                    $('#div_head_committee_to_members_vote_modal_error').show();
                                    
                                    let response_arr = (result.errors) ? result.errors : result.response;
                                    $.each(response_arr, function(key, value){
                                        $('#div_head_committee_to_members_vote_modal_error').append('<li class="">'+value+'</li>');
                                    });

                                    $(".spinner-committee-head-final").hide();
                                    $("#btn-committee-head-final").attr('disabled', false);
                                }else{
                                    swal({
                                        title: "Processed",
                                        text: "Consideration for " + itemType + " request saved successfully",
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

                                $(".spinner-committee-head-final").hide();
                                $("#btn-committee-head-final").attr('disabled', false);

                            }
                        });
                    }
                });

            });

        });
    </script>
@endpush
