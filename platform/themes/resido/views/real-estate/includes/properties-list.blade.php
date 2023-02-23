@php
    $params = request()->input();
    if( empty($params) )
        $params['show_all'] = 1;

    if( empty($type)) $type = false;
    
    if( !empty($city->id) )
        $params['city_id'] = (int)$city->id;
    
    if( !empty($category->slug) ){
        $url = (app()->getLocale() == 'ar') ? '/category/'.$category->slug : ''.'/category/'.$category->slug;
        $params['category_id'] = $category->id;
    } else {
        $url = (app()->getLocale() == 'ar') ? '/listing' : ''.'/listing';
        if( !empty($type->slug))
            $url .= app()->getLocale() == 'ar' ? "/".$type->slug : ''."/".$type->slug;
    }


    Theme::asset()
        ->usePath()
        ->add('leaflet-css', 'plugins/leaflet.css');
    Theme::asset()
        ->container('footer')
        ->usePath()
        ->add('leaflet-js', 'plugins/leaflet.js');
    Theme::asset()
        ->container('footer')
        ->usePath()
        ->add('leaflet.markercluster-src-js', 'plugins/leaflet.markercluster-src.js');
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

    if( Request::segment(1) == 'ar'){
        $addLink = '/ar';
    }

@endphp



<div class="half-map container-fluid max-w-screen-2xl">
  


                            <!-- 
                            <a class="nav-item nav-link " id="nav-caravans-tab"   href="{{ $addLink }}/listing"  ><img src="{{asset('themes/resido/img/truck.png')}}" class="icon" alt="{{ __('All Caravans') }}"> <img src="{{asset('themes/resido/img/truck-blue.png')}}" class="hover_icon" alt="{{ __('All Caravans') }}">{{ __('All Caravans') }}</a>
                            <a class="nav-item nav-link" id="nav-drivables-tab"  href="{{ $addLink }}/category/drivable" ><img src="themes/resido/img/drivable.png" class="icon"  alt="{{ __('Drivables') }}"> <img src="{{asset('themes/resido/img/drivable-blue.png')}}" class="hover_icon" alt="{{ __('Drivables') }}"> {{ __('Drivables') }} </a>
                            <a class="nav-item nav-link" id="nav-towables-tab"  href="{{ $addLink }}/category/towables" ><img src="themes/resido/img/towables.png" class="icon"   alt="{{ __('Towables') }}"> <img src="{{asset('themes/resido/img/towables-blue.png')}}" class="hover_icon"   alt="{{ __('Towables') }}">{{ __('Towables') }}</a>
                            <a class="nav-item nav-link" id="nav-delivery-tab"  href="{{ $addLink }}/category/deliverables" ><img src="themes/resido/img/delivery.png" class="icon"  alt="{{ __('Destination Delivery') }}"> <img src="{{asset('themes/resido/img/delivery-blue.png')}}" class="hover_icon"   alt="{{ __('Destination Delivery') }}">{{ __('Destination Delivery') }}</a>
                            <a class="nav-item nav-link" id="nav-parking-tab"  href="{{ $addLink }}/listing/Parking" ><img src="themes/resido/img/parking.png" class="icon"  alt="{{ __('Parking') }}"> <img src="{{asset('themes/resido/img/parking-blue.png')}}" class="hover_icon"   alt="{{ __('Parking') }}">{{ __('Parking') }}</a>
                            <a class="nav-item nav-link" id="nav-accessories-tab"  href="{{ $addLink }}/listing/Accessory" ><img src="themes/resido/img/access.png" class="icon"  alt="{{ __('Accessories') }}"> <img src="{{asset('themes/resido/img/access-blue.png')}}" class="hover_icon"   alt="{{ __('Accessories') }}">{{ __('Accessories') }}</a> -->
                   
         
    <div class="fs-content">
        

    <div class="row mb-3">  
            <div class="col-lg-2 col-md-6 col-sm-12">
                <a class="nav-item  " id="nav-caravans-tab"   href="{{ $addLink }}/listing"  > <img src="{{asset('themes/resido/img/truck-blue.png')}}" class="hover_icon" alt="{{ __('All Caravans') }}">{{ __('All Caravans') }}</a>

            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <a class="nav-item " id="nav-drivables-tab"  href="{{ $addLink }}/category/drivable" >  <img src="{{asset('themes/resido/img/drivable-blue.png')}}" class="hover_icon" alt="{{ __('Drivables') }}"> {{ __('Drivables') }} </a>

            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <a class="nav-item " id="nav-towables-tab"  href="{{ $addLink }}/category/towables" > <img src="{{asset('themes/resido/img/towables-blue.png')}}" class="hover_icon"   alt="{{ __('Towables') }}">{{ __('Towables') }}</a>
            </div> 
            <div class="col-lg-2 col-md-6 col-sm-12">
                
                <a class="nav-item " id="nav-delivery-tab"  href="{{ $addLink }}/category/deliverables" > <img src="{{asset('themes/resido/img/delivery-blue.png')}}" class="hover_icon"   alt="{{ __('Destination Delivery') }}">{{ __('Destination Delivery') }}</a>
            </div> 
            <div class="col-lg-2 col-md-6 col-sm-12">
                <a class="nav-item " id="nav-parking-tab"  href="{{ $addLink }}/listing/Parking" >  <img src="{{asset('themes/resido/img/parking-blue.png')}}" class="hover_icon"   alt="{{ __('Parking') }}">{{ __('Parking') }}</a>
            </div> 
            <div class="col-lg-2 col-md-6 col-sm-12">
                <a class="nav-item " id="nav-accessories-tab"  href="{{ $addLink }}/listing/Accessory" >  <img src="{{asset('themes/resido/img/access-blue.png')}}" class="hover_icon"   alt="{{ __('Accessories') }}">{{ __('Accessories') }}</a>
            </div> 
    </div>




        <form action="{{ $addLink }}{{ $url }}" method="get" ids="ajax-filters-form">
            <input type="hidden" name="page" data-value="{{ $properties->currentPage() }}">
       
            @include(Theme::getThemeNamespace('views.real-estate.includes.filters-halfmap'))
            @if(!isset($type->is_Accessory))    
            <div class="row">
                <div class="fs-inner-container1 col-md-8 data-listing" id="properties-list">
                    
                    <div class="row">

                        <div class="col-lg-12 col-md-12 list-layout">
                            <div class="row justify-content-center">
                                @include(Theme::getThemeNamespace('views.real-estate.includes.sorting-box'))
                            </div>

                            <div class="row">
                                @foreach ($properties as $property)
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                            {!! Theme::partial('real-estate.properties.item-grid', compact('property')) !!}
                                        
                                    </div>
                                    <!-- End Single Property -->
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <nav class="d-flex justify-content-center pt-3" aria-label="Page navigation">
                                        {!! $properties->withQueryString()->onEachSide(1)->links() !!}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="fs-left-map-box1 col-md-4">
                    <div class="rightmap">
                        <div id="map" class="leaflet-map-loader" data-template="traffic-popup-map-template-listing" data-type="{{ $type ? $type->slug : '' }}" data-params='{{ json_encode($params) }}' data-url="{{ route('public.ajax.properties.map') }}" data-center="[{{request()->input('latlng') ? request()->input('latlng') : '43.615134, -76.393186' }}]"></div>

                    </div>
                </div>
               




            </div>




            @elseif(isset($type->is_Accessory))
            <div class="row">
                <div class="fs-inner-container1 col-md-12 data-listing" id="properties-list">
                    
                    <div class="row">

                        <div class="col-lg-12 col-md-12 list-layout">
                            <div class="row justify-content-center">
                                @include(Theme::getThemeNamespace('views.real-estate.includes.sorting-box'))
                            </div>

                            <div class="row">
                                @foreach ($properties as $property)
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                            {!! Theme::partial('real-estate.properties.item-grid', compact('property')) !!}
                                        
                                    </div>
                                    <!-- End Single Property -->
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <nav class="d-flex justify-content-center pt-3" aria-label="Page navigation">
                                        {!! $properties->withQueryString()->onEachSide(1)->links() !!}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               
               




            </div>
            @endif
        </form>
    </div>
</div>
<div class="clearfix"></div>
<script id="traffic-popup-map-template-listing" type="text/x-custom-template">
    {!! Theme::partial('real-estate.properties.map-popup', ['property' => get_object_property_map()]) !!}
</script>
