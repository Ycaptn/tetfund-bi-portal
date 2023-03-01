<!-- Full Name Field -->
{{-- <div id="div_beneficiary_full_name" class="col-sm-12 ">
    <p>
        <strong> {!! Form::label('full_name', 'BENEFICIARY NAME:', ['class'=>'control-label']) !!} </strong> &nbsp;
        <span id="spn_beneficiary_full_name">
        @if (isset($beneficiary->full_name) && empty($beneficiary->full_name)==false)
            {!! $beneficiary->full_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div> --}}

<!-- Email Field -->
<div id="div_beneficiary_email" class="col-sm-6">
    <p>
        <strong> {!! Form::label('email', 'EMAIL:', ['class'=>'control-label']) !!} </strong>
        <span id="spn_beneficiary_email" class="col-sm-6"> &nbsp; &nbsp;
        @if (isset($beneficiary->email) && empty($beneficiary->email)==false)
            {{ $beneficiary->email }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Short Name Field -->
<div id="div_beneficiary_short_name" class="col-sm-6">
    <p>
        <strong> {!! Form::label('short_name', 'SHORT NAME:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_short_name">
        @if (isset($beneficiary->short_name) && empty($beneficiary->short_name)==false)
            {{ $beneficiary->short_name }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Official Email Field -->
<div id="div_beneficiary_official_email" class="col-sm-6">
    <p>
        <strong> {!! Form::label('official_email', 'OFFICIAL EMAIL:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_official_email">
        @if (isset($beneficiary->official_email) && empty($beneficiary->official_email)==false)
            {{ $beneficiary->official_email }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Official Website Field -->
<div id="div_beneficiary_official_website" class="col-sm-6">
    <p>
        <strong> {!! Form::label('official_website', 'OFFICIAL WEBSITE:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_official_website">
        @if (isset($beneficiary->official_website) && empty($beneficiary->official_website)==false)
            {{ $beneficiary->official_website }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Type Field -->
<div id="div_beneficiary_type" class="col-sm-6">
    <p>
        <strong> {!! Form::label('type', 'BENEFICIARY TYPE:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_type">
        @if (isset($beneficiary->type) && empty($beneficiary->type)==false)
            {{ ucwords($beneficiary->type) }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Official Phone Field -->
<div id="div_beneficiary_official_phone" class="col-sm-6">
    <p>
        <strong> {!! Form::label('official_phone', 'OFFICIAL PHONE:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_official_phone">
        @if (isset($beneficiary->official_phone) && empty($beneficiary->official_phone)==false)
            {{ $beneficiary->official_phone }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<div id="div_beneficiary_owner_agency_type" class="col-sm-6">
    <p>
        <strong> {!! Form::label('owner_agency_type', 'AGENCY TYPE:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_owner_agency_type">
        @if (isset($beneficiary->owner_agency_type) && empty($beneficiary->owner_agency_type)==false)
            {{ ucwords($beneficiary->owner_agency_type) }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Address Street Field -->
<div id="div_beneficiary_address_street" class="col-sm-6 ">
    <p>
        <strong> {!! Form::label('address_street', 'ADDRESS STREET:', ['class'=>'control-label']) !!} </strong>
        <span id="spn_beneficiary_address_street"> &nbsp; &nbsp;
        @if (isset($beneficiary->address_street) && empty($beneficiary->address_street)==false)
            {{ $beneficiary->address_street }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>
<!-- Address Town Field -->
<div id="div_beneficiary_address_town" class="col-sm-6">
    <p>
        <strong> {!! Form::label('address_town', 'ADDRESS TOWN:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_address_town">
        @if (isset($beneficiary->address_town) && empty($beneficiary->address_town)==false)
            {{ $beneficiary->address_town }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Address State Field -->
<div id="div_beneficiary_address_state" class="col-sm-6">
    <p>
        <strong> {!! Form::label('address_state', 'ADDRESS STATE:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_address_state">
        @if (isset($beneficiary->address_state) && empty($beneficiary->address_state)==false)
            {{ ucwords($beneficiary->address_state) }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Head Of Institution Title Field -->
<div id="div_beneficiary_head_of_institution_title" class="col-sm-6">
    <p>
        <strong> {!! Form::label('head_of_institution_title', 'HEAD OF BENEFICIARY TITLE:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_head_of_institution_title">
        @if (isset($beneficiary->head_of_institution_title) && empty($beneficiary->head_of_institution_title)==false)
            {{ ucwords($beneficiary->head_of_institution_title) }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Geo Zone Field -->
<div id="div_beneficiary_geo_zone" class="col-sm-6">
    <p>
        <strong> {!! Form::label('geo_zone', 'GEO ZONE:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
        <span id="spn_beneficiary_geo_zone">
        @if (isset($beneficiary->geo_zone) && empty($beneficiary->geo_zone)==false)
            {{ ucwords($beneficiary->geo_zone) }}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

@if(auth()->user()->hasAnyRole('admin'))
    <!-- Tf Iterum Portal Beneficiary Status Field -->
    <div id="div_beneficiary_tf_iterum_portal_beneficiary_status" class="col-sm-6">
        <p>
            <strong> {!! Form::label('tf_iterum_portal_beneficiary_status', 'TF ITERUM PORTAL BENEFICIARY STATUS:', ['class'=>'control-label']) !!} </strong> &nbsp; &nbsp;
            <span id="spn_beneficiary_tf_iterum_portal_beneficiary_status">
            @if (isset($beneficiary->tf_iterum_portal_beneficiary_status) && empty($beneficiary->tf_iterum_portal_beneficiary_status)==false)
                {!! $beneficiary->tf_iterum_portal_beneficiary_status !!}
            @else
                N/A
            @endif
            </span>
        </p>
    </div>
@endif

