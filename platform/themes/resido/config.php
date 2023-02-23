<?php

use Botble\Theme\Theme;

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials" and "views"
    |
    | [Notice] assets cannot inherit.
    |
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these event can be override by package config.
    |
    */

    'events' => [

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before'             => function ($theme) {
            // You can remove this line anytime.
        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme'  => function (Theme $theme) {
            $themeInfo = json_decode(file_get_contents(dirname(__FILE__) . '/theme.json'), true);
            // $version = $themeInfo['version'];
            $version = time();

            $theme->asset()->usePath()->add('animation-css', 'plugins/animation.css');
            $theme->asset()->add('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');
            $theme->asset()->add('font-awesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
            $theme->asset()->add('daterangepicker-css', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css');

             $theme->asset()->add('owl-carousel-css', '/vendor/core/plugins/simple-slider/libraries/owl-carousel/owl.carousel.css');
            $theme->asset()->add('simple-slider-css', '/vendor/core/plugins/simple-slider/css/simple-slider.css');
           
            
            $theme->asset()->usePath()->add('rangeSlider-css', 'plugins/ion.rangeSlider.min.css');
            $theme->asset()->usePath()->add('dropzone-css', 'plugins/dropzone.css');
            $theme->asset()->usePath()->add('select2-css', 'plugins/select2.css');
            $theme->asset()->usePath()->add('slick-css', 'plugins/slick.css');
            $theme->asset()->usePath()->add('slick-theme-css', 'plugins/slick-theme.css');
            $theme->asset()->usePath()->add('font-awesome-css', 'plugins/font-awesome.css');
            $theme->asset()->usePath()->add('icofont-css', 'plugins/icofont.css');
            $theme->asset()->usePath()->add('light-box-css', 'plugins/light-box.css');
            $theme->asset()->usePath()->add('line-icon-css', 'plugins/line-icon.css');

            $theme->asset()->usePath()->add('themify-css', 'plugins/themify.css');

            $theme->asset()->usePath()->add('style-css', 'css/style.css', [], [], $version);
            if (BaseHelper::siteLanguageDirection() == 'rtl') {
                $theme->asset()->usePath()->add('rtl-style', 'css/rtl-style.css', [], [], $version);
            }

            $theme->asset()->container('header')->usePath()->add('jquery', 'plugins/jquery.min.js');
           // $theme->asset()->container('footer')->usePath()->add('popper-js', 'plugins/bootstrap/popper.min.js');
           // $theme->asset()->container('footer')->usePath()->add('bootstrap-js', 'plugins/bootstrap/bootstrap.min.js');
            $theme->asset()->container('header')->add('jquery', 'https://cdn.jsdelivr.net/jquery/latest/jquery.min.js');
            $theme->asset()->container('header')->add('popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js');
            $theme->asset()->container('header')->add('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js');
            $theme->asset()->container('header')->add('moment-js', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js');
            $theme->asset()->container('header')->add('daterangepicker-js', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js');
            $theme->asset()->container('header')->add('intlTelInput-js', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/js/intlTelInput-jquery.min.js');
           
            $theme->asset()->container('footer')->usePath()->add('rangeslider-js', 'plugins/rangeslider.js');
            $theme->asset()->container('footer')->usePath()->add('select2-js', 'plugins/select2.min.js');
            $theme->asset()->container('footer')->usePath()->add('magnific-popup-js', 'plugins/jquery.magnific-popup.min.js');
            $theme->asset()->container('footer')->usePath()->add('slick-js', 'plugins/slick.js');
            $theme->asset()->container('footer')->usePath()->add('slider-bg-js', 'plugins/slider-bg.js');

            $theme->asset()->container('footer')->usePath()->add('lightbox-js', 'plugins/lightbox.js');
            $theme->asset()->container('footer')->usePath()->add('imagesloaded-js', 'plugins/imagesloaded.js');
            $theme->asset()->container('footer')->usePath()->add('lazyload', 'plugins/lazyload.min.js');
            
            $theme->asset()->container('footer')->usePath()->add('components-js', 'js/components.js', [], [], $version);
            $theme->asset()->container('footer')->usePath()->add('wishlist', 'js/wishlist.js', [], [], $version);
            
            
            if( request()->route()->getName() != "public.account.settings"){
                $theme->asset()->container('footer')->usePath()->add('app-js', 'js/app.js', [], [], $version);
            }

            $theme->asset()->container('footer')->usePath()->add('custom-js', 'js/custom.js', [], [], $version);
            
            if (function_exists('shortcode')) {
                $theme->composer([
                    'page',
                    'post',
                    'career.career',
                    'real-estate.property',
                ], function (\Botble\Shortcode\View\View $view) {
                    $view->withShortcodes();
                });
            }
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [

            'default' => function ($theme) {
                // $theme->asset()->usePath()->add('ipad', 'css/layouts/ipad.css');
            },
        ],
    ],
];
