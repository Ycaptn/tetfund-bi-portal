


    <div class="form-group mb-3">
        {{-- <label class="col-lg-12 control-label">Intervention</label> --}}
        <div class="col-lg-12">
            <div class="input-group">
                <select name="intervention_type" id="intervention_type" class="form-select">
                    <option value="">Select the type of Intervention</option>
                    @if (isset($intervention_types) && $intervention_types != null)
                        @php
                            $unique_intervention = [];
                            foreach ($intervention_types as $intervention) {
                                if (!in_array($intervention->type, $unique_intervention)) {
                                    array_push($unique_intervention, $intervention->type);
                                    if (isset($selected_intervention_line) && !empty($selected_intervention_line) && $selected_intervention_line->type == $intervention->type) {
                                        echo "<option selected='selected' value='". $intervention->type ."' >" . ucwords($intervention->type) . "</option>";
                                    } else {
                                        echo "<option value='". $intervention->type ."' >" . ucwords($intervention->type) . "</option>";
                                    }
                                }
                            }
                        @endphp
                    @endif
                </select>
                <span class="input-group-text"><span class="fa fa-folder-open"></span></span>
            </div>
        </div>
    </div>

    <div id='request_type_div' class="form-group mb-3">
        {{-- <label class="col-lg-12 control-label">Intervention Line</label> --}}
        <div class="col-lg-12">
            <div class="input-group">
                <select name="tf_iterum_intervention_line_key_id" id="intervention_line" class="form-select">
                    <option value="">Select an Intervention Line</option>   
                </select>
                <span class="input-group-text"><span class="fa fa-archive"></span></span>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="col-lg-12 control-label">Project Title</label>
        <div class="col-lg-12">
            <div class="input-group">
                <input type='text' class="form-control" name="title" value="{{ (isset($submissionRequest) && old('title') == null) ? $submissionRequest->title : old('title')  }}" />
                <span class="input-group-text"><span class="fa fa-file"></span></span>
            </div>
        </div>
    </div>

    <div id='intervention_years_div' class="form-group mb-3">
        <label class="col-lg-12 control-label">Intervention Year<span id="year_plural">s</span></label>
        <div class="col-lg-12">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-4 g-3">
                <div class="col">
                    <select name="intervention_year1" class="form-select">
                        <option value="">N/A</option>
                        @if(isset($years))
                            @foreach($years as $idx=>$year)
                                <option {{ (old('intervention_year1') == $year || (isset($submissionRequest) && $submissionRequest->intervention_year1==$year)) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col" id="div_intervention_year2">
                    <select name="intervention_year2" id="intervention_year2" class="form-select">
                        <option value="">N/A</option>
                        @if(isset($years))
                            @foreach($years as $idx=>$year)
                                <option {{ (old('intervention_year2') == $year || (isset($submissionRequest) && $submissionRequest->intervention_year2==$year)) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col" id="div_intervention_year3">
                    <select name="intervention_year3" id="intervention_year3" class="form-select">
                        <option value="">N/A</option>
                        @if(isset($years))
                            @foreach($years as $idx=>$year)
                                <option {{ (old('intervention_year3') == $year || (isset($submissionRequest) && $submissionRequest->intervention_year3==$year)) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col" id="div_intervention_year4">
                    <select name="intervention_year4" id="intervention_year4" class="form-select">
                        <option value="">N/A</option>
                        @if(isset($years))
                            @foreach($years as $idx=>$year)
                                <option {{ (old('intervention_year4') == $year || (isset($submissionRequest) && $submissionRequest->intervention_year4==$year)) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="col-lg-12 control-label">Requested Amount (&#8358)</label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type='hidden' class="form-control" name="amount_requested" id="amount_requested" value="{{ (isset($submissionRequest) && old('amount_requested') == null) ? $submissionRequest->amount_requested : old('amount_requested')  }}" />

                <input type='text' class="form-control" name="amount_requested_digit" id="amount_requested_digit" value="{{ (isset($submissionRequest) && old('amount_requested_digit') == null) ? number_format($submissionRequest->amount_requested) : old('amount_requested_digit')  }}" />
                <span class="input-group-text"><span class="fa fa-money"></span></span>
            </div>
        </div>
    </div>


