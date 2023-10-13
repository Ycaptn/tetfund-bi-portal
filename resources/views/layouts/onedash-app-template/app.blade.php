<!doctype html>
<html lang="en" class="semi-dark color-header headercolor4">
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
	<link href="{{ asset('onedash-app-template/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
	<link href="{{ asset('onedash-app-template/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('onedash-app-template/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('onedash-app-template/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	<!-- Bootstrap CSS -->
	<link href="{{ asset('onedash-app-template/css/bootstrap.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('onedash-app-template/css/bootstrap-extended.css') }}" rel="stylesheet" />
	<link href="{{ asset('onedash-app-template/css/style.css') }}" rel="stylesheet" />
	<link href="{{ asset('onedash-app-template/css/icons.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


	<!-- loader-->
	<link href="{{ asset('onedash-app-template/css/pace.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('dist/css/font-awesome.min.css') }}" rel="stylesheet">

	<!--Theme Styles-->
	<link href="{{ asset('onedash-app-template/css/dark-theme.css') }}" rel="stylesheet" />
	<link href="{{ asset('onedash-app-template/css/light-theme.css') }}" rel="stylesheet" />
	<link href="{{ asset('onedash-app-template/css/semi-dark.css') }}" rel="stylesheet" />
	<link href="{{ asset('onedash-app-template/css/header-colors.css') }}" rel="stylesheet" />

	<link href="{{ asset('vendors/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('vendors/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('vendors/bower_components/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('vendors/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('vendors/bower_components/switchery/dist/switchery.min.css') }}" rel="stylesheet" type="text/css"/>

	<!--====== font awesome ======-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	
    @yield('cdn_scripts')
    @yield('third_party_stylesheets')
    @stack('page_css')

</head>
<body>

		<!--start wrapper-->
		<div class="wrapper">

			@include('layouts.onedash-app-template.sidebar')

		    <!--start content-->
			<main class="page-content mt-0">

				<div class="page-breadcrumb d-flex align-items-center">
					<div class="breadcrumb-title pe-3">
						@yield('page_title')
					</div>
					<div class="text-danger fs-5 ps-3 pe-3">
						@yield('page_title_suffix')
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

			</main>

		</div>
		<!--end page wrapper -->

		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->

		<!--Start Back To Top Button--> 
		<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

	</div>
	<!--end wrapper -->

	<!-- Bootstrap bundle JS -->
	<script src="{{ asset('onedash-app-template/js/bootstrap.bundle.min.js') }}"></script>
	
	<!--plugins-->
	<script src="{{ asset('onedash-app-template/js/jquery.min.js') }}"></script>
	<script src="{{ asset('onedash-app-template/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('onedash-app-template/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('onedash-app-template/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ asset('onedash-app-template/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
	<script src="{{ asset('onedash-app-template/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
	<script src="{{ asset('onedash-app-template/js/pace.min.js') }}"></script>
	<script src="{{ asset('onedash-app-template/plugins/chartjs/js/Chart.min.js') }}"></script>
	<script src="{{ asset('onedash-app-template/plugins/chartjs/js/Chart.extension.js') }}"></script>
	<script src="{{ asset('onedash-app-template/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>

	<!--app-->
	<script src="{{ asset('onedash-app-template/js/app.js') }}"></script>
	
	<script src="{{ asset('vendors/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/moment/min/moment-with-locales.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/waypoints/lib/jquery.waypoints.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/jquery.counterup/jquery.counterup.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
	<script src="{{ asset('vendors/bower_components/switchery/dist/switchery.min.js') }}"></script>
	<script type="text/javascript">
		
			$(document).ready(function(){
        $.fn.digits = function(){
            return this.each(function(){

                inputString = $(this).val().replace(/[^0-9.]/g,"");
                $(this).val( inputString.replace(
                    /^([-+]?)(0?)(\d+)(.?)(\d+)$/g, function(match, sign, zeros, before, decimal, after) {
                        var reverseString = function(string) { return string.split('').reverse().join(''); };
                        var insertCommas = function(string) {
                            var reversed = reverseString(string);
                            var reversedWithCommas = reversed.match(/.{1,3}/g).join(',');
                            return reverseString(reversedWithCommas);
                        };
                        return sign + (decimal ? insertCommas(before) + decimal + after : insertCommas(before + after));
                    }
                ));

            })
        };
    });
	</script>

	@yield('third_party_scripts')
	@stack('third_party_scripts')

	@yield('page_scripts')
	@stack('page_scripts')
	
	@yield('page_scripts2')
	@stack('page_scripts2')

</body>

</html>