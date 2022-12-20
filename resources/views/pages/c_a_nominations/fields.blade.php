{{-- input fields default values --}}
@php
    //first_name
    $first_name = (isset($nominationRequest->user->first_name) ? $nominationRequest->user->first_name : ($current_user->hasRole('BI-staff') ? $current_user->first_name : '') );
    $middle_name = (isset($nominationRequest->user->middle_name) ? $nominationRequest->user->middle_name : ($current_user->hasRole('BI-staff') ? $current_user->middle_name : '') );
    $last_name = (isset($nominationRequest->user->last_name) ? $nominationRequest->user->last_name : ($current_user->hasRole('BI-staff') ? $current_user->last_name : '') );
    $email = (isset($nominationRequest->user->email) ? $nominationRequest->user->email : ($current_user->hasRole('BI-staff') ? $current_user->email : '') );
    $telephone = (isset($nominationRequest->user->telephone) ? $nominationRequest->user->telephone : ($current_user->hasRole('BI-staff') ? $current_user->telephone : '') );
    $gender = (isset($nominationRequest->user->gender) ? $nominationRequest->user->gender : ($current_user->hasRole('BI-staff') ? $current_user->gender : '') );
    $gender = (isset($nominationRequest->user->gender) ? $nominationRequest->user->gender : ($current_user->hasRole('BI-staff') ? $current_user->gender : '') );

@endphp


{{-- nomination request nominee relevant information div --}}
<div id="user_info_section" class="form-group row col-sm-12">
    <div class="col-sm-12 col-md-8 col-lg-3 mb-3">
        <label class="col-sm-12"><b>Beneficiary Institution:</b></label>
        <div class="col-sm-12 ">
            <!-- Beneficiary Institution Field -->
            {!! Form::hidden('beneficiary_institution_id_select', $beneficiary->id ?? '', ['id'=>'beneficiary_institution_id_select_ca', 'class'=>'form-control', 'disabled'=>'disabled']) !!}

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
            {!! Form::hidden('first_name_ca', $first_name, ['id'=>'first_name_ca', 'class'=>'form-control', 'placeholder'=>'required field', 'disabled'=>'disabled']) !!}

            <!-- Middle Name Field -->
            {!! Form::hidden('middle_name_ca', $nominationRequest->user->middle_name ?? '', ['id'=>'middle_name_ca', 'class'=>'form-control', 'placeholder'=>'optional field', 'disabled'=>'disabled']) !!}

            <!-- Last Name Field -->
            {!! Form::hidden('last_name_ca', $last_name, ['id'=>'last_name_ca', 'class'=>'form-control', 'placeholder'=>'required field', 'disabled'=>'disabled']) !!}

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
            {!! Form::hidden('email_ca', $email, ['id'=>'email_ca', 'class'=>'form-control', 'disabled'=>'disabled']) !!}

            <i class="email">
                {{$email}}
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
        <label class="col-sm-12"><b>Telephone:</b></label>
        <div class="col-sm-12 ">
            <!-- Telephone Field -->
            {!! Form::hidden('telephone_ca', $telephone, ['id'=>'telephone_ca', 'class'=>'form-control', 'disabled'=>'disabled']) !!}
            
            <i class="telephone">
                {{$telephone}}
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
        <label class="col-sm-12"><b>Gender:</b></label>
        <div class="col-sm-12 ">
            <!-- Gender Field -->
            {!! Form::hidden('gender_ca', $gender, ['id'=>'gender_ca', 'class'=>'form-control', 'disabled'=>'disabled']) !!}
            
            <i class="gender">
                {{ ucfirst($gender) }}                
            </i>
        </div>
    </div>

</div>
<hr>

<!-- Country Field -->
<div id="div-country_id_ca" class="form-group mb-3 col-md-6">
    <label for="country_id_ca" class="col-sm-11 col-form-label">Country:</label>
    <div class="col-sm-12">
        <select id="country_id_select_ca" class="form-select">
            <option value=''>-- None selected --</option>
            @if(isset($countries))
                @foreach($countries as $cont)
                    <option value='{{ $cont->id }}'> {{$cont->name}}  (  {{$cont->country_code}} ) </option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!-- Conference Field -->
<div id="div-conference_id_ca" class="form-group mb-3 col-md-6">
    <label for="conference_id_ca" class="col-sm-11 col-form-label">Conference:</label>
    <div class="col-sm-12">
        <select id="conference_id_select_ca" class="form-select">
            <option value=''>-- None selected --</option>
        </select>
    </div>
</div>



<!-- Bank Account Name Field -->
<div id="div-bank_account_name_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_account_name_ca" class="col-sm-11 col-form-label">Bank Account Name:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_account_name_ca', null, ['id'=>'bank_account_name_ca', 'class' => 'form-control','minlength' => 2,'maxlength' => 100, 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Account Number Field -->
<div id="div-bank_account_number_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_account_number_ca" class="col-sm-11 col-form-label">Bank Account Number:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_account_number_ca', null, ['id'=>'bank_account_number_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Name Field -->
<div id="div-bank_name_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_name_ca" class="col-sm-11 col-form-label">Bank Name:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_name_ca', null, ['id'=>'bank_name_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Sort Code Field -->
<div id="div-bank_sort_code_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_sort_code_ca" class="col-sm-11 col-form-label">Bank Sort Code:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_sort_code_ca', null, ['id'=>'bank_sort_code_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Verification Number Field -->
<div id="div-bank_verification_number_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_verification_number_ca" class="col-sm-11 col-form-label">Bank Verification Number:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_verification_number_ca', null, ['id'=>'bank_verification_number_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Intl Passport Number Field -->
<div id="div-intl_passport_number_ca" class="form-group mb-3 col-md-6 col-lg-4" style="display: none;">
    <label for="intl_passport_number_ca" class="col-sm-11 col-form-label">Intl Passport Number:</label>
    <div class="col-sm-12">
        {!! Form::text('intl_passport_number_ca', null, ['id'=>'intl_passport_number_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- National Id Number Field -->
<div id="div-national_id_number_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="national_id_number_ca" class="col-sm-11 col-form-label">National Id Number:</label>
    <div class="col-sm-12">
        {!! Form::text('national_id_number_ca', null, ['id'=>'national_id_number_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Organizer Name Field -->
<div id="div-organizer_name_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="organizer_name_ca" class="col-sm-11 col-form-label">Organizer Name:</label>
    <div class="col-sm-12">
        {!! Form::text('organizer_name_ca', null, ['id'=>'organizer_name_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Conference Theme Field -->
<div id="div-conference_theme_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="conference_theme_ca" class="col-sm-11 col-form-label">Conference Theme:</label>
    <div class="col-sm-12">
        {!! Form::text('conference_theme_ca', null, ['id'=>'conference_theme_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Attendee Department Name Field -->
<div id="div-attendee_department_name_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="attendee_department_name_ca" class="col-sm-11 col-form-label">Attendee Department Name:</label>
    <div class="col-sm-12">
        {!! Form::text('attendee_department_name_ca', null, ['id'=>'attendee_department_name_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Attendee Grade Level Field -->
<div id="div-attendee_grade_level_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="attendee_grade_level_ca" class="col-sm-11 col-form-label">Attendee Rank/GL. Equivalent:</label>
    <div class="col-sm-12">
        <select class="form-select" name="attendee_grade_level_ca" id="attendee_grade_level_ca" >
            <option value=''>-- None Selected--</option>
            <option value='chief_lecturer'>Chief Lecturer</option>
            <option value='principal_lecturer'>Principal Lecturer</option>
            <option value='senior_lecturer'>Senior Lecturer</option>
            <option value='lecturer_1'>Lecturer 1</option>
            <option value='lecturer_2'>Lecturer 2</option>
        </select>
    </div>
</div>

<!-- Has Paper Presentation Field -->
<div id="div-has_paper_presentation_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="has_paper_presentation_ca" class="col-sm-11 col-form-label">Any Paper Presentation ?</label>
    <div class="col-sm-12">
        <select name="has_paper_presentation_ca" id="has_paper_presentation_ca" class="form-control">
            <option value="">-- None selected --</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </div>
</div>

<!-- Accepted Paper Title Field -->
<div id="div-accepted_paper_title_ca" class="form-group mb-3 col-md-6 col-lg-4" style="display: none;">
    <label for="accepted_paper_title_ca" class="col-sm-11 col-form-label">Accepted Paper Title:</label>
    <div class="col-sm-12">
        {!! Form::text('accepted_paper_title_ca', null, ['id'=>'accepted_paper_title_ca', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Is Academic Program Field -->
<div id="div-is_academic_staff_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="is_academic_staff_ca" class="col-sm-11 col-form-label">Staff Type: </label>
    <div class="col-sm-12">
        <select name="is_academic_staff_ca" id="is_academic_staff_ca" class="form-control">
            <option value="">-- None selected --</option>
            <option value="1">Academic Staff</option>
            <option value="0">None Academic Staff</option>
        </select>
    </div>
</div>

<!-- Conference Start Date Field -->
<div id="div-conference_start_date_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="conference_start_date_ca" class="col-sm-11 col-form-label">Conference Start Date:</label>
    <div class="col-sm-12">
        {!! Form::date('conference_start_date_ca', null, ['id'=>'conference_start_date_ca', 'class' => 'form-control']) !!}
    </div>
</div>

<!-- Conference End Date Field -->
<div id="div-conference_end_date_ca" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="conference_end_date_ca" class="col-sm-11 col-form-label">Conference End Date:</label>
    <div class="col-sm-12">
        {!! Form::date('conference_end_date_ca', null, ['id'=>'conference_end_date_ca', 'class' => 'form-control']) !!}
    </div>
</div>

<hr>
<div class="col-sm-12" style="display: none;" id="attachments_info_ca">
    <small>
        <i class="text-danger">
            <strong>NOTE:</strong>
            Uploading an attachment will authomatically replace older attachment provided initially. 
        </i>
    </small>
</div>

<!-- passport photo -->
<div id="div-passport_photo_ca" class="form-group  col-md-4">
    <label for="passport_photo_ca" class="col-sm-11 col-form-label">Passport Photo:</label>
    <div class="col-sm-12">
        <input type="file" id="passport_photo_ca" name="passport_photo_ca" class="form-control">
    </div>
</div>

<!-- admission letter -->
<div id="div-conference_attendance_letter_ca" class="form-group  col-md-4">
    <label for="conference_attendance_letter_ca" class="col-sm-11 col-form-label">Conference Attendance Letter:</label>
    <div class="col-sm-12">
        <input type="file" id="conference_attendance_letter_ca" name="conference_attendance_letter_ca" class="form-control">
    </div>
</div>

<!-- paper presentation -->
<div id="div-paper_presentation_ca" class="form-group col-md-4" style="display: none;">
    <label for="paper_presentation_ca" class="col-sm-11 col-form-label">Presentaion paper:</label>
    <div class="col-sm-12">
        <input type="file" id="paper_presentation_ca" name="paper_presentation_ca" class="form-control">
    </div>
</div>

<!-- international passport bio page -->
<div id="div-international_passport_bio_page_ca" class="form-group col-md-4" style="display: none;">
    <label for="international_passport_bio_page_ca" class="col-sm-11 col-form-label">Int'l Passport Bio Page:</label>
    <div class="col-sm-12">
        <input type="file" id="international_passport_bio_page_ca" name="international_passport_bio_page_ca" class="form-control">
    </div>
</div>