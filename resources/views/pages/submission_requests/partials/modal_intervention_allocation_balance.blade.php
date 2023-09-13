<div class="modal fade" id="mdl-interventionAllcoationDetails-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-interventionAllcoationDetails-modal-title" class="modal-title"><span id="mdl_intervention_name"></span> Allocation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" id="frm-interventionAllcoationDetails-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row m-3">
                        @csrf
                        <div class="offline-flag col-sm-12 text-danger text-center">
                            <b class="offline-intervention_allocation_details">You are currently offline</b>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="table-responsive" id="div_intervention_allocation_details" style="display:none;">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width:35%"><b>Total Allocated Amount</b></th>
                                        <td style="width:65%">
                                            ₦ <span id="total_allocated_amount"></span> &nbsp; 
                                            (<span id="allocation_merged_years"></span>)
                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="width:35%"><b>Total Available Balance</b></th>
                                        <td style="width:65%">
                                            ₦ <span id="total_available_balance"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm pull-right" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-intervention_allocation_details').hide();

    // trigger on clicking btn-get-intervention-allocation-details
    $(document).on('click', "#btn-get-intervention-allocation-details", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
        
        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-intervention_allocation_details').fadeIn(300);
            return;
        }else{
            $('.offline-intervention_allocation_details').fadeOut(300);
        }

        $('#frm-interventionAllcoationDetails-modal').trigger("reset");

        swal({
            title: '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden"></span> </div> <br><br>Fetching...',
            text: 'Please wait while Allocation balance is being fetched <br><br> Do not refresh this page! ',
            showConfirmButton: false,
            allowOutsideClick: false,
            html: true
        });

        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());
        formData.append('_method', 'POST');
        formData.append('intervention_name', $('#intervention_name').val());
        formData.append('tf_intervention_id', $('#intervention_line').val());
        formData.append('intervention_year1', $('#intervention_year1').val());
        formData.append('intervention_year2', $('#intervention_year2').val());
        formData.append('intervention_year3', $('#intervention_year3').val());
        formData.append('intervention_year4', $('#intervention_year4').val());
        formData.append('tf_beneficiary_id', '{{optional($beneficiary)->tf_iterum_portal_key_id}}');
        
        $.ajax({
            url: "{{ route('tf-bi-portal-api.get_bi_intervention_allocated_funds_data') }}",
            type: "POST",
            data: formData,
            cache: false,
            processData:false,
            contentType: false,
            dataType: 'json',
            success: function(response){                                
                if(response.data) {
                    $('#mdl-interventionAllcoationDetails-modal').modal('show');
                    $('#div_intervention_allocation_details').show('slow');
                    $('#mdl_intervention_name').text($('#intervention_name').val());
                    let total_allocated_fund_formated = response.data.total_allocated_fund.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    let total_available_fund_formated = response.data.total_available_fund.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    $('#total_allocated_amount').text(total_allocated_fund_formated);
                    $('#total_available_balance').text(total_available_fund_formated);
                    $('#allocation_merged_years').text(response.data.allocation_end_year+ ' - ' +response.data.allocation_start_year);
                    swal.close();
                }
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");
            }
        });
    });
   

});
</script>
@endpush
