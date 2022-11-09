<!-- Email Field -->
<div id="div_tPNomination_email" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('email', 'Email:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_email">
        @if (isset($tPNomination->email) && empty($tPNomination->email)==false)
            {!! $tPNomination->email !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Telephone Field -->
<div id="div_tPNomination_telephone" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('telephone', 'Telephone:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_telephone">
        @if (isset($tPNomination->telephone) && empty($tPNomination->telephone)==false)
            {!! $tPNomination->telephone !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Beneficiary Institution Field -->
<div id="div_tPNomination_beneficiary_institution_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('beneficiary_institution_id', 'Beneficiary Institution:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_beneficiary_institution_name">
        @if (isset($tPNomination->beneficiary_institution_id) && empty($tPNomination->beneficiary)==false)
            {!! $tPNomination->beneficiary->full_name !!}
            {!! $tPNomination->beneficiary->short_name ? '('.$tPNomination->beneficiary->short_name.')' : '' !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Institution Field -->
<div id="div_tPNomination_institution_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('institution_id', 'Institution:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_institution_name">
        @if (isset($tPNomination->institution_id) && empty($tPNomination->institution)==false)
            {!! $tPNomination->institution->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- country Name Field -->
<div id="div_tPNomination_country_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('country_id', 'Country:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_country_name">
        @if (isset($tPNomination->country_id) && empty($tPNomination->country_id)==false)
            {!! $tPNomination->country->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Gender Field -->
<div id="div_tPNomination_gender" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('gender', 'Gender:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tPNomination_gender">
        @if (isset($tPNomination->gender) && empty($tPNomination->gender)==false)
            {!! $tPNomination->gender !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Name Title Field -->
<div id="div_tPNomination_name_title" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('name_title', 'Name Title:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tPNomination_name_title">
        @if (isset($tPNomination->name_title) && empty($tPNomination->name_title)==false)
            {!! $tPNomination->name_title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- First Name Field -->
<div id="div_tPNomination_first_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('first_name', 'First Name:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tPNomination_first_name">
        @if (isset($tPNomination->first_name) && empty($tPNomination->first_name)==false)
            {!! $tPNomination->first_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Middle Name Field -->
<div id="div_tPNomination_middle_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('middle_name', 'Middle Name:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_middle_name">
        @if (isset($tPNomination->middle_name) && empty($tPNomination->middle_name)==false)
            {!! $tPNomination->middle_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Last Name Field -->
<div id="div_tPNomination_last_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('last_name', 'Last Name:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_last_name">
        @if (isset($tPNomination->last_name) && empty($tPNomination->last_name)==false)
            {!! $tPNomination->last_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Name Suffix Field -->
<div id="div_tPNomination_name_suffix" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('name_suffix', 'Name Suffix:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_name_suffix">
        @if (isset($tPNomination->name_suffix) && empty($tPNomination->name_suffix)==false)
            {!! $tPNomination->name_suffix !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Name Field -->
<div id="div_tPNomination_bank_account_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_name', 'Bank Account Name:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tPNomination_bank_account_name">
        @if (isset($tPNomination->bank_account_name) && empty($tPNomination->bank_account_name)==false)
            {!! $tPNomination->bank_account_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Number Field -->
<div id="div_tPNomination_bank_account_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_number', 'Bank Account Number:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_bank_account_number">
        @if (isset($tPNomination->bank_account_number) && empty($tPNomination->bank_account_number)==false)
            {!! $tPNomination->bank_account_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Name Field -->
<div id="div_tPNomination_bank_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_name', 'Bank Name:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_bank_name">
        @if (isset($tPNomination->bank_name) && empty($tPNomination->bank_name)==false)
            {!! $tPNomination->bank_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Sort Code Field -->
<div id="div_tPNomination_bank_sort_code" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_sort_code', 'Bank Sort Code:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_bank_sort_code">
        @if (isset($tPNomination->bank_sort_code) && empty($tPNomination->bank_sort_code)==false)
            {!! $tPNomination->bank_sort_code !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Intl Passport Number Field -->
<div id="div_tPNomination_intl_passport_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('intl_passport_number', 'Intl Passport Number:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_intl_passport_number">
        @if (isset($tPNomination->intl_passport_number) && empty($tPNomination->intl_passport_number)==false)
            {!! $tPNomination->intl_passport_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Verification Number Field -->
<div id="div_tPNomination_bank_verification_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_verification_number', 'Bank Verification Number:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_bank_verification_number">
        @if (isset($tPNomination->bank_verification_number) && empty($tPNomination->bank_verification_number)==false)
            {!! $tPNomination->bank_verification_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- National Id Number Field -->
<div id="div_tPNomination_national_id_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('national_id_number', 'National Id Number:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tPNomination_national_id_number">
        @if (isset($tPNomination->national_id_number) && empty($tPNomination->national_id_number)==false)
            {!! $tPNomination->national_id_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Degree Type Field -->
<div id="div_tPNomination_degree_type" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('degree_type', 'Degree Type:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_degree_type">
        @if (isset($tPNomination->degree_type) && empty($tPNomination->degree_type)==false)
            {!! $tPNomination->degree_type !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program Title Field -->
<div id="div_tPNomination_program_title" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('program_title', 'Program Title:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tPNomination_program_title">
        @if (isset($tPNomination->program_title) && empty($tPNomination->program_title)==false)
            {!! $tPNomination->program_title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program Type Field -->
<div id="div_tPNomination_program_type" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('program_type', 'Program Type:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_program_type">
        @if (isset($tPNomination->program_type) && empty($tPNomination->program_type)==false)
            {!! $tPNomination->program_type !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Is Science Program Field -->
<div id="div_tPNomination_is_science_program" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('is_science_program', 'Is Science Program:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tPNomination_is_science_program">
        @if (isset($tPNomination->is_science_program))
            {!! $tPNomination->is_science_program ? 'Yes' : 'No' !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program Start Date Field -->
<div id="div_program_start_date" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('program_start_date', 'Program Start Date:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_program_start_date">
        @if (isset($tPNomination->program_start_date) && empty($tPNomination->program_start_date)==false)
            {!! date('D, d-M-Y', strtotime($tPNomination->program_start_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program end date -->
<div id="div_program_end_date" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('program_end_date', 'Program End Date:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_program_end_date">
        @if (isset($tPNomination->program_end_date) && empty($tPNomination->program_end_date)==false)
            {!! date('D, d-M-Y', strtotime($tPNomination->program_end_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Fee Amount Field -->
<div id="div_tPNomination_fee_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('fee_amount', 'Fee Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_fee_amount">
        @if (isset($tPNomination->fee_amount) && empty($tPNomination->fee_amount)==false)
            {!! $tPNomination->fee_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Tuition Amount Field -->
<div id="div_tPNomination_tuition_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('tuition_amount', 'Tuition Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_tuition_amount">
        @if (isset($tPNomination->tuition_amount) && empty($tPNomination->tuition_amount)==false)
            {!! $tPNomination->tuition_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Upgrade Fee Amount Field -->
<div id="div_tPNomination_upgrade_fee_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('upgrade_fee_amount', 'Upgrade Fee Amount:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tPNomination_upgrade_fee_amount">
        @if (isset($tPNomination->upgrade_fee_amount) && empty($tPNomination->upgrade_fee_amount)==false)
            {!! $tPNomination->upgrade_fee_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Stipend Amount Field -->
<div id="div_tPNomination_stipend_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('stipend_amount', 'Stipend Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_stipend_amount">
        @if (isset($tPNomination->stipend_amount) && empty($tPNomination->stipend_amount)==false)
            {!! $tPNomination->stipend_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Passage Amount Field -->
<div id="div_tPNomination_passage_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('passage_amount', 'Passage Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_passage_amount">
        @if (isset($tPNomination->passage_amount) && empty($tPNomination->passage_amount)==false)
            {!! $tPNomination->passage_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Medical Amount Field -->
<div id="div_tPNomination_medical_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('medical_amount', 'Medical Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_medical_amount">
        @if (isset($tPNomination->medical_amount) && empty($tPNomination->medical_amount)==false)
            {!! $tPNomination->medical_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Warm Clothing Amount Field -->
<div id="div_tPNomination_warm_clothing_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('warm_clothing_amount', 'Warm Clothing Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_warm_clothing_amount">
        @if (isset($tPNomination->warm_clothing_amount) && empty($tPNomination->warm_clothing_amount)==false)
            {!! $tPNomination->warm_clothing_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Study Tours Amount Field -->
<div id="div_tPNomination_study_tours_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('study_tours_amount', 'Study Tours Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_study_tours_amount">
        @if (isset($tPNomination->study_tours_amount) && empty($tPNomination->study_tours_amount)==false)
            {!! $tPNomination->study_tours_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Education Materials Amount Field -->
<div id="div_tPNomination_education_materials_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('education_materials_amount', 'Education Materials Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_education_materials_amount">
        @if (isset($tPNomination->education_materials_amount) && empty($tPNomination->education_materials_amount)==false)
            {!! $tPNomination->education_materials_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Thesis Research Amount Field -->
<div id="div_tPNomination_thesis_research_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('thesis_research_amount', 'Thesis Research Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_thesis_research_amount">
        @if (isset($tPNomination->thesis_research_amount) && empty($tPNomination->thesis_research_amount)==false)
            {!! $tPNomination->thesis_research_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Final Remarks Field -->
<div id="div_tPNomination_final_remarks" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('final_remarks', 'Final Remarks:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_final_remarks">
        @if (isset($tPNomination->final_remarks) && empty($tPNomination->final_remarks)==false)
            {!! $tPNomination->final_remarks !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Total Requested Amount Field -->
<div id="div_tPNomination_total_requested_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('total_requested_amount', 'Total Requested Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_total_requested_amount">
        @if (isset($tPNomination->total_requested_amount) && empty($tPNomination->total_requested_amount)==false)
            {!! $tPNomination->total_requested_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Total Approved Amount Field -->
<div id="div_tPNomination_total_approved_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('total_approved_amount', 'Total Approved Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tPNomination_total_approved_amount">
        @if (isset($tPNomination->total_approved_amount) && empty($tPNomination->total_approved_amount)==false)
            {!! $tPNomination->total_approved_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

