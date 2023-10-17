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
                    <option value="">-- None Selected --</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
        </div> 
        <div class="col-sm-6 form-group mb-3">
            <label class="col-sm-12 control-label" for="bi_grade_level">Grade LeveL</label>
            <div class="col-sm-12 input-group">
                <select class="form-select" name="bi_grade_level" id="bi_grade_level">
                    <option value="">--- Select Grade Level ---</option>
                    <option value="6">GL-6</option>
                    <option value="7">GL-7</option>
                    <option value="8">GL-8</option>
                    <option value="9">GL-9</option>
                    <option value="10">GL-10</option>
                    <option value="11">GL-11</option>
                    <option value="12">GL-12</option>
                    <option value="13">GL-13</option>
                    <option value="14">GL-14</option>
                    <option value="15">GL-15</option>
                    <option value="16">GL-16</option>
                    <option value="17">GL-17</option>
                </select>
            </div>
        </div>

        <div class="col-sm-6 form-group mb-3">
            <label class="col-sm-12 control-label" for="bi_member_type">User Type</label>
            <div class="col-sm-12 input-group">
                <select class="form-select" name="bi_member_type" id="bi_member_type">
                    <option value="">--- Select Type ---</option>
                    <option value="academic">Academic</option>
                    <option value="non-academic">Non-Academic</option>
                </select>
            </div>
        </div>

        <div class="col-sm-6 form-group mb-3" id="div_bi_academic_member_level" style="display:none;">
            <label class="col-sm-12 control-label" for="bi_academic_member_level">Academic Staff Level</label>
            <div class="col-sm-12 input-group">
                <select class="form-select" name="bi_academic_member_level" id="bi_academic_member_level">
                    <option value="">-- None Selected --</option>
                    <option value="chief_lecturer">Chief Lecturer</option>
                    <option value="principal_lecturer">Principal Lecturer</option>
                    <option value="senior_lecturer">Senior Lecturer</option>
                    <option value="lecturer_1">Lecturer 1</option>
                    <option value="lecturer_2">Lecturer 2</option>
                    <option value="lecturer_3">Lecturer 3</option>
                </select>
            </div>
        </div> 
        @if($current_user->hasRole('BI-desk-officer'))
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
        @endif

        @if($current_user->hasRole('BI-astd-desk-officer') && !$current_user->hasRole('BI-desk-officer'))
           {{--  <div class="row col-sm-12 mb-3 mt-3">
                <hr><strong>USER ROLE(s)</strong><hr>
                
                <div class="form-group col-sm-4"> --}}
                    <input type="checkbox" class="d-none" name="userRole_BI-staff" id="userRole_BI-staff" checked>
                   {{--  <label class="form-label" for="userRole_BI-staff">BI-staff</label> --}}
            {{--     </div>

            </div> --}} 
        @endif

    </div>
</div>