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
<a href="{{ route('tf-bi-portal.submissionRequests.create') }}" id="btn-new-submissionRequests" class="btn btn-sm btn-primary">
    <i class="bx bx-book-add mr-1"></i>New Submission Request
</a>
@stop

@section('content')
<div class="card border-top border-0 border-4 border-primary">
    
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
                <i class="bx bxs-user me-1 font-22 text-primary"></i>
            </div>
            <h5 class="mb-0 text-primary">Modify Submission Request Details</h5>
        </div>

        {!! Form::model($submissionRequest, ['class'=>'form-horizontal', 'onsubmit'=>'filter_removing_comma()', 'route' => ['tf-bi-portal.submissionRequests.update', $submissionRequest->id], 'method' => 'patch']) !!}

            @include('tf-bi-portal::pages.submission_requests.fields')

            <div class="col-lg-offset-3 col-lg-9">
                <hr/>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('tf-bi-portal.submissionRequests.index') }}" class="btn btn-warning btn-default">Cancel</a>
            </div>
        {!! Form::close() !!}                

    </div>
</div>
@stop

@section('side-panel')
<div class="card radius-5 border-top border-0 border-4 border-primary">
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
                            let type = "{{ (isset($submissionRequest) && old('tf_iterum_intervention_line_key_id') == null) ? $submissionRequest->tf_iterum_intervention_line_key_id : old('tf_iterum_intervention_line_key_id') }}";
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
                
            if (("{{ old('intervention_type') }}" != null && "{{ old('intervention_type') }}" != '') || ("{{ isset($submissionRequest) }}" && "{{$submissionRequest->tf_iterum_intervention_line_key_id}}" != null)) {
                porpulateInterventionLine();
            }
        });
    </script>
@endpush