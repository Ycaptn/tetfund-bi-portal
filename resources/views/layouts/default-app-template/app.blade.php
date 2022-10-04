<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
        @if (isset($app_settings) && isset($app_settings['portal_short_name']))
            {{ $app_settings['portal_short_name'] }} ::
        @else
            @yield('title', config('app.title', 'TETFund ::'))
        @endif
        @yield('title_prefix')
        @yield('title_postfix', config('app.title_postfix', ''))
    </title>
    
    @if (isset($app_settings) && isset($app_settings['portal_seo_description']))
        <meta name="description" content="{{ $app_settings['portal_seo_description'] }}" />
    @endif

    @if (isset($app_settings) && isset($app_settings['portal_seo_keywords']))
        <meta name="keywords" content="{{ $app_settings['portal_seo_keywords'] }}" />
    @endif

    @if (isset($app_settings) && isset($app_settings['portal_analytics_code']))
        {!! $app_settings['portal_analytics_code'] !!}
    @endif
    
    <!-- Favicon -->
    @if (isset($app_settings) && isset($app_settings['portal_file_icon_picture']))
        <link rel="shortcut icon" href="{{ route('fc.attachment.show',$app_settings['portal_file_icon_picture']) }}">
        <link rel="icon" href="{{ route('fc.attachment.show',$app_settings['portal_file_icon_picture']) }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('imgs/logo.png') }}">
        <link rel="icon" href="{{ asset('imgs/logo.png') }}" type="image/png">
    @endif
	
    <!--plugins-->
	<link href="{{ asset('default-app-template/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
	<link href="{{ asset('default-app-template/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('default-app-template/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('default-app-template/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	
    <!-- loader-->
	<link href="{{ asset('default-app-template/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('default-app-template/js/pace.min.js') }}"></script>
	
    <!-- Bootstrap CSS -->
	<link href="{{ asset('default-app-template/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('default-app-template/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('default-app-template/css/icons.css') }}" rel="stylesheet">
	
	<link href="{{ asset('dist/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Theme Style CSS -->
	<link href="{{ asset('default-app-template/css/dark-theme.css') }}" rel="stylesheet" />
	<link href="{{ asset('default-app-template/css/semi-dark.css') }}" rel="stylesheet" />
	<link href="{{ asset('default-app-template/css/header-colors.css') }}" rel="stylesheet" />

	<link href="{{ asset('vendors/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('vendors/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('vendors/bower_components/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('vendors/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('vendors/bower_components/switchery/dist/switchery.min.css') }}" rel="stylesheet" type="text/css"/>
	
    @yield('cdn_scripts')
    @yield('third_party_stylesheets')
    @stack('page_css')

</head>
<body>

	<!--wrapper-->
	<div class="wrapper">

		@include('layouts.default-app-template.sidebar')

		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">

				<div class="page-breadcrumb d-none d-sm-flex align-items-center">
					<div class="breadcrumb-title pe-3">
						@yield('page_title')
					</div>
					<div class="ms-auto">
						<div class="btn-group" role="group" aria-label="Action Buttons">
							@yield('page_title_buttons')
						</div>
					</div>
				</div>

				<p class="mb-0 small">
					@yield('page_title_subtext')
				</p>

				<div class="row">
					@include('layouts.errors')
				</div>

				<div class="row my-3">
					<div class="col-lg-{{(isset($hide_right_panel) && $hide_right_panel==true)?12:9}}">
						
							@yield('content')
						
					</div>
					<div class="col-12 col-lg-3 col-xl-3 {{(isset($hide_right_panel)&&$hide_right_panel==true)?'invisible':'visible'}}">
						@yield('side-panel')
						@include('dashboard.partials.right-panel')
						@include('dashboard.partials.help-panel')
						@yield('bottom-side-panel')
					</div>
				</div>

			</div>
		</div>
		<!--end page wrapper -->


		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->

		<!--Start Back To Top Button--> 
		<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">{{ date('Y') }} &copy; ScolaLMS - Powered by <a href="http://hasob.com.ng" target="_blank">HASOB</a></p>
		</footer>

	</div>
	<!--end wrapper -->

	<!-- Bootstrap JS -->
	<script src="{{ asset('default-app-template/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('default-app-template/js/jquery.min.js') }}"></script>
	<script src="{{ asset('default-app-template/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('default-app-template/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('default-app-template/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ asset('default-app-template/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('default-app-template/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
	<script src="{{ asset('default-app-template/plugins/chartjs/js/Chart.min.js') }}"></script>
	<script src="{{ asset('default-app-template/plugins/chartjs/js/Chart.extension.js') }}"></script>
	
	<script src="{{ asset('vendors/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/moment/min/moment-with-locales.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/waypoints/lib/jquery.waypoints.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/jquery.counterup/jquery.counterup.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/switchery/dist/switchery.min.js') }}"></script>
	
	{{-- <script src="{{ asset('default-app-template/js/index.js') }}"></script> --}}
	<!--app JS-->
	<script src="{{ asset('default-app-template/js/app.js') }}"></script>


	@yield('third_party_scripts')
	@stack('third_party_scripts')

	@yield('page_scripts')
	@stack('page_scripts')
	
	@yield('page_scripts2')
	@stack('page_scripts2')

</body>

</html>