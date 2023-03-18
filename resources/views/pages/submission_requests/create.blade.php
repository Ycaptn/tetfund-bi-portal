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
                    {!! Form::submit('Save New Request', ['class' => 'btn btn-primary', 'id'=>'btn_submit_request']) !!}
                    <a href="{{ route('tf-bi-portal.submissionRequests.index') }}" class="btn btn-default btn-warning">Cancel Submission</a>
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
                                    <th width="50%">
                                        Nominees' Fullname
                                    </th>
                                    <th width="25%">
                                        Total amount requested
                                    </th>
                                    <th width="20%" class="text-center">
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="current_nomination_details_submitted">
                                <tr>
                                   <td colspan="4" class="text-danger text-center">
                                       <i>No record found!</i>
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
            //console.log(numeric_amount);
        }

        $(document).ready(function() {
            let all_astd_interventions_id = [];
            let all_none_astd_interventions_id = [];
            let all_intervention_records = '{!! json_encode($intervention_types) ?? [] !!}';

            // hide 3 intervention years input fields
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
                    $.each(JSON.parse(all_intervention_records), function(key, intervention) {
                        // appending intervention lines options
                        if (intervention.type == selected_intevention_type) {
                            intervention_line_html += "<option value='"+ intervention.id +"'>"+ intervention.name +"</option>";
                        }

                        // setting all astd interventions into the array
                        if (intervention.name.includes('Teaching Practice') || intervention.name.includes('Conference Attendance') || intervention.name.includes('TETFund Scholarship')) {
                            all_astd_interventions_id[intervention.id] = intervention.name;
                        } else {
                            all_none_astd_interventions_id[intervention.id] = intervention.name;
                        }
                    });
                }

                let astd_inteventions_keys = Object.keys(all_astd_interventions_id);
                $('#intervention_line').html(intervention_line_html);
                $('#astd_interventions_ids').val(astd_inteventions_keys.join(','));
            });

            // triggered on changing intervention line
            $('#intervention_line').on('change', function() {
                let selected_intervention_line = $(this).val();
                $('#div_current_nomination_details_submitted').hide();
                if (selected_intervention_line != '' && selected_intervention_line in all_astd_interventions_id) {

                    let intevention_line_name = all_astd_interventions_id[selected_intervention_line];
                    let line_type_short = '';
                    let nomination_label = '';
                    let relationship_name = 'user';
                    if (intevention_line_name.includes('Teaching Practice')) {
                        line_type_short = 'tp';
                        relationship_name = 'tp_submission';
                        nomination_label = 'TP NOMINATION';
                        hide_3_intervention_years();
                    } else if(intevention_line_name.includes('Conference Attendance')) {
                        line_type_short = 'ca';
                        relationship_name = 'ca_submission';
                        nomination_label = 'CA NOMINATION';
                        hide_3_intervention_years();
                    } else if (intevention_line_name.includes('TETFund Scholarship')) {
                        line_type_short = 'tsas';
                        relationship_name = 'tsas_submission';
                        nomination_label = 'TSAS NOMINATION';
                        hide_3_intervention_years();
                    }

                    if (line_type_short != '') {
                        let html_to_return = '';
                        $.get( "{{ route('tf-bi-portal-api.submission_requests.get_all_related_nomination_request','') }}/"+line_type_short).done(function( response ) {
                           
                            if (response.data) {
                                let s_n_counter = 1;
                                $.each(response.data, function(key, nominee) {
                                    
                                    let formated_date = new Date(nominee[relationship_name]['updated_at']).toDateString();

                                    let middle_name = (nominee[relationship_name]['middle_name']) ? nominee[relationship_name]['middle_name'] : '';

                                    let total_request_amount = nominee[relationship_name]['total_requested_amount'] ? nominee[relationship_name]['total_requested_amount'] : "0.00";

                                    html_to_return += "<tr><td>"+ s_n_counter +"</td>  <td>"+ nominee[relationship_name]['first_name'] + " " + middle_name + " " + nominee[relationship_name]['last_name'] +"</td>  <td> &#8358 "+ total_request_amount +"</td>  <td>"+ formated_date +"</td>  </tr>";

                                    s_n_counter += 1;

                                }); 
                                
                                $('#nomination_type').text(nomination_label);
                                $('#div_current_nomination_details_submitted').show();
                                $('#current_nomination_details_submitted').html(html_to_return);
                            }
                        });
                    }
                } else {

                    $("#intervention_year2").attr('disabled', false);
                    $("#intervention_year3").attr('disabled', false);
                    $("#intervention_year4").attr('disabled', false);
                    $("#year_plural").show();
                }

                // settings to formulate intervention_title
                let confirmed_selected_line = all_none_astd_interventions_id[selected_intervention_line]!=null ?
                        all_none_astd_interventions_id[selected_intervention_line] : 
                        all_astd_interventions_id[selected_intervention_line];
              
                $('#intervention_title').val(confirmed_selected_line);
            });

        });
    </script>
@endpush