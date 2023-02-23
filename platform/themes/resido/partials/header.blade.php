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
session_start()  
@endphp
@php
    $addLink = ''; 
    
    if( Request::segment(1) == 'en'){
        $addLink = '/en';
    }
    if( Request::segment(1) == 'es'){
        $addLink = '/es';
    }
    if( Request::segment(1) == 'fr'){
        $addLink = '/fr';
    }

    $addLink_Param = ''; 
     foreach ($supportedLocales as $localeCode => $properties){ 
         if ($localeCode == Language::getCurrentLocale()){
            
             $addLink_Param = '?language='.$properties['lang_code'];
             
         }
     }

     $website_settings = [
        
        'website_icon_url' => 'https://caravanek.com/themes/resido/caravanek_logo_400x400.jpg',
        'website_cover' => 'https://caravanek.com/themes/resido/caravanek_logo_400x400.jpg',
        'website_name' => __('شركة البيوت المتنقلة المحدودة.') .' - '. __('caravanek'),
        'website_url' =>  'https://caravanek.com',
        'phone' =>  '966562441995',
        'search_url' => env('APP_URL')."/q",
        
        ];

@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport"/>
    <meta name="appleid-signin-client-id" content="com.caravanek.webapp">
    <meta name="appleid-signin-scope" content="name email">
    <meta name="appleid-signin-redirect-uri" content="https://caravanek.com/auth/callback/apple">
    <meta name="appleid-signin-state" content="origin:web">
    <meta name="appleid-signin-use-popup" content="true">  
    <meta name="appleid-signin-response-type" content="code">     
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- /. -----  seo ---- -->
    <meta name="author" content="{{$website_settings['website_name']}}" />
    <meta property="og:locale" content="{{Request::segment(1)}}"/>
    <meta property="og:locale:alternate" content="{{Request::segment(1)}}"/>
    <meta property="og:image" content="https://caravanek.com/themes/resido/caravanek_logo_400x400.jpg" />
    <meta property="twitter:image:src" content="https://caravanek.com/themes/resido/caravanek_logo_400x400.jpg">
    <meta name="twitter:image" content="https://caravanek.com/themes/resido/caravanek_logo_400x400.jpg">
    <meta name="twitter:card" content="{{__('RVs & Resorts rental, online platform for related services')}}">
    <meta name="twitter:site" content="@Caravanek_com">
    <meta name="twitter:creator" content="@Caravanek_com" />
    <meta name="twitter:image.src" content="https://caravanek.com/themes/resido/caravanek_logo_400x400.jpg" />

    <link rel="canonical" href="{{request()->url()}}">
    <link rel="alternate" hreflang="ar" href="{{request()->url()}}">

    <meta name="keywords" content=' {{__("caravans" ) }},{{__("كرفانة") }}, {{__("كرفان للبيع") }}, {{__("كرفان للايجار") }}, {{__("الكرفانات") }}, {{__("caravanek" ) }},   {{__("Rental Parking Caravans") }}, {{__("Caravan services") }}, {{__("Caravan rental") }}, {{__("caravan" ) }}, {{__("travel" ) }}, {{__("tourism" ) }}, {{__("Saudi Arabia") }}, {{__("camping" ) }}, {{__("Caravan Resorts") }}, {{__("Caravan Parts") }}' />
    
        
    <meta name="msapplication-TileColor" content="{{ theme_option('skin', 'blue') }}">
    <meta name="msapplication-TileImage" content="{{$website_settings['website_icon_url']}}">
    <meta name="msapplication-square70x70logo" content="{{$website_settings['website_cover']}}" />
    <meta name="msapplication-square150x150logo" content="{{$website_settings['website_cover']}}" />
    <meta name="msapplication-wide310x150logo" content="{{$website_settings['website_cover']}}" />
    <meta name="msapplication-square310x310logo" content="{{$website_settings['website_cover']}}" />
    <link rel="apple-touch-icon-precomposed" href="{{$website_settings['website_cover']}}" />

    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content='{{__("caravans" ) }}'>
    <link rel="apple-touch-icon" href="{{$website_settings['website_icon_url']}}?v=2">

    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="{{$website_settings['website_icon_url']}}" style="@media(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" /> 


    <link rel='alternate' href="{{request()->url()}}" hreflang='x-default' />
<!-- 
    <meta itemprop="name" content="{{__('caravanek')}} " />
    <meta itemprop="url" content="{{request()->url()}}" />
    <meta itemprop="author" content="{{$website_settings['website_name']}}" />
    <meta itemprop="image" content="https://caravanek.com/themes/resido/caravanek_logo_400x400.jpg" />
    <meta itemprop="description" content="{{theme_option('seo_description')}} " /> -->

    <!-- google search console -->
    <meta name="google-site-verification" content="JRPjTkhRkNWavL-rpHHxYlhgF5Ts0dd4x4WVM7S3sUY" />


    <script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "Organization",
    "name": "{{$website_settings['website_name']}}",
    "url": "{{$website_settings['website_url']}}",
    "logo": "{{$website_settings['website_icon_url']}}",
     
    "contactPoint": [
        @if($website_settings['phone']!=null)
        {
            "@type": "ContactPoint",
            "telephone": "{{$website_settings['phone']}}",
            "contactType": "customer support"
        },
        {
            "@type": "ContactPoint",
            "telephone": "{{$website_settings['phone']}}",
            "contactType": "technical support"
        }, {
            "@type": "ContactPoint",
            "telephone": "{{$website_settings['phone']}}",
            "contactType": "billing support"
        }
        @endif
    ]
}
{
    "@context": "http://schema.org",
    "@type": "WebSite",
    "url": "{{$website_settings['website_url']}}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{$website_settings['search_url']}}?key={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{theme_option('site_title')}}",
    "description": "{{theme_option('seo_description')}}",
    "publisher": {
        "@type": "Organization",
        "name": "{{$website_settings['website_name']}}"
    }
}
</script>

 <!-- /. -----  seo ---- -->




<!-- /. google adsense -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4248476627593722" crossorigin="anonymous"></script>
<!-- ./ google adsense -->
<!-- Google Tag Manager --> 
<!-- End Google Tag Manager -->
     
    <style>
        :root {
            --primary-color: {{ theme_option('primary_color', '#2b4db9') }};
            --font-body: {{ theme_option('font_body', 'Muli') }}, sans-serif;
            --font-heading: {{ theme_option('font_heading', 'Jost') }}, sans-serif;
        }
        .destinations_right .item-wrap {
            overflow: hidden;
            position: relative;
            width: 100%;
            height: 558px !important;
        }

        /*Apple btn style start here*/
        .signin-button {
            height: 56px;
        }
        .signin-button > div > div > svg {  
            height: 50px;  
            width: 100%;   
        } 
        .link {
            display: inline-block;
            margin-top: 0px;
            color: white;
        }
        
        /*Apple btn style end here*/
    </style>

    <script >
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
    <script >
        
        // Listen for authorization success.
        document.addEventListener('AppleIDSignInOnSuccess', (data) => {
            // Handle successful response.
            if(data.type == "AppleIDSignInOnSuccess"){ 
                var appleToken = data.detail.authorization.id_token ;
                var appleCode = data.detail.authorization.code ;
                
                // console.log("Token: "+appleToken);
                // console.log("Code: "+appleCode); 
                // https://programmierfrage.com/items/signature-verification-failed-apple-signin-using-firebase-jwt
                // url: "/auth/callback/apple?authCode="+appleCode+"&idToken="+appleToken, 
                // var appleUser = JSON.parse(result);
                // console.log(data.detail);
                // console.log("Customer Email: " + appleUser.email);

                jQuery.ajax({
                    type: "post",
                    url: "/auth/callback/apple", 
                    data: JSON.stringify({ 
                        "idToken": appleToken, 
                        "authCode" : appleCode , 
                        "_token": "{{ csrf_token() }}",
                    }),
                    dataType: "json",
                    contentType: "application/json; charset=utf-8", 
                    success: function(result){
                        var error = result.error;
                        if(error == false){
                            window.location.href = '{{ $addLink }}/account/dashboard'
                        }
                    }
                });
            }
        });
    </script>
    
    
    <!-- Compiled and minified CSS -->
    {!! Theme::header() !!}
    <!-- -------------magh -->
</head>
<body class="{{ theme_option('skin', 'blue') }}" @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif>
<!-- Google Tag Manager (noscript) --> 
<!-- End Google Tag Manager (noscript) -->



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
                            <img   class="img-responsive"  src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="{{ theme_option('site_title') }}">
                        </a>
                    @endif
                    <!-- <a class="navbar-brand" href="#"><img src="images/logo.png" alt="logo" class="img-responsive" ></a> -->
                    <button class="navbar-toggler leftNavbarToggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                        aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                        <ul class="nav navbar-nav nav-flex-icons ml-auto">
                             <li class="nav-item active"> 
                                <a class="nav-link" href="{{ $addLink }}/about-us{{ $addLink_Param}}">{{ __('About us') }} </a>
                            </li>  
                             <li class="nav-item active"> 
                                <a class="nav-link" href="{{ $addLink }}/news{{ $addLink_Param}}">{{ __('blog') }}</a>
                            </li>  
                            @if (auth('account')->check()) 
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ route('public.account.dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>   
                            <li class="nav-item">
                                <a href="{{ $addLink }}" class="nav-link"
                                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();" rel="nofollow"> {{ __('Logout') }}</a>
                            </li>
                            @else
                               
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ $addLink }}/register">{{ __('Sign Up') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $addLink }}/login">{{ __('Log In') }}</a>
                            </li> 
                            @endif


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="{{ $addLink }}/contact" >{{ __('contact us') }} </a>
                                 
                            </li>
                            <li class="nav-item dropdown"> 
                                @php
                                    $languageDisplay = setting('language_display', 'all');
                                    $showRelated = setting('language_show_default_item_if_current_version_not_existed', true);
                                @endphp
                             
                                <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    @foreach ($supportedLocales as $localeCode => $properties)
                                    @if ($localeCode == Language::getCurrentLocale())
                                              {{ $properties['lang_name'] }} 
                                    @endif
                                    @endforeach
                                 <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                <div class="dropdown-menu" aria-labelledby="dropdown01">
                                    @foreach ($supportedLocales as $localeCode => $properties)
                                        @php
                                        $addLink_Param = '?language='.$properties['lang_code'];
                                        @endphp
                                        <a  rel="alternate"   data-hreflang="{{ $localeCode }}" data-flag="{{ $properties['lang_code'] }}" href="javascript:void(0)" data-flag2="{{ $showRelated ? Language::getLocalizedURL($localeCode) : url($localeCode) }}"     class="post_lang_choice_id dropdown-item" > {{ $properties['lang_name'] }}</a>
                                    @endforeach
                                </div> 

                                 
                                
                                

                            </li>
                        </ul>
                        @if (auth('account')->check())
                            <form id="logout-form" action="{{ route('public.account.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endif
                        <div class="caravan_btn my-2 my-lg-0">
                             
                             @php
                                $current_url = URL::current();
                                $array = explode('/', $current_url);  
                             @endphp
                             
                             @if (in_array("news", $array))
                             
                                <a class="buttonStyle-new" href="{{ route('public.account.blogs.create') }}">{{ __('Add a subject') }}</a>
                             @else

                             <a class="buttonStyle-new" href="{{ route('public.account.properties.create') }}">{{ __('add your advertising') }}</a>
                             @endif
                        </div> 
                    </div>
                </div>
            </div>
        </nav>





 
                <div class="property_block_wrap social_share_panel_wrap style-2 p-4">

                    <div class="social_share_panel_float top_start">
                            <a href="https://www.facebook.com/Caravanek_com-110406761577331/"
                            target="_blank" class="share_icons cl-facebook">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                            <a href="https://twitter.com/Caravanek_com"
                            target="_blank" class="share_icons cl-twitter">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                            <a href="https://www.linkedin.com/company/motorhome-co-ltd" 
                                target="_blank" class="share_icons cl-linkedin">
                                <i class="fa fa-linkedin" aria-hidden="true"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send/?phone=%2B966562441995&text&app_absent=0" 
                                target="_blank" class="share_icons cl-whatsapp">
                                <i class="fa fa-whatsapp" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            
 



    </header> 


<script >
    jQuery(function($){

         
        $(document).on("click",".post_lang_choice_id",function(){ 
             
            let main__url = $(this).data('flag2');
            var full__main__url = main__url.split('?')[0]+'?language='+$(this).data('flag');
            window.location.href = full__main__url ;
          
            //  console.log(  location.host , location.pathname) 
            //  console.log(main__url.split('?')[0])
            //  console.log(full__main__url)
        });
    });
</script>




<style type="text/css">
    .property_block_wrap.social_share_panel_wrap {
    position: relative;
    overflow-x: hidden;
}
    .top_start {
        position: fixed;
        top: 40%;
        left: 10px;
        bottom: 0;
        z-index: 1;
    }
    .top_start.share_fixed {
    position: absolute;
    bottom: 22%;
    top: unset;
    transform: unset;
    left: 1px;
    z-index: 9;
}
    .top_start a {
        display: block;
        background: #12a3dc;
        width: 25px;
        height: 25px;
        text-align: center;
        line-height: 25px;
        margin: 5px 0;
        border-radius: 50px;
    }
 

    .top_start a i {
        color: #fff !important;
    }
</style>
<script type="text/javascript">
    $(window).scroll(function () {
        // var offsetStart = $(".property_block").offset().top;
        // if ($(window).scrollTop() >= offsetStart) {
        //     jQuery('.social_share_panel_float').addClass('share_fixed');
        // }else{
        //     jQuery('.social_share_panel_float').removeClass('share_fixed');
        // }

        // console.log($(window).scrollTop(),"sdfsd");
        // console.log($(".featured_slick_gallery").offset().top,"000");
        // var offsetEnd = $(".featured_slick_gallery").offset().top;
        // if ($(window).scrollTop() >= offsetEnd) {
        //     // console.log('if')
        //     jQuery('.social_share_panel_float').addClass('top_start');
        // }else{
        //     // console.log('else')
        //     jQuery('.social_share_panel_float').removeClass('top_start');
        // }

        // var offsetEnd = $(".featured_slick_padd").offset().top;
        // if ($(window).scrollTop() >= offset) {
        //     jQuery('.social_share_panel_float').addClass('share_fixed');
        // }

    });
</script>
 