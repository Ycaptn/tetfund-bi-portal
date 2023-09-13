@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
New Intervention Submission
@stop

@section('page_title')
Intervention
@stop

@section('page_title_suffix')
New Submission
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.submissionRequests.index') }}">
    <i class="bx bx-chevron-left"></i> Back to Submissions
</a>
@stop

@section('page_title_buttons')
@stop

@section('content')
    <div class="card border-top border-0 border-4 border-success">

        <div class="card-body p-4">   
            <div class="card-title d-flex align-items-center">
                <div>
                    <i class="bx bx-highlight me-1 font-22 text-primary"></i>
                </div>
                <h5 class="mb-0 text-primary">Please provide details of your submission</h5>
            </div>
            <hr />
            {!! Form::open(['route' => 'tf-bi-portal.submissionRequests.store','class'=>'form-horizontal', 'onsubmit'=>'filter_removing_comma()' ]) !!}
            
                @include('pages.submission_requests.fields')
                <hr />

                <div class="col-lg-offset-3 col-lg-12">
                    <button class="btn btn-primary" id="btn_submit_request" type="submit">
                        <span class='fa fa-save'></span> Save New Request
                    </button>

                    <a href="{{ route('tf-bi-portal.submissionRequests.index') }}" class="btn btn-default btn-warning">
                        <span class="fa fa-times"></span> Cancel Submission
                    </a>
                </div>

                <div class="form-group mb-3" style="display: none;" id="div_current_nomination_details_submitted">
                    <br><hr>
                    <div class="col-lg-12 text-center"><b>ALL SUBMITTED <span id="nomination_type"></span> DETAILS </b></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="10%">
                                        S/N
                                    </th>
                                    <th width="35%">
                                        Nominees' Fullname
                                    </th>
                                    <th width="25%">
                                        Total Amount Requested
                                    </th>
                                    <th width="15%" id="nomination_table_label">
                                        Date
                                    </th>
                                    <th width="20%" class="text-center">
                                        Request Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="current_nomination_details_submitted">
                                <tr>
                                   <td colspan="5" class="text-danger text-center">
                                       <i>No record found!</i>year
                                   </td> 
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            {!! Form::close() !!}
            @include('pages.submission_requests.partials.modal_intervention_allocation_balance')
        </div>
    </div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">TETFund Submissions</h5></div>
        <p class="small">
            Process and track your submissions to TETFund from this window. You may begin new submissions by clicking the new submission button.
        </p>
    </div>
</div>
@stop

@push('page_scripts')
    <script type="text/javascript">

        // filter function to remove comma in amount before posting 
        function filter_removing_comma() {
            var numeric_amount = $('#amount_requested_digit').val().replace(/,/g,'');
            $("#amount_requested").val(numeric_amount);
            let intervention_year1 = $('#intervention_year1').val();
            $('.intervention_year1').val(intervention_year1);
            //console.log(numeric_amount);
        }

        $(document).ready(function() {
            const d = new Date();
            let year = d.getFullYear();
            let all_astd_interventions_id = [];
            let all_none_astd_interventions_id = [];
            let all_first_tranche_based_intervention_id = [];
            let all_intervention_records = '{!! json_encode($intervention_types) ?? [] !!}';
            let roles = JSON.parse('{!! json_encode(auth()->user()->getRoleNames()) ?? [] !!}');
            // console.log(roles);

            // hide 4 intervention years input fields
            $("#intervention_year2").val('');
            $("#intervention_year2").attr('disabled', true);
            $("#intervention_year3").val('');
            $("#intervention_year3").attr('disabled', true);
            $("#intervention_year4").val('');
            $("#intervention_year4").attr('disabled', true);
            $("#year_plural").hide();
            
            $('#amount_requested_digit').keyup(function(event){
                $('#amount_requested_digit').digits();
            });

            // hiding 3 intervention years
            function hide_3_intervention_years() {
                $(".intervention_year1").val(year);
                $("#intervention_year1").attr('disabled', true);
                $("#intervention_year2").val('');
                $("#intervention_year2").attr('disabled', true);
                $("#intervention_year3").val('');
                $("#intervention_year3").attr('disabled', true);
                $("#intervention_year4").val('');
                $("#intervention_year4").attr('disabled', true);
                $("#year_plural").hide();
            }

            // triggered on changing intervention type
            $('#intervention_type').on('change', function() {
                let selected_intevention_type = $(this).val();
                let intervention_line_html = "<option value=''>Select an Intervention Line</option>";
                if (selected_intevention_type != '' && all_intervention_records != null) {

                    all_astd_interventions_id.length = 0; /* resetting array to empty */
                    
                    @if($current_user->hasAnyRole(['BI-desk-officer','BI-head-of-institution']))
                        $.each(JSON.parse(all_intervention_records), function(key, intervention) {            
                            // appending intervention lines options
                            if (intervention.type == selected_intevention_type) {
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                            }

                            // setting all astd interventions into the array
                            if (intervention.name.includes('Teaching Practice') || intervention.name.includes('Conference Attendance') || intervention.name.includes('TETFund Scholarship')) {
                                all_astd_interventions_id[intervention.id] = intervention.name;
                            } else {
                                if(intervention.name.includes('Equipment Fabrication') || intervention.name.includes('Advocacy And Publicity') ||  intervention.name.includes('Academic Manuscript') ||  intervention.name.includes('Academic Research Journal')) {
                                    all_first_tranche_based_intervention_id[intervention.id] = intervention.name;
                                }

                                all_none_astd_interventions_id[intervention.id] = intervention.name;
                            }
                        });
                    @else
                        $.each(JSON.parse(all_intervention_records), function(key, intervention) {

                            // appending intervention lines options
                            let role_string = roles.join(',')
                            if(intervention.name == "ICT Support" && role_string.includes("BI-ict") && intervention.type == selected_intevention_type){
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                                all_none_astd_interventions_id[intervention.id] = intervention.name;
                              
                            } else if(intervention.name == "Library Development" && role_string.includes("BI-librarian") && intervention.type == selected_intevention_type){
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                                all_none_astd_interventions_id[intervention.id] = intervention.name;
                             
                            }else if(intervention.name == "Physical Infrastructure / Program Upgrade" && role_string.includes("BI-works") && intervention.type == selected_intevention_type ){
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                                all_none_astd_interventions_id[intervention.id] = intervention.name;
                              
                            }else if(role_string.includes("BI-astd-desk-officer") && (intervention.name.includes('Teaching Practice') || intervention.name.includes('Conference Attendance') || intervention.name.includes('TETFund Scholarship')) && intervention.type == selected_intevention_type){
                                intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                                all_astd_interventions_id[intervention.id] = intervention.name;
                               
                            }
                            
                        });
                
                     @endif
                }

                $('#intervention_line').html(intervention_line_html);
            });

            function getMonthDifference(startDate, endDate) {
                let diff = (endDate.getTime() - startDate.getTime());
              
                return (
                    diff / (1000 *3600 * 24 * 30)
                );
            }

            // triggered on changing intervention line
            $('#intervention_line').on('change', function() {
                let selected_intervention_line = $(this).val();
                $('#div_current_nomination_details_submitted').hide();
                
                if (selected_intervention_line != '' && selected_intervention_line in all_astd_interventions_id) {
                    $('#btn-get-intervention-allocation-details').hide();
                    let intevention_line_name = all_astd_interventions_id[selected_intervention_line];
                    let line_type_short = '';
                    let nomination_label = '';
                    let relationship_name = 'user';
                    let nomination_table_label = '';
                    if (intevention_line_name.includes('Teaching Practice')) {
                        line_type_short = 'tp';
                        relationship_name = 'tp_submission';
                        nomination_label = 'TP NOMINATION';
                        nomination_table_label = 'Program Date';
                        hide_3_intervention_years();
                    } else if(intevention_line_name.includes('Conference Attendance')) {
                        line_type_short = 'ca';
                        relationship_name = 'ca_submission';
                        nomination_label = 'CA NOMINATION';
                        nomination_table_label = 'Conference Date';
                        hide_3_intervention_years();
                    } else if (intevention_line_name.includes('TETFund Scholarship')) {
                        line_type_short = 'tsas';
                        relationship_name = 'tsas_submission';
                        nomination_label = 'TSAS NOMINATION';
                        nomination_table_label = 'Program Date';
                        hide_3_intervention_years();
                    }

                    if (line_type_short != '') {
                        let html_to_return = '';
                        $.get( "{{ route('tf-bi-portal-api.submission_requests.get_all_related_nomination_request','') }}/"+line_type_short).done(function( response ) {
                           
                            if (response.data) {
                                let s_n_counter = 1;
                                let requested_amount = 0.00;
                                let today = new Date();
                               
                                $.each(response.data, function(key, nominee) {
                                    console.log(nominee);
                                  
                                    let start_date = new Date(nominee[relationship_name]['program_start_date']);

                                    if(relationship_name == 'ca_submission') {
                                        start_date = new Date(nominee[relationship_name]['conference_start_date']);
                                    }
                                 
                                    let additionl_msg = '';
                                    let month_diff = getMonthDifference(today, start_date);

                                    if(month_diff < 3 && relationship_name == "ca_submission"){
                                        additionl_msg = "<br><span class='text-danger'> submission grace period passed <span>";
                                    }

                                    let formated_date = new Date(nominee[relationship_name]['updated_at']).toDateString();

                                    let middle_name = (nominee[relationship_name]['middle_name']) ? nominee[relationship_name]['middle_name'] : '';

                                    let total_request_amount = nominee[relationship_name]['total_requested_amount'] ? nominee[relationship_name]['total_requested_amount'] : "0.00";

                                    let formated_total_request_amount = parseFloat(total_request_amount).toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                
                                    html_to_return += `"<tr><td> ${s_n_counter} </td><td> ${nominee[relationship_name]['first_name']} ${middle_name}  ${nominee[relationship_name]['last_name']} </td>  <td> &#8358 ${formated_total_request_amount} </td> <td> ${start_date.toDateString()} ${additionl_msg} </td>  <td> ${formated_date} </td>  </tr>"`;

                                    s_n_counter += 1;
                                    if(relationship_name == "ca_submission"){
                                        if(month_diff >= 3){
                                            requested_amount += parseFloat(total_request_amount);
                                        }
                                        
                                    }else{
                                        requested_amount += parseFloat(total_request_amount);
                                    }
                                    

                                }); 
                                
                                let formated_requested_total = requested_amount.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });

                                html_to_return += "<tr><td colspan='2'><b> &nbsp; &nbsp; &nbsp; Grand Total</b></td>  <td colspan='2'><b> &#8358 "+ formated_requested_total +"</b></td></tr>";

                                $('#nomination_type').text(nomination_label);
                                $('#nomination_table_label').text(nomination_table_label);
                                $('#div_current_nomination_details_submitted').show();
                                $('#current_nomination_details_submitted').html(html_to_return);
                                $('#amount_requested').val(requested_amount);
                                $('#amount_requested_digit').val(formated_requested_total);
                            }
                        });
                    }
                } else if (selected_intervention_line != '' && selected_intervention_line in all_first_tranche_based_intervention_id) {
                    hide_3_intervention_years();
                    $('#btn-get-intervention-allocation-details').show();
                } else {
                    $('#btn-get-intervention-allocation-details').hide();
                    $('#amount_requested').val('');
                    $('#amount_requested_digit').val('');
                    $("#intervention_year1").val('');
                    $("#intervention_year1").attr('disabled', false);
                    $("#intervention_year2").attr('disabled', false);
                    $("#intervention_year3").attr('disabled', false);
                    $("#intervention_year4").attr('disabled', false);
                    $("#year_plural").show();
                }

                // settings to formulate intervention_name
                let confirmed_selected_line = all_none_astd_interventions_id[selected_intervention_line]!=null ?
                        all_none_astd_interventions_id[selected_intervention_line] : 
                        all_astd_interventions_id[selected_intervention_line];
              
                $('#intervention_name').val(confirmed_selected_line);
            });

        });
    </script>
@endpush