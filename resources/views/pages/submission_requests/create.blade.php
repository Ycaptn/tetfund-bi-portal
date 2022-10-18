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
{{--
<a id="btn-new-mdl-submissionRequest-modal" class="btn btn-sm btn-primary btn-new-mdl-submissionRequest-modal">
    <i class="bx bx-book-add me-1"></i>New Submission Request
</a>
--}}
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
            {!! Form::open(['route' => 'tf-bi-portal.submissionRequests.store','class'=>'form-horizontal']) !!}
            
                @include('pages.submission_requests.fields')

                <hr />

                <div class="col-lg-offset-3 col-lg-12">
                    {!! Form::submit('Submit Request', ['class' => 'btn btn-primary', 'id'=>'btn_submit_request']) !!}
                    <a href="{{ route('tf-bi-portal.submissionRequests.index') }}" class="btn btn-default btn-warning">Cancel Submission</a>
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
        $(document).ready(function() {
            /* Converting string words to upper-case */
            function upperCaseFirstLetterInString(str){
                var splitStr = str.toLowerCase().split(" ");
                for(var i=0; i<splitStr.length; i++){
                    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].slice(1);
                }
                return splitStr.join(" ");
            }

            /* update intervention line on changing intervention type */
            $(document).on('change', "#intervention_type", function(e) {
                let intervention_type_val = $('#intervention_type').val();
                let default_option = "<option value=''>Select an Intervention Line</option>"
                $("#btn_submit_request").attr('disabled', true);
                if (intervention_type_val == '') {
                    $("#intervention_line").html(default_option);
                    $("#btn_submit_request").attr('disabled', false);
                } else {
                    /* get all related Intervention Lines */
                    $.get( "{{ route('tf-bi-portal-api.getAllInterventionLinesForSpecificType', '') }}?intervention_type="+intervention_type_val).done(function( response ) {
                        if (response && response != null) {
                            $.each(response, function( index, value ) {
                                default_option += "<option value='" + value.id + "'>" + upperCaseFirstLetterInString(value.name) + ' (' + upperCaseFirstLetterInString(value.type) + ')' + "</option>";
                            });
                            
                            $('#intervention_line').html(default_option);
                        }
                    });
                    $("#btn_submit_request").attr('disabled', false);
                }
            });
        });
    </script>
@endpush