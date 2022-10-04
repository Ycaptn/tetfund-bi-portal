
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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


    <!--====== Slick css ======-->
    <link rel="stylesheet" href="{{ asset('wavey-frontend-blue/css/slick.css') }}">

    <!--====== Line Icons css ======-->
    <link rel="stylesheet" href="{{ asset('wavey-frontend-blue/css/LineIcons.css') }}">

    <!--====== Magnific Popup css ======-->
    <link rel="stylesheet" href="{{ asset('wavey-frontend-blue/css/magnific-popup.css') }}">

    <!--====== tailwind css ======-->
    <link rel="stylesheet" href="{{ asset('wavey-frontend-blue/css/tailwind.css') }}">


    <!-- Custom CSS -->
    @yield('third_party_stylesheets')
    @stack('page_css')
</head>
<body>


<!--====== HEADER PART START ======-->

<header class="header-area">
    <div class="navigation">
        <div class="container">
            <div class="row">
                <div class="w-full">

                    @yield('nav')

                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </div> <!-- navgition -->

    @yield('header-content')

    <!-- header content -->
</header>

<!--====== HEADER PART ENDS ======-->


@yield('body')

@yield('contact-us')

@yield('footer')


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


</body>
</html>