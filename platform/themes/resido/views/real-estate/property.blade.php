@php
    Theme::asset()->usePath()->add('leaflet-css', 'plugins/leaflet.css');
    Theme::asset()->usePath()->add('jquery-bar-rating', 'plugins/jquery-bar-rating/themes/fontawesome-stars.css');
    Theme::asset()->container('footer')->usePath()->add('leaflet-js', 'plugins/leaflet.js');
    Theme::asset()->usePath()->add('magnific-css', 'plugins/magnific-popup.css');
    Theme::asset()->container('footer')->usePath()->add('magnific-js', 'plugins/jquery.magnific-popup.min.js');
    Theme::asset()->container('footer')->usePath()->add('property-js', 'js/property.js');
    Theme::asset()->container('footer')->usePath()->add('jquery-bar-rating-js', 'plugins/jquery-bar-rating/jquery.barrating.min.js');
    Theme::asset()->container('footer')->usePath()->add('wishlist', 'js/wishlist.js', [], []);
    $headerLayout = MetaBox::getMetaData($property, 'header_layout', true);
    $headerLayout = $headerLayout ?: 'layout-1';

    $is_fixable = @DB::table('re_property_types')->select('is_fixable')->where('id', '=', (int)@$property->type_id)->get()->toArray()[0]->is_fixable == 1;
@endphp

{!! Theme::partial('real-estate.properties.headers.' . $headerLayout, compact('property')) !!}
 
<!-- ============================ Property Detail Start ================================== --> 
<section class="property-detail gray-simple">
    <div data-property-id="{{ $property->id }}"></div>
    <div class="container">
        <div class="row">
            <!-- property main detail -->
            <div class="col-lg-8 col-md-12 col-sm-12">
                <div class="property_block_wrap social_share_panel_wrap style-2 p-4">
                    <div class="social_share_panel_float top_start">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}&title={{ $property->description }}"
                           target="_blank" class="share_icons cl-facebook">
                           <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ $property->description }}"
                           target="_blank" class="share_icons cl-twitter">
                           <i class="fa fa-twitter" aria-hidden="true"></i>
                        </a>
                        <a href="https://linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&summary={{ rawurldecode($property->description) }}&source=Linkedin" target="_blank" class="share_icons cl-linkedin">
                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                        </a>
                    </div>


                    <div class="prt-detail-title-desc">
                        <span class="prt-types {{ $property->type_slug }}">{{ $property->type_name }}</span>
                        
                        <h3>{{ $property->name }}</h3>
                        <div class="top_rating">
                            @php  
                                $rvw_avg = (float)$property->reviews_avg;
                                $rvw_md = (int)explode(".",number_format($rvw_avg,'2','.',''))[1];
                                $rvw_avg = $rvw_md >= 50 ? ceil($rvw_avg) : floor($rvw_avg);
                                for($si = 1; $si<=5; $si++){
                                    echo '<i class="fa fa-star '.( $rvw_avg >= $si  ? 'act': '').'" aria-hidden="true"></i>';
                                }
                            @endphp        
                        </div>

                        <div class="row mt-4">
                            <div class="top_details col-md-8">
                                <h4 class="property_block_title">{{ __("Summary") }}</h4>
                                <p>{{ (!empty($property->location) ? $property->location . ', ' : ''). $property->city_name }}</p>
                                <p>{{ $property->getFeatureValues(1) }}</p>
                                @if ($property->square)<p>{{ $property->square_text }}</p>@endif
                                @if ($is_fixable)
                                <p>{{__("Arriving On")}}: {{date("h:i A",strtotime($property->arriving_time))}}, {{__("Departing On")}}: {{date("h:i A",strtotime($property->departing_time))}}
                                </p>
                                @endif
                            </div>
                            <div class="col-md-4 text-right">
                                <h3 class="prt-price-fix mb-2">{{ $property->price_html }}</h3>
                                <ul class="like_share_list">
                                    <li class="social_share_list">

                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>

                    @if( trim($property->content) !='')
                    <div class="property_block mt-4">
                        <h4 class="property_block_title">{{ __("Description") }}</h4>
                        {!! clean($property->content) !!}
                    </div>
                    @endif

                    <div class="property_block mt-4">  
                        <table class="table">
                            @if( isset($property->brand)) 
                            <tr>
                                <th>{{__('brand')}}</th> 
                                <td>{{ $property->brand}}</td> 
                            </tr>
                            @endif
                            @if( isset($property->made_in))
                            <tr>
                                <th>{{__('made in')}}</th> 
                                <td>{{ $property->made_in}}</td>  
                            </tr>
                            @endif
                            @if( isset($property->color))
                            <tr>
                                <th>{{__('color')}}</th> 
                                <td>{{ $property->color}}</td>  
                            </tr>
                            @endif
                            @if( isset($property->weight))
                            <tr>
                                <th>{{__('weight')}}</th> 
                                <td>{{ $property->weight}}</td> 
                            </tr>
                            @endif
                            @if( isset($property->length))
                            <tr>
                                <th>{{__('length')}}</th> 
                                <td>{{ $property->length}}</td>   
                            </tr>
                            @endif
                            @if( isset($property->width))
                            <tr>
                                <th>{{__('width')}}</th> 
                                <td>{{ $property->width}}</td>  
                            </tr> 
                            @endif
                             
                        </table>
                    </div>

                    @if ($author = $property->author)
                    @if ($author->username)
                    @php 
                        $crtAt = (array)($author->created_at);
                    @endphp
                    <div class="auther_details mt-4">
                        <img src="{{ !empty($author->avatar->url) ? RvMedia::getImageUrl($author->avatar->url, 'thumb') : '/storage/user.jpg' }}"
                                 alt="{{ $author->name }}">
                        <h4>
                            {{ __("About the Owner") }}, {{ ucwords($author->name) }}
                        </h4>
                        <span>{{ __("Member since") }}: {{ date("M, Y",strtotime($crtAt['date'])) }}</span>
                        @if( !empty($author->phone))
                            <span><i class="lni-phone-handset"></i>{{ $author->phone }}</span>
                        @endif        
                    </div>
                    @endif
                    @endif
                </div>

                @php
                    $features = $property->getFeatureValues();
                @endphp
                @if (!empty($features))
                <div class="property_block_wrap style-2 p-4">
                    <h4 class="property_block_title">{{ __('Amenities') }}</h4>
                       
                    @foreach($features as $grp)
                    <div class="mt-4">
                        <h6>{{ $grp->name }}</h6>
                        <div class="">
                            <div class="row"> 
                            @foreach($grp->fields as $itm)
                            <div class="col-sm-6 col-md-4 mt-2">
                                @php
                                    if( $itm->type =='checkbox' ){
                                        if( $itm->value == 'Y')
                                            echo $itm->name;
                                    }
                                    else
                                        echo $itm->name.' - '.$itm->value;
                                @endphp
                            </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Single Block Wrap - Gallery -->
                {!! Theme::partial('real-estate.elements.gallery', compact('property')) !!}

                <!-- Single Block Wrap - Video -->
                {!! Theme::partial('real-estate.elements.video', ['property' => $property]) !!}

                <!-- Single Block Wrap -->
                <div class="property_block_wrap style-2">

                    <h4 class="property_block_title px-4 pt-3 pb-2"> {{ __("Location") }}</h4>

                    <div id="clSix" class="panel-collapse collapse show">
                        <div class="block-body">
                            @if ($property->latitude && $property->longitude)
                                {!! Theme::partial('real-estate.elements.traffic-map-modal', ['location' => $property->location . ', ' . $property->city_name]) !!}
                            @else
                                {!! Theme::partial('real-estate.elements.gmap-canvas', ['location' => $property->location]) !!}
                            @endif
                        </div>
                    </div>

                </div>

                @if(is_review_enabled())
                <!-- Single Review -->
                    <div id="reviewWrapper">
                        {!! Theme::partial('real-estate.elements.review', compact('property')) !!}
                    </div>
                @endif
            </div>

            <!-- property Sidebar -->
            <div class="col-lg-4 col-md-12 col-sm-12">  
                
                <div class="details-sidebar">
                    <div class="sides-widget">
                        @if(empty($property->monthly_price))
                            <div class="price_right px-4 pt-4">
                                <span>{{ $property->price_html }}</span> {{ $property->type->slug == 'Accessory' ? "" : __('per night')}} 
                            </div>  
                        @endif                                                                                    
                        <div class="sides-widget-body simple-form">
                            {!! Theme::partial('real-estate.elements.form-contact-consult', ['data' => $property, 'consults' => $consults]) !!}
                        </div>
                    </div> 
                    {!! dynamic_sidebar('property_sidebar') !!}
                </div>
            </div>
        </div>

        <div class="row">[recently-viewed-properties title="{{ __('Recently Viewed Properties') }}"
            subtitle="{{ __('Your currently viewed properties.') }}"][/recently-viewed-properties]
        </div>
    </div>
</section>

@if ($property->latitude && $property->longitude)
    <div
        data-magnific-popup="#trafficMap"
        data-map-id="trafficMap"
        data-popup-id="#traffic-popup-map-template"
        data-map-icon="{{ $property->map_icon }}"
        data-center="{{ json_encode([$property->latitude, $property->longitude]) }}">
    </div>
@endif

<script id="traffic-popup-map-template" type="text/x-custom-template">
    {!! Theme::partial('real-estate.properties.map', ['property' => $property]) !!}
</script>

<!-- ============================ Property Detail End ================================== -->


<style type="text/css">
    .property_block_wrap.social_share_panel_wrap {
    position: relative;
    overflow-x: hidden;
}
    .top_start {
        position: fixed;
        top: 40%;left: 22px;
        bottom: 0;z-index: 9;
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
        width: 42px;
        height: 42px;
        text-align: center;
        line-height: 45px;
        margin: 5px 0;
        border-radius: 50px;
    }
    .top_start a i {
        color: #fff !important;
    }
</style>
<script type="text/javascript">
    $(window).scroll(function () {
        var offsetStart = $(".property_block").offset().top;
        if ($(window).scrollTop() >= offsetStart) {
            jQuery('.social_share_panel_float').addClass('share_fixed');
        }else{
            jQuery('.social_share_panel_float').removeClass('share_fixed');
        }

        // console.log($(window).scrollTop(),"sdfsd");
        // console.log($(".featured_slick_gallery").offset().top,"000");
        var offsetEnd = $(".featured_slick_gallery").offset().top;
        if ($(window).scrollTop() >= offsetEnd) {
            // console.log('if')
            jQuery('.social_share_panel_float').addClass('top_start');
        }else{
            // console.log('else')
            jQuery('.social_share_panel_float').removeClass('top_start');
        }

        // var offsetEnd = $(".featured_slick_padd").offset().top;
        // if ($(window).scrollTop() >= offset) {
        //     jQuery('.social_share_panel_float').addClass('share_fixed');
        // }

    });
</script>