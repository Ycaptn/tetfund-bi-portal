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
            <i class="bx bx-right-arrow-alt mx-1"></i>My Inflow files <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Files & Memos <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Staff Directory <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Department Directory <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Employee Shared Services <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Portal User Guide <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Notice Board<br/>

            {{-- <i class="bx bx-right-arrow-alt mx-1"></i>Hosting Services <br/>
            <i class="bx bx-right-arrow-alt mx-1"></i>Technical Support <br/> --}}
        </p>

    </div>
</div>
