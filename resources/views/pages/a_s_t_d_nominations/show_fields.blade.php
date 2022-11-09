<!-- Email Field -->
<div id="div_aSTDNomination_email" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('email', 'Email:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_email">
        @if (isset($aSTDNomination->email) && empty($aSTDNomination->email)==false)
            {!! $aSTDNomination->email !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Telephone Field -->
<div id="div_aSTDNomination_telephone" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('telephone', 'Telephone:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_telephone">
        @if (isset($aSTDNomination->telephone) && empty($aSTDNomination->telephone)==false)
            {!! $aSTDNomination->telephone !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Beneficiary Institution Field -->
<div id="div_aSTDNomination_beneficiary_institution_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('beneficiary_institution_id', 'Beneficiary Institution:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_beneficiary_institution_name">
        @if (isset($aSTDNomination->beneficiary_institution_id) && empty($aSTDNomination->beneficiary)==false)
            {!! $aSTDNomination->beneficiary->full_name !!}
            {!! $aSTDNomination->beneficiary->short_name ? '('.$aSTDNomination->beneficiary->short_name.')' : '' !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Institution Field -->
<div id="div_aSTDNomination_institution_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('institution_id', 'Institution:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_institution_name">
        @if (isset($aSTDNomination->institution_id) && empty($aSTDNomination->institution)==false)
            {!! $aSTDNomination->institution->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- country Name Field -->
<div id="div_aSTDNomination_country_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('country_id', 'Country:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_country_name">
        @if (isset($aSTDNomination->country_id) && empty($aSTDNomination->country_id)==false)
            {!! $aSTDNomination->country->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Gender Field -->
<div id="div_aSTDNomination_gender" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('gender', 'Gender:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_aSTDNomination_gender">
        @if (isset($aSTDNomination->gender) && empty($aSTDNomination->gender)==false)
            {!! $aSTDNomination->gender !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Name Title Field -->
<div id="div_aSTDNomination_name_title" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('name_title', 'Name Title:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_aSTDNomination_name_title">
        @if (isset($aSTDNomination->name_title) && empty($aSTDNomination->name_title)==false)
            {!! $aSTDNomination->name_title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- First Name Field -->
<div id="div_aSTDNomination_first_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('first_name', 'First Name:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_aSTDNomination_first_name">
        @if (isset($aSTDNomination->first_name) && empty($aSTDNomination->first_name)==false)
            {!! $aSTDNomination->first_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Middle Name Field -->
<div id="div_aSTDNomination_middle_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('middle_name', 'Middle Name:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_middle_name">
        @if (isset($aSTDNomination->middle_name) && empty($aSTDNomination->middle_name)==false)
            {!! $aSTDNomination->middle_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Last Name Field -->
<div id="div_aSTDNomination_last_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('last_name', 'Last Name:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_last_name">
        @if (isset($aSTDNomination->last_name) && empty($aSTDNomination->last_name)==false)
            {!! $aSTDNomination->last_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Name Suffix Field -->
<div id="div_aSTDNomination_name_suffix" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('name_suffix', 'Name Suffix:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_name_suffix">
        @if (isset($aSTDNomination->name_suffix) && empty($aSTDNomination->name_suffix)==false)
            {!! $aSTDNomination->name_suffix !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Name Field -->
<div id="div_aSTDNomination_bank_account_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_name', 'Bank Account Name:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_aSTDNomination_bank_account_name">
        @if (isset($aSTDNomination->bank_account_name) && empty($aSTDNomination->bank_account_name)==false)
            {!! $aSTDNomination->bank_account_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Number Field -->
<div id="div_aSTDNomination_bank_account_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_number', 'Bank Account Number:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_bank_account_number">
        @if (isset($aSTDNomination->bank_account_number) && empty($aSTDNomination->bank_account_number)==false)
            {!! $aSTDNomination->bank_account_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Name Field -->
<div id="div_aSTDNomination_bank_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_name', 'Bank Name:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_bank_name">
        @if (isset($aSTDNomination->bank_name) && empty($aSTDNomination->bank_name)==false)
            {!! $aSTDNomination->bank_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Sort Code Field -->
<div id="div_aSTDNomination_bank_sort_code" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_sort_code', 'Bank Sort Code:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_bank_sort_code">
        @if (isset($aSTDNomination->bank_sort_code) && empty($aSTDNomination->bank_sort_code)==false)
            {!! $aSTDNomination->bank_sort_code !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Intl Passport Number Field -->
<div id="div_aSTDNomination_intl_passport_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('intl_passport_number', 'Intl Passport Number:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_intl_passport_number">
        @if (isset($aSTDNomination->intl_passport_number) && empty($aSTDNomination->intl_passport_number)==false)
            {!! $aSTDNomination->intl_passport_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Verification Number Field -->
<div id="div_aSTDNomination_bank_verification_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_verification_number', 'Bank Verification Number:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_bank_verification_number">
        @if (isset($aSTDNomination->bank_verification_number) && empty($aSTDNomination->bank_verification_number)==false)
            {!! $aSTDNomination->bank_verification_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- National Id Number Field -->
<div id="div_aSTDNomination_national_id_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('national_id_number', 'National Id Number:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_aSTDNomination_national_id_number">
        @if (isset($aSTDNomination->national_id_number) && empty($aSTDNomination->national_id_number)==false)
            {!! $aSTDNomination->national_id_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Degree Type Field -->
<div id="div_aSTDNomination_degree_type" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('degree_type', 'Degree Type:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_degree_type">
        @if (isset($aSTDNomination->degree_type) && empty($aSTDNomination->degree_type)==false)
            {!! $aSTDNomination->degree_type !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program Title Field -->
<div id="div_aSTDNomination_program_title" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('program_title', 'Program Title:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_aSTDNomination_program_title">
        @if (isset($aSTDNomination->program_title) && empty($aSTDNomination->program_title)==false)
            {!! $aSTDNomination->program_title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program Type Field -->
<div id="div_aSTDNomination_program_type" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('program_type', 'Program Type:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_program_type">
        @if (isset($aSTDNomination->program_type) && empty($aSTDNomination->program_type)==false)
            {!! $aSTDNomination->program_type !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Is Science Program Field -->
<div id="div_aSTDNomination_is_science_program" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('is_science_program', 'Is Science Program:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_aSTDNomination_is_science_program">
        @if (isset($aSTDNomination->is_science_program))
            {!! $aSTDNomination->is_science_program ? 'Yes' : 'No' !!}
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
        @if (isset($aSTDNomination->program_start_date) && empty($aSTDNomination->program_start_date)==false)
            {!! date('D, d-M-Y', strtotime($aSTDNomination->program_start_date)) !!}
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
        @if (isset($aSTDNomination->program_end_date) && empty($aSTDNomination->program_end_date)==false)
            {!! date('D, d-M-Y', strtotime($aSTDNomination->program_end_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Fee Amount Field -->
<div id="div_aSTDNomination_fee_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('fee_amount', 'Fee Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_fee_amount">
        @if (isset($aSTDNomination->fee_amount) && empty($aSTDNomination->fee_amount)==false)
            {!! $aSTDNomination->fee_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Tuition Amount Field -->
<div id="div_aSTDNomination_tuition_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('tuition_amount', 'Tuition Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_tuition_amount">
        @if (isset($aSTDNomination->tuition_amount) && empty($aSTDNomination->tuition_amount)==false)
            {!! $aSTDNomination->tuition_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Upgrade Fee Amount Field -->
<div id="div_aSTDNomination_upgrade_fee_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('upgrade_fee_amount', 'Upgrade Fee Amount:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_aSTDNomination_upgrade_fee_amount">
        @if (isset($aSTDNomination->upgrade_fee_amount) && empty($aSTDNomination->upgrade_fee_amount)==false)
            {!! $aSTDNomination->upgrade_fee_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Stipend Amount Field -->
<div id="div_aSTDNomination_stipend_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('stipend_amount', 'Stipend Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_stipend_amount">
        @if (isset($aSTDNomination->stipend_amount) && empty($aSTDNomination->stipend_amount)==false)
            {!! $aSTDNomination->stipend_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Passage Amount Field -->
<div id="div_aSTDNomination_passage_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('passage_amount', 'Passage Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_passage_amount">
        @if (isset($aSTDNomination->passage_amount) && empty($aSTDNomination->passage_amount)==false)
            {!! $aSTDNomination->passage_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Medical Amount Field -->
<div id="div_aSTDNomination_medical_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('medical_amount', 'Medical Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_medical_amount">
        @if (isset($aSTDNomination->medical_amount) && empty($aSTDNomination->medical_amount)==false)
            {!! $aSTDNomination->medical_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Warm Clothing Amount Field -->
<div id="div_aSTDNomination_warm_clothing_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('warm_clothing_amount', 'Warm Clothing Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_warm_clothing_amount">
        @if (isset($aSTDNomination->warm_clothing_amount) && empty($aSTDNomination->warm_clothing_amount)==false)
            {!! $aSTDNomination->warm_clothing_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Study Tours Amount Field -->
<div id="div_aSTDNomination_study_tours_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('study_tours_amount', 'Study Tours Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_study_tours_amount">
        @if (isset($aSTDNomination->study_tours_amount) && empty($aSTDNomination->study_tours_amount)==false)
            {!! $aSTDNomination->study_tours_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Education Materials Amount Field -->
<div id="div_aSTDNomination_education_materials_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('education_materials_amount', 'Education Materials Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_education_materials_amount">
        @if (isset($aSTDNomination->education_materials_amount) && empty($aSTDNomination->education_materials_amount)==false)
            {!! $aSTDNomination->education_materials_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Thesis Research Amount Field -->
<div id="div_aSTDNomination_thesis_research_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('thesis_research_amount', 'Thesis Research Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_thesis_research_amount">
        @if (isset($aSTDNomination->thesis_research_amount) && empty($aSTDNomination->thesis_research_amount)==false)
            {!! $aSTDNomination->thesis_research_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Final Remarks Field -->
<div id="div_aSTDNomination_final_remarks" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('final_remarks', 'Final Remarks:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_final_remarks">
        @if (isset($aSTDNomination->final_remarks) && empty($aSTDNomination->final_remarks)==false)
            {!! $aSTDNomination->final_remarks !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Total Requested Amount Field -->
<div id="div_aSTDNomination_total_requested_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('total_requested_amount', 'Total Requested Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_total_requested_amount">
        @if (isset($aSTDNomination->total_requested_amount) && empty($aSTDNomination->total_requested_amount)==false)
            {!! $aSTDNomination->total_requested_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Total Approved Amount Field -->
<div id="div_aSTDNomination_total_approved_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('total_approved_amount', 'Total Approved Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_aSTDNomination_total_approved_amount">
        @if (isset($aSTDNomination->total_approved_amount) && empty($aSTDNomination->total_approved_amount)==false)
            {!! $aSTDNomination->total_approved_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

