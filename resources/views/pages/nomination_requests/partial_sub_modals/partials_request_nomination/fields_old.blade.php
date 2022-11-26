

<div class="row col-sm-12 form-group mb-3">
    <label class="col-sm-12 control-label" for="bi_staff_email">Beneficiary Staff Email</label>
    <div class="col-sm-12 input-group">
        <input type="text" name="bi_staff_email" value="{{ $current_user->email }}" placeholder="Staff Email Address" class="form-control" id="bi_staff_email" disabled='disabled'>
        <span class="input-group-text"><span class="fa fa-envelope"></span></span>
    </div>
</div>

<div class="row col-sm-12">
    <div class="col-sm-6 form-group mb-3">
        <label class="col-sm-12 control-label" for="bi_staff_fname">Staff First Name</label>
        <div class="col-sm-12 input-group">
           <input type="text" name="bi_staff_fname" value="{{ $current_user->first_name }}" placeholder="Staff First Name" class="form-control" id="bi_staff_fname" disabled='disabled'>
        </div>
    </div>

    <div class="col-sm-6 form-group mb-3">
        <label class="col-sm-12 control-label" for="bi_staff_lname">Staff Last Name</label>
        <div class="col-sm-12 input-group">
            <input type="text" name="bi_staff_lname" value="{{ $current_user->last_name }}" placeholder="Staff Last Name" class="form-control" id="bi_staff_lname" disabled='disabled'>
        </div>
    </div>
</div>

<div class="row col-sm-12">
    <div class="col-sm-6 form-group mb-3">
        <label class="col-sm-12 control-label" for="bi_telephone">Telephone</label>
        <div class="col-sm-12 input-group">
           <input type="text" name="bi_telephone" value="{{ $current_user->telephone }}" placeholder="Staff Phone Number" class="form-control" id="bi_telephone" disabled='disabled'>
        </div>
    </div>

    <div class="col-sm-6 form-group mb-3">
        <label class="col-sm-12 control-label" for="bi_staff_gender">Gender</label>
        <div class="col-sm-12 input-group">
            @php
                $gender = strtolower($current_user->gender);
                $m = $f = $n = '';
                if($gender == 'male') {
                    $m = "selected='selected'";
                } elseif ($gender == 'female') {
                    $f = "selected='selected'";
                } else {
                    $n = "selected='selected'";
                }
            @endphp

            <select class="form-select" name="bi_staff_gender" id="bi_staff_gender" disabled='disabled'>
                <option value="" {{ $n }}>None Selected</option>
                <option value="male" {{ $m }}>Male</option>
                <option value="female" {{ $f }}>Female</option>
            </select>
        </div>
    </div>    
</div>

<div class="row col-sm-12 form-group mb-3">
    <label class="col-sm-12 control-label" for="nomination_type">Type of Nomination</label>
    <div class="col-sm-12 input-group">
        <select name="nomination_type" id="nomination_type" class="form-select">
        <option value="">Select type of Nomination</option> 
            <option value="astd">Academic Staff Training and Development &nbsp; (ASTD)</option>
            <option value="ca">Conference Attendance &nbsp; (CA)</option> 
            <option value="tp">Teaching Practice &nbsp; (TP)</option> 
            <option value="tsas">TETFund Scholarship for Academic Staff &nbsp; (TSAS)</option>
        </select>
        <span class="input-group-text"><span class="fa fa-archive"></span></span>
    </div>
</div>