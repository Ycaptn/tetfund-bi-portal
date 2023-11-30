{{-- input fields default values --}}
@php
  
    //first_name
   
    $first_name = $current_user->first_name ;
    $middle_name =  $current_user->middle_name ;
    $last_name = $current_user->last_name ;
    $email = $current_user->email;
    $telephone = $current_user->telephone ;
    $gender =  strtolower($current_user->gender);
    $attendee_member_type = isset($beneficiary_member->member_type) && in_array($beneficiary_member->member_type, ['academic', 'non-academic']) ? $beneficiary_member->member_type : '';
    $attendee_member_type_flag = isset($beneficiary_member->member_type) && in_array($beneficiary_member->member_type, ['academic', 'non-academic']) ? ($beneficiary_member->member_type=='academic' ? '1' : '0') : '';
    $attendee_grade_level = isset($beneficiary_member->grade_level) ? $beneficiary_member->grade_level : '';

    $banks = [
            array('id' => '1','name' => 'Access Bank','code'=>'044'),
            array('id' => '2','name' => 'Citibank','code'=>'023'),
            array('id' => '3','name' => 'Diamond Bank','code'=>'063'),
            array('id' => '4','name' => 'Dynamic Standard Bank','code'=>''),
            array('id' => '5','name' => 'Ecobank Nigeria','code'=>'050'),
            array('id' => '6','name' => 'Fidelity Bank Nigeria','code'=>'070'),
            array('id' => '7','name' => 'First Bank of Nigeria','code'=>'011'),
            array('id' => '8','name' => 'First City Monument Bank','code'=>'214'),
            array('id' => '9','name' => 'Guaranty Trust Bank','code'=>'058'),
            array('id' => '10','name' => 'Heritage Bank Plc','code'=>'030'),
            array('id' => '11','name' => 'Jaiz Bank','code'=>'301'),
            array('id' => '12','name' => 'Keystone Bank Limited','code'=>'082'),
            array('id' => '13','name' => 'Providus Bank Plc','code'=>'101'),
            array('id' => '14','name' => 'Polaris Bank','code'=>'076'),
            array('id' => '15','name' => 'Stanbic IBTC Bank Nigeria Limited','code'=>'221'),
            array('id' => '16','name' => 'Standard Chartered Bank','code'=>'068'),
            array('id' => '17','name' => 'Sterling Bank','code'=>'232'),
            array('id' => '18','name' => 'Suntrust Bank Nigeria Limited','code'=>'100'),
            array('id' => '19','name' => 'Union Bank of Nigeria','code'=>'032'),
            array('id' => '20','name' => 'United Bank for Africa','code'=>'033'),
            array('id' => '21','name' => 'Unity Bank Plc','code'=>'215'),
            array('id' => '22','name' => 'Wema Bank','code'=>'035'),
            array('id' => '23','name' => 'Zenith Bank','code'=>'057')
        ];
@endphp


{{-- nomination request nominee relevant information div --}}
<div id="user_info_section" class="form-group row col-sm-12">
    <div class="col-sm-12 col-md-8 col-lg-3 mb-3">
        <label class="col-sm-12"><b>Beneficiary Institution:</b></label>
        <div class="col-sm-12 ">
            <!-- Beneficiary Institution Field -->
            {!! Form::hidden('beneficiary_institution_id_select', $beneficiary->id ?? '', ['id'=>'beneficiary_institution_id_select_tsas', 'class'=>'form-control', 'disabled'=>'disabled']) !!}

            <i class="beneficiary_institution_id_select">
                - {{$beneficiary->full_name ?? ''}}
                {{(isset($beneficiary->short_name) && !empty($beneficiary->short_name)) ? '('.strtoupper($beneficiary->short_name).')' : ''}}
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
        <label class="col-sm-12"><b>Fullname:</b></label>
        <div class="col-sm-12 ">
            <!-- First Name Field -->
            {!! Form::hidden('first_name_tsas', $first_name, ['id'=>'first_name_tsas', 'class'=>'form-control', 'placeholder'=>'required field', 'disabled'=>'disabled']) !!}

            <!-- Middle Name Field -->
            {!! Form::hidden('middle_name_tsas', $middle_name ?? '', ['id'=>'middle_name_tsas', 'class'=>'form-control', 'placeholder'=>'optional field', 'disabled'=>'disabled']) !!}

            <!-- Last Name Field -->
            {!! Form::hidden('last_name_tsas', $last_name, ['id'=>'last_name_tsas', 'class'=>'form-control', 'placeholder'=>'required field', 'disabled'=>'disabled']) !!}

            <i class="full_name">
                - {{$first_name}} {{$middle_name}} {{$last_name}}
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
        <label class="col-sm-12"><b>Email/Gender:</b></label>
        <div class="col-sm-12 ">
            <!-- Email Field -->
            {!! Form::hidden('email_tsas', $email, ['id'=>'email_tsas', 'class'=>'form-control', 'disabled'=>'disabled']) !!}

            <!-- Gender Field -->
            {!! Form::hidden('gender_tsas', $gender, ['id'=>'gender_tsas', 'class'=>'form-control', 'disabled'=>'disabled']) !!}
            
            <i class="email">
                - {{$email}} 
            </i><br>
            <i class="gender">
                - {!! $gender ? ucfirst($gender) : 
                    "<span class='text-danger small'>Please modify your 
                        <a target='__blank' href='/fc/profile?edit=1' class='font-weight-bold text-decoration-underline text-danger'><b>profile</b></a> to select a gender</span>" 
                    !!}                
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
        <label class="col-sm-12"><b>Type/Grade Level:</b></label>
        <div class="col-sm-12 ">            
            <!-- Attendee Member Level Field -->
            {!! Form::hidden('is_academic_staff_tsas', $attendee_member_type_flag, ['id'=>'is_academic_staff_tsas', 'class'=>'form-control', 'disabled'=>'disabled']) !!}
            
            <!-- Attendee Grade Level Field -->
            {!! Form::hidden('rank_gl_equivalent_tsas', $attendee_grade_level, ['id'=>'rank_gl_equivalent_tsas', 'class'=>'form-control', 'placeholder'=>'required field', 'disabled'=>'disabled']) !!}
            
            <i class="attendee_grade_level">
               - {{ $attendee_member_type ? ucfirst($attendee_member_type). ' Staff' : ''}} <br>
               - {{ $attendee_grade_level ? 'GL-'.ucfirst($attendee_grade_level) : ''}}
            </i>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
        <label class="col-sm-12"><b>Telephone:</b></label>
        <div class="col-sm-12 ">
            <!-- Telephone Field -->
            {!! Form::hidden('telephone_tsas', $telephone, ['id'=>'telephone_tsas', 'class'=>'form-control', 'disabled'=>'disabled']) !!}
            
            <i class="telephone">
               - {{$telephone}}
            </i>
        </div>
    </div>

</div>
<hr>

<!-- Country Field -->
<div id="div-country_id_tsas" class="form-group mb-3 col-md-6">
    <label for="country_id_tsas" class="col-sm-11 col-form-label">Country:</label>
    <div class="col-sm-12">
        <select id="country_id_select_tsas" class="form-select">
            <option value=''>-- None selected --</option>
            @if(isset($countries))
                @foreach($countries as $cont)
                    <option value='{{ $cont->id }}'> {{$cont->name}}  (  {{$cont->country_code}} ) </option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!-- Institution Field -->
{{-- <div id="div-institution_id_tsas" class="form-group mb-3 col-md-6">
    <label for="institution_id_tsas" class="col-sm-11 col-form-label">Institution:</label>
    <div class="col-sm-12">
        <select id="institution_id_select_tsas" class="form-select">
            <option value=''>-- None selected --</option>
        </select>
    </div>
</div> --}}

<!-- Institution Field -->
<div id="div-institution_name_tsas" class="form-group mb-3 col-md-6">
    <label for="institution_name_tsas" class="col-sm-11 col-form-label">Institution Name (<small class="text-danger">where tenable</small>):</label>
    <div class="col-sm-12">
        {!! Form::text('institution_name_tsas', null, ['id'=>'institution_name_tsas', 'class'=>'form-control', 'list'=>'institutions', 'placeholder'=>'required field']) !!}
        <datalist id='institutions'>
            @if(isset($world_institutions))
                @foreach($world_institutions as $institution)
                    <option value="{{ $institution }}">
                @endforeach
            @endif
        </datalist>
    </div>
</div>

<!-- Institution State Field -->
<div id="div-institution_state_tsas" class="form-group mb-3 col-md-6 col-lg-4" style="display: none;">
    <label for="institution_state_tsas" class="col-sm-11 col-form-label">Institution State (<small class="text-danger">where tenable</small>):</label>
    <div class="col-sm-12">
        <select id="institution_state_tsas" class="form-select">
            <option value=''>-- None selected --</option>
            @if(isset($nigerian_states))
                @foreach($nigerian_states as $state)
                    <option value='{{$state}}'> {{$state}} </option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!-- Name Title Field -->
{{-- <div id="div-name_title_tsas" class="form-group col-md-4 col-lg-6">
    <label for="name_title" class="col-sm-11 col-form-label">Name Title:</label>
    <div class="col-sm-12"> --}}
        {!! Form::hidden('name_title_tsas', null, ['id'=>'name_title_tsas', 'class' => 'form-control', 'placeholder'=>'optional field']) !!}
{{--     </div>
</div> --}}

<!-- Name Suffix Field -->
{{-- <div id="div-name_suffix_tsas" class="form-group col-md-4 col-lg-6">
    <label for="name_suffix" class="col-sm-12 col-form-label">Name Suffix:</label>
    <div class="col-sm-12"> --}}
        {!! Form::hidden('name_suffix_tsas', null, ['id'=>'name_suffix_tsas', 'class' => 'form-control', 'placeholder'=>'optional field']) !!}
{{--     </div>
</div> --}}

<!-- Intl Passport Number Field -->
<div id="div-intl_passport_number_tsas" class="form-group mb-3 col-md-6 col-lg-4" style="display: none;">
    <label for="intl_passport_number_tsas" class="col-sm-11 col-form-label">Intl Passport Number:</label>
    <div class="col-sm-12">
        {!! Form::text('intl_passport_number_tsas', null, ['id'=>'intl_passport_number_tsas', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- National Id Number Field -->
<div id="div-national_id_number_tsas" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="national_id_number_tsas" class="col-sm-11 col-form-label">National Id Number:</label>
    <div class="col-sm-12">
        {!! Form::text('national_id_number_tsas', null, ['id'=>'national_id_number_tsas', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Degree Type Field -->
<div id="div-degree_type_tsas" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="degree_type_tsas" class="col-sm-11 col-form-label">Degree Type:</label>
    <div class="col-sm-12">
        <select name="degree_type_tsas" id="degree_type_tsas" class="form-select">
            <option value="">-- None selected --</option>
            <option value="Ph.D">Ph.D</option>
            <option value="M.Sc">M.Sc</option>
            <option value="Bench-Work">Bench-Work</option>
            <option value="Post-Doc">Post-Doc</option>
        </select>
    </div>
</div>

<!-- Program Title Field -->
<div id="div-program_title_tsas" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="program_title_tsas" class="col-sm-12 col-form-label">Program Title:</label>
    <div class="col-sm-12">
        {!! Form::text('program_title_tsas', null, ['id'=>'program_title_tsas', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Is Science Program Field -->
<div id="div-is_science_program_tsas" class="form-group mb-3 col-md-4" style="display: none;">
    <label for="is_science_program_tsas" class="col-sm-12 col-form-label">Is Science Program ? </label>
    <div class="col-sm-12">
        <select name="is_science_program_tsas" id="is_science_program_tsas" class="form-select">
            <option value="">-- None selected --</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </div>
</div>

@php
    // $todayDate = date('Y-m-d');
    $sixMonthsAhead = date('Y-m-d', strtotime(date('Y-m-d') . ' +6 months'));
@endphp

<!-- Program Start Date Field -->
<div id="div-program_start_date_tsas" class="form-group mb-3 col-md-4">
    <label for="program_start_date_tsas" class="col-sm-12 col-form-label">Program Start Date:<i class="text-danger" id="tsas_program_start_date_notice" style="display:none;">-- (6 months ahead)</i></label>
    <div class="col-sm-12">
        {!! Form::date('program_start_date_tsas', null, ['id'=>'program_start_date_tsas', 'class' => 'form-control', 'min'=>$sixMonthsAhead]) !!}
    </div>
</div>

<!-- Program End Date Field -->
<div id="div-program_end_date_tsas" class="form-group mb-3 col-md-4">
    <label for="program_end_date_tsas" class="col-sm-12 col-form-label">Program End Date:</label>
    <div class="col-sm-12">
        {!! Form::date('program_end_date_tsas', null, ['id'=>'program_end_date_tsas', 'class' => 'form-control', 'min' => $sixMonthsAhead]) !!}
    </div>
</div>

<!-- Bank Account Name Field -->
<div id="div-bank_account_name_tsas" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_account_name_tsas" class="col-sm-11 col-form-label">Bank Account Name:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_account_name_tsas', null, ['id'=>'bank_account_name_tsas', 'class' => 'form-control','minlength' => 2,'maxlength' => 100, 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Account Number Field -->
<div id="div-bank_account_number_tsas" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_account_number_tsas" class="col-sm-11 col-form-label">Bank Account Number:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_account_number_tsas', null, ['id'=>'bank_account_number_tsas', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>

<!-- Bank Name Field -->
<div id="div-bank_name_tsas" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_name_tsas" class="col-sm-11 col-form-label">Bank Name:</label>
    <div class="col-sm-12">
        <select name="bank_name_tsas" id="bank_name_tsas" class="form-select" required>
            <option value="">Select a Bank</option>
            @foreach ($banks as $bank)
                <option value="{{ $bank['name'] }}" data-val-code="{{$bank['code']}}">{{ $bank['name'] }}</option>        
            @endforeach
        </select>
       {{--  {!! Form::text('bank_name_tsas', null, ['id'=>'bank_name_tsas', 'class' => 'form-control', 'placeholder'=>'required field']) !!} --}}
    </div>
</div>

{{-- <!-- Bank Sort Code Field -->
<div id="div-bank_sort_code_tsas" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_sort_code_tsas" class="col-sm-11 col-form-label">Bank Sort Code:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_sort_code_tsas', null, ['id'=>'bank_sort_code_tsas', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div> --}}

{{-- <!-- Bank Verification Number Field -->
<div id="div-bank_verification_number_tsas" class="form-group mb-3 col-md-6 col-lg-4">
    <label for="bank_verification_number_tsas" class="col-sm-11 col-form-label">Bank Verification Number:</label>
    <div class="col-sm-12">
        {!! Form::text('bank_verification_number_tsas', null, ['id'=>'bank_verification_number_tsas', 'class' => 'form-control', 'placeholder'=>'required field']) !!}
    </div>
</div>
 --}}
<hr>
<div class="col-sm-12" style="display: none;" id="attachments_info_tsas">
    <small>
        <i class="text-danger">
            <strong>NOTE:</strong>
            Uploading an attachment will authomatically replace older attachment provided initially. 
        </i>
    </small>
</div>

<!-- passport photo -->
<div id="div-passport_photo_tsas" class="form-group  col-md-4">
    <label for="passport_photo_tsas" class="col-sm-11 col-form-label">Passport Photo:</label>
    <div class="col-sm-12">
        <input type="file" id="passport_photo_tsas" name="passport_photo_tsas" class="form-control">
    </div>
</div>

<!-- admission letter -->
<div id="div-admission_letter_tsas" class="form-group  col-md-4">
    <label for="admission_letter_tsas" class="col-sm-12 col-form-label">Admission Letter: <i class="text-danger">(Containing tuition fee invoice)</i></label>
    <div class="col-sm-12">
        <input type="file" id="admission_letter_tsas" name="admission_letter_tsas" class="form-control">
    </div>
</div>

<!-- health report -->
<div id="div-health_report_tsas" class="form-group  col-md-4">
    <label for="health_report_tsas" class="col-sm-11 col-form-label">Health Report:</label>
    <div class="col-sm-12">
        <input type="file" id="health_report_tsas" name="health_report_tsas" class="form-control">
    </div>
</div>

<!-- curricullum vitae -->
<div id="div-curriculum_vitae_tsas" class="form-group col-md-4">
    <label for="curriculum_vitae_tsas" class="col-sm-11 col-form-label">Curriculum Vitae:</label>
    <div class="col-sm-12">
        <input type="file" id="curriculum_vitae_tsas" name="curriculum_vitae_tsas" class="form-control">
    </div>
</div>

<!-- copy of singed bond with beneficiary vitae -->
<div id="div-signed_bond_with_beneficiary_tsas" class="form-group col-md-4">
    <label for="signed_bond_with_beneficiary_tsas" class="col-sm-11 col-form-label">Signed Bond with Beneficiary:</label>
    <div class="col-sm-12">
        <input type="file" id="signed_bond_with_beneficiary_tsas" name="signed_bond_with_beneficiary" class="form-control">
    </div>
</div>

<!-- international passport bio page -->
<div id="div-international_passport_bio_page_tsas" class="form-group col-md-4" style="display: none;">
    <label for="international_passport_bio_page_tsas" class="col-sm-11 col-form-label">Int'l Passport Bio Page:</label>
    <div class="col-sm-12">
        <input type="file" id="international_passport_bio_page_tsas" name="international_passport_bio_page_tsas" class="form-control">
    </div>
</div>