


    <div class="form-group mb-3">
        {{-- <label class="col-lg-12 control-label">Intervention</label> --}}
        <div class="col-lg-12">
            <div class="input-group">
                <select name="intervention_type" id="intervention_type" class="form-select">
                    <option value="">Select the type of Intervention</option>
                @if (isset($intervention_types) && $intervention_types != null)
                    @foreach ($intervention_types as $idx=>$type)
                        @if (in_array('bi_ict', $bi_roles) || in_array('bi-desk-officer', $bi_roles))
                            <option {{ (old('intervention_type') == $type || isset($beneficiary_request) && $beneficiary_request->intervention_request_type==$type) ? "selected" : '' }} value="{{$type}}"> {{ ucwords($type) }}</option>
                        @endif
                    @endforeach
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
                <select name="type" id="intervention_line" class="form-select">
                    <option value="">Select an Intervention Line</option>sssss   
                </select>
                <span class="input-group-text"><span class="fa fa-archive"></span></span>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="col-lg-12 control-label">Project Title</label>
        <div class="col-lg-12">
            <div class="input-group">
                <input type='text' class="form-control" name="title" value="{{ old('title')  }}" />
                <span class="input-group-text"><span class="fa fa-file"></span></span>
            </div>
        </div>
    </div>

    <div id='intervention_years_div' class="form-group mb-3">
        <label class="col-lg-12 control-label">Intervention Years</label>
        <div class="col-lg-12">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-4 g-3">
                <div class="col">
                    <select name="intervention_year1" class="form-select">
                        <option value="">N/A</option>
                        @foreach($years as $idx=>$year)
                        <option {{ (old('intervention_year1') == $year || isset($beneficiary_request) && $beneficiary_request->intervention_year1==$year) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select name="intervention_year2" class="form-select">
                        <option value="">N/A</option>
                        @foreach($years as $idx=>$year)
                        <option {{ (old('intervention_year2') == $year || isset($beneficiary_request) && $beneficiary_request->intervention_year2==$year) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select name="intervention_year3" class="form-select">
                        <option value="">N/A</option>
                        @foreach($years as $idx=>$year)
                        <option {{ (old('intervention_year3') == $year || isset($beneficiary_request) && $beneficiary_request->intervention_year3==$year) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select name="intervention_year4" class="form-select">
                        <option value="">N/A</option>
                        @foreach($years as $idx=>$year)
                        <option {{ (old('intervention_year4') == $year || isset($beneficiary_request) && $beneficiary_request->intervention_year4==$year) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="col-lg-12 control-label">Requested Amount (&#8358)</label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type='text' class="form-control" name="amount_requested" value="{{ old('amount_requested')  }}" />
                <span class="input-group-text"><span class="fa fa-money"></span></span>
            </div>
        </div>
    </div>


