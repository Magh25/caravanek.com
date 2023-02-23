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

    $showRelated = '';

    $addLink_Param = '';
    $supportedLocales = Language::getSupportedLocales();
    foreach ($supportedLocales as $localeCode => $properties){ 
        if ($localeCode == Language::getCurrentLocale()){
           
            $addLink_Param = '?language='.$properties['lang_code'];
            
        }
    }
     
@endphp
    <footer class="footer">
        <div class="footer_sec">
            <div class="container">
                <div class="row">
                    <div class="footer_wrap">
                        <div class="colum_footer">
                            <h5>{{ __('Caravanek') }} 
                                 
                            </h5>
                            <ul class="footer_items">
                                <li class="item"><a href="{{ $addLink }}/about-us"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('About us') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/trust-and-safety"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Trust and Safety') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/careers"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Careers') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/how-it-works"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('How it Works') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/news"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Blog') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Sitemap') }}</a></li>
                            </ul>
                        </div>
                        <div class="colum_footer center_line"><img src="/themes/resido/img/border_line.png" alt="{{ __('border_line') }}"></div>
                        <div class="colum_footer">
                            <h5>{{ __('Explore') }}</h5>
                            <ul class="footer_items">
                                <li class="item"><a href="{{ $addLink }}/national-parks"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('National Parks') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/national-reserves"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('National reserves') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/national-heritage-sites"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('National Heritage Sites') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/campgrounds"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Campgrounds') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/dump-stations"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Dump Stations') }}</a></li>
                            </ul>
                        </div>
                        <div class="colum_footer center_line"><img src="/themes/resido/img/border_line.png" alt="{{ __('border_line') }}"></div>
                        <div class="colum_footer">
                            <h5>{{ __('Renters') }}</h5>
                            <ul class="footer_items">
                                <li class="item"><a href="{{ $addLink }}/listing"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Search Caravan Rentals') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/worry-free-rental-guarantee"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Worry-free Rental Guarantee') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/temporary-housing{{$addLink_Param}}"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Temporary Housing') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/one-way-caravan-rentals"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('One Way Caravan Rentals') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/refer-a-friend-get-sar-25-off"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Refer a Friend, Get SAR 25 Off!') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/contact"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Help and Support') }}</a></li>
                            </ul>
                        </div>
                        
                        <div class="colum_footer center_line"><img src="/themes/resido/img/border_line.png" alt="{{ __('border_line') }}"></div>
                        <div class="colum_footer">
                            <h5>{{ __('Owners') }}</h5>
                            <ul class="footer_items">
                                <li class="item"><a href="{{ $addLink }}/account/properties/create"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('List Your Caravan') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/owner-toolkit"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Owner Toolkit') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/insurance"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Insurance') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/disaster-relief"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Disaster Relief') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/refer-an-owner-get-sar100"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Refer an Owner, Get SAR100') }}</a></li>
                                <li class="item"><a href="{{ $addLink }}/contact"><i class="fa fa-angle-double-right" aria-hidden="true"></i>{{ __('Feedback') }}</a></li>
                            </ul>
                        </div>
                    
                    </div>
                </div>              
                <div class="row">
                    <div class="copy_right">
                        <p>{{ __('© Copy right') }} {{date('Y')}} {{ __('شركة البيوت المتنقلة المحدودة.') }} &nbsp;&nbsp;| <a href="{{ $addLink }}/terms-conditions{{$addLink_Param}}">{{ __('Terms of Service') }}</a> | <a href="{{ $addLink }}/privacy-policy{{$addLink_Param}}">{{ __('Privacy Policy') }}</a>  | <a href="{{ $addLink }}/cancellation-and-retrieval-policy{{$addLink_Param}}">{{ __('Cancellation and Retrieval Policy') }}</a></p>
                    </div>
                     
                </div> 
                <div class="row">
                    <div class="copy_right">
                        <p> {{__(' رقم السجل التجاري :2055131437 | العنوان : 966562441995 | 2169 المللك عبدالله | الجبيل | الرمز البريدي:35513 | المملكة العربية السعودية ') }}</p>
                    </div>
                     
                </div>              
            </div>
        </div>
        <div class="footer_bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 social_colum">
                        <ul class="social_icons">
                            <li>
                                <a target="_blank" href="https://www.facebook.com/Caravanek_com-110406761577331/">
                                    <i class="fa fa-facebook" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="https://www.instagram.com/caravanek_com/">
                                    <i class="fa fa-instagram" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="https://twitter.com/Caravanek_com">
                                    <i class="fa fa-twitter" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="https://www.youtube.com/channel/UC7AoBdlO-8R75dEhRQpT85A">
                                    <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="https://tiktok.com/@caravanek_com">
                                    <img src="/themes/resido/img/tiktok-icon.png" alt="{{ __('@caravanek_com') }}">
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="https://snapchat.com/add/caravanekcom">
                                    <i class="fa fa-snapchat-ghost" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="https://api.whatsapp.com/send/?phone=%2B966562441995&text&app_absent=0">
                                    <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="https://www.linkedin.com/company/motorhome-co-ltd">
                                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 payment_colum">
                        <ul class="pay_icons  "> 

                            <li><a   class="col-sm-2 .android_payment" ><img style="" src="/themes/resido/img/mastercard.jpeg" alt="{{ __('@caravanek_com') }}"></a></li>
                            <li><a   class="col-sm-2 .android_payment" ><img style="" src="/themes/resido/img/mada.jpeg" alt="{{ __('@caravanek_com') }}"></a></li>
                            <li><a   class="col-sm-2 .android_payment" ><img style="" src="/themes/resido/img/visa.jpeg" alt="{{ __('@caravanek_com') }}"></a></li>
                            <li><a onClick="divFunction()" class="col-sm-2 .android_payment" ><img src="/themes/resido/img/android_payment.png" alt="{{ __('@caravanek_com') }}"></a></li>
                            <li><a onClick="divFunction()" class="col-sm-2 .android_payment" ><img src="/themes/resido/img/apple_icon.png" alt="{{ __('@caravanek_com') }}"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>          
    </footer>   
</div>
{!! Theme::footer() !!}
@if (session()->has('success_msg') || session()->has('error_msg') || (isset($errors) && $errors->count() > 0) || isset($error_msg))
    <script >
        "use strict";
        $(document).ready(function () {
            @if (session()->has('success_msg'))
            window.showAlert('alert-success', '{{ session('success_msg') }}');
            @endif

            @if (session()->has('error_msg'))
            window.showAlert('alert-danger', '{{ session('error_msg') }}');
            @endif

            @if (isset($error_msg))
            window.showAlert('alert-danger', '{{ $error_msg }}');
            @endif

            @if (isset($errors))
            @foreach ($errors->all() as $error)
            window.showAlert('alert-danger', '{!! $error !!}');
            @endforeach
            @endif

       

        });
    </script>
    @endif
<?php /*
Airtel-scf50@esteplogic
<script src="<?php echo url('/'); ?>/themes/resido/plugins/leaflet.js"></script>
<script src="<?php echo url('/'); ?>/themes/resido/plugins/leaflet.markercluster-src.js"></script>

<script src="<?php echo url('/'); ?>/vendor/core/core/media/libraries/lodash/lodash.min.js"></script>

    <script src="<?php echo url('/'); ?>/vendor/core/core/base/libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js?v=5.22"></script>
    <script src="<?php echo url('/'); ?>/vendor/core/core/base/js/core.js?v=5.22"></script>
    <script src="<?php echo url('/'); ?>/vendor/core/core/base/libraries/toastr/toastr.min.js?v=5.22"></script>
    <script src="<?php echo url('/'); ?>/vendor/core/core/base/libraries/fancybox/jquery.fancybox.min.js?v=5.22"></script>
    <script src="<?php echo url('/'); ?>/vendor/core/plugins/language/js/language-global.js?v=5.22"></script>

    <!-- Laravel Javascript Validation -->
    <script  src="<?php echo url('/'); ?>/vendor/core/plugins/real-estate/js/app.js"></script>
    <script  src="<?php echo url('/'); ?>/vendor/core/core/js-validation/js/js-validation.js"></script>

    <script  src=""></script>
    <script  src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script  src=""></script>
    </div>
*/?>
<script> 'use strict'; $(document).ready(function () { $('#preloader').remove(); })</script>
<script >

    if (window.location.hash && window.location.hash == '#_=_') {
        if (window.history && history.pushState) {
            window.history.pushState("", document.title, window.location.pathname);
        } else {
            // Prevent scrolling by storing the page's current scroll offset
            var scroll = {
                top: document.body.scrollTop,
                left: document.body.scrollLeft
            };
            window.location.hash = '';
            // Restore the scroll offset, should be flicker free
            document.body.scrollTop = scroll.top;
            document.body.scrollLeft = scroll.left;
        }
    }

    function divFunction(){
        window.showAlert('alert-success', '{{ __('under development') }}');

        }
</script>
<script  src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>

<!-- Minified CSS and JS -->
<link   rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" 
        crossorigin="anonymous">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" 
        crossorigin="anonymous">
</script>
<script >
    /* $(function() {
        var $image = $('#image'), $image.height() + 4;
        $('.preview').css({ 
            width: '100%',   
            overflow: 'hidden',
            height:    height,
            maxWidth:  $image.width(),
            maxHeight: height
        });
        $image.cropper({
            preview: '.preview'
        });
    }); */


    $(function() {
        
        
        // var myEl = document.getElementByClass('android_payment');
                
        // myEl.addEventListener('click', function() {
        //     alert('Hello world');
        // }, false);
       
        // $('.android_payment').onClick(function(){
            
        //     window.showAlert('alert-success', '{{ session('success_msg') }}');
            
        // });
    });
</script>


  
</body>
</html>
