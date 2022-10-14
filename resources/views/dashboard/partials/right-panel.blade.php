@if (isset($app_settings) && isset($app_settings['portal_file_high_res_picture']))
<div class="card radius-5">
    <div class="card-body">
        <div class="text-center">

            <img style="max-width:150px;" src="{{ route('fc.attachment.show',$app_settings['portal_file_high_res_picture']) }}" />

            @if (isset($app_settings) && isset($app_settings['portal_long_name']))
                <p>{{ strtoupper($app_settings['portal_long_name']) }}</p>
            @endif

        </div>
    </div>
</div>
@endif

{{-- Load all available panels --}}
@foreach(\FoundationCore::get_right_panels($organization) as $idx=>$panel)
    @include($panel->value)
@endforeach


<div class="card radius-5">
    <div class="card-body">
        <div>
            <h5 class="card-title">Quick Links</h5>
        </div>

        <p>
            <i class="bx bx-right-arrow-alt mx-1"></i>My Submissions <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Fund Availability <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>ASTD Nominations <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Portal User Guide <br/>

            {{-- <i class="bx bx-right-arrow-alt mx-1"></i>Hosting Services <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Technical Support <br/> --}}
        </p>

    </div>
</div>

@if (Auth()->user()->hasAnyRole(['bi-staff']))
<div class="card radius-5">
    <div class="card-body">
        <div>
            <h5 class="card-title">Beneficiary Staff</h5>
        </div>

        <p class="small">
            You can use this portal to request nomination for ASTD interventions for conferences, staff training, scholoarships, and much. Contact your desk officer to nominate you to submit your details.
        </p>

    </div>
</div>
@endif

@if (Auth()->user()->hasAnyRole(['bi-ict']))
<div class="card radius-5">
    <div class="card-body">
        <div>
            <h5 class="card-title">Director ICT</h5>
        </div>

        <p class="small">
            You can use this portal to manage your submissions and identities of staff and students of your institution on the Beneficiary Identity Management Service (BIMS), enabling access to TETFund services such Mobile Data.
        </p>

    </div>
</div>
@endif

@if (Auth()->user()->hasAnyRole(['bi-lib']))
<div class="card radius-5">
    <div class="card-body">
        <div>
            <h5 class="card-title">Librarian</h5>
        </div>

        <p class="small">
            You can use this portal to manage your submissions and access to the Tertiary Education Research Application System, TeRAS, as well as the Consolidated Library Aggreegation Platform, CLAP. Enabling access to electronic journal subscription and other services being sponsored by TETFund.
        </p>

    </div>
</div>
@endif

@if (Auth()->user()->hasAnyRole(['bi-works']))
<div class="card radius-5">
    <div class="card-body">
        <div>
            <h5 class="card-title">Director Works & PI</h5>
        </div>

        <p class="small">
            You can use this portal to manage your submissions and access to the Remote Monitoring platform giving you access to live video and picture streams of your construction projects.
        </p>

    </div>
</div>
@endif