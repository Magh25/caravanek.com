<div class="page-header navbar navbar-static-top">
    <div class="page-header-inner">

            <div class="page-logo">
                <a href="{{ route('dashboard.index') }}" style="color: #fff;font-size: 20px;line-height: 49px;display: block;font-weight: bold;">
                    ADMIN PANEL
                </a>

                @auth
                    <div class="menu-toggler sidebar-toggler">
                        <span></span>
                    </div>
                @endauth
            </div>

            @auth
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-bs-toggle="collapse" data-bs-target=".navbar-collapse">
                    <span></span>
                </a>
            @endauth

            @include('core/base::layouts.partials.top-menu')
        </div>
</div>
