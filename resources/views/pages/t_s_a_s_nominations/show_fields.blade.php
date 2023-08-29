<!-- Email Field -->
<div id="div_tSASNomination_email" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('email', 'Email:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_email">
        @if (isset($tSASNomination->email) && empty($tSASNomination->email)==false)
            {!! $tSASNomination->email !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Telephone Field -->
<div id="div_tSASNomination_telephone" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('telephone', 'Telephone:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_telephone">
        @if (isset($tSASNomination->telephone) && empty($tSASNomination->telephone)==false)
            {!! $tSASNomination->telephone !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Beneficiary Institution Field -->
<div id="div_tSASNomination_beneficiary_institution_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('beneficiary_institution_id', 'Beneficiary Institution:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_beneficiary_institution_name">
        @if (isset($tSASNomination->beneficiary_institution_id) && empty($tSASNomination->beneficiary)==false)
            {!! $tSASNomination->beneficiary->full_name !!}
            {!! $tSASNomination->beneficiary->short_name ? '('.$tSASNomination->beneficiary->short_name.')' : '' !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- country Name Field -->
<div id="div_tSASNomination_country_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('country_id', 'Country:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_country_name">
        @if (isset($tSASNomination->country_id) && empty($tSASNomination->country_id)==false)
            {!! $tSASNomination->country->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Institution Name Field -->
<div id="div_tSASNomination_institution_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('institution_name', 'Institution FullName:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_institution_name">
        @if (isset($tSASNomination->institution_name) && empty($tSASNomination->institution_name)==false)
            {!! $tSASNomination->institution_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Institution State Field -->
<div id="div_tSASNomination_institution_state" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('intitution_state', 'Institution State:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_institution_state">
        @if (isset($tSASNomination->intitution_state) && empty($tSASNomination->intitution_state)==false)
            {!! $tSASNomination->intitution_state !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Gender Field -->
<div id="div_tSASNomination_gender" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('gender', 'Gender:', ['class'=>'control-label']) !!} 
        </strong><br>
        <span id="spn_tSASNomination_gender">
        @if (isset($tSASNomination->gender) && empty($tSASNomination->gender)==false)
            {!! $tSASNomination->gender !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Name Title Field -->
<div id="div_tSASNomination_name_title" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('name_title', 'Name Title:', ['class'=>'control-label']) !!} 
        </strong><br>
        <span id="spn_tSASNomination_name_title">
        @if (isset($tSASNomination->name_title) && empty($tSASNomination->name_title)==false)
            {!! $tSASNomination->name_title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- First Name Field -->
<div id="div_tSASNomination_first_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('first_name', 'First Name:', ['class'=>'control-label']) !!} 
        </strong><br>
        <span id="spn_tSASNomination_first_name">
        @if (isset($tSASNomination->first_name) && empty($tSASNomination->first_name)==false)
            {!! $tSASNomination->first_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Middle Name Field -->
<div id="div_tSASNomination_middle_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('middle_name', 'Middle Name:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_middle_name">
        @if (isset($tSASNomination->middle_name) && empty($tSASNomination->middle_name)==false)
            {!! $tSASNomination->middle_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Last Name Field -->
<div id="div_tSASNomination_last_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('last_name', 'Last Name:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_last_name">
        @if (isset($tSASNomination->last_name) && empty($tSASNomination->last_name)==false)
            {!! $tSASNomination->last_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Name Field -->
<div id="div_tSASNomination_bank_account_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_name', 'Bank Account Name:', ['class'=>'control-label']) !!} 
        </strong><br>
        <span id="spn_tSASNomination_bank_account_name">
        @if (isset($tSASNomination->bank_account_name) && empty($tSASNomination->bank_account_name)==false)
            {!! $tSASNomination->bank_account_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Number Field -->
<div id="div_tSASNomination_bank_account_number" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_number', 'Bank Account Number:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_bank_account_number">
        @if (isset($tSASNomination->bank_account_number) && empty($tSASNomination->bank_account_number)==false)
            {!! $tSASNomination->bank_account_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Name Field -->
<div id="div_tSASNomination_bank_name" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_name', 'Bank Name:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_bank_name">
        @if (isset($tSASNomination->bank_name) && empty($tSASNomination->bank_name)==false)
            {!! $tSASNomination->bank_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Sort Code Field -->
<div id="div_tSASNomination_bank_sort_code" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_sort_code', 'Bank Sort Code:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_bank_sort_code">
        @if (isset($tSASNomination->bank_sort_code) && empty($tSASNomination->bank_sort_code)==false)
            {!! $tSASNomination->bank_sort_code !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Intl Passport Number Field -->
<div id="div_tSASNomination_intl_passport_number" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('intl_passport_number', 'Intl Passport Number:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_intl_passport_number">
        @if (isset($tSASNomination->intl_passport_number) && empty($tSASNomination->intl_passport_number)==false)
            {!! $tSASNomination->intl_passport_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

{{-- <!-- Bank Verification Number Field -->
<div id="div_tSASNomination_bank_verification_number" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_verification_number', 'Bank Verification Number:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_bank_verification_number">
        @if (isset($tSASNomination->bank_verification_number) && empty($tSASNomination->bank_verification_number)==false)
            {!! $tSASNomination->bank_verification_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div> --}}

<!-- National Id Number Field -->
<div id="div_tSASNomination_national_id_number" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('national_id_number', 'National Id Number:', ['class'=>'control-label']) !!} 
        </strong><br>
        <span id="spn_tSASNomination_national_id_number">
        @if (isset($tSASNomination->national_id_number) && empty($tSASNomination->national_id_number)==false)
            {!! $tSASNomination->national_id_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Degree Type Field -->
<div id="div_tSASNomination_degree_type" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('degree_type', 'Degree Type:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_degree_type">
        @if (isset($tSASNomination->degree_type) && empty($tSASNomination->degree_type)==false)
            {!! $tSASNomination->degree_type !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program Title Field -->
<div id="div_tSASNomination_program_title" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('program_title', 'Program Title:', ['class'=>'control-label']) !!} 
        </strong><br>
        <span id="spn_tSASNomination_program_title">
        @if (isset($tSASNomination->program_title) && empty($tSASNomination->program_title)==false)
            {!! $tSASNomination->program_title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Is Science Program Field -->
<div id="div_tSASNomination_is_science_program" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('is_science_program', 'Is Science Program:', ['class'=>'control-label']) !!} 
        </strong><br>
        <span id="spn_tSASNomination_is_science_program">
        @if (isset($tSASNomination->is_science_program))
            {!! $tSASNomination->is_science_program ? 'Yes' : 'No' !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program Start Date Field -->
<div id="div_tSASNomination_program_start_date" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('program_start_date', 'Program Start Date:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_program_start_date">
        @if (isset($tSASNomination->program_start_date) && empty($tSASNomination->program_start_date)==false)
            {!! date('D, d-M-Y', strtotime($tSASNomination->program_start_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program end date -->
<div id="div_tSASNomination_program_end_date" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('program_end_date', 'Program End Date:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_program_end_date">
        @if (isset($tSASNomination->program_end_date) && empty($tSASNomination->program_end_date)==false)
            {!! date('D, d-M-Y', strtotime($tSASNomination->program_end_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Final Remarks Field -->
<div id="div_tSASNomination_final_remarks" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('final_remarks', 'Final Remarks:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_final_remarks">
        @if (isset($tSASNomination->final_remarks) && empty($tSASNomination->final_remarks)==false)
            {!! $tSASNomination->final_remarks !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Total Requested Amount Field -->
<div id="div_tSASNomination_total_requested_amount" class="col-sm-12 col-md-6 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('total_requested_amount', 'Total Requested Amount:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tSASNomination_total_requested_amount">
        @if (isset($tSASNomination->total_requested_amount) && empty($tSASNomination->total_requested_amount)==false)
            {!! $tSASNomination->total_requested_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

