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
       
        {{-- fainted loader --}}
        <div class="col-sm-12 text-center" id="spinner-submission_requests" style="display: none; width:100%; height: 100%; position: absolute; z-index: 2;">
            
            <div style="position: absolute; background-color: lightgrey; width:100%; height: 100%; opacity:0.5;">
            </div>

            <div class="col-sm-12 text-center" style="position: absolute; width:100%; height: 100%; padding-top: 150px;">
                <div class="spinner-border text-primary" role="status"> 
                    <span class="visually-hidden">Loading...</span>
                </div>
                <br>
                <div class="col-sm-12">
                    <h3><strong>Please Wait...</strong></h3>
                    <h5><strong><i>Loading related intervention lines !</i></strong></h5>
                </div>
            </div>
        </div>

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

            $('#amount_requested_digit').keyup(function(event){
                $('#amount_requested_digit').digits();
            });

            /* Converting string words to upper-case */
            function upperCaseFirstLetterInString(str){
                var splitStr = str.toLowerCase().split(" ");
                for(var i=0; i<splitStr.length; i++){
                    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].slice(1);
                }
                return splitStr.join(" ");
            }

            function porpulateInterventionLine(){
                let intervention_type_val = $('#intervention_type').val();
                let default_option = "<option value=''>Select an Intervention Line</option>"
                $("#btn_submit_request").attr('disabled', true);
                if (intervention_type_val == '') {
                    $("#intervention_line").html(default_option);
                    $("#btn_submit_request").attr('disabled', false);
                } else {
                    
                    $("#spinner-submission_requests").show();
                    $("#spinner-submission_requests").focus();

                    /* get all related Intervention Lines */
                    $.get( "{{ route('tf-bi-portal-api.getAllInterventionLinesForSpecificType', '') }}?intervention_type="+intervention_type_val).done(function( response ) {
                        if (response && response != null) {
                            let type = "{{ old('tf_iterum_intervention_line_key_id') }}";
                            $.each(response, function( index, value ) {
                                var selection = (type == value.id) ? 'selected' : '';
                                default_option += "<option " + selection + " value='" + value.id + "'>" + upperCaseFirstLetterInString(value.name) + "</option>";
                            });
                            
                            $('#intervention_line').html(default_option);

                            $("#spinner-submission_requests").hide();
                        }
                    });
                    $("#btn_submit_request").attr('disabled', false);
                }
            }

            /* update intervention line on changing intervention type */
            $(document).on('change', "#intervention_type", function(e) {
                porpulateInterventionLine();
            });
                
            if ("{{ old('intervention_type') }}" != null && "{{ old('intervention_type') }}" != '') {
                porpulateInterventionLine();
            }
        });
    </script>
@endpush