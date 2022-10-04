@if (isset($app_settings['portal_contact_name']) || isset($app_settings['portal_contact_phone']) || isset($app_settings['portal_contact_email']))
<div class="card radius-5 border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div>
            <h5 class="card-title">Help & Support</h5>
        </div>

        <p>If you are having challenges with your account please contact;</p>

        @if (isset($app_settings) && isset($app_settings['portal_contact_name']))
			{{ $app_settings['portal_contact_name'] }}
            <br/>
		@endif
        @if (isset($app_settings) && isset($app_settings['portal_contact_phone']))
            <i class="bx bx-phone-call"></i> {{$app_settings['portal_contact_phone']}}
            <br/>
		@endif
        @if (isset($app_settings) && isset($app_settings['portal_contact_email']))
            <i class="bx bx-mail-send"></i> {{$app_settings['portal_contact_email']}}
            <br/>
		@endif
    </div>
</div>
@endif