<!DOCTYPE html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
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
        
        <!-- Web Font -->
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">

        <!-- ========================= CSS here ========================= -->
        <link rel="stylesheet" href="{{ asset('green-impact/assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('green-impact/assets/css/LineIcons.2.0.css') }}" />
        <link rel="stylesheet" href="{{ asset('green-impact/assets/css/animate.css') }}" />
        <link rel="stylesheet" href="{{ asset('green-impact/assets/css/tiny-slider.css') }}" />
        <link rel="stylesheet" href="{{ asset('green-impact/assets/css/glightbox.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('green-impact/assets/css/main.css') }}" />


        <!-- Custom CSS -->
        @yield('third_party_stylesheets')
        @stack('page_css')

    </head>
    <body>

        <!-- Preloader -->
        <div class="preloader">
            <div class="preloader-inner">
                <div class="preloader-icon">
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        <!-- /End Preloader -->

        @yield('nav')

        @yield('errors')

        @yield('body')

        @yield('footer')

        <!-- ========================= scroll-top ========================= -->
        <a href="#" class="scroll-top btn-hover">
            <i class="lni lni-chevron-up"></i>
        </a>

        <!-- ========================= JS here ========================= -->
        <script src="{{ asset('green-impact/assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('green-impact/assets/js/count-up.min.js') }}"></script>
        <script src="{{ asset('green-impact/assets/js/wow.min.js') }}"></script>
        <script src="{{ asset('green-impact/assets/js/tiny-slider.js') }}"></script>
        <script src="{{ asset('green-impact/assets/js/glightbox.min.js') }}"></script>
        <script src="{{ asset('green-impact/assets/js/main.js') }}"></script>
        <script type="text/javascript">
            //========= Hero Slider 
            tns({
                container: '.hero-slider',
                items: 1,
                slideBy: 'page',
                autoplay: false,
                mouseDrag: true,
                gutter: 0,
                nav: true,
                controls: false,
                controlsText: ['<i class="lni lni-arrow-left"></i>', '<i class="lni lni-arrow-right"></i>'],
            });
            //====== Clients Logo Slider
            tns({
                container: '.client-logo-carousel',
                slideBy: 'page',
                autoplay: true,
                autoplayButtonOutput: false,
                mouseDrag: true,
                gutter: 15,
                nav: false,
                controls: false,
                responsive: {
                    0: {
                        items: 1,
                    },
                    540: {
                        items: 3,
                    },
                    768: {
                        items: 4,
                    },
                    992: {
                        items: 4,
                    },
                    1170: {
                        items: 6,
                    }
                }
            });
            //========= glightbox
            GLightbox({
                'href': 'https://www.youtube.com/watch?v=r44RKWyfcFw&fbclid=IwAR21beSJORalzmzokxDRcGfkZA1AtRTE__l5N4r09HcGS5Y6vOluyouM9EM',
                'type': 'video',
                'source': 'youtube', //vimeo, youtube or local
                'width': 900,
                'autoplayVideos': true,
            });
        </script>
        
    </body>

</html>