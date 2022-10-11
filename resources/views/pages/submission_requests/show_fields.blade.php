<!-- Title Field -->
<div id="div_submissionRequest_title" class="col-sm-12 mb-10">
    <p>
        {!! Form::label('title', 'Title:', ['class'=>'control-label']) !!} 
        <span id="spn_submissionRequest_title">
        @if (isset($submissionRequest->title) && empty($submissionRequest->title)==false)
            {!! $submissionRequest->title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Status Field -->
<div id="div_submissionRequest_status" class="col-sm-12 mb-10">
    <p>
        {!! Form::label('status', 'Status:', ['class'=>'control-label']) !!} 
        <span id="spn_submissionRequest_status">
        @if (isset($submissionRequest->status) && empty($submissionRequest->status)==false)
            {!! $submissionRequest->status !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Type Field -->
<div id="div_submissionRequest_type" class="col-sm-12 mb-10">
    <p>
        {!! Form::label('type', 'Type:', ['class'=>'control-label']) !!} 
        <span id="spn_submissionRequest_type">
        @if (isset($submissionRequest->type) && empty($submissionRequest->type)==false)
            {!! $submissionRequest->type !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Tf Iterum Portal Request Status Field -->
<div id="div_submissionRequest_tf_iterum_portal_request_status" class="col-sm-12 mb-10">
    <p>
        {!! Form::label('tf_iterum_portal_request_status', 'Tf Iterum Portal Request Status:', ['class'=>'control-label']) !!} 
        <span id="spn_submissionRequest_tf_iterum_portal_request_status">
        @if (isset($submissionRequest->tf_iterum_portal_request_status) && empty($submissionRequest->tf_iterum_portal_request_status)==false)
            {!! $submissionRequest->tf_iterum_portal_request_status !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

