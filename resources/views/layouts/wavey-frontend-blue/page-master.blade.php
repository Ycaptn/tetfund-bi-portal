<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>
            @if (isset($app_settings) && isset($app_settings['portal_short_name']))
                {{ $app_settings['portal_short_name'] }}
            @else
                @yield('title', config('app.title', 'TETFund '))
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


		<link rel="shortcut icon" href="{{ asset('imgs/logo.png') }}">
		<link rel="icon" href="{{ asset('imgs/logo.png') }}" type="image/png">

        <!--====== Slick css ======-->
        <link rel="stylesheet" href="{{ asset('wavey-frontend-blue/css/slick.css') }}">

        <!--====== Line Icons css ======-->
        <link rel="stylesheet" href="{{ asset('wavey-frontend-blue/css/LineIcons.css') }}">

        <!--====== Magnific Popup css ======-->
        <link rel="stylesheet" href="{{ asset('wavey-frontend-blue/css/magnific-popup.css') }}">

        <!--====== tailwind css ======-->
        <link rel="stylesheet" href="{{ asset('wavey-frontend-blue/css/tailwind.css') }}">

        <!--====== font awesome ======-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

		<!-- Custom CSS -->
        @yield('third_party_stylesheets')
        @stack('page_css')

        <style>
            .header-hero::before {
                content: '';
                z-index: -1;
                opacity: .9;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                --bg-opacity: 0.9;
                background-color: #bee3f8;
                background-color: rgba(190, 227, 248, var(--bg-opacity));
            }
            .services-title {
                margin-bottom: 0rem;
                font-size: 1.5rem;
                font-weight: 500;
                --text-opacity: 1;
                color: #1a202c;
                color: rgba(26, 32, 44, var(--text-opacity));
            }
        </style>
          
    </head>
    <body>


    <!--====== HEADER PART START ======-->
<div class="h-screen relative z-10 w-full" style="background-image: url({{asset('imgs/bck.jpg')}});
      no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;"
>
    <header class="header-area h-screen">
        <div class ="w-full h-screen">
            <div class="navigation">
                <div class="container">
                        <div class="w-full">

                            @yield('nav')

                        </div>
                </div> <!-- container -->
            </div> <!-- navgition -->

     
            <div id="home " class="  header-hero w-full h-screen" >
                <div class="container h-screen">
                    <div class="row h-screen justify-center items-center overflow-y-scroll">
                        <div class="w-full lg:w-5/6 xl:w-2/3 items-center">
                                @yield('body')
                        </div>
                    </div>  <!--row -->
            </div> 
        </div>
        <!-- header content -->
    </header>
  
    <!--====== HEADER PART ENDS ======-->

    @yield('footer-login')
</div>

    <!--====== jquery js ======-->
    <script src="{{ asset('wavey-frontend-blue/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset('wavey-frontend-blue/js/vendor/jquery-1.12.4.min.js') }}"></script>

    <!--====== Ajax Contact js ======-->
    <script src="{{ asset('wavey-frontend-blue/js/ajax-contact.js') }}"></script>

    <!--====== Scrolling Nav js ======-->
    <script src="{{ asset('wavey-frontend-blue/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('wavey-frontend-blue/js/scrolling-nav.js') }}"></script>

    <!--====== Validator js ======-->
    <script src="{{ asset('wavey-frontend-blue/js/validator.min.js') }}"></script>

    <!--====== Magnific Popup js ======-->
    <script src="{{ asset('wavey-frontend-blue/js/jquery.magnific-popup.min.js') }}"></script>

    <!--====== Slick js ======-->
    <script src="{{ asset('wavey-frontend-blue/js/slick.min.js') }}"></script>

    <!--====== Main js ======-->
    <script src="{{ asset('wavey-frontend-blue/js/main.js') }}"></script>

    @yield('third_party_scripts')
	@stack('third_party_scripts')

	@yield('page_scripts')
	@stack('page_scripts')
	
	@yield('page_scripts2')
	@stack('page_scripts2')


    </body>
</html>