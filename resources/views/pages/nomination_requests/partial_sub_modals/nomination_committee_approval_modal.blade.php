
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
                                    <tbody class="committee_table_body">
                                        
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        
            <div class="modal-footer" id="div-save-mdl-nomination_request_vote-modal">
                <button type="button" class="btn btn-sm btn-primary" id="btn-save-mdl-nomination_request_vote-modal" value="add">
                <div id="spinner-nomination_request_vote" style="color: white;">
                    <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                    </div>
                    <span class="">Loading...</span><hr>
                </div>
                <span class="fa fa-vote-yea"></span> Vote for approval
                </button>

                @if(auth()->user()->hasAnyRole(['bi-astd-commitee-head', 'bi-tp-commitee-head', 'bi-ca-commitee-head', 'bi-tsas-commitee-head']))
                    <button type="button" class="btn btn-sm btn-primary" id="btn-save-mdl-nomination_request_push-modal" value="add" style="display: none;">
                    <div id="spinner-nomination_request_push" style="color: white; display: none;">
                        <div class="spinner-border" style="width: 1rem; height: 1rem;" role="status">
                        </div>
                        <span class="">Loading...</span><hr>
                    </div>
                    <span class="fa fa-send"></span> Push approval to Desk Officer
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

            // show nomination committee voting modal
            $(document).on('click', ".btn-committee-vote-modal", function(e) {
                e.preventDefault();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

                //check for internet status 
                if (!window.navigator.onLine) {
                    $('.offline-nomination_request_vote').fadeIn(300);
                    return;
                }else{
                    $('.offline-nomination_request_vote').fadeOut(300);
                }

                $('#div-nomination_request_vote-modal-error').hide();
                $('#form-nomination_request_vote-modal').trigger("reset");
                $("#spinner-nomination_request_vote").show();
                $("#spinner-nomination_request_push").show();

                let itemId = $(this).attr('data-val');
                $('#txt-nomination_request_vote-primary-id').val(itemId);
                $('#mdl-nomination_request_vote-modal').modal('show');

                $("#btn-save-mdl-nomination_request_vote-modal").attr('disabled', true);
                $("#btn-save-mdl-nomination_request_push-modal").attr('disabled', true);

                $.get( "{{ route('tf-bi-portal-api.nomination_requests.show','') }}/"+itemId).done(function( response ) {

                    let table_body = '';
                    let count_commitee_voter = response.data.count_committee_votes;
                    let count_commitee_members = response.data.count_committee_members;

                    $('#committee_type').text(response.data.nomination_request_type.toUpperCase() + 'Nomination');
                    $('#committee_ratio').text('(RATIO: ' + count_commitee_voter + ' of ' + count_commitee_members +  ' ' + response.data.nomination_request_type.toUpperCase() + 'Nomination Committee Member)');
                    
                    console.log(response.data);

                    $("#spinner-nomination_request_vote").hide();
                    $("#spinner-nomination_request_push").hide();
                    $("#btn-save-mdl-nomination_request_vote-modal").attr('disabled', false);
                    $("#btn-save-mdl-nomination_request_push-modal").attr('disabled', false);
                });

            });
        });

    </script>
@endpush
