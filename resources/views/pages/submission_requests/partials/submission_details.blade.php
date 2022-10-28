<div class="col-sm-12">
    <div class="container">
        {{-- <i class="fa fa-briefcase fa-fw"></i> <b>File_Number:</b> &nbsp; ______ {{ optional($submissionRequest)->file_number }} <br/> --}}
        <i class="fa fa-calendar-o fa-fw"></i> <strong>Created on </strong> {{ \Carbon\Carbon::parse($submissionRequest->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($submissionRequest->created_at)->diffForHumans() !!} </span> <br />

        <i class="fa fa-bank fa-fw"></i> <b>{{ ucwords($intervention->type) }} Intervention </b> - {{ $intervention->name }} <br/>
        @php
            $years_str = $submissionRequest->intervention_year1;
            
            // merged years, unique years & sorted years
            if (isset($years) && count($years) > 1) {
                $years_detail = array_values(array_unique($years));
                sort($years_detail);
                $years_detail[count($years_detail) - 1] = ' and ' . $years_detail[count($years_detail) - 1];
                $years_str = implode(" ", $years_detail);
            } 

        @endphp

        <i class="fa fa-crosshairs fa-fw"></i><b>Intervention Year(s) - </b> {{ $years_str }} <br/>
        <i class="fa fa-thumbs-up fa-fw"></i><b>Current Stage:</b> {{ strtoupper($submissionRequest->status) }}<br/><br/>

        {{-- @include('tf-bi-portal::pages.submission_requests.show_fields') --}}
    </div>
</div>