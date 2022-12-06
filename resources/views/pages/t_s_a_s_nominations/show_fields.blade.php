<!-- Email Field -->
<div id="div_tSASNomination_email" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('email', 'Email:', ['class'=>'control-label']) !!}
        </strong>
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
<div id="div_tSASNomination_telephone" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('telephone', 'Telephone:', ['class'=>'control-label']) !!}
        </strong>
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
<div id="div_tSASNomination_beneficiary_institution_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('beneficiary_institution_id', 'Beneficiary Institution:', ['class'=>'control-label']) !!}
        </strong>
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

<!-- Institution Field -->
<div id="div_tSASNomination_institution_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('institution_id', 'Institution:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_institution_name">
        @if (isset($tSASNomination->institution_id) && empty($tSASNomination->institution)==false)
            {!! $tSASNomination->institution->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- country Name Field -->
<div id="div_tSASNomination_country_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('country_id', 'Country:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_country_name">
        @if (isset($tSASNomination->country_id) && empty($tSASNomination->country_id)==false)
            {!! $tSASNomination->country->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Gender Field -->
<div id="div_tSASNomination_gender" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('gender', 'Gender:', ['class'=>'control-label']) !!} 
        </strong>
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
<div id="div_tSASNomination_name_title" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('name_title', 'Name Title:', ['class'=>'control-label']) !!} 
        </strong>
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
<div id="div_tSASNomination_first_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('first_name', 'First Name:', ['class'=>'control-label']) !!} 
        </strong>
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
<div id="div_tSASNomination_middle_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('middle_name', 'Middle Name:', ['class'=>'control-label']) !!}
        </strong>
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
<div id="div_tSASNomination_last_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('last_name', 'Last Name:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_last_name">
        @if (isset($tSASNomination->last_name) && empty($tSASNomination->last_name)==false)
            {!! $tSASNomination->last_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Name Suffix Field -->
<div id="div_tSASNomination_name_suffix" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('name_suffix', 'Name Suffix:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_name_suffix">
        @if (isset($tSASNomination->name_suffix) && empty($tSASNomination->name_suffix)==false)
            {!! $tSASNomination->name_suffix !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Name Field -->
<div id="div_tSASNomination_bank_account_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_name', 'Bank Account Name:', ['class'=>'control-label']) !!} 
        </strong>
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
<div id="div_tSASNomination_bank_account_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_number', 'Bank Account Number:', ['class'=>'control-label']) !!}
        </strong>
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
<div id="div_tSASNomination_bank_name" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_name', 'Bank Name:', ['class'=>'control-label']) !!}
        </strong>
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
<div id="div_tSASNomination_bank_sort_code" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_sort_code', 'Bank Sort Code:', ['class'=>'control-label']) !!}
        </strong>
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
<div id="div_tSASNomination_intl_passport_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('intl_passport_number', 'Intl Passport Number:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_intl_passport_number">
        @if (isset($tSASNomination->intl_passport_number) && empty($tSASNomination->intl_passport_number)==false)
            {!! $tSASNomination->intl_passport_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Verification Number Field -->
<div id="div_tSASNomination_bank_verification_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_verification_number', 'Bank Verification Number:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_bank_verification_number">
        @if (isset($tSASNomination->bank_verification_number) && empty($tSASNomination->bank_verification_number)==false)
            {!! $tSASNomination->bank_verification_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- National Id Number Field -->
<div id="div_tSASNomination_national_id_number" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('national_id_number', 'National Id Number:', ['class'=>'control-label']) !!} 
        </strong>
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
<div id="div_tSASNomination_degree_type" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('degree_type', 'Degree Type:', ['class'=>'control-label']) !!}
        </strong>
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
<div id="div_tSASNomination_program_title" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('program_title', 'Program Title:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tSASNomination_program_title">
        @if (isset($tSASNomination->program_title) && empty($tSASNomination->program_title)==false)
            {!! $tSASNomination->program_title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program Type Field -->
<div id="div_tSASNomination_program_type" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('program_type', 'Program Type:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_program_type">
        @if (isset($tSASNomination->program_type) && empty($tSASNomination->program_type)==false)
            {!! $tSASNomination->program_type !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Is Science Program Field -->
<div id="div_tSASNomination_is_science_program" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('is_science_program', 'Is Science Program:', ['class'=>'control-label']) !!} 
        </strong>
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
<div id="div_program_start_date" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('program_start_date', 'Program Start Date:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_program_start_date">
        @if (isset($tSASNomination->program_start_date) && empty($tSASNomination->program_start_date)==false)
            {!! date('D, d-M-Y', strtotime($tSASNomination->program_start_date)) !!}
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
        @if (isset($tSASNomination->program_end_date) && empty($tSASNomination->program_end_date)==false)
            {!! date('D, d-M-Y', strtotime($tSASNomination->program_end_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Fee Amount Field -->
<div id="div_tSASNomination_fee_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('fee_amount', 'Fee Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_fee_amount">
        @if (isset($tSASNomination->fee_amount) && empty($tSASNomination->fee_amount)==false)
            {!! $tSASNomination->fee_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Tuition Amount Field -->
<div id="div_tSASNomination_tuition_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('tuition_amount', 'Tuition Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_tuition_amount">
        @if (isset($tSASNomination->tuition_amount) && empty($tSASNomination->tuition_amount)==false)
            {!! $tSASNomination->tuition_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Upgrade Fee Amount Field -->
<div id="div_tSASNomination_upgrade_fee_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('upgrade_fee_amount', 'Upgrade Fee Amount:', ['class'=>'control-label']) !!} 
        </strong>
        <span id="spn_tSASNomination_upgrade_fee_amount">
        @if (isset($tSASNomination->upgrade_fee_amount) && empty($tSASNomination->upgrade_fee_amount)==false)
            {!! $tSASNomination->upgrade_fee_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Stipend Amount Field -->
<div id="div_tSASNomination_stipend_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('stipend_amount', 'Stipend Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_stipend_amount">
        @if (isset($tSASNomination->stipend_amount) && empty($tSASNomination->stipend_amount)==false)
            {!! $tSASNomination->stipend_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Passage Amount Field -->
<div id="div_tSASNomination_passage_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('passage_amount', 'Passage Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_passage_amount">
        @if (isset($tSASNomination->passage_amount) && empty($tSASNomination->passage_amount)==false)
            {!! $tSASNomination->passage_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Medical Amount Field -->
<div id="div_tSASNomination_medical_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('medical_amount', 'Medical Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_medical_amount">
        @if (isset($tSASNomination->medical_amount) && empty($tSASNomination->medical_amount)==false)
            {!! $tSASNomination->medical_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Warm Clothing Amount Field -->
<div id="div_tSASNomination_warm_clothing_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('warm_clothing_amount', 'Warm Clothing Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_warm_clothing_amount">
        @if (isset($tSASNomination->warm_clothing_amount) && empty($tSASNomination->warm_clothing_amount)==false)
            {!! $tSASNomination->warm_clothing_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Study Tours Amount Field -->
<div id="div_tSASNomination_study_tours_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('study_tours_amount', 'Study Tours Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_study_tours_amount">
        @if (isset($tSASNomination->study_tours_amount) && empty($tSASNomination->study_tours_amount)==false)
            {!! $tSASNomination->study_tours_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Education Materials Amount Field -->
<div id="div_tSASNomination_education_materials_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('education_materials_amount', 'Education Materials Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_education_materials_amount">
        @if (isset($tSASNomination->education_materials_amount) && empty($tSASNomination->education_materials_amount)==false)
            {!! $tSASNomination->education_materials_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Thesis Research Amount Field -->
<div id="div_tSASNomination_thesis_research_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('thesis_research_amount', 'Thesis Research Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_thesis_research_amount">
        @if (isset($tSASNomination->thesis_research_amount) && empty($tSASNomination->thesis_research_amount)==false)
            {!! $tSASNomination->thesis_research_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Final Remarks Field -->
<div id="div_tSASNomination_final_remarks" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('final_remarks', 'Final Remarks:', ['class'=>'control-label']) !!}
        </strong>
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
<div id="div_tSASNomination_total_requested_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('total_requested_amount', 'Total Requested Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_total_requested_amount">
        @if (isset($tSASNomination->total_requested_amount) && empty($tSASNomination->total_requested_amount)==false)
            {!! $tSASNomination->total_requested_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Total Approved Amount Field -->
<div id="div_tSASNomination_total_approved_amount" class="col-sm-12 mb-10">
    <p>
        <strong>
            {!! Form::label('total_approved_amount', 'Total Approved Amount:', ['class'=>'control-label']) !!}
        </strong>
        <span id="spn_tSASNomination_total_approved_amount">
        @if (isset($tSASNomination->total_approved_amount) && empty($tSASNomination->total_approved_amount)==false)
            {!! $tSASNomination->total_approved_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

