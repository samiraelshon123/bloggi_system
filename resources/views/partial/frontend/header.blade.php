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
                        <li class="drop with--one--item"><a href="{{url('user/post_show/About Us')}}">About Us</a></li>
                        <li class="drop with--one--item"><a href="{{url('user/post_show/Our Vesion')}}">Our vesion</a></li>

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
            <div class="col-md-8 col-sm-8 col-5 col-lg-2">
                <ul class="header__sidebar__right d-flex justify-content-end align-items-center">
                    <li class="shop_search"><a class="search__active" href="#"></a></li>

                    <li class="shopcart"><a class="cartbox_active" href="#"><span class="product_qun">{{session()->get('user_notification')}}</span></a>
                        <!-- Start Shopping Cart -->
                        <div class="block-minicart minicart__active">
                            <div class="minicart-content-wrapper">

                              @foreach ($comments as $comment)
                              <div class="single__items">
                                <div class="miniproduct">
                                    <div class="item01 d-flex">
                                        <div class="thumb">
                                            <a href="product-details.html"><img src="{{asset('frontend/images/icons/comment.png')}}" width="50" height="50" alt="product images"></a>
                                        </div>
                                        <div class="content">
                                            <h6><a href="{{url('user/notification/'.$comment->id)}}">You have new comment on:{{$comment->post_title}}</a></h6>

                                        </div>
                                    </div>

                                </div>
                            </div>
                              @endforeach

                            </div>
                        </div>
                        <!-- End Shopping Cart -->
                    </li>




                <li class="setting__bar__icon">

                    <a id="click" class="setting__active" href="#"></a>
                        <div class="searchbar__content setting__block">
                            <div class="content-inner">

                                <div class="switcher-currency" style="visibility: visible">
                                    <strong class="label switcher-label">
                                        <span>My Account</span>
                                    </strong>
                                    <div class="switcher-options">
                                        <div class="switcher-currency-trigger">
                                            <div class="setting__menu">


                                            @guest
                                                <span><a href="{{ url('login') }}">Login</a></span>
                                                <span><a href="{{ url('register') }}">Register</a></span>
                                            @else
                                                <span><a href="{{ url('user/dashboard') }}">My Dashboard</a></span>
                                                <span><a href="{{ url('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></span>
                                                <form id="logout-form" action="{{ url('logout') }}" method="GET" style="display: none;">
                                                    @csrf
                                                </form>
                                            @endguest


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
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

    {!! Form::open(['url' => 'user/search', 'method' => 'get', 'id' => 'search_mini_form', 'class' => 'minisearch'])!!}
    <div class="field__search">
            {!! Form::text('keyword', old('keyword', request()->keyword), ['placeholder' => "Search entire store here..."]) !!}
            <div class="action">
                <a href="#" onclick="event.preventDefault(); document.getElementById('search_mini_form').submit();"><i class="zmdi zmdi-search"></i></a>
            </div>
        </div>
        {!! Form::close() !!}

    <div class="close__wrap">
        <span>close</span>
    </div>
</div>
<!-- End Search Popup -->
<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area bg-image--4">

</div>
<!-- End Bradcaump area -->

{{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav> --}}


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
