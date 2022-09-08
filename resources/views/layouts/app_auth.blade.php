<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="userId" content="{{ auth()->check() ? auth()->id() : '' }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="">

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Google font (font-family: 'Roboto', sans-serif; Poppins ; Satisfy) -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,600i,700,700i,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link href="{{ asset('frontend/js/bootstrap-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />

    <!-- Modernizer js -->
    <script src="{{ asset('frontend/js/vendor/modernizr-3.5.0.min.js') }}"></script>

    @yield('style')
</head>
<body>
    <div id="app">
        <div class="wrapper" id="wrapper">

            <!-- Header -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

<header id="wn__header" class="oth-page header__area header__absolute sticky__header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-7 col-lg-2">
                <div class="logo">
                    <a href="{{url('user')}}">
                        <img src="{{asset('frontend/images/logo/logo.png')}}" alt="logo images">
                    </a>
                </div>
            </div>
            <div class="col-lg-8 d-none d-lg-block">
                <nav class="mainmenu__nav">
                    <ul class="meninmenu d-flex justify-content-start">
                        <li class="drop with--one--item"><a href="{{url('user')}}">Home</a></li>
                        <li class="drop with--one--item"><a href="{{url('user/post_show/about_us')}}">About Us</a></li>
                        <li class="drop with--one--item"><a href="{{url('user/post_show/our_vession')}}">Our vesion</a></li>

                        <li class="drop"><a href="#">Blog</a>
                            <div class="megamenu dropdown">
                                <ul class="item item01">
                                    @foreach ($global_categories as $global_category)
                                    <li><a href="{{url('user/category/'.$global_category->id)}}">{{$global_category->name}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                        <li><a href="{{url('user/contact-us')}}">Contact</a></li>
                    </ul>
                </nav>
            </div>
           
        </div>
        <!-- Start Mobile Menu -->
        <div class="row d-none">
            <div class="col-lg-12 d-none">
                <nav class="mobilemenu__nav">
                    <ul class="meninmenu">
                        <li class="drop with--one--item"><a href="{{url('user')}}">Home</a></li>
                        <li class="drop with--one--item"><a href="{{url('post_show/about_us')}}">About Us</a></li>
                        <li class="drop with--one--item"><a href="{{url('post_show/our_vession')}}">Ourvesion</a></li>

                        <li><a href="#">Blog</a>
                            <ul>
                                @foreach ($global_categories as $global_category)
                                    <li><a href="{{url('user/category/'.$global_category->id)}}">{{$global_category->name}}</a></li>
                                @endforeach

                            </ul>
                        </li>
                        <li><a href="{{url('user/contact-us')}}">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- End Mobile Menu -->
        <div class="mobile-menu d-block d-lg-none">
        </div>
        <!-- Mobile Menu -->
    </div>
</header>
<!-- //Header -->
<!-- Start Search Popup -->
<div class="box-search-content search_active block-bg close__top">
    <form id="search_mini_form" class="minisearch" action="#">
        <div class="field__search">
            <input type="text" placeholder="Search entire store here...">
            <div class="action">
                <a href="#"><i class="zmdi zmdi-search"></i></a>
            </div>
        </div>
    </form>
    <div class="close__wrap">
        <span>close</span>
    </div>
</div>
<!-- End Search Popup -->
<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area bg-image--4">

</div>
<!-- End Bradcaump area -->


            <main>
                @include('partial.flash')
                @yield('content')
            </main>

            @include('partial.frontend.footer')

        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('frontend/js/plugins.js') }}"></script>
    <script src="{{ asset('frontend/js/active.js') }}"></script>

    <script src="{{ asset('frontend/js/bootstrap-fileinput/js/plugins/piexif.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap-fileinput/js/plugins/sortable.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap-fileinput/js/plugins/purify.min.js') }}"></script>

    <script src="{{ asset('frontend/js/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap-fileinput/themes/fa/theme.js') }}"></script>


    <script src="{{ asset('frontend/js/custom.js') }}"></script>
    @yield('script')
</body>
</html>
