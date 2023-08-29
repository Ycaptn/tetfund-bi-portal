<!-- Email Field -->
<div id="div_cANomination_email" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('email', 'Email:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_email">
        @if (isset($cANomination->email) && empty($cANomination->email)==false)
            {!! $cANomination->email !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Telephone Field -->
<div id="div_cANomination_telephone" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('telephone', 'Telephone:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_telephone">
        @if (isset($cANomination->telephone) && empty($cANomination->telephone)==false)
            {!! $cANomination->telephone !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Beneficiary Institution Field -->
<div id="div_cANomination_beneficiary_institution_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('beneficiary_institution_id', 'Beneficiary Institution:', ['class'=>'control-label']) !!}
        </strong><br>        
        <span id="spn_cANomination_beneficiary_institution_name">
        @if (isset($cANomination->beneficiary_institution_id) && empty($cANomination->beneficiary)==false)
            {!! $cANomination->beneficiary->full_name !!}
            {!! $cANomination->beneficiary->short_name ? '('.$cANomination->beneficiary->short_name.')' : '' !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- country Name Field -->
<div id="div_cANomination_country_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('country_id', 'Country:', ['class'=>'control-label']) !!}
        </strong><br>        
        <span id="spn_cANomination_country_name">
        @if (isset($cANomination->country_id) && empty($cANomination->country_id)==false)
            {!! $cANomination->country->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Conference Field -->
<div id="div_cANomination_conference_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('conference_id', 'Conference:', ['class'=>'control-label']) !!}
        </strong><br>        
        <span id="spn_cANomination_conference_name">
        @if (isset($cANomination->conference_id) && empty($cANomination->conference)==false)
            {!! $cANomination->conference->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Gender Field -->
<div id="div_cANomination_gender" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('gender', 'Gender:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_gender">
        @if (isset($cANomination->gender) && empty($cANomination->gender)==false)
            {!! $cANomination->gender !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- First Name Field -->
<div id="div_cANomination_first_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('first_name', 'First Name:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_first_name">
        @if (isset($cANomination->first_name) && empty($cANomination->first_name)==false)
            {!! $cANomination->first_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Middle Name Field -->
<div id="div_cANomination_middle_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('middle_name', 'Middle Name:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_middle_name">
        @if (isset($cANomination->middle_name) && empty($cANomination->middle_name)==false)
            {!! $cANomination->middle_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Last Name Field -->
<div id="div_cANomination_last_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('last_name', 'Last Name:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_last_name">
        @if (isset($cANomination->last_name) && empty($cANomination->last_name)==false)
            {!! $cANomination->last_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Name Field -->
<div id="div_cANomination_bank_account_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_name', 'Bank Account Name:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_bank_account_name">
        @if (isset($cANomination->bank_account_name) && empty($cANomination->bank_account_name)==false)
            {!! $cANomination->bank_account_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Number Field -->
<div id="div_cANomination_bank_account_number" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_number', 'Bank Account Number:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_bank_account_number">
        @if (isset($cANomination->bank_account_number) && empty($cANomination->bank_account_number)==false)
            {!! $cANomination->bank_account_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Name Field -->
<div id="div_cANomination_bank_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_name', 'Bank Name:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_bank_name">
        @if (isset($cANomination->bank_name) && empty($cANomination->bank_name)==false)
            {!! $cANomination->bank_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Sort Code Field -->
<div id="div_cANomination_bank_sort_code" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_sort_code', 'Bank Sort Code:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_bank_sort_code">
        @if (isset($cANomination->bank_sort_code) && empty($cANomination->bank_sort_code)==false)
            {!! $cANomination->bank_sort_code !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Intl Passport Number Field -->
<div id="div_cANomination_intl_passport_number" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('intl_passport_number', 'Intl Passport Number:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_intl_passport_number">
        @if (isset($cANomination->intl_passport_number) && empty($cANomination->intl_passport_number)==false)
            {!! $cANomination->intl_passport_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

{{-- <!-- Bank Verification Number Field -->
<div id="div_cANomination_bank_verification_number" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_verification_number', 'Bank Verification Number:', ['class'=>'control-label'])
             !!}
        </strong><br>        
        <span id="spn_cANomination_bank_verification_number">
        @if (isset($cANomination->bank_verification_number) && empty($cANomination->bank_verification_number)==false)
            {!! $cANomination->bank_verification_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div> --}}

<!-- National Id Number Field -->
<div id="div_cANomination_national_id_number" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('national_id_number', 'National Id Number:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_national_id_number">
        @if (isset($cANomination->national_id_number) && empty($cANomination->national_id_number)==false)
            {!! $cANomination->national_id_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Organizer Name Field -->
<div id="div_cANomination_organizer_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('organizer_name', 'Organizer Name:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_organizer_name">
        @if (isset($cANomination->organizer_name) && empty($cANomination->organizer_name)==false)
            {!! $cANomination->organizer_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Conference Theme Field -->
<div id="div_cANomination_conference_theme" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('conference_theme', 'Conference Theme:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_conference_theme">
        @if (isset($cANomination->conference_theme) && empty($cANomination->conference_theme)==false)
            {!! $cANomination->conference_theme !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Accepted Paper Title Field -->
<div id="div_cANomination_accepted_paper_title" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('accepted_paper_title', 'Abstract Title:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_accepted_paper_title">
        @if (isset($cANomination->accepted_paper_title) && empty($cANomination->accepted_paper_title)==false)
            {!! $cANomination->accepted_paper_title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Attendee Department Name Field -->
<div id="div_cANomination_attendee_department_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('attendee_department_name', 'Attendee Department Name:', ['class'=>'control-label'])
             !!}
        </strong> <br>        
        <span id="spn_cANomination_attendee_department_name">
        @if (isset($cANomination->attendee_department_name) && empty($cANomination->attendee_department_name)==false)
            {!! $cANomination->attendee_department_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Attendee Grade Level Field -->
<div id="div_cANomination_attendee_grade_level" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('attendee_grade_level', 'Attendee Grade Level:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_attendee_grade_level">
        @if (isset($cANomination->attendee_grade_level) && empty($cANomination->attendee_grade_level)==false)
            {!! $cANomination->attendee_grade_level !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>


<!-- Has Paper Presentation Field -->
<div id="div_cANomination_has_paper_presentation" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('has_paper_presentation', 'Has Paper Presentation:', ['class'=>'control-label']) !!}
        </strong><br>        
        <span id="spn_cANomination_has_paper_presentation">
        @if (isset($cANomination->has_paper_presentation))
            {!! $cANomination->has_paper_presentation ? 'Yes' : 'No' !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Is Academic Staff Field -->
<div id="div_cANomination_is_academic_staff" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('is_academic_staff', 'Is Academic Staff:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_is_academic_staff">
        @if (isset($cANomination->is_academic_staff))
            {!! $cANomination->is_academic_staff ? 'Yes' : 'No' !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Conference Start Date Field -->
<div id="div_cANomination_conference_start_date" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('conference_start_date', 'Conference Start Date:', ['class'=>'control-label']) !!}
        </strong><br>        
        <span id="spn_cANomination_conference_start_date">
        @if (isset($cANomination->conference_start_date) && empty($cANomination->conference_start_date)==false)
            {!! date('D, d-M-Y', strtotime($cANomination->conference_start_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Conference end date -->
<div id="div_cANomination_conference_end_date" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('conference_end_date', 'Conference End Date:', ['class'=>'control-label']) !!}
        </strong><br>        
        <span id="spn_cANomination_conference_end_date">
        @if (isset($cANomination->conference_end_date) && empty($cANomination->conference_end_date)==false)
            {!! date('D, d-M-Y', strtotime($cANomination->conference_end_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Final Remarks Field -->
<div id="div_cANomination_final_remarks" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('final_remarks', 'Final Remarks:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_final_remarks">
        @if (isset($cANomination->final_remarks) && empty($cANomination->final_remarks)==false)
            {!! $cANomination->final_remarks !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Total Requested Amount Field -->
<div id="div_cANomination_total_requested_amount" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('total_requested_amount', 'Total Requested Amount:', ['class'=>'control-label']) !!} 
        </strong><br>        
        <span id="spn_cANomination_total_requested_amount">
        @if (isset($cANomination->total_requested_amount) && empty($cANomination->total_requested_amount)==false)
            {!! $cANomination->total_requested_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

