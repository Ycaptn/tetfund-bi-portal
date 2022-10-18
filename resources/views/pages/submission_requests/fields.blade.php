


    <div class="form-group mb-3">
        {{-- <label class="col-lg-12 control-label">Intervention</label> --}}
        <div class="col-lg-12">
            <div class="input-group">
                <select name="intervention_type" id="intervention_type" class="form-select">
                    <option value="">Select the type of Intervention</option>
                @if (isset($intervention_types) && $intervention_types != null)
                    @foreach ($intervention_types as $idx=>$type)
                        <option {{ (isset($beneficiary_request) && $beneficiary_request->intervention_request_type==$type) ? "selected" : '' }} value="{{$type}}"> {{ ucwords($type) }}</option>
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
                <select name="intervention_line" id="intervention_line" class="form-select">
                    <option value="">Select an Intervention Line</option>
                    @if (isset($intervention_lines) && $intervention_lines != null)
                        @foreach ($intervention_lines as $idx=>$line)
                            @if (in_array('bi_ict', $bi_roles_arr) || in_array('bi_deskofficer', $bi_roles_arr))
                                <option {{ (isset($beneficiary_request) && $beneficiary_request->intervention_request_line==$line) ? "selected" : '' }} value="{{$line}}" > {{$line}}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
                <span class="input-group-text"><span class="fa fa-archive"></span></span>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="col-lg-12 control-label">Project Title</label>
        <div class="col-lg-12">
            <div class="input-group">
                <input type='text' class="form-control" name="project_title" value="{{ old('project_title')  }}" />
                <span class="input-group-text"><span class="fa fa-file"></span></span>
            </div>
        </div>
    </div>

    <div id='intervention_years_div' class="form-group mb-3">
        <label class="col-lg-12 control-label">Intervention Years</label>
        <div class="col-lg-12">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-4 g-3">
                
                @php
                    $years = [];
                    $years []= date("Y");
                    $years []= date("Y")-1;
                    $years []= date("Y")-2;
                    $years []= date("Y")-3;
                    $years []= date("Y")-4;
                    $years []= date("Y")-5;
                @endphp

                <div class="col">
                    <select name="intervention_year1" class="form-select">
                        <option value="">N/A</option>
                        @foreach($years as $idx=>$year)
                        <option {{ (isset($beneficiary_request) && $beneficiary_request->intervention_year1==$year) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select name="intervention_year2" class="form-select">
                        <option value="">N/A</option>
                        @foreach($years as $idx=>$year)
                        <option {{ (isset($beneficiary_request) && $beneficiary_request->intervention_year2==$year) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select name="intervention_year3" class="form-select">
                        <option value="">N/A</option>
                        @foreach($years as $idx=>$year)
                        <option {{ (isset($beneficiary_request) && $beneficiary_request->intervention_year3==$year) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select name="intervention_year4" class="form-select">
                        <option value="">N/A</option>
                        @foreach($years as $idx=>$year)
                        <option {{ (isset($beneficiary_request) && $beneficiary_request->intervention_year4==$year) ? "selected" : '' }} value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="col-lg-12 control-label">Requested Amount</label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type='text' class="form-control" name="amount_requested" value="{{ old('amount_requested')  }}" />
                <span class="input-group-text"><span class="fa fa-money"></span></span>
            </div>
        </div>
    </div>


