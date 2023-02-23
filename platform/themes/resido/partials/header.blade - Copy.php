@php
    $supportedLocales = Language::getSupportedLocales();
    if (!isset($options) || empty($options)) {
        $options = [
            'before' => '',
            'lang_flag' => true,
            'lang_name' => true,
            'class' => '',
            'after' => '',
        ];
    }
@endphp
@php
session_start()  
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/js/intlTelInput-jquery.min.js"></script>
    <!-- <link href="./themes/resido/css/font-awesome.css" rel="stylesheet"> -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- Date picker code start here --> 
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Date picker code end here -->


    <!-- <script src="https://dev.caravanek.com/public/vendor/core/core/acl/js/profile.js"></script>
    <script src="https://dev.caravanek.com/vendor/core/plugins/real-estate/js/app.js"></script> -->

    <!-- Fonts-->
    <!--<link href="https://fonts.googleapis.com/css2?family={{ urlencode(theme_option('font_heading', 'Jost')) }}:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family={{ urlencode(theme_option('font_body', 'Muli')) }}:300,400,600,700" rel="stylesheet" type="text/css"> -->
    <!-- CSS Library-->

    <style>
        :root {
            --primary-color: {{ theme_option('primary_color', '#2b4db9') }};
            --font-body: {{ theme_option('font_body', 'Muli') }}, sans-serif;
            --font-heading: {{ theme_option('font_heading', 'Jost') }}, sans-serif;
        }
    </style>

    <script>
        "use strict";
        window.trans = {
            "Price": "{{ __('Price') }}",
            "Number of rooms": "{{ __('Number of rooms') }}",
            "Number of rest rooms": "{{ __('Number of rest rooms') }}",
            "Square": "{{ __('Square') }}",
            "No property found": "{{ __('No property found') }}",
            "million": "{{ __('million') }}",
            "billion": "{{ __('billion') }}",
            "in": "{{ __('in') }}",
            "Added to wishlist successfully!": "{{ __('Added to wishlist successfully!') }}",
            "Removed from wishlist successfully!": "{{ __('Removed from wishlist successfully!') }}",
            "I care about this property!!!": "{{ __('I care about this property!!!') }}",
            "See More Reviews": "{{ __('See More Reviews') }}",
            "Reviews": "{{ __('Reviews') }}",
            "out of 5.0": "{{ __('out of 5.0') }}",
            "service": "{{ trans('plugins/real-estate::review.service') }}",
            "value": "{{ trans('plugins/real-estate::review.value') }}",
            "location": "{{ trans('plugins/real-estate::review.location') }}",
            "cleanliness": "{{ trans('plugins/real-estate::review.cleanliness') }}",
        }
        window.themeUrl = '{{ Theme::asset()->url('') }}';
        window.siteUrl = '{{ url('') }}';
        window.currentLanguage = '{{ App::getLocale() }}';
    </script>

    {!! Theme::header() !!}
</head>
<body class="{{ theme_option('skin', 'blue') }}" @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif>
<div id="alert-container"></div>

@if (theme_option('preloader_enabled', 'no') == 'yes')
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div id="preloader"><div class="preloader"><span></span><span></span></div></div>
@endif
<div class="main_wrap">
    <header>
        <nav class="navbar navbar-default navbar-fixed-top cutom_menu" data-sidebarClass="navbar-dark bg-dark">
            <div class="main_navbar_section">   
                <div class="container-fluid">
                     @if (theme_option('logo'))
                        <a class="navbar-brand" href="{{ route('public.index') }}">
                            <img alt="logo" class="img-responsive"  src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="{{ setting('site_title') }}">
                        </a>
                    @endif
                    <!-- <a class="navbar-brand" href="#"><img src="images/logo.png" alt="logo" class="img-responsive" ></a> -->
                    <button class="navbar-toggler leftNavbarToggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                        aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                        <ul class="nav navbar-nav nav-flex-icons ml-auto">
                        @if (auth('account')->check())
                        <li class="nav-item active">
                                <a class="nav-link" href="/account/dashboard">{{ __('Dashboard') }}</a>
                                </li>   
                                <li class="nav-item">
                                <a href="#" class="nav-link"
                                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();" rel="nofollow"> {{ __('Logout') }}</a></li>
                            @else
                               
                            <li class="nav-item active">
                                <a class="nav-link" href="/register">{{ __('Sign Up') }}</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" href="/login">{{ __('Log In') }}</a>
                                </li> 
                            @endif

                                
                           
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">{{ __('Help') }} <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                <div class="dropdown-menu" aria-labelledby="dropdown01">
                                    <a class="dropdown-item" href="#">{{ __('Help Center') }}</a>
                                    <a class="dropdown-item" href="#">{{ __('Give Feedback') }}</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown"> 
                                @php
                                    $languageDisplay = setting('language_display', 'all');
                                    $showRelated = setting('language_show_default_item_if_current_version_not_existed', true);
                                @endphp
                             
                                <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">@foreach ($supportedLocales as $localeCode => $properties)
                                    @if ($localeCode == Language::getCurrentLocale())
                                              {{ $properties['lang_name'] }} 
                                    @endif
                                 @endforeach<i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                <div class="dropdown-menu" aria-labelledby="dropdown01">
                                    @foreach ($supportedLocales as $localeCode => $properties)
                                        <a  rel="alternate" hreflang="{{ $localeCode }}" href="{{ $showRelated ? Language::getLocalizedURL($localeCode) : url($localeCode) }}" class="dropdown-item" >{{ $properties['lang_name'] }}</a>
                                    @endforeach
                                </div>  
                            </li>
                        </ul>
                       <div class="caravan_btn my-2 my-lg-0">
                            <a class="buttonStyle-new" href="#">{{ __('List Your Caravan') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
