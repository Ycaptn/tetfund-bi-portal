{{-- input fields default values --}}
@php
    //first_name
    $first_name = (isset($nominationRequest->user->first_name) ? $nominationRequest->user->first_name : ($current_user->hasRole('bi-staff') ? $current_user->first_name : '') );
    $middle_name = (isset($nominationRequest->user->middle_name) ? $nominationRequest->user->middle_name : ($current_user->hasRole('bi-staff') ? $current_user->middle_name : '') );
    $last_name = (isset($nominationRequest->user->last_name) ? $nominationRequest->user->last_name : ($current_user->hasRole('bi-staff') ? $current_user->last_name : '') );
    $email = (isset($nominationRequest->user->email) ? $nominationRequest->user->email : ($current_user->hasRole('bi-staff') ? $current_user->email : '') );
    $telephone = (isset($nominationRequest->user->telephone) ? $nominationRequest->user->telephone : ($current_user->hasRole('bi-staff') ? $current_user->telephone : '') );
    $gender = (isset($nominationRequest->user->gender) ? $nominationRequest->user->gender : ($current_user->hasRole('bi-staff') ? $current_user->gender : '') );
    $gender = (isset($nominationRequest->user->gender) ? $nominationRequest->user->gender : ($current_user->hasRole('bi-staff') ? $current_user->gender : '') );

@endphp


{{-- nomination request nominee relevant information div --}}
<div id="user_info_section" class="form-group row col-sm-12">
    <div class="col-sm-12 col-md-8 col-lg-3 mb-3">
        <label class="col-sm-12"><b>Beneficiary Institution:</b></label>
        <div class="col-sm-12 ">
            <!-- Beneficiary Institution Field -->
            {!! Form::hidden('beneficiary_institution_id_select', $beneficiary->id ?? '', ['id'=>'beneficiary_institution_id_select_tp', 'class'=>'form-control', 'disabled'=>'disabled']) !!}

            <i class="beneficiary_institution_id_select">
                {{$beneficiary->full_name ?? ''}}
                {{(isset($beneficiary->short_name) && !empty($beneficiary->short_name)) ? '('.strtoupper($beneficiary->short_name).')' : ''}}
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
        <label class="col-sm-12"><b>Fullname:</b></label>
        <div class="col-sm-12 ">
            <!-- First Name Field -->
            {!! Form::hidden('first_name_tp', $first_name, ['id'=>'first_name_tp', 'class'=>'form-control', 'placeholder'=>'required field', 'disabled'=>'disabled']) !!}

            <!-- Middle Name Field -->
            {!! Form::hidden('middle_name_tp', $nominationRequest->user->middle_name ?? '', ['id'=>'middle_name_tp', 'class'=>'form-control middle_name', 'placeholder'=>'optional field', 'disabled'=>'disabled']) !!}

            <!-- Last Name Field -->
            {!! Form::hidden('last_name_tp', $last_name, ['id'=>'last_name_tp', 'class'=>'form-control', 'placeholder'=>'required field', 'disabled'=>'disabled']) !!}

            <i class="full_name">
                {{$first_name}}
                {{$middle_name}}
                {{$last_name}}
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
        <label class="col-sm-12"><b>Email:</b></label>
        <div class="col-sm-12 ">
            <!-- Email Field -->
            {!! Form::hidden('email_tp', $email, ['id'=>'email_tp', 'class'=>'form-control', 'disabled'=>'disabled']) !!}

            <i class="email">
                {{$email}}
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
        <label class="col-sm-12"><b>Telephone:</b></label>
        <div class="col-sm-12 ">
            <!-- Telephone Field -->
            {!! Form::hidden('telephone_tp', $telephone, ['id'=>'telephone_tp', 'class'=>'form-control', 'disabled'=>'disabled']) !!}
            
            <i class="telephone">
                {{$telephone}}
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
        <label class="col-sm-12"><b>Gender:</b></label>
        <div class="col-sm-12 ">
            <!-- Gender Field -->
            {!! Form::hidden('gender_tp', $gender, ['id'=>'gender_tp', 'class'=>'form-control', 'disabled'=>'disabled']) !!}
            
            <i class="gender">
                {{ ucfirst($gender) }}                
            </i>
        </div>
    </div>

</div>
<hr>

<!-- Institution Field -->
<div id="div-institution_id_select_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="institution_id_select_tp" class="col-sm-11 col-form-label">Institution:</label>
    <div class="col-sm-12">
        <select id="institution_id_select_tp" class="form-select">
            <option value=''>-- None selected --</option>
            @if(isset($institutions))
                @foreach($institutions as $institute)
                    <option value='{{ $institute->id }}'> {{$institute->name}} </option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!-- Name Title Field -->
<div id="div-name_title_tp" class="form-group col-md-6 col-lg-4">
    <label for="name_title_tp" class="col-sm-11 col-form-label">Rank/GL. Equivalent:</label>
    <div class="col-sm-12">
        <select class="form-select" name="name_title_tp" id="name_title_tp" >
            <option value=''>-- None Selected--</option>
            <option value='chief lecturer'>Chief Lecturer</option>
            <option value='prin lecturer'>Prin Lecturer</option>
            <option value='senior lecturer'>Senior Lecturer</option>
            <option value='lecturer 1'>Lecturer 1</option>
            <option value='lecturer 2'>Lecturer 2</option>
        </select>
    </div>
</div>

{!! Form::hidden('name_suffix_tp', null, ['id'=>'name_suffix_tp', 'class' => 'form-control', 'placeholder'=>'optional field']) !!}

<!-- Bank Account Name Field -->
<div id="div-bank_account_name_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_account_name_tp" class="col-sm-11 col-form-label">Bank Account Name:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_account_name_tp', null, ['id'=>'bank_account_name_tp', 'class' => 'form-control','minlength' => 2,'maxlength' => 100, 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Account Number Field -->
<div id="div-bank_account_number_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_account_number_tp" class="col-sm-11 col-form-label">Bank Account Number:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_account_number_tp', null, ['id'=>'bank_account_number_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Name Field -->
<div id="div-bank_name_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_name_tp" class="col-sm-11 col-form-label">Bank Name:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_name_tp', null, ['id'=>'bank_name_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Sort Code Field -->
<div id="div-bank_sort_code_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_sort_code_tp" class="col-sm-11 col-form-label">Bank Sort Code:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_sort_code_tp', null, ['id'=>'bank_sort_code_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Verification Number Field -->
<div id="div-bank_verification_number_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_verification_number_tp" class="col-sm-11 col-form-label">Bank Verification Number:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_verification_number_tp', null, ['id'=>'bank_verification_number_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- National Id Number Field -->
<div id="div-national_id_number_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="national_id_number_tp" class="col-sm-11 col-form-label">National Id Number:</label>
    <div class="col-sm-12">
        {!! Form::text('national_id_number_tp', null, ['id'=>'national_id_number_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Program Start Date Field -->
<div id="div-program_start_date_tp" class="form-group mb-3 col-md-4 col-lg-4">
    <label for="program_start_date_tp" class="col-sm-12 col-form-label">Program Start Date:</label>
    <div class="col-sm-12">
        {!! Form::date('program_start_date_tp', null, ['id'=>'program_start_date_tp', 'class' => 'form-control']) !!}
    </div>
</div>

<!-- Program End Date Field -->
<div id="div-program_end_date_tp" class="form-group mb-3 col-md-4 col-lg-4">
    <label for="program_end_date_tp" class="col-sm-12 col-form-label">Program End Date:</label>
    <div class="col-sm-12">
        {!! Form::date('program_end_date_tp', null, ['id'=>'program_end_date_tp', 'class' => 'form-control']) !!}
    </div>
</div>

<hr>
<div class="col-sm-12" style="display: none;" id="attachements_info_tp">
    <small>
        <i class="text-danger">
            <strong>NOTE:</strong>
            Uploading an attachement will authomatically replace older attachement provided initially. 
        </i>
    </small>
</div>
<!-- passport photo -->
<div id="div-passport_photo_tp" class="form-group  col-md-4">
    <label for="passport_photo_tp" class="col-sm-11 col-form-label">Passport Photo:</label>
    <div class="col-sm-12">
        <input type="file" id="passport_photo_tp" name="passport_photo_tp" class="form-control">
    </div>
</div>

<!-- admission letter -->
<div id="div-invitation_letter_tp" class="form-group  col-md-4">
    <label for="invitation_letter_tp" class="col-sm-11 col-form-label">Invitation Letter:</label>
    <div class="col-sm-12">
        <input type="file" id="invitation_letter_tp" name="invitation_letter_tp" class="form-control">
    </div>
</div>