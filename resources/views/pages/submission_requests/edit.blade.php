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
{{ $submissionRequest->title }}
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
                {!! Form::submit('Update Request', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('tf-bi-portal.submissionRequests.index') }}" class="btn btn-warning btn-default">Cancel</a>
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
            This is the help message.
            This is the help message.
            This is the help message.
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
            let all_intervention_records = '{!! json_encode($intervention_types) ?? [] !!}';
            let selected_intervention_line = JSON.parse('{!! json_encode($selected_intervention_line) !!}');
            let all_astd_interventions_id = [];

            // Converting string words to upper-case
            function upperCaseFirstLetterInString(str){
                var splitStr = str.toLowerCase().split(" ");
                for(var i=0; i<splitStr.length; i++){
                    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].slice(1);
                }
                return splitStr.join(" ");
            }

            // function to always hide intervention years
            function hide_3_intervention_years() {
                $("#div_intervention_year2").hide();
                $("#div_intervention_year3").hide();
                $("#div_intervention_year4").hide();
                $("#year_plural").hide();
            }

            // function to always show intervention years
            function show_3_intervention_years() {
                $("#div_intervention_year2").show();
                $("#div_intervention_year3").show();
                $("#div_intervention_year4").show();
                $("#year_plural").show();
            }



            @if (!isset(request()->tf_iterum_intervention_line_key_id))
                let intervention_line_html = "<option value=''>Select an Intervention Line</option>";
                all_astd_interventions_id.length = 0; /* resetting array to empty */
                
                $.each(JSON.parse(all_intervention_records), function(key, intervention) {
                    if (intervention.type == selected_intervention_line.type && intervention.id == selected_intervention_line.id) {
                        intervention_line_html += "<option selected='selected' value='"+ intervention.id +"'>"+ upperCaseFirstLetterInString(intervention.name) +"</option>";
                    } else if (intervention.type == selected_intervention_line.type) {
                        intervention_line_html += "<option value='"+ intervention.id +"'>"+ upperCaseFirstLetterInString(intervention.name) +"</option>";
                    }

                    // setting all astd interventions into the array
                    let intervention_name = intervention.name.toLowerCase();
                    if (intervention_name.includes('teaching practice') || intervention_name.includes('conference attendance') || intervention_name.includes('tetfund scholarship')) {
                        all_astd_interventions_id[intervention.id] = intervention_name;
                    }
                });

                //hiding intervention years
                let intervention_name = selected_intervention_line.name.toLowerCase();
                if (intervention_name.includes('teaching practice') || intervention_name.includes('conference attendance') || intervention_name.includes('tetfund scholarship')) {
                    hide_3_intervention_years();
                } else {
                    show_3_intervention_years();
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
                            intervention_line_html += "<option value='"+ intervention.id +"'>"+ upperCaseFirstLetterInString(intervention.name) +"</option>";
                        }

                        // setting all astd interventions into the array
                        let intervention_name = intervention.name.toLowerCase();
                        if (intervention_name.includes('teaching practice') || intervention_name.includes('conference attendance') || intervention_name.includes('tetfund scholarship')) {
                            all_astd_interventions_id[intervention.id] = intervention_name;
                        }

                    });
                }
                $('#intervention_line').html(intervention_line_html);
            });

            // triggered on changing intervention line
            $('#intervention_line').on('change', function() {
                let view_selected_intervention_line = $(this).val();
                
                if (view_selected_intervention_line != '' && view_selected_intervention_line in all_astd_interventions_id) {
                    hide_3_intervention_years();
                } else {
                    show_3_intervention_years();
                }
            });

        });
    </script>
@endpush