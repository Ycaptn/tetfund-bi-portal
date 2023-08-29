<!-- Email Field -->
<div id="div_tPNomination_email" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('email', 'Email:', ['class'=>'control-label']) !!}
        </strong><br>
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
<div id="div_tPNomination_telephone" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('telephone', 'Telephone:', ['class'=>'control-label']) !!}
        </strong><br>
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
<div id="div_tPNomination_beneficiary_institution_name" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('beneficiary_institution_id', 'Beneficiary Institution:', ['class'=>'control-label']) !!}
        </strong><br>
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

<!-- Institution FullName Field -->
<div id="div_tPNomination_institution_name" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('institution_name', 'Institution FullName:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tPNomination_institution_name">
        @if (isset($tPNomination->institution_name) && empty($tPNomination->institution_name)==false)
            {!! $tPNomination->institution_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Institution State Field -->
<div id="div_tPNomination_institution_state" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('institution_state', 'Institution State:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tPNomination_institution_state">
        @if (isset($tPNomination->intitution_state) && empty($tPNomination->intitution_state)==false)
            {!! $tPNomination->intitution_state !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Institution Address Field -->
<div id="div_tPNomination_institution_address" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('institution_address', 'Institution Address:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tPNomination_institution_address">
        @if (isset($tPNomination->institution_address) && empty($tPNomination->institution_address)==false)
            {!! $tPNomination->institution_address !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Gender Field -->
<div id="div_tPNomination_gender" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('gender', 'Gender:', ['class'=>'control-label']) !!} 
        </strong><br>
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
<div id="div_tPNomination_rank_gl_equivalent" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('name_title', 'Rank/GL Equivalent:', ['class'=>'control-label']) !!} 
        </strong><br>
        <span id="spn_tPNomination_rank_gl_equivalent">
        @if (isset($tPNomination->rank_gl_equivalent) && empty($tPNomination->rank_gl_equivalent)==false)
            {!! $tPNomination->rank_gl_equivalent !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- First Name Field -->
<div id="div_tPNomination_first_name" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('first_name', 'First Name:', ['class'=>'control-label']) !!} 
        </strong><br>
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
<div id="div_tPNomination_middle_name" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('middle_name', 'Middle Name:', ['class'=>'control-label']) !!}
        </strong><br>
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
<div id="div_tPNomination_last_name" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('last_name', 'Last Name:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tPNomination_last_name">
        @if (isset($tPNomination->last_name) && empty($tPNomination->last_name)==false)
            {!! $tPNomination->last_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Bank Account Name Field -->
<div id="div_tPNomination_bank_account_name" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_name', 'Bank Account Name:', ['class'=>'control-label']) !!} 
        </strong><br>
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
<div id="div_tPNomination_bank_account_number" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_account_number', 'Bank Account Number:', ['class'=>'control-label']) !!}
        </strong><br>
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
<div id="div_tPNomination_bank_name" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_name', 'Bank Name:', ['class'=>'control-label']) !!}
        </strong><br>
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
<div id="div_tPNomination_bank_sort_code" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_sort_code', 'Bank Sort Code:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tPNomination_bank_sort_code">
        @if (isset($tPNomination->bank_sort_code) && empty($tPNomination->bank_sort_code)==false)
            {!! $tPNomination->bank_sort_code !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

{{-- <!-- Bank Verification Number Field -->
<div id="div_tPNomination_bank_verification_number" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('bank_verification_number', 'Bank Verification Number:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tPNomination_bank_verification_number">
        @if (isset($tPNomination->bank_verification_number) && empty($tPNomination->bank_verification_number)==false)
            {!! $tPNomination->bank_verification_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>
 --}}
<!-- National Id Number Field -->
<div id="div_tPNomination_national_id_number" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('national_id_number', 'National Id Number:', ['class'=>'control-label']) !!} 
        </strong><br>
        <span id="spn_tPNomination_national_id_number">
        @if (isset($tPNomination->national_id_number) && empty($tPNomination->national_id_number)==false)
            {!! $tPNomination->national_id_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program Start Date Field -->
<div id="div_tPNomination_program_start_date" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('program_start_date', 'Program Start Date:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tPNomination_program_start_date">
        @if (isset($tPNomination->program_start_date) && empty($tPNomination->program_start_date)==false)
            {!! date('D, d-M-Y', strtotime($tPNomination->program_start_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Program end date -->
<div id="div_tPNomination_program_end_date" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('program_end_date', 'Program End Date:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tPNomination_program_end_date">
        @if (isset($tPNomination->program_end_date) && empty($tPNomination->program_end_date)==false)
            {!! date('D, d-M-Y', strtotime($tPNomination->program_end_date)) !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Final Remarks Field -->
<div id="div_tPNomination_final_remarks" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('final_remarks', 'Final Remarks:', ['class'=>'control-label']) !!}
        </strong><br>
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
<div id="div_tPNomination_total_requested_amount" class="col-sm-12 col-md-4 col-lg-3 mb-10">
    <p>
        <strong>
            {!! Form::label('total_requested_amount', 'Total Requested Amount:', ['class'=>'control-label']) !!}
        </strong><br>
        <span id="spn_tPNomination_total_requested_amount">
        @if (isset($tPNomination->total_requested_amount) && empty($tPNomination->total_requested_amount)==false)
            {!! $tPNomination->total_requested_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

