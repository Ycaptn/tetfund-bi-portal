
<div class="modal fade" id="mdl-nomination_request_vote-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-nomination_request_vote-modal-title" class="modal-title">Committee Nomination Approval Zone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-nomination_request_vote-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="form-nomination_request_vote-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row m-3">
                        <div class="col-sm-12">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-nomination_request_vote">You are currently offline</span></div>

                            <input type="hidden" id="txt-nomination_request_vote-primary-id" value="0" />
                            <input type="hidden" id="txt-nomination_request_push-primary-id" value="0" />
                            <div id="div-show-txt-nomination_request_vote-primary-id" class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th colspan="4">
                                                <h6>Total Approval By <span id="committee_type"></span> Commitee Members <br> <b><span id="committee_ratio"></span></b> </h6>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Member Name</th>
                                            <th>Member Email</th>
                                            <th>Approval Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="committee_table_body">
                                        
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        
            <div class="modal-footer" id="div-save-mdl-nomination_request_vote-modal">
                <button type="button" class="btn btn-sm btn-primary" id="btn-save-nomination_request_vote" value="add">
                    <div id="spinner-nomination_request_vote" style="color: white;">
                        <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                        </div>
                        <span class="">Loading...</span><hr>
                    </div>
                    <span class="fa fa-vote-yea"></span> Vote for approval
                </button>

                @if(auth()->user()->hasAnyRole(['bi-astd-commitee-head', 'bi-tp-commitee-head', 'bi-ca-commitee-head', 'bi-tsas-commitee-head']))
                    <button type="button" class="btn btn-sm btn-primary" id="btn-save-nomination_request_push" value="add" style="display: none;">
                        <div id="spinner-nomination_request_push" style="color: white; display: none;">
                            <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                            </div>
                            <span class="">Loading...</span><hr>
                        </div>
                        <span class="fa fa-send"></span> Push nomination to Desk Officer
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>


@push('page_scripts')
    <script type="text/javascript">
        
        $(document).ready(function() {
            $('.offline-nomination_request_vote').hide();

            //process pushing nomination back to Desk officer dashboard
            @if(auth()->user()->hasAnyRole(['bi-astd-commitee-head', 'bi-tp-commitee-head', 'bi-ca-commitee-head', 'bi-tsas-commitee-head']))
                $(document).on('click', "#btn-save-nomination_request_push", function(e) {
                    e.preventDefault();
                    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                    //check for internet status 
                    if (!window.navigator.onLine) {
                        $('.offline-nomination_request_vote').fadeIn(300);
                        return;
                    }else{
                        $('.offline-nomination_request_vote').fadeOut(300);
                    }

                    swal({
                        title: "You are about to push this Nomination Request back to Desk Officer!",
                        text: "Majority committee member have successfully voted approval for this Nomination request.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, push",
                        cancelButtonText: "No, don't push",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $("#spinner-nomination_request_push").show();
                            $("#btn-save-nomination_request_push").attr('disabled', true);

                            let itemName = $('#txt-nomination_request_push-primary-id').val();
                            let itemArr = itemName.split('$');

                            let formData = new FormData();
                            formData.append('_token', $('input[name="_token"]').val());                
                            formData.append('_method', 'PUT');
                            formData.append('id', itemArr[0]);
                            formData.append('column_to_update', itemArr[1]);
                            
                            $.ajax({
                                url: "{{ route('tf-bi-portal-api.nomination_requests.process_forward_details','') }}/"+itemArr[0],
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
                                            title: "Completed",
                                            text: "Nomination request forwarded to Desk Officer successfully",
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
            @endif


            //process approval vote button
            $(document).on('click', "#btn-save-nomination_request_vote", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-nomination_request_vote').fadeIn(300);
                    return;
                }else{
                    $('.offline-nomination_request_vote').fadeOut(300);
                }

                swal({
                    title: "Are you sure you want to vote Approval for this Nomination Request?",
                    text: "You will not be able to revert your approval status once completed.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Vote approval",
                    cancelButtonText: "Don't vote approval",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {

                        $("#spinner-nomination_request_vote").show();
                        $("#btn-save-nomination_request_vote").attr('disabled', true);

                        let primaryId = $('#txt-nomination_request_vote-primary-id').val();
                        
                        let formData = new FormData();
                        formData.append('_token', $('input[name="_token"]').val());                
                        formData.append('_method', 'POST');
                        formData.append('nomination_request_id', primaryId);

                        $.ajax({
                            url: "{{ route('tf-bi-portal-api.nomination_requests.process_approval_by_vote','') }}/"+primaryId,
                            type: "POST",
                            data: formData,
                            cache: false,
                            processData:false,
                            contentType: false,
                            dataType: 'json',
                            success: function(result){
                                if(result.errors || (result.status && result.status == 'fail' && result.response)) {
                                    $('#div-nomination_request_vote-modal-error').html('');
                                    $('#div-nomination_request_vote-modal-error').show();
                                    
                                    let response_arr = (result.errors) ? result.errors : result.response;
                                    $.each(response_arr, function(key, value){
                                        $('#div-nomination_request_vote-modal-error').append('<li class="">'+value+'</li>');
                                    });
                                }else{
                                    $('#div-nomination_request_vote-modal-error').hide();
                                    window.setTimeout( function(){

                                        $('#div-nomination_request_vote-modal-error').hide();

                                        swal({
                                            title: "Saved",
                                            text: result.message,
                                            type: "success"
                                        });
                                        location.reload(true);
                                    },20);
                                }

                                $("#spinner-nomination_request_vote").hide();
                                $("#btn-save-nomination_request_vote").attr('disabled', false);
                                
                            }, error: function(data){
                                console.log(data);
                                swal("Error", "Oops an error occurred. Please try again.", "error");

                                $("#spinner-nomination_request_vote").hide();
                                $("#btn-save-nomination_request_vote").attr('disabled', false);

                            }
                        });
                    }
                });
            });


            // show nomination committee voting modal
            $(document).on('click', ".btn-committee-vote-modal", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                $('#div-nomination_request_vote-modal-error').hide();
                $('#form-nomination_request_vote-modal').trigger("reset");
                $("#spinner-nomination_request_vote").show();
                $("#spinner-nomination_request_push").show();
                $("#div-save-mdl-nomination_request_vote-modal").hide();

                let itemId = $(this).attr('data-val');
                $('#txt-nomination_request_vote-primary-id').val(itemId);
                $('#mdl-nomination_request_vote-modal').modal('show');

                $("#btn-save-nomination_request_vote").attr('disabled', true);
                $("#btn-save-nomination_request_push").attr('disabled', true);

                $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {
                    console.log(response.data);

                    let table_body = '';
                    let count_commitee_voter = response.data.count_committee_votes;
                    let count_commitee_members = response.data.count_committee_members;

                    $('#committee_type').text(response.data.nomination_request_type.toUpperCase() + 'Nomination');
                    $('#committee_ratio').text('(RATIO: ' + count_commitee_voter + ' of ' + count_commitee_members +  ' ' + response.data.nomination_request_type.toUpperCase() + 'Nomination Committee Member)');

                    if (response.data.nomination_committee_voters) {
                        let counter = 1;
                        $.each(response.data.nomination_committee_voters, function(key, value){
                            var serverDate = new Date(value.created_at).toDateString();
                            table_body += "<tr> <td>"+ counter +"</td> <td>"+value.first_name+' '+value.last_name+"</td> <td>"+value.email+"</td> <td>"+serverDate+"</td> </tr>";
                            counter += 1;
                        });
                    }

                    $("#committee_table_body").html(table_body);

                    //show push approval button on committee head dashboard if average members vote for approval
                    if (count_commitee_voter > (count_commitee_members/2)) {
                        let push_nomination_primary_id = itemId+"$is_head_commitee_members_check";
                        $("#txt-nomination_request_push-primary-id").val(push_nomination_primary_id);
                        $("#btn-save-nomination_request_push").show();
                    }

                    //show modal footer if nomination request is stil with committee
                    if (response.data.is_head_commitee_members_check == 0 && response.data.is_desk_officer_check == 1) {
                        $("#div-save-mdl-nomination_request_vote-modal").show();
                    } 

                    $("#spinner-nomination_request_vote").hide();
                    $("#spinner-nomination_request_push").hide();
                    $("#btn-save-nomination_request_vote").attr('disabled', false);
                    $("#btn-save-nomination_request_push").attr('disabled', false);
                });

            });
        });

    </script>
@endpush
