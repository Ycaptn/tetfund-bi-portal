<div class="row col-sm-12 form-group mb-3">
    <label class="col-sm-12 control-label" for="nomination_type">Type of Nomination</label>
    <div class="col-sm-12 input-group">
        <select name="nomination_type" id="nomination_type" class="form-select">
        <option value="">Select type of Nomination</option> 
            @if(isset($beneficiary->type) && strtolower($beneficiary->type)=='college')
                <option value="tp">Teaching Practice &nbsp; (TP)</option> 
            @endif
            <option value="ca">Conference Attendance &nbsp; (CA)</option> 
            <option value="tsas">TETFund Scholarship for Academic Staff &nbsp; (TSAS)</option>
        </select>
        <span class="input-group-text"><span class="fa fa-archive"></span></span>
    </div>
</div>

@if(isset($beneficiary->type) && strtolower($beneficiary->type)=='college')
    <div class="row col-sm-12 form-group mb-3" id="tp_nomination_form" style="display: none;">
        <hr>
        @include('pages.t_p_nominations.fields')        
    </div>
@endif

<div class="row col-sm-12 form-group mb-3" id="ca_nomination_form" style="display: none;">
    <hr>
    @include('pages.c_a_nominations.fields')
</div>

<div class="row col-sm-12 form-group mb-3" id="tsas_nomination_form" style="display: none;">
    <hr>
    @include('pages.t_s_a_s_nominations.fields')
</div>