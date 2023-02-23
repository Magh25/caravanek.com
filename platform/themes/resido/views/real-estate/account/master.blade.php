@php
    Theme::layout('account');
    $user = auth('account')->user();
@endphp
<section class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="filter_search_opt">
                    <a href="javascript:void(0);" class="open_search_menu">
                        {{ __('Dashboard Navigation') }}
                        <i class="ml-2 ti-menu"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="simple-sidebar sm-sidebar" id="filter_search">
                    <div class="search-sidebar_header">
                        <h4 class="ssh_heading">{{ __('Close Filter') }}</h4>
                        <button class="w3-bar-item w3-button w3-large close_search_menu">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    <div class="sidebar-widgets">
                        <div class="dashboard-navbar">
                            <div class="d-user-avater">
                                <img
                                    src="{{ $user->avatar->url ? RvMedia::getImageUrl($user->avatar->url, 'thumb') : $user->avatar_url }}"
                                    alt="{{ $user->name }}" class="img-fluid avater" style="width: 150px;">
                                <h4>{{ $user->name }}</h4>
                                <span>{{ $user->phone }}</span>
                            </div>

                            <div class="d-navigation">
                                <ul>
                                    <li class="{{ request()->routeIs('public.account.dashboard') ? 'active' : '' }}">
                                        <a href="{{ route('public.account.dashboard') }}"
                                           title="{{ trans('plugins/real-estate::dashboard.header_profile_link') }}">
                                            <i class="ti-dashboard"></i>{{ __('Dashboard') }}</a>
                                        </a>
                                    </li>

                                    <li class="{{ request()->routeIs('public.account.settings') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('public.account.settings') }}"
                                            title="{{ trans('plugins/real-estate::dashboard.header_settings_link') }}">
                                            <i class="ti-settings"></i>{{ trans('plugins/real-estate::dashboard.header_settings_link') }}
                                        </a>
                                    </li>

                                    <!-- <li class="{{ request()->routeIs('public.account.packages') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('public.account.packages') }}"
                                            title="{{ trans('plugins/real-estate::account.credits') }}">
                                            <i class="far fa-credit-card mr1"></i>{{ trans('plugins/real-estate::account.buy_credits') }}
                                            <span
                                                class="badge badge-info">{{ auth('account')->user()->credits }} {{ trans('plugins/real-estate::account.credits') }}</span>
                                        </a>
                                    </li> -->
                                    @php
                                        $role = auth('account')->user()->role;
                                    @endphp
                                    
                                    <li class="{{ request()->routeIs('public.account.my-bookings') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('public.account.my-bookings') }}"
                                            title="{{ trans('plugins/real-estate::account-property.my_bookings') }}">
                                            <i class="ti-view-list-alt mr1"></i>{{ trans('plugins/real-estate::account-property.my_bookings') }}
                                        </a>
                                    </li> 
                                    <li class="{{ request()->routeIs('public.account.commissions') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('public.account.commissions') }}"
                                            title="{{ trans('plugins/real-estate::commission.name') }}">
                                            <i class="ti-wallet mr1"></i>{{ trans('plugins/real-estate::commission.name') }}
                                        </a>
                                    </li> 
                                    
                                     <li class="{{ request()->routeIs('public.account.booked-by-me') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('public.account.booked-by-me') }}"
                                            title="{{ trans('plugins/real-estate::account-property.booked_by_me') }}">
                                            <i class="ti-layout-accordion-list mr1"></i>{{ trans('plugins/real-estate::account-property.booked_by_me') }}
                                        </a>
                                    </li> 

                                    {!! apply_filters(ACCOUNT_TOP_MENU_FILTER, null) !!}
                                      
                                    <li class="{{ request()->routeIs('public.account.properties.index') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('public.account.properties.index') }}"
                                            title="{{ trans('plugins/real-estate::account-property.properties') }}">
                                            <i class="ti-view-list mr1"></i>{{ trans('properties') }}
                                        </a>
                                    </li> 

                                    
                                        <li class="{{ request()->routeIs('public.account.properties.create') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('public.account.properties.create') }}"
                                                title="{{ trans('plugins/real-estate::account-property.write_property') }}">
                                                <i class="fa fa-edit mr1"></i>{{ trans('create property') }}
                                            </a>
                                        </li>
                                    
                                    <li class="{{ request()->routeIs('public.account.security') ? 'active' : '' }}">
                                        <a href="{{ route('public.account.security') }}">
                                            <i class="ti-unlock"></i>
                                            {{ trans('plugins/real-estate::dashboard.sidebar_security') }}
                                        </a>
                                    </li>

                                    <li class="{{ request()->routeIs('public.account.blog') ? 'active' : '' }}">
                                        <a href="{{ route('public.account.blogs.index') }}">
                                            <i class="ti-unlock"></i>
                                            {{ trans('Blog') }} 
                                        </a>
                                    </li>

                                    <li>
                                        <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db"
                                           href="#"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                           title="{{ trans('plugins/real-estate::dashboard.header_logout_link') }}">
                                            <i class="ti-share mr1"></i>
                                            <span>{{ trans('plugins/real-estate::dashboard.header_logout_link') }}</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('public.account.logout') }}"
                                              method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-9 col-md-12">
                @yield('content')
            </div>

        </div>
    </div>
</section>

@php ob_start(); @endphp
<!-- Put translation key to translate in VueJS -->
<script type="text/javascript">
    "use strict";
    window.trans = JSON.parse('{!! addslashes(json_encode(trans('plugins/real-estate::dashboard'))) !!}');
    var BotbleVariables = BotbleVariables || {};
    BotbleVariables.languages = {
        tables: {!! json_encode(trans('core/base::tables'), JSON_HEX_APOS) !!},
        notices_msg: {!! json_encode(trans('core/base::notices'), JSON_HEX_APOS) !!},
        pagination: {!! json_encode(trans('pagination'), JSON_HEX_APOS) !!},
        system: {
            'character_remain': '{{ trans('core/base::forms.character_remain') }}'
        }
    };
    var RV_MEDIA_URL = {'media_upload_from_editor': '{{ route('public.account.upload-from-editor') }}'};
</script>
@stack('header')
@php $masterHeaderScript = ob_get_clean(); @endphp

@php ob_start(); @endphp
{!! Assets::renderFooter() !!}
@stack('scripts')
@stack('footer')
@php $masterFooterScript = ob_get_clean(); @endphp

@php
    Theme::asset()->container('footer')->usePath(false)->add('lodash-js', asset('vendor/core/core/media/libraries/lodash/lodash.min.js'));
    Theme::asset()->usePath(false)->add('real-estate-app_custom-css', asset('vendor/core/plugins/real-estate/css/app_custom.css'));
    Theme::asset()->container('header')->writeContent('master-header-js', $masterHeaderScript);
    Theme::asset()->container('footer')->writeContent('master-footer-js', "<script> 'use strict'; $(document).ready(function () { $('#preloader').remove(); })</script>" . $masterFooterScript);
    Theme::asset()->container('footer')->usePath()->remove('components-js');
@endphp
