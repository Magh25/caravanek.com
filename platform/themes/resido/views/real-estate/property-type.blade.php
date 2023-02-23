<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h1 class="ipt-title">
                    @if($type->is_Accessory == 1)
                        {{ __('Accessories')}}
                    @elseif($type->is_fixable == 1) 
                        {{ __('Parking Spot')}} 
                    @elseif($type->slug == 'vehicle')
                        {{ __('Caravans')}} 
                    @endif 

                </h1>
            </div>
        </div>
    </div>
</div>

@include(Theme::getThemeNamespace('views.real-estate.includes.properties-list'))
 