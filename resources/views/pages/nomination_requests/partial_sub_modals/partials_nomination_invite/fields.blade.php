

<div class="row col-sm-12 form-group mb-3">
    <label class="col-sm-12 control-label" for="bi_staff_email">Beneficiary Staff Email</label>
    <div class="col-sm-12 input-group">
        <input type="text" list="bi_staffs_email" name="bi_staff_email" value="{{ old('bi_staff_email') }}" placeholder="Staff Email Address" class="form-control" id="bi_staff_email">
        <datalist id="bi_staffs_email">
            @if(isset($all_beneficiary_users) && count($all_beneficiary_users) > 0)
                @foreach($all_beneficiary_users as $beneficiary_user)
                    <option value="{{ $beneficiary_user->email }}">
                @endforeach
            @endif
        </datalist>
        <span class="input-group-text"><span class="fa fa-envelope"></span></span>
    </div>
</div>

<div class="row col-sm-12">
    <div class="col-sm-6 form-group mb-3">
        <label class="col-sm-12 control-label" for="bi_staff_fname">Staff First Name</label>
        <div class="col-sm-12 input-group">
           <input type="text" name="bi_staff_fname" value="{{ old('bi_staff_fname') }}" placeholder="Staff First Name" class="form-control" id="bi_staff_fname">
        </div>
    </div>

    <div class="col-sm-6 form-group mb-3">
        <label class="col-sm-12 control-label" for="bi_staff_lname">Staff Last Name</label>
        <div class="col-sm-12 input-group">
            <input type="text" name="bi_staff_lname" value="{{ old('bi_staff_lname') }}" placeholder="Staff Last Name" class="form-control" id="bi_staff_lname">
        </div>
    </div>
</div>

<div class="row col-sm-12">
    <div class="col-sm-6 form-group mb-3">
        <label class="col-sm-12 control-label" for="bi_telephone">Telephone</label>
        <div class="col-sm-12 input-group">
           <input type="text" name="bi_telephone" value="{{ old('bi_telephone') }}" placeholder="Staff Phone Number" class="form-control" id="bi_telephone">
        </div>
    </div>

    <div class="col-sm-6 form-group mb-3">
        <label class="col-sm-12 control-label" for="bi_staff_gender">Gender</label>
        <div class="col-sm-12 input-group">
            <select class="form-select" name="bi_staff_gender" id="bi_staff_gender">
                <option value="">None Selected</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
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