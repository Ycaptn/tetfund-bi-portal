

<div class="row col-sm-12 form-group mb-3">
    <label class="col-sm-12 control-label" for="bi_staff_email">Beneficiary Staff Email</label>
    <div class="col-sm-12 input-group">
        <input type="text" name="bi_staff_email" value="{{ old('bi_staff_email') }}" placeholder="Staff Email Address" class="form-control" id="bi_staff_email">
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
            <option value="ca">CA &nbsp; (Conference)</option> 
            <option value="tsas">TSAS &nbsp; (TETFund Scholar)</option> 
            <option value="tp">TP &nbsp; (Teaching Practice)</option> 
            <option value="astd">ASTD &nbsp; (Staff Training)</option> 
        </select>
        <span class="input-group-text"><span class="fa fa-archive"></span></span>
    </div>
</div>