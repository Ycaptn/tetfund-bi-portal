<!-- Full Name Field -->
<div id="div-bi_full_name" class="col-md-12 form-group mt-3">
    <label for="bi_full_name" class="col-sm-12 col-form-label"><strong>Beneficiary Full Name</strong></label>
    {!! Form::text('full_name', null, ['id'=>'bi_full_name', 'class' => 'form-control']) !!}
</div>

<!-- Institution Type Field -->
<div id="div-bi_type" class="col-md-4 form-group mt-3">
    <label for="bi_type" class="col-sm-12 col-form-label"><strong>Institution Type</strong></label>
    <select class="form-select" id="bi_type" name="type" disabled='disabled'>
        <option value="">-- None Selected --</option>
        <option value="university">University</option>
        <option value="polytechnic">Polytechnic</option>
        <option value="college">College</option>
    </select>
</div>

<!-- Email Field -->
<div id="div-bi_email" class="col-md-4 form-group mt-3">
    <label for="bi_email" class="col-sm-12 col-form-label"><strong>Email</strong></label>
    {!! Form::text('email', '', ['id'=>'bi_email', 'class' => 'form-control']) !!}
</div>

<!-- Official Email Field -->
<div id="div-bi_official_email" class="col-md-4 form-group mt-3">
    <label for="bi_official_email" class="col-sm-12 col-form-label"><strong>Official Email</strong></label>
    {!! Form::text('official_email', '', ['id'=>'bi_official_email', 'class' => 'form-control','maxlength' => 200, 'disabled'=>'disabled']) !!}
</div>

<!-- Short Name Field -->
<div id="div-bi_short_name" class="col-md-4 form-group mt-3">
    <label for="bi_short_name" class="col-sm-12 col-form-label"><strong>Short Name</strong></label>
    {!! Form::text('short_name', '', ['id'=>'bi_short_name', 'class' => 'form-control']) !!}
</div>

<!-- Official Website Field -->
<div id="div-bi_official_website" class="col-md-4 form-group mt-3">
    <label for="bi_official_website" class="col-sm-12 col-form-label"><strong>Official Website</strong></label>
    {!! Form::text('official_website', '', ['id'=>'bi_official_website', 'class' => 'form-control','maxlength' => 200]) !!}
</div>

<!-- Official Phone Field -->
<div id="div-bi_official_phone" class="col-md-4 form-group mt-3">
    <label for="bi_official_phone" class="col-sm-12 col-form-label"><strong>Official Phone</strong></label>
    {!! Form::text('official_phone','', ['id'=>'bi_official_phone', 'class' => 'form-control']) !!}
</div>

<div class="col-sm-12 mt-5">
    <div class="row" style="border-top:1px solid gray;">
        <!-- Address Street Field -->
        <div id="div-bi_address_street" class="col-md-4 form-group mt-3">
            <label for="bi_address_street" class="col-sm-12 col-form-label"><strong>Address Street</strong></label>
            {!! Form::text('address_street', '', ['id'=>'bi_address_street', 'class' => 'form-control','maxlength' => 200]) !!}
        </div>

        <!-- Address Town Field -->
        <div id="div-bi_address_town" class="col-md-4 form-group mt-3">
            <label for="bi_address_town" class="col-sm-12 col-form-label"><strong>Address Town</strong></label>
            {!! Form::text('address_town', '', ['id'=>'bi_address_town', 'class' => 'form-control','maxlength' => 200]) !!}
        </div>

        <!-- Address State Field -->
        <div id="div-bi_address_state" class="col-md-4 form-group mt-3">
            <label for="bi_address_state" class="col-sm-12 col-form-label"><strong>Address State</strong></label>
            <select class="form-select" id="bi_address_state" name="address_state">
                <option value="">-- None Selected --</option>
                @if(isset($states_list) && count($states_list) > 0)
                    @foreach($states_list as $state)
                        <option value="{{strtolower($state)}}">
                            {{ ucwords($state) }}
                        </option>
                    @endforeach
                @endif
            </select>

        </div>

        <!-- Head Of Institution Title Field -->
        <div id="div-bi_head_of_institution_title" class="col-md-4 form-group mt-3">
            <label for="bi_head_of_institution_title" class="col-sm-12 col-form-label"><strong>Head Of Institution Title</strong></label>
            <select class="form-select" id="bi_head_of_institution_title" name="head_of_institution_title">
                <option value="">
                    -- None Selected --
                </option>
                <option value="chancellor">
                    Chancellor
                </option>
                <option value="vice chancellor">
                    Vice Chancellor
                </option>
                <option value="rector">
                    Rector
                </option>
                <option value="provost">
                    Provost
                </option>
                <option value="director">
                    Director
                </option>
                <option value="chairman">
                    Chairman
                </option>
                <option value="principal">
                    Principal
                </option>
            </select>
        </div>

        <!-- Geo Zone Field -->
        <div id="div-bi_geo_zone" class="col-md-4 form-group mt-3">
            <label for="bi_geo_zone" class="col-sm-12 col-form-label"><strong>Geo Zone</strong></label>
            <select class="form-select" id="bi_geo_zone" name="geo_zone">
                <option value="">-- None Selected --</option>
                @if(isset($geo_zone_list) && count($geo_zone_list) > 0)
                    @foreach($geo_zone_list as $geo_zone)
                        <option value="{{strtolower($geo_zone)}}">
                            {{ ucwords($geo_zone) }}
                        </option>

                    @endforeach
                @endif
            </select>
        </div>

        <!-- Owner Agency Type Field -->
        <div id="div-bi_owner_agency_type" class="col-md-4 form-group mt-3">
            <label for="bi_owner_agency_type" class="col-sm-12 col-form-label"><strong>Owner Agency Type</strong></label>
            <select class="form-select" id="bi_owner_agency_type" name="owner_agency_type">
                <option value="">-- None Selected --</option>
                <option value="federal">Federal</option>
                <option value="state">State</option>
            </select>
        </div>
    </div>
</div>