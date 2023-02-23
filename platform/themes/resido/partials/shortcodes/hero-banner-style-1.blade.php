@php
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
@endphp
<!-- $supportedLocales = Language::getSupportedLocales();
foreach ($supportedLocales as $localeCode => $properties){
    $localeCode = $properties['lang_code'];
} 
$addLink = '';
if($localeCode == 'en'){
    $addLink = 'en';
} -->
<div class="banner_sec">
    <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="mask flex-center">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-12 col-12 order-md-1 order-2 content_banner">
                                <h2>{{ __('Beta Version') }}</h2>
                                <h1>{{ __('Search and book thousands of Caravans') }} <br>{{ __('and campgrounds across Saudi Arabia') }} <br>{{ __('and gulf countries') }}</h1>
                            </div>
                            <div class="col-md-12 col-12 order-md-1 order-1"><img src="themes/resido/img/3.jpg" class="mx-auto" alt="{{ __('Search and book thousands of Caravans') }}"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="mask flex-center">
                    <div class="container">
                        <div class="row align-items-center">
                             <div class="col-md-12 col-12 order-md-1 order-2 content_banner">
                                <h2>{{ __('Beta Version') }}</h2>
                                <h1>{{ __('Search and book thousands of Caravans') }} <br>{{ __('and campgrounds across Saudi Arabia') }} <br>{{ __('and gulf countries') }}</h1>
                            </div>
                            <div class="col-md-12 col-12 order-md-2 order-1"><img src="themes/resido/img/2.webp" class="mx-auto" alt="{{ __('Search and book thousands of Caravans') }}"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="mask flex-center">
                    <div class="container">
                        <div class="row align-items-center">
                             <div class="col-md-12 col-12 order-md-1 order-2 content_banner">
                                <h2>{{ __('Beta Version') }}</h2>
                                <h1>{{ __('Search and book thousands of Caravans') }} <br>{{ __('and campgrounds across Saudi Arabia') }} <br>{{ __('and gulf countries') }}</h1>
                            </div>
                            <!-- themes/resido/Gilroy-ExtraBold.ttf  -->
                            <div class="col-md-12 col-12 order-md-2 order-1"><img src="themes/resido/img/1.webp" class="mx-auto" alt="{{ __('Search and book thousands of Caravans') }}"></div>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span> <span class="sr-only">{{ __('Previous') }}</span> </a> 
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span class="sr-only">{{ __('Next') }}</span> </a>   
        </div>
    </div>
</div>
<!-- <div class="carvan_tabs" style="z-index:99;">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 content_tab"> -->
                <!-- <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link " id="nav-caravans-tab"   href="{{ $addLink }}/listing"  ><img src="themes/resido/img/truck.png" class="icon" alt="{{ __('All Caravans') }}"> <img src="themes/resido/img/truck-blue.png" class="hover_icon" alt="{{ __('All Caravans') }}">{{ __('All Caravans') }}</a>
                        <a class="nav-item nav-link" id="nav-drivables-tab"  href="{{ $addLink }}/category/drivable" ><img src="themes/resido/img/drivable.png" class="icon"  alt="{{ __('Drivables') }}"> <img src="themes/resido/img/drivable-blue.png" class="hover_icon" alt="{{ __('Drivables') }}"> {{ __('Drivables') }} </a>
                        <a class="nav-item nav-link" id="nav-towables-tab"  href="{{ $addLink }}/category/towables" ><img src="themes/resido/img/towables.png" class="icon"   alt="{{ __('Towables') }}"> <img src="themes/resido/img/towables-blue.png" class="hover_icon"   alt="{{ __('Towables') }}">{{ __('Towables') }}</a>
                        <a class="nav-item nav-link" id="nav-delivery-tab"  href="{{ $addLink }}/category/deliverables" ><img src="themes/resido/img/delivery.png" class="icon"  alt="{{ __('Destination Delivery') }}"> <img src="themes/resido/img/delivery-blue.png" class="hover_icon"   alt="{{ __('Destination Delivery') }}">{{ __('Destination Delivery') }}</a>
                        <a class="nav-item nav-link" id="nav-parking-tab"  href="{{ $addLink }}/listing/Parking" ><img src="themes/resido/img/parking.png" class="icon"  alt="{{ __('Parking') }}"> <img src="themes/resido/img/parking-blue.png" class="hover_icon"   alt="{{ __('Parking') }}">{{ __('Parking') }}</a>
                        <a class="nav-item nav-link" id="nav-accessories-tab"  href="{{ $addLink }}/listing/Accessory" ><img src="themes/resido/img/access.png" class="icon"  alt="{{ __('Accessories') }}"> <img src="themes/resido/img/access-blue.png" class="hover_icon"   alt="{{ __('Accessories') }}">{{ __('Accessories') }}</a>
                    </div>
                </nav> -->
                <!-- <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-caravans" role="tabpanel" aria-labelledby="nav-caravans-tab">
                        <h2>{{ __('Find the Perfect caravan Rental') }}</h2>
                      
                        <div class="search_form">
                            <form action="{{ $addLink }}/listing" class="daterangepicker-container">

                                <div class="col-md-6 col-6 elem-group">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/location.png" alt="{{ __('Find the Perfect caravan Rental') }}"></span>
                                        <input type="text" id="location" id="all_location" class="field_googlemap_autocomplete" data-target=".googlemap_latlng" name="location" placeholder="{{ __('Enter pickup location or address') }}" required>
                                        <input type="hidden" class="googlemap_latlng" disabled="disabled" name="latlng" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>
                                        <input type="text" class="field_daterangepicker pickup field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start" name="pickup" placeholder="{{ __('Pick Up') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>    
                                        <input type="text" class="field_daterangepicker dropoff field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" placeholder="{{ __('Drop Off') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 elem-group guests">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/guests-icon.png" alt="{{ __('Guests') }}"></span>
                                        <span>{{ __('Guests') }}</span>
                                        <select name="guests" id="num_adults">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10+</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 col-6 elem-group">
                                    <button class="buttonStyle-new" type="submit">{{ __('Search for all Caravans') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-drivables" role="tabpanel" aria-labelledby="nav-drivables-tab">
                        <h2>{{ __('Find the Perfect caravan Rental') }}</h2>
                        <div class="search_form">
                            <form action="/category/drivable" class="daterangepicker-container">
                                <div class="col-md-6 col-6 elem-group">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/location.png" alt="{{ __('Find the Perfect caravan Rental') }}"></span>
                                        <input type="text" class="field_googlemap_autocomplete" data-target=".googlemap_latlng" id="drivable_location" name="location" placeholder="{{ __('Enter pickup location or address') }}" required>
                                        <input type="hidden" class="googlemap_latlng" disabled="disabled" name="latlng" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>
                                        <input type="text" id="pickup" class="field_daterangepicker field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start"  name="pickup" placeholder="{{ __('Pick Up') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>    
                                        <input type="text" id="dropoff" class="field_daterangepicker field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" placeholder="{{ __('Drop Off') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 elem-group guests">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/guests-icon.png" alt="{{ __('Guests') }}"></span>
                                        <span>{{ __('Guests') }}</span>
                                        <select name="guests">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10+</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 col-6 elem-group">
                                    <button type="submit">{{ __('Search for all Caravans') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-towables" role="tabpanel" aria-labelledby="nav-towables-tab">
                        <h2>{{ __('Find the Perfect caravan Rental') }}</h2>
                        <div class="search_form">
                            <form action="/category/towables" class="daterangepicker-container">
                                <div class="col-md-6 col-6 elem-group">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/location.png" alt="{{ __('Find the Perfect caravan Rental') }}"></span>
                                        <input type="text" class="field_googlemap_autocomplete" data-target=".googlemap_latlng" id="drivable_location" name="location" placeholder="{{ __('Enter pickup location or address') }}" required>
                                        <input type="hidden" class="googlemap_latlng" disabled="disabled" name="latlng" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>
                                        <input type="text" id="pickup" class="field_daterangepicker field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start"  name="pickup" placeholder="{{ __('Pick Up') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>    
                                        <input type="text" id="dropoff" class="field_daterangepicker field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" placeholder="{{ __('Drop Off') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 elem-group guests">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/guests-icon.png" alt="{{ __('Guests') }}"></span>
                                        <span>{{ __('Guests') }}</span>
                                        <select name="guests">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10+</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 col-6 elem-group">
                                    <button type="submit">{{ __('Search for all Caravans') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-delivery" role="tabpanel" aria-labelledby="nav-delivery-tab">
                        <h2>{{ __('Find the Perfect caravan Rental') }}</h2>
                        <p>{{ __('Owner will deliver the caravan to your destination - no driving required.') }}</p>
                        <div class="search_form">
                            <form  action="/category/deliverables" class="daterangepicker-container">
                                <div class="col-md-6 col-6 elem-group">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/location.png" alt="{{ __('Find the Perfect caravan Rental') }}"></span>
                                        <input type="text" class="field_googlemap_autocomplete" data-target=".googlemap_latlng" id="deliverables_location" name="location" placeholder="{{ __('Enter pickup location or address') }}" required>
                                        <input type="hidden" class="googlemap_latlng" disabled="disabled" name="latlng" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>
                                        <input type="text" class="field_daterangepicker field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start" name="pickup" placeholder="{{ __('Pick Up') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>    
                                        <input type="text" class="field_daterangepicker field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" placeholder="{{ __('Drop Off') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 elem-group guests">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/guests-icon.png" alt="{{ __('Guests') }}"></span>
                                        <span>{{ __('Guests') }}</span>
                                        <select name="num_adults" id="num_adults">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10+</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 col-6 elem-group">
                                    <button type="submit">{{ __('Search for all Caravans') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-parking" role="tabpanel" aria-labelledby="nav-parking-tab">
                        <h2>{{ __('Find the Perfect caravan Rental Parkings') }}</h2>
                        <div class="search_form filter-map-search">
                            <form action="#" class="daterangepicker-container row row1">
                                <div class="col-md-6 col-6 elem-group elem-group1">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/location.png" alt="{{ __('Find the Perfect caravan Rental') }}"></span>
                                        <input type="text" class="field_googlemap_autocomplete" data-target=".googlemap_latlng" id="map_location" name="location" placeholder="{{ __('Enter pickup location or address') }}" required>
                                        <input type="hidden" class="googlemap_latlng" disabled="disabled" name="latlng" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined elem-group1">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>
                                        <input type="text" id="pickup" class="field_daterangepicker field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start"  name="pickup" placeholder="{{ __('Pick Up') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-3 elem-group inlined elem-group1">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/date-piker.png" alt="{{ __('Pick Up') }}"></span>    
                                        <input type="text" id="dropoff" class="field_daterangepicker field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" placeholder="{{ __('Drop Off') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 elem-group guests elem-group1">
                                    <div class="box_filed">
                                        <span class="icon"><img src="themes/resido/img/guests-icon.png" alt="{{ __('Guests') }}"></span>
                                        <span>{{ __('Guests') }}</span>
                                        <select name="guests">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10+</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 col-6 elem-group elem-group1">
                                    <button type="submit">{{ __('Search for all Caravans') }}</button>
                                </div>
                            </form>
                        </div>
                        

                        <div class="search_form">
                            <div class="map">
                                <div id="map" data-params='{}' data-lt="1" class="leaflet-map-loader filter-map-search-loader" data-template="traffic-popup-map-template-hp" data-type="parking" data-url="{{ route('public.ajax.properties.map') }}" data-center="[23.886,45.079]"></div>
                                <script id="traffic-popup-map-template-hp" type="text/x-custom-template">
                                    {!! Theme::partial('real-estate.properties.map-popup', ['property' => get_object_property_map()]) !!}
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-accessories" role="tabpanel" aria-labelledby="nav-accessories-tab">
                        <h2>{{ __('Find the Perfect caravan Rental Accessories') }}</h2>
                        <div class="search_form access">
                            <div class="row">
                                <img src="themes/resido/img/ads.jpg" class="img-fluid"  alt="{{ __('Find the Perfect caravan Rental Accessories') }}">
                                <img src="themes/resido/img/ads.jpg" class="img-fluid"  alt="{{ __('Find the Perfect caravan Rental Accessories') }}">
                            </div>
                        </div>
                    </div>
                </div> -->
            <!-- </div>
        </div>
    </div>
</div> -->
<!-- <div class="image-cover hero-banner"
    style="background:url({{ RvMedia::getImageUrl($bg, null, false, RvMedia::getDefaultImage()) }}) no-repeat;">
    <div class="container">
        <div class="hero-search-wrap">
            <div class="hero-search">
                <h1>{!! clean($title) !!}</h1>
            </div>
            <form action="{{ route('public.properties') }}" method="GET" id="frmhomesearch">
                <div class="hero-search-content side-form">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <input type="text" class="form-control"
                                        placeholder="{{ __('Search for a location') }}">
                                    <img src="{{ Theme::asset()->url('img/pin.svg') }}" width="18" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Min Price') }}</label>
                                {!! Theme::partial('real-estate.filters.min-price') !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Max Price') }}</label>
                                {!! Theme::partial('real-estate.filters.max-price') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Property Type') }}</label>
                                {!! Theme::partial('real-estate.filters.categories') !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Bed Rooms') }}</label>
                                {!! Theme::partial('real-estate.filters.bedrooms') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Property Location') }}</label>
                                {!! Theme::partial('real-estate.filters.cities') !!}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="hero-search-action">
                    <button class="btn search-btn" type="submit">{{ __('Search Result') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
 -->
<script type="text/javascript">
    jQuery( document ).ready(function() {
        /* jQuery('.field_daterangepicker').on('apply.daterangepicker', function(ev, picker) {
            var endDate=  jQuery(this).data('daterangepicker').endDate.format('DD/MM/YYYY');
            var startDate=  jQuery(this).data('daterangepicker').endDate.format('DD/MM/YYYY');
            jQuery('.pickup').val(endDate);
            jQuery('.dropoff').val(startDate);
        });*/ 

        /*jQuery('.field_daterangepicker').on('onfocus',function(){
            alert('sdf');
            $(this).attr('disabled', 'disabled');
        });*/
    });

</script>