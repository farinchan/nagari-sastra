<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @php
        $setting_web = \App\Models\SettingWebsite::first();
    @endphp

    <!-- SITE TITLE -->
    <title>
        @isset($title)
            {{ $title }} |
        @endisset
        {{ $setting_web->name }}
    </title>
    @yield('seo')
    <link rel="shortcut icon" href="{{ Storage::url($setting_web->favicon) }}">


    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700;800;900&display=swap"
        rel="stylesheet">

    <!-- BOOTSTRAP CSS -->
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- FONT ICONS -->
    <link href="{{ asset('front/css/flaticon.css') }}" rel="stylesheet">

    <!-- PLUGINS STYLESHEET -->
    <link href="{{ asset('front/css/menu.css') }}" rel="stylesheet">
    <link id="effect" href="{{ asset('front/css/dropdown-effects/fade-down.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('front/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/flexslider.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/slick-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/owl.theme.default.min.css') }}" rel="stylesheet">

    <!-- TEMPLATE CSS -->
    <!-- <link href="css/azure-theme.css" rel="stylesheet">     -->
    <link href="{{ asset('front/css/blue-theme.css') }}" rel="stylesheet">
    <!-- <link href="css/brown-theme.css" rel="stylesheet">     -->
    <!-- <link href="css/dimgreen-theme.css" rel="stylesheet">  -->
    <!-- <link href="css/olive-theme.css" rel="stylesheet">     -->
    <!-- <link href="css/orange-theme.css" rel="stylesheet">    -->
    <!-- <link href="css/purple-theme.css" rel="stylesheet">    -->
    <!-- <link href="css/red-theme.css" rel="stylesheet">       -->
    <!-- <link href="css/rose-theme.css" rel="stylesheet">      -->
    <!-- <link href="css/royalblue-theme.css" rel="stylesheet"> -->
    {{-- <link href="{{ asset('front/css/skyblue-theme.css') }}" rel="stylesheet"> --}}
    <!-- <link href="css/violet-theme.css" rel="stylesheet">    -->
    {{-- <link href="{{ asset('front/css/yellow-theme.css') }}" rel="stylesheet"> --}}

    <!-- ON SCROLL ANIMATION -->
    <link href="{{ asset('front/css/animate.css') }}" rel="stylesheet">

    <!-- RESPONSIVE CSS -->
    <link href="{{ asset('front/css/responsive.css') }}" rel="stylesheet">

</head>




<body>




    <!-- PRELOADER SPINNER
  ============================================= -->
    {{-- <div id="loader-wrapper">
        <div id="loader"></div>
    </div> --}}




    <!-- PAGE CONTENT
  ============================================= -->
    <div id="page" class="page">

        @if (route('home') == url()->current())
            @include('front.partials.hero')
        @else
            @include('front.partials.breadcrumb')
        @endif

        <!-- HEADER
   ============================================= -->
        @include('front.partials.header')
        <!-- END HEADER -->


        @yield('content')



        <!-- FOOTER-2
   ============================================= -->
        @include('front.partials.footer')
        <!-- END FOOTER-2 -->




        {{-- WEBCHAT WIDGET (auto-load default widget) --}}
        @php
            $webchatWidget = \App\Models\WebchatWidget::where('is_active', true)->first();
        @endphp
        @if($webchatWidget)
            <script src="{{ url('/api/webchat/embed.js?token=' . $webchatWidget->token) }}"></script>
        @endif

    </div> <!-- END PAGE CONTENT -->




    <!-- EXTERNAL SCRIPTS
  ============================================= -->
    <script src="{{ asset('front/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front/js/modernizr.custom.js') }}"></script>
    <script src="{{ asset('front/js/jquery.easing.js') }}"></script>
    <script src="{{ asset('front/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('front/js/jquery.scrollto.js') }}"></script>
    <script src="{{ asset('front/js/menu.js') }}"></script>
    <script src="{{ asset('front/js/materialize.js') }}"></script>
    <script src="{{ asset('front/js/slick.min.js') }}"></script>
    <script src="{{ asset('front/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('front/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.flexslider.js') }}"></script>
    <script src="{{ asset('front/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('front/js/wow.js') }}"></script>

    <!-- Custom Script -->
    <script src="{{ asset('front/js/custom.js') }}"></script>

    @include('sweetalert::alert')

    @yield('scripts')

    <!-- Google Analytics: Change UA-XXXXX-X to be your site's ID. Go to http://www.google.com/analytics/ for more information. -->
    <!--
  <script>
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-XXXXX-X']);
      _gaq.push(['_trackPageview']);

      (function() {
          var ga = document.createElement('script');
          ga.type = 'text/javascript';
          ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') +
              '.google-analytics.com/ga.js';
          var s = document.getElementsByTagName('script')[0];
          s.parentNode.insertBefore(ga, s);
      })();
  </script>
  -->



</body>



</html>
