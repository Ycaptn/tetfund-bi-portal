<!-- Email Field -->
<div id="div-email" class="form-group mb-3 col-md-6">
    <label for="email" class="col-sm-11 col-form-label">Email:</label>
    <div class="col-sm-12">
        {!! Form::text('email', $nominationRequest->user->email ?? '', ['id'=>'email', 'class' => 'form-control', 'disabled'=>'disabled']) !!}
    </div>
</div>

<!-- Telephone Field -->
<div id="div-telephone" class="form-group mb-3 col-md-6">
    <label for="telephone" class="col-sm-11 col-form-label">Telephone:</label>
    <div class="col-sm-12">
        {!! Form::text('telephone', $nominationRequest->user->telephone ?? '', ['id'=>'telephone', 'class' => 'form-control', 'disabled'=>'disabled']) !!}
    </div>
</div>

<!-- Beneficiary Institution Field -->
<div id="div-beneficiary_institution_id" class="form-group mb-3">
    <label for="beneficiary_institution_id" class="col-sm-11 col-form-label">Beneficiary Institution:</label>
    <div class="col-sm-12">
        <select id="beneficiary_institution_id_select" class="form-select" disabled='disabled'>
            <option value=''>-- None selected --</option>
            @if(isset($beneficiaries))
                @foreach($beneficiaries as $benef)
                    @if($benef->id == $beneficiary->id)
                        <option selected='selected' value='{{ $benef->id }}'> {{$benef->full_name}}  (  {{$benef->short_name}} ) </option>
                    @else
                        <option value='{{ $benef->id }}'> {{$benef->full_name}}  (  {{$benef->short_name}} ) </option>
                    @endif
                @endforeach
            @endif
        </select>
    </div>
</div>

<!-- Institution Field -->
<div id="div-institution_id" class="form-group mb-3 col-md-6">
    <label for="institution_id" class="col-sm-11 col-form-label">Institution:</label>
    <div class="col-sm-12">
        <select id="institution_id_select" class="form-select">
            <option value=''>-- None selected --</option>
            @if(isset($institutions))
                @foreach($institutions as $institute)
                    <option value='{{ $institute->id }}'> {{$institute->name}} </option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!-- Country Field -->
<div id="div-country_id" class="form-group mb-3 col-md-6">
    <label for="country_id" class="col-sm-11 col-form-label">Country:</label>
    <div class="col-sm-12">
        <select id="country_id_select" class="form-select">
            <option value=''>-- None selected --</option>
            @if(isset($countries))
                @foreach($countries as $cont)
                    <option value='{{ $cont->id }}'> {{$cont->name}}  (  {{$cont->country_code}} ) </option>
                @endforeach
            @endif
        </select>
    </div>
</div>


<!-- Gender Field -->
<div id="div-gender" class="form-group mb-3 col-md-4">
    <label for="gender" class="col-sm-11 col-form-label">Gender:</label>
     @php
        $gender = strtolower($nominationRequest->user->gender ?? '');
        $m = $f = $n = '';
        if($gender == 'male') {
            $m = "selected='selected'";
        } elseif ($gender == 'female') {
            $f = "selected='selected'";
        } else {
            $n = "selected='selected'";
        }
    @endphp
    <div class="col-sm-12">
        <select class="form-select" id="gender" name="gender" disabled='disabled'>
            <option value="" {{ $n }}>-- None selected --</option>
            <option value="Male" {{ $m }}>Male</option>
            <option value="Female" {{ $f }}>Female</option>
        </select>
    </div>
</div>

<!-- Name Title Field -->
<div id="div-name_title" class="form-group mb-3 col-md-4">
    <label for="name_title" class="col-sm-11 col-form-label">Name Title:</label>
    <div class="col-sm-12">
        {!! Form::text('name_title', null, ['id'=>'name_title', 'class' => 'form-control', 'placeholder'=>'optional field']) !!}
    </div>
</div>

<!-- Name Suffix Field -->
<div id="div-name_suffix" class="form-group mb-3 col-md-4">
    <label for="name_suffix" class="col-sm-12 col-form-label">Name Suffix:</label>
    <div class="col-sm-12">
        {!! Form::text('name_suffix', null, ['id'=>'name_suffix', 'class' => 'form-control', 'placeholder'=>'optional field']) !!}
    </div>
</div>

<!-- First Name Field -->
<div id="div-first_name" class="form-group mb-3 col-md-4">
    <label for="first_name" class="col-sm-11 col-form-label">First Name:</label>
    <div class="col-sm-12">
        {!! Form::text('first_name', $nominationRequest->user->first_name ?? '', ['id'=>'first_name', 'class' => 'form-control', 'placeholder'=>'required field', 'disabled'=>'disabled']) !!}
    </div>
</div>

<!-- Middle Name Field -->
<div id="div-middle_name" class="form-group mb-3 col-md-4">
    <label for="middle_name" class="col-sm-11 col-form-label">Middle Name:</label>
    <div class="col-sm-12">
        {!! Form::text('middle_name', $nominationRequest->user->middle_name ?? '', ['id'=>'middle_name', 'class' => 'form-control', 'placeholder'=>'optional field', 'disabled'=>'disabled']) !!}
    </div>
</div>

<!-- Last Name Field -->
<div id="div-last_name" class="form-group mb-3 col-md-4">
    <label for="last_name" class="col-sm-11 col-form-label">Last Name:</label>
    <div class="col-sm-12">
        {!! Form::text('last_name', $nominationRequest->user->last_name ?? '', ['id'=>'last_name', 'class' => 'form-control', 'placeholder'=>'required field', 'disabled'=>'disabled']) !!}
    </div>
</div>

<!-- Bank Account Name Field -->
<div id="div-bank_account_name" class="form-group mb-3 col-md-6">
    <label for="bank_account_name" class="col-sm-11 col-form-label">Bank Account Name:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_account_name', null, ['id'=>'bank_account_name', 'class' => 'form-control','minlength' => 2,'maxlength' => 100, 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Account Number Field -->
<div id="div-bank_account_number" class="form-group mb-3 col-md-6">
    <label for="bank_account_number" class="col-sm-11 col-form-label">Bank Account Number:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_account_number', null, ['id'=>'bank_account_number', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Name Field -->
<div id="div-bank_name" class="form-group mb-3 col-md-6">
    <label for="bank_name" class="col-sm-11 col-form-label">Bank Name:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_name', null, ['id'=>'bank_name', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Sort Code Field -->
<div id="div-bank_sort_code" class="form-group mb-3 col-md-6">
    <label for="bank_sort_code" class="col-sm-11 col-form-label">Bank Sort Code:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_sort_code', null, ['id'=>'bank_sort_code', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Intl Passport Number Field -->
<div id="div-intl_passport_number" class="form-group mb-3 col-md-6">
    <label for="intl_passport_number" class="col-sm-11 col-form-label">Intl Passport Number:</label>
    <div class="col-sm-12">
        {!! Form::text('intl_passport_number', null, ['id'=>'intl_passport_number', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Verification Number Field -->
<div id="div-bank_verification_number" class="form-group mb-3 col-md-6">
    <label for="bank_verification_number" class="col-sm-11 col-form-label">Bank Verification Number:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_verification_number', null, ['id'=>'bank_verification_number', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- National Id Number Field -->
<div id="div-national_id_number" class="form-group mb-3 col-md-6">
    <label for="national_id_number" class="col-sm-11 col-form-label">National Id Number:</label>
    <div class="col-sm-12">
        {!! Form::text('national_id_number', null, ['id'=>'national_id_number', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Degree Type Field -->
<div id="div-degree_type" class="form-group mb-3 col-md-6">
    <label for="degree_type" class="col-sm-11 col-form-label">Degree Type:</label>
    <div class="col-sm-12">
        {!! Form::text('degree_type', null, ['id'=>'degree_type', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Program Title Field -->
<div id="div-program_title" class="form-group mb-3 col-md-6">
    <label for="program_title" class="col-sm-12 col-form-label">Program Title:</label>
    <div class="col-sm-12">
        {!! Form::text('program_title', null, ['id'=>'program_title', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Program Type Field -->
<div id="div-program_type" class="form-group mb-3 col-md-6">
    <label for="program_type" class="col-sm-12 col-form-label">Program Type:</label>
    <div class="col-sm-12">
        {!! Form::text('program_type', null, ['id'=>'program_type', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Is Science Program Field -->
<div id="div-is_science_program" class="form-group mb-3 col-md-4">
    <label for="is_science_program" class="col-sm-12 col-form-label">Is Science Program ? </label>
    <div class="col-sm-12">
        <select name="is_science_program" id="is_science_program" class="form-control">
            <option value="">-- None selected --</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </div>
</div>

<!-- Program Start Date Field -->
<div id="div-program_start_date" class="form-group mb-3 col-md-4">
    <label for="program_start_date" class="col-sm-12 col-form-label">Program Start Date:</label>
    <div class="col-sm-12">
        {!! Form::date('program_start_date', null, ['id'=>'program_start_date', 'class' => 'form-control']) !!}
    </div>
</div>

<!-- Program End Date Field -->
<div id="div-program_end_date" class="form-group mb-3 col-md-4">
    <label for="program_end_date" class="col-sm-12 col-form-label">Program End Date:</label>
    <div class="col-sm-12">
        {!! Form::date('program_end_date', null, ['id'=>'program_end_date', 'class' => 'form-control']) !!}
    </div>
</div>

<!-- Start Fee Amount Field -->
<div id="div-fee_amount" class="form-group mb-3 col-md-6">
    <label for="fee_amount" class="col-sm-12 col-form-label">Fee Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('fee_amount', null, ['id'=>'fee_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Fee Amount Field -->

<!-- Start Tuition Amount Field -->
<div id="div-tuition_amount" class="form-group mb-3 col-md-6">
    <label for="tuition_amount" class="col-sm-11 col-form-label">Tuition Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('tuition_amount', null, ['id'=>'tuition_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Tuition Amount Field -->

<!-- Start Upgrade Fee Amount Field -->
<div id="div-upgrade_fee_amount" class="form-group mb-3 col-md-6">
    <label for="upgrade_fee_amount" class="col-sm-11 col-form-label">Upgrade Fee Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('upgrade_fee_amount', null, ['id'=>'upgrade_fee_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Upgrade Fee Amount Field -->

<!-- Start Stipend Amount Field -->
<div id="div-stipend_amount" class="form-group mb-3 col-md-6">
    <label for="stipend_amount" class="col-sm-11 col-form-label">Stipend Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('stipend_amount', null, ['id'=>'stipend_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Stipend Amount Field -->

<!-- Start Passage Amount Field -->
<div id="div-passage_amount" class="form-group mb-3 col-md-6">
    <label for="passage_amount" class="col-sm-11 col-form-label">Passage Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('passage_amount', null, ['id'=>'passage_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Passage Amount Field -->

<!-- Start Medical Amount Field -->
<div id="div-medical_amount" class="form-group mb-3 col-md-6">
    <label for="medical_amount" class="col-sm-11 col-form-label">Medical Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('medical_amount', null, ['id'=>'medical_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Medical Amount Field -->

<!-- Start Warm Clothing Amount Field -->
<div id="div-warm_clothing_amount" class="form-group mb-3 col-md-6">
    <label for="warm_clothing_amount" class="col-sm-11 col-form-label">Warm Clothing Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('warm_clothing_amount', null, ['id'=>'warm_clothing_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Warm Clothing Amount Field -->

<!-- Start Study Tours Amount Field -->
<div id="div-study_tours_amount" class="form-group mb-3 col-md-6">
    <label for="study_tours_amount" class="col-sm-11 col-form-label">Study Tours Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('study_tours_amount', null, ['id'=>'study_tours_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Study Tours Amount Field -->

<!-- Start Education Materials Amount Field -->
<div id="div-education_materials_amount" class="form-group mb-3 col-md-6">
    <label for="education_materials_amount" class="col-sm-11 col-form-label">Education Materials Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('education_materials_amount', null, ['id'=>'education_materials_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Education Materials Amount Field -->

<!-- Start Thesis Research Amount Field -->
<div id="div-thesis_research_amount" class="form-group mb-3 col-md-6">
    <label for="thesis_research_amount" class="col-sm-11 col-form-label">Thesis Research Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('thesis_research_amount', null, ['id'=>'thesis_research_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Thesis Research Amount Field -->

<!-- Final Remarks Field -->
<div id="div-final_remarks" class="form-group mb-3">
    <label for="final_remarks" class="col-sm-11 col-form-label">Final Remarks:</label>
    <div class="col-sm-12">
        {!! Form::text('final_remarks', null, ['id'=>'final_remarks', 'class' => 'form-control', 'placeholder'=>'optional field']) !!}
    </div>
</div>

<!-- Start Total Requested Amount Field -->
<div id="div-total_requested_amount" class="form-group mb-3 col-md-6">
    <label for="total_requested_amount" class="col-sm-11 col-form-label">Total Requested Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('total_requested_amount', null, ['id'=>'total_requested_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Total Requested Amount Field -->

<!-- Start Total Approved Amount Field -->
<div id="div-total_approved_amount" class="form-group mb-3 col-md-6">
    <label for="total_approved_amount" class="col-sm-11 col-form-label">Total Approved Amount:</label>
    <div class="col-sm-12">
        {!! Form::text('total_approved_amount', null, ['id'=>'total_approved_amount', 'class' => 'form-control','min' => 0,'max' => 100000000, 'placeholder'=>'optional field']) !!}
    </div>
</div>
<!-- End Total Approved Amount Field -->