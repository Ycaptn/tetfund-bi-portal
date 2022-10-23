<div class="col-sm-12">
    <i class="fa fa-briefcase fa-fw"></i> <b>File_Number:</b> &nbsp; ______ {{ optional($submissionRequest)->file_number }} <br/>
    <i class="fa fa-calendar-o fa-fw"></i> <strong>Received on </strong> {{ \Carbon\Carbon::parse($submissionRequest->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($submissionRequest->created_at)->diffForHumans() !!} </span> <br />

    <i class="fa fa-bank fa-fw"></i> <b>{{ ucwords($intervention->type) }} Intervention </b> - {{ $intervention->name }} <br/>
    <i class="fa fa-crosshairs fa-fw"></i><b>Intervention Year(s) - </b> {{ $submissionRequest->intervention_year1 }} <br/>
    <i class="fa fa-thumbs-up fa-fw"></i><b>Current Stage:</b> {{ strtoupper($submissionRequest->status) }}<br/><br/>

    {{-- @include('tf-bi-portal::pages.submission_requests.show_fields') --}}
</div>