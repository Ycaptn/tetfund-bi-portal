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
<div id="div-institution_id_select_tp" class="form-group mb-3 col-md-6">
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

<!-- Country Field -->
<div id="div-country_id_select_tp" class="form-group mb-3 col-md-6">
    <label for="country_id_select_tp" class="col-sm-11 col-form-label">Country:</label>
    <div class="col-sm-12">
        <select id="country_id_select_tp" class="form-select">
            <option value=''>-- None selected --</option>
            @if(isset($countries))
                @foreach($countries as $cont)
                    <option value='{{ $cont->id }}'> {{$cont->name}}  (  {{$cont->country_code}} ) </option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!-- Name Title Field -->
<div id="div-name_title_tp" class="form-group col-md-4 col-lg-6">
    {{-- <label for="name_title_tp" class="col-sm-11 col-form-label">Name Title:</label> --}}
    <div class="col-sm-12">
        {!! Form::hidden('name_title_tp', null, ['id'=>'name_title_tp', 'class' => 'form-control', 'placeholder'=>'optional field']) !!}
    </div>
</div>

<!-- Name Suffix Field -->
<div id="div-name_suffix_tp" class="form-group col-md-4 col-lg-6">
    {{-- <label for="name_suffix_tp" class="col-sm-12 col-form-label">Name Suffix:</label> --}}
    <div class="col-sm-12">
        {!! Form::hidden('name_suffix_tp', null, ['id'=>'name_suffix_tp', 'class' => 'form-control', 'placeholder'=>'optional field']) !!}
    </div>
</div>

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

<!-- Intl Passport Number Field -->
<div id="div-intl_passport_number_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="intl_passport_number_tp" class="col-sm-11 col-form-label">Intl Passport Number:</label>
    <div class="col-sm-12">
        {!! Form::text('intl_passport_number_tp', null, ['id'=>'intl_passport_number_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- National Id Number Field -->
<div id="div-national_id_number_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="national_id_number_tp" class="col-sm-11 col-form-label">National Id Number:</label>
    <div class="col-sm-12">
        {!! Form::text('national_id_number_tp', null, ['id'=>'national_id_number_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Degree Type Field -->
{{-- <div id="div-degree_type_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="degree_type_tp" class="col-sm-11 col-form-label">Degree Type:</label>
    <div class="col-sm-12">
        {!! Form::text('degree_type_tp', null, ['id'=>'degree_type_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div> --}}

<!-- Program Title Field -->
{{-- <div id="div-program_title_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="program_title_tp" class="col-sm-12 col-form-label">Program Title:</label>
    <div class="col-sm-12">
        {!! Form::text('program_title_tp', null, ['id'=>'program_title_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div> --}}

<!-- Program Type Field -->
{{-- <div id="div-program_type_tp" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="program_type_tp" class="col-sm-12 col-form-label">Program Type:</label>
    <div class="col-sm-12">
        {!! Form::text('program_type_tp', null, ['id'=>'program_type_tp', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div> --}}

<!-- Is Science Program Field -->
{{-- <div id="div-is_science_program_tp" class="form-group mb-3 col-md-4 col-lg-4">
    <label for="is_science_program_tp" class="col-sm-12 col-form-label">Is Science Program ? </label>
    <div class="col-sm-12">
        <select name="is_science_program_tp" id="is_science_program_tp" class="form-select">
            <option value="">-- None selected --</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </div>
</div> --}}

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
<div class="col-sm-12" style="display: none;" id="attachements_info">
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
<div id="div-admission_letter_tp" class="form-group  col-md-4">
    <label for="admission_letter_tp" class="col-sm-11 col-form-label">Admission Letter:</label>
    <div class="col-sm-12">
        <input type="file" id="admission_letter_tp" name="admission_letter_tp" class="form-control">
    </div>
</div>

<!-- health report -->
<div id="div-health_report_tp" class="form-group  col-md-4">
    <label for="health_report_tp" class="col-sm-11 col-form-label">Health Report:</label>
    <div class="col-sm-12">
        <input type="file" id="health_report_tp" name="health_report_tp" class="form-control">
    </div>
</div>

<!-- international passport bio page -->
<div id="div-international_passport_bio_page_tp" class="form-group col-md-6">
    <label for="international_passport_bio_page_tp" class="col-sm-11 col-form-label">Int'l Passport Bio Page:</label>
    <div class="col-sm-12">
        <input type="file" id="international_passport_bio_page_tp" name="international_passport_bio_page_tp" class="form-control">
    </div>
</div>

<!-- conference attendence letter -->
{{-- <div id="div-conference_attendence_letter_tp" class="form-group  col-md-6">
    <label for="conference_attendence_letter_tp" class="col-sm-11 col-form-label">Conference Attendance Letter:</label>
    <div class="col-sm-12">
        <input type="file" id="conference_attendence_letter_tp" name="conference_attendence_letter_tp" class="form-control">
    </div>
</div> --}}

{{-- <!-- Start Fee Amount Field -->
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
<!-- End Total Approved Amount Field --> --}}