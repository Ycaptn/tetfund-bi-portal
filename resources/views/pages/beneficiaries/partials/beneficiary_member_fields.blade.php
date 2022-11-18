@csrf                        
<div class="offline-flag"><span class="offline-beneficiary-member">You are currently offline</span></div>

<input type="hidden" id="txt-beneficiary-member-primary-id" value="0" />
<div class="row col-sm-12">
   
    <div class="row col-sm-12 form-group mb-3">
        <label class="col-sm-12 control-label" for="bi_staff_email">Beneficiary User Email</label>
        <div class="col-sm-12 input-group">
            <input type="text" list="bi_staffs_email" name="bi_staff_email" value="{{ old('bi_staff_email') }}" placeholder="User Email Address" class="form-control" id="bi_staff_email">
            <span class="input-group-text"><span class="fa fa-envelope"></span></span>
        </div>
    </div>

    <div class="row col-sm-12">
        <div class="col-sm-6 form-group mb-3">
            <label class="col-sm-12 control-label" for="bi_staff_fname">First Name</label>
            <div class="col-sm-12 input-group">
               <input type="text" name="bi_staff_fname" value="{{ old('bi_staff_fname') }}" placeholder="User First Name" class="form-control" id="bi_staff_fname">
            </div>
        </div>

        <div class="col-sm-6 form-group mb-3">
            <label class="col-sm-12 control-label" for="bi_staff_lname">Last Name</label>
            <div class="col-sm-12 input-group">
                <input type="text" name="bi_staff_lname" value="{{ old('bi_staff_lname') }}" placeholder="User Last Name" class="form-control" id="bi_staff_lname">
            </div>
        </div>
    </div>

    <div class="row col-sm-12">
        <div class="col-sm-6 form-group mb-3">
            <label class="col-sm-12 control-label" for="bi_telephone">Telephone</label>
            <div class="col-sm-12 input-group">
               <input type="text" name="bi_telephone" value="{{ old('bi_telephone') }}" placeholder="User Phone Number" class="form-control" id="bi_telephone">
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

        <div class="row col-sm-12 mb-3 mt-3">
            <hr><strong>USER ROLE(s)</strong><hr>
            
            @if(isset($roles) && count($roles) > 0)
                @foreach($roles as $role)
                    @if($role == "admin")
                        @continue;
                    @endif

                    <div class="form-group col-sm-4">
                        <input type="checkbox" name="userRole_{{ $role }}" id="userRole_{{ $role }}">
                        <label class="form-label" for="userRole_{{ $role }}">{{ $role }}</label>
                    </div>

                @endforeach
            @endif
        </div> 

    </div>
</div>