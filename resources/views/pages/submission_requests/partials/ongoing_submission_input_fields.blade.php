@php
	$current_year = date('Y');
	$years = [$current_year];
	for($i=1; $i<=5; $i++) {
		array_push($years, $current_year-$i);
	}
@endphp

<div class="row mt-5">
    <div class="col-md-6 mb-5">
	    <div class="input-group">
            <select name="intervention_type" id="intervention_type" class="form-select">
                <option value="">Select type of Intervention - (AIP)</option>
                @if (isset($intervention_types) && $intervention_types != null)
                    @php
                        $unique_inter_type = [];
                    @endphp
                    @foreach ($intervention_types as $intervention)
                        @if(!in_array($intervention->type, $unique_inter_type))
                            {{ array_push($unique_inter_type, $intervention->type) }}
                            <option value='{{ $intervention->type }}' > {{ ucwords($intervention->type) }} </option>
                        @endif
                    @endforeach
                @endif
            </select>
            <span class="input-group-text"><span class="fa fa-folder-open"></span></span>
        </div>
    </div>
    
    <div class="col-md-6 mb-5">
        <div class="input-group">
            <select id="intervention_line" class="form-select">
                <option value="">Select AIP Intervention Line</option>   
            </select>
            <span class="input-group-text"><span class="fa fa-archive"></span></span>
        </div>
    </div>

    <div class="col-sm-12 mb-5">
        <label class="col-lg-12 control-label"><b>AIP INTERVENTION YEAR<span id="year_plural">s</span></b></label>
        <div class="col-lg-12">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-4 g-3">
                <div class="col" id="div_intervention_year1">
                    <select id="intervention_year1" class="form-select">
                        <option value="">N/A</option>
                        @if(isset($years))
                            @foreach($years as $year)
                                <option value="{{$year}}">{{$year}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col" id="div_intervention_year2">
                    <select id="intervention_year2" class="form-select">
                        <option value="">N/A</option>
                        @if(isset($years))
                            @foreach($years as $year)
                                <option value="{{$year}}">{{$year}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col" id="div_intervention_year3">
                    <select id="intervention_year3" class="form-select">
                        <option value="">N/A</option>
                        @if(isset($years))
                            @foreach($years as $year)
                                <option value="{{$year}}">{{$year}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col" id="div_intervention_year4">
                    <select id="intervention_year4" class="form-select">
                        <option value="">N/A</option>
                        @if(isset($years))
                            @foreach($years as $year)
                                <option value="{{$year}}">{{$year}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-5">
        <label class="col-md-12 control-label"><b>AIP APPROVED AMOUNT (&#8358)</b></label>
        <div class="input-group">
            <input type='text' class="form-control" id="amount_requested" value="" />
            <span class="input-group-text"><span class="fa fa-money"></span></span>
        </div>    
    </div>

    <div class="col-md-6 mb-2">
        <label class="col-md-12 control-label"><b>FILE ATTACHMENTS</b></label>
        <div class="input-group">
	        <input multiple="multiple" type='file' class="form-control" name="file_attachments[]" id="file_attachments" />
	        <span class="input-group-text"><span class="fa fa-folder-open"></span></span>
		    <em>
		    	<small class="text-danger">
		    		- You may select multiple files for upload. Each file must be a PDF.<br>
                    - Max file Size 100M each.<br>
                    - You may select multiple files for upload where neccessary by holding down <b>Ctrl key</b> then click to select the desired files to be uploaded.<br>

                    @if($ongoing_label=='1st_Tranche_Payment')
                        - The files to be attached should contain a copy of your <b>AIP Document</b>.
                    @elseif($ongoing_label=='2nd_Tranche_Payment')
                        - The files to be attached should contain a copy of your <b>AIP Document and First Tranche Disbursment Document</b>.
                    @elseif($ongoing_label=='Final_Tranche_Payment' || $ongoing_label=='Monitoring_Request')
                        - The files to be attached should contain a copy of your <b>AIP Document, First Tranche Disbursment Document and Second Tranche Disbursement Document (where applicable)</b>.                    
                    @endif
		    	</small>
		    </em>  
	    </div>
    </div>

    <input type='hidden' id="intervention_title" value="" />
</div>