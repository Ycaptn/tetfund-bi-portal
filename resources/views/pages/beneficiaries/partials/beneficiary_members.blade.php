{{-- new beneficiary member modal --}}
<div class="modal fade" id="mdl-beneficiary-member-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-beneficiary-modal-title" class="modal-title">Create Beneficiary User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="col-sm-12 alert alert-info">
                    <i class=""><strong>NOTE:</strong> You will be creating a new beneficiary user for <strong>{{ ucwords(strtolower($beneficiary->full_name)); }}</strong></i>
                </div>
                <div id="div-beneficiary-member-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="form-beneficiary-member-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    <div class="row">
                        <div class="col-sm-12 m-3">
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
                        </div>
                    </div>
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-beneficiary-member-modal">
                <button type="button" class="btn btn-primary" id="btn-save-beneficiary-member-modal" value="add">
                    <div id="spinner-beneficiary-member" class="spinner-border text-info" role="status"> 
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Process & Create
                </button>
            </div>

        </div>
    </div>
</div>



{{-- beneficiary members users table --}}
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    S/N
                </th>
                <th>
                    Full Name
                </th>
                <th>
                    Email
                </th>
                <th>
                    Phone
                </th>
                <th>
                    Roles
                </th>
            </tr>
        </thead>
        <tbody>
            @if(isset($beneficiary_members) && count($beneficiary_members) > 0)
                @php
                    $counter = 0;
                @endphp
                @foreach($beneficiary_members as $beneficiary_member)
                    <tr>
                        <td>
                            <b>{{ $counter += 1 }}). </b>  
                        </td>
                        <td>
                            {{ $beneficiary_member->user->fullname }}
                        </td>
                        <td>
                            {{ $beneficiary_member->user->email }}
                        </td>
                        <td>
                            {{ $beneficiary_member->user->telephone }}
                        </td>
                        <td>
                            @php
                                $user_roles = $beneficiary_member->user->roles()->pluck('name')->toArray();
                            @endphp
                            @if(count($user_roles) > 0)
                                @foreach($user_roles as $user_role)
                                    <b>||</b>
                                    {{ucwords($user_role)}}
                                    <b>||</b>
                                    <br>
                                @endforeach
                            @endif
                        </td>
                    </tr> 
                @endforeach
            @endif
        </tbody>
    </table>
</div>