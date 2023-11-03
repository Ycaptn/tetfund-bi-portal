@extends('layouts.app')

@section('app_css')
@stop

@section('title_postfix')
Edit Submission Request
@stop

@section('page_title')
Edit Submission Request
@stop

@section('page_title_suffix')
{{ $submissionRequest->getOriginal('title') }}
@stop

@section('page_title_subtext')
<a class="ms-1" href="{{ route('tf-bi-portal.submissionRequests.show', $submissionRequest->id) }}">
    <i class="bx bx-chevron-left"></i> Back to Submission Request Details
</a>
@stop

@section('page_title_buttons')
{{-- <a href="{{ route('tf-bi-portal.submissionRequests.create') }}" id="btn-new-submissionRequests" class="btn btn-sm btn-primary">
    <i class="bx bx-book-add mr-1"></i>New Submission Request
</a> --}}
@stop

@section('content')
<div class="card border-top border-0 border-4 border-success">

    <div class="card-body p-4">

        <div class="card-title d-flex align-items-center">
            <div>
                <i class="bx bxs-user me-1 font-22 text-primary"></i>
            </div>
            <h5 class="mb-0 text-primary">Modify Submission Request Details</h5>
        </div>

        {!! Form::model($submissionRequest, ['class'=>'form-horizontal', 'onsubmit'=>'filter_removing_comma()', 'route' => ['tf-bi-portal.submissionRequests.update', $submissionRequest->id], 'method' => 'patch']) !!}

            @include('tf-bi-portal::pages.submission_requests.fields')

            <div class="col-lg-offset-3 col-lg-9">
                <hr/>
                <button class="btn btn-primary" id="btn_submit_request" type="submit">
                    <span class='fa fa-save'></span> Update Request
                </button>
                
                <a href="{{ route('tf-bi-portal.submissionRequests.index') }}" class="btn btn-warning btn-default">
                    <span class="fa fa-times"></span> Cancel
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

    </div>
</div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-success">
    <div class="card-body">
        <div><h5 class="card-title">More Information</h5></div>
        <p class="small">
            Modify and track your submissions to TETFund from this window. You may begin new submissions by clicking the new submission button.
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
            let all_astd_interventions_id = [];
            let all_none_astd_interventions_id = [];
            let all_intervention_records = '{!! json_encode($intervention_types) ?? [] !!}';
            let selected_intervention_line = JSON.parse('{!! json_encode($selected_intervention_line) !!}');

            // function to always disable intervention years
            function disable_3_intervention_years(disable_requested_amount = false) {
                $("#intervention_year2").val('');
                $("#intervention_year2").attr('disabled', true);
                $("#intervention_year3").val('');
                $("#intervention_year3").attr('disabled', true);
                $("#intervention_year4").val('');
                $("#intervention_year4").attr('disabled', true);
                $("#year_plural").hide();
                $("#amount_requested_digit").attr('disabled', (disable_requested_amount==true) ? true : false);
            }

            $('#amount_requested_digit').keyup(function(event){
                $('#amount_requested_digit').digits();
            });

            // function to always enable intervention years
            function enable_3_intervention_years(enable_requested_amount = false) {
                $("#intervention_year2").attr('disabled', false);
                $("#intervention_year3").attr('disabled', false);
                $("#intervention_year4").attr('disabled', false);
                $("#amount_requested_digit").attr('disabled', (enable_requested_amount==true) ? true : false);
                $("#year_plural").hide();
            }


            // get months difference between two dates
            function getMonthDifference(startDate, endDate) {
                let diff = (endDate.getTime() - startDate.getTime());
              
                return (
                    diff / (1000 *3600 * 24 * 30)
                );
            }


            // function to display ASTD nomination list
            function display_astd_nomination_list(intevention_line_name) {

                let line_type_short = '';
                let nomination_label = '';
                let relationship_name = 'user';
                let nomination_table_label = '';
                if (intevention_line_name.includes('Teaching Practice')) {
                    line_type_short = 'tp';
                    relationship_name = 'tp_submission';
                    nomination_label = 'TP NOMINATION';
                    nomination_table_label = 'Program Date';
                } else if(intevention_line_name.includes('Conference Attendance')) {
                    line_type_short = 'ca';
                    relationship_name = 'ca_submission';
                    nomination_label = 'CA NOMINATION';
                    nomination_table_label = 'Conference Date';
                } else if (intevention_line_name.includes('TETFund Scholarship')) {
                    line_type_short = 'tsas';
                    relationship_name = 'tsas_submission';
                    nomination_label = 'TSAS NOMINATION';
                    nomination_table_label = 'Program Date';
                }

                if (line_type_short != '') {

                    let html_to_return = '';
                    let additional_params = "?submission_request_id={{ $submissionRequest->id }}";

                    $.get( "{{ route('tf-bi-portal-api.submission_requests.get_all_related_nomination_request', '') }}/"+line_type_short+additional_params).done(function( response ) {
                       
                       console.log(response);
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

                                if(month_diff < 2 && relationship_name == "ca_submission"){
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
                                    if(month_diff >= 2){
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
            }


            // execute this by default on page load
            @if (!isset(request()->tf_iterum_intervention_line_key_id))
                let intervention_line_html = "<option value=''>Select an Intervention Line</option>";
                all_astd_interventions_id.length = 0; /* resetting array to empty */
                
                $.each(JSON.parse(all_intervention_records), function(key, intervention) {
                    if (intervention.type == selected_intervention_line.type && intervention.id == selected_intervention_line.id) {
                        intervention_line_html += "<option selected='selected' value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                    } else if (intervention.type == selected_intervention_line.type) {
                        intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                    }

                    // setting all astd interventions into the array
                    if (intervention.name.includes('Teaching Practice') || intervention.name.includes('Conference Attendance') || intervention.name.includes('TETFund Scholarship')) {
                        all_astd_interventions_id[intervention.id] = intervention.name;
                    } else {
                        all_none_astd_interventions_id[intervention.id] = intervention.name;
                    }
                });

                //hiding intervention years
                if (selected_intervention_line.name && (selected_intervention_line.name.includes('Teaching Practice') || selected_intervention_line.name.includes('Conference Attendance') || selected_intervention_line.name.includes('TETFund Scholarship'))) {
                    
                    disable_3_intervention_years(true);
                    display_astd_nomination_list(selected_intervention_line.name);

                } else {
                    enable_3_intervention_years();
                }

                $('#intervention_line').html(intervention_line_html);
            @endif

            // triggered on changing intervention type
            $('#intervention_type').on('change', function() {
                let selected_intevention_type = $(this).val();
                let intervention_line_html = "<option value=''>Select an Intervention Line</option>";
                
                if (selected_intevention_type != '' && all_intervention_records != null) {
                    all_astd_interventions_id.length = 0; /* resetting array to empty */
                    $.each(JSON.parse(all_intervention_records), function(key, intervention) {
                        // appending intervention lines options
                        if (intervention.type == selected_intevention_type) {
                            intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                        }

                        // setting all astd interventions into the array
                        if (intervention.name.includes('Teaching Practice') || intervention.name.includes('Conference Attendance') || intervention.name.includes('TETFund Scholarship')) {
                            all_astd_interventions_id[intervention.id] = intervention.name;
                        }

                    });
                }
                
                $('#intervention_line').html(intervention_line_html);
            });

            // triggered on changing intervention line
            $('#intervention_line').on('change', function() {
                let view_selected_intervention_line = $(this).val();

                if (view_selected_intervention_line!='' && view_selected_intervention_line in all_astd_interventions_id) {
                    disable_3_intervention_years(true);
                    display_astd_nomination_list(all_astd_interventions_id[view_selected_intervention_line]);
                } else {
                    enable_3_intervention_years();
                    $('#current_nomination_details_submitted').html('');
                    $('#div_current_nomination_details_submitted').hide();
                }
              
                // settings to fomulate intervention_name
                let confirmed_selected_line = all_none_astd_interventions_id[view_selected_intervention_line]!=null ?
                        all_none_astd_interventions_id[view_selected_intervention_line] : 
                        all_astd_interventions_id[view_selected_intervention_line];
              
                $('#intervention_name').val(confirmed_selected_line);
            });

        });
    </script>
@endpush