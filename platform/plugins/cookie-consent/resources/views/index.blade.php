@if ($cookieConsentConfig['enabled'] && !$alreadyConsentedWithCookies)

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

   
     
@endphp

    <div class="js-cookie-consent cookie-consent cookie-consent-{{ theme_option('cookie_consent_style', 'full-width') }}" style="background-color: {{ theme_option('cookie_consent_background_color', '#000') }} !important; color: {{ theme_option('cookie_consent_text_color', '#fff') }} !important;">
        <div class="cookie-consent-body" style="max-width: {{ theme_option('cookie_consent_max_width', 1170) }}px;">
            <span class="cookie-consent__message">
                
                {{  __('Your experience on this site will be improved by allowing cookies.') }} 
                @if (theme_option('cookie_consent_learn_more_url') && theme_option('cookie_consent_learn_more_text'))
                    <a href="{{ url(theme_option('cookie_consent_learn_more_url')) }}"  style="text-decoration: underline !important; ">{{ theme_option('cookie_consent_learn_more_text') }}</a>
                @endif
            </span>

            <button class="js-cookie-consent-agree cookie-consent__agree" style="background-color: {{ theme_option('cookie_consent_background_color', '#000') }} !important; color: {{ theme_option('cookie_consent_text_color', '#fff') }} !important; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }} !important;">
                {{  __('Allow cookies') }}
            </button>

            <button class="js-cookie-consent-cancel cookie-consent__agree mr-2 ml-2 " style="background-color: {{ theme_option('cookie_consent_background_color', '#000') }} !important; color: {{ theme_option('cookie_consent_text_color', '#fff') }} !important; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }} !important;">
                {{  __('cancel') }}
            </button>


            <script> 
                $(document).on('click', '.js-cookie-consent-cancel', function () {
                    // hideCookieDialog(); 
                    $('.js-cookie-consent').hide();
                });
            </script>

        </div>
    </div>
    <div data-site-cookie-name="{{ $cookieConsentConfig['cookie_name'] }}"></div>
    <div data-site-cookie-lifetime="{{ $cookieConsentConfig['cookie_lifetime'] }}"></div>
    <div data-site-cookie-domain="{{ config('session.domain') ?? request()->getHost() }}"></div>
    <div data-site-session-secure="{{ config('session.secure') ? ';secure' : null }}"></div>

@endif
