@php
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Repositories\Interfaces\CityInterface;

$cities = collect([]);
if (is_plugin_active('location')) {
    $cities = app(CityInterface::class)->getFeaturedCities([
        'condition' => [
            'cities.status' => BaseStatusEnum::PUBLISHED,
        ],
        'take' => (int) theme_option('number_of_featured_cities', 6),
        'withCount' => ['properties'],
        'select' => ['cities.id', 'cities.name'],
        'with' => ['metadata', 'slugable'],
    ]);
}






 
      


@endphp






<div class="travel_sec mt-5">
    <div class="container">
        <h2 class="text-center">{{ __('caravanek for All Your Travel Needs') }}</h2>
        <div class="row">
            <div class="travel_colum">
            <a href="/listing/Parking">
                <img src="themes/resido/img/caravan_2.jpeg" alt="{{ __('caravanek for All Your Travel Needs') }}" class="img-fluid">
                <div class="travel_content">
                    <h4>{{ __('Book a Parking Spot') }}</h4>
                    <p>{{ __('Reserve a dedicated car park for your caravan at one of our caravan resorts') }}</p>
                </div>
            </a>
            </div>
            <div class="travel_colum">
            <a href="/listing/Vehicle">
                <img src="themes/resido/img/caravan_1.jpeg" alt="{{ __('caravanek for All Your Travel Needs') }}" class="img-fluid">
                <div class="travel_content">
                    <h4>{{ __('Book a caravan') }}</h4>
                    <p>{{ __('Book your caravan and receive it from one of our branches, or request a connection to the interface you want') }}</p>
                </div>
            </a>
            </div>

            <div class="travel_colum">
            <a href="/listing/Accessory">
                <img src="themes/resido/img/caravan_3.jpeg" alt="{{ __('caravanek for All Your Travel Needs') }}" class="img-fluid">
                <div class="travel_content">
                    <h4>{{ __('Caravan Accessories') }}</h4>
                    <p>{{ __('You can order many caravan accessories') }}</p>
                </div>
            </a>
            </div>
        </div>
    </div>
</div>






<!-- ========start edit by magh get_properties_last_four======================= -->
<div class="top_picks_sec">
    <div class="container">
        <div class="row">
            <div class="heading">
                <h2>{{ __('Latest Advertisements') }}</h2>
            </div> 
            <div class="view_right">
                <a href="/listing">{{ __('View all') }} <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
            </div>             
        </div>
        <div class="row travel_pick">
            <div class="pick_wrap_row">
                @foreach($properties as $property)
                    <div class="col-md-12 pick_wrap">
                        {!! Theme::partial('real-estate.properties.item-list', compact('property')) !!}
                    </div>  
                @endforeach               
            </div>
        </div>
    </div>
</div>
<!-- ==============end edit by magh get_properties_last_four================= -->




<div class="categ_sec">
    <div class="container">
        <div class="row">
            <div class="cat_colum">
                <div class="icon_box">
                    <img src="themes/resido/img/wild-raning.png" alt="{{ __('caravanek for All Your Travel Needs') }}">
                </div>
                <div class="content_cat">
                    <h5>{{ __('LARGEST RV RENTAL MARKETPLACE IN MIDDLE EAST') }}</h5>
                    <p>{{ __('From affordable pop-ups to luxury') }} <br>{{ __('motorhomes') }}</p>
                </div>
            </div>
            <div class="cat_colum">
           
                <div class="icon_box">
                    <img src="themes/resido/img/marketplace.png" alt="{{ __('caravanek for All Your Travel Needs') }}">
                </div>
                <div class="content_cat">
                    <h5>{{ __('LARGEST caravan RENTAL MARKETPLACE') }}</h5>
                    <!-- <p>{{ __('Thousands of 5 star reviews from') }} <br>{{ __('happy customers') }}</p> -->
                    <p>{{ __('Our goal is to provide high quality') }} <br>{{ __('services') }}</p>
                </div>
             
            </div>
            <div class="cat_colum">
                <div class="icon_box">
                    <img src="themes/resido/img/trusted.png" alt="{{ __('caravanek for All Your Travel Needs') }}">
                </div>
                <div class="content_cat">
                    <h5>{{ __('SAFEST AND MOST TRUSTED') }}</h5>
                    <p>{{ __('24/7 Emergency roadside assistance') }} <br>{{ __('on every booking') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>







<!-- ========start edit by magh get_properties_last_four======================= -->
<div class="top_picks_sec">
    <div class="container">
        <div class="row">
            <div class="heading">
                <h2>{{ __('Latest articles') }}</h2>
            </div> 
            <div class="view_right">
                <a href="/listing">{{ __('View all') }} <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
            </div>             
        </div>
        <div class="row travel_pick">
            <div class="pick_wrap_row">
                     


                @php 
                
                    $addLink_Param = '';
                    $supportedLocales = Language::getSupportedLocales();
                    foreach ($supportedLocales as $localeCode => $properties){ 
                        if ($localeCode == Language::getCurrentLocale()){
                            
                            $addLink_Param = '?language='.$properties['lang_code'];
                            
                        }
     }
                $posts = \Botble\Blog\Models\Post::orderBy('id', 'desc')->take(3)->get() ;
                
                @endphp
                @foreach($posts as $post)
                    
                    <div class="col-md-12 pick_wrap">
                        <div class="blog-wrap-grid">
                            <div class="blog-thumb">
                                <a href="{{ $post->url }}{{$addLink_Param}}">
                                    <img
                                        data-src="{{ RvMedia::getImageUrl($post->image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                        src="{{ get_image_loading() }}"
                                        alt="{{ $post->name }}" class="img-fluid thumb lazy">
                                </a>
                            </div>

                            <div class="blog-info"> 
                            {!! Theme::partial('post-meta', compact('post')) !!}

                            </div>

                            <div class="blog-body">
                                <h4 class="bl-title">
                                    <a href="{{ $post->url }}{{$addLink_Param}}" title="{{ $post->name }}">
                                        {{ $post->name }}
                                    </a>
                                </h4>
                                <p>{{ Str::words($post->description, 50) }}</p>
                                <a href="{{ $post->url }}{{$addLink_Param}}" class="bl-continue">{{ __('Continue') }}</a>
                            </div>

                        </div>
                    </div>
             
                @endforeach




            </div>
        </div>
    </div>
</div>
<!-- ==============end edit by magh get_properties_last_four================= -->









<div class="destinations_sec">
    <div class="container">
        <div class="row">
            <div class="heading">
                <h2>{{ __('our top caravan picks') }}</h2>
            </div>
            <div class="view_right">
                <a href="/listing">{{ __('View all') }} <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
            </div>
        </div>
        <div class="row destinations_wrap">
            <div class="col-md-8 destinations_left">   
                @php
                    $count = 1;
                @endphp
                @foreach ($cities as $city)
                @if($count <= 4)
                @php
                    $avgPrice = $_price = $_pcount = 0;
                    foreach($city->properties as $p){
                        $_pcount++;
                        $_price += !empty($p->price) ? floatval($p->price) : 0;                        
                    }
                    $avgPrice = ceil($_price/( $_pcount ?: 1));
                @endphp
                    <div class="item-wrap">
                        <a href="{{ $city->url }}">
                        <div class="img_item">
                            <img src="{{ RvMedia::getImageUrl($city->getMetaData('image', true), 'medium', false, RvMedia::getDefaultImage()) }}" class="img-fluid" alt="{{ $city->name }}">
                        </div>
                        <div class="content_box">
                            <h4>{{ $city->name }}</h4>
                            <p>{{ __('For an average of :currency :price per night',['price'=>$avgPrice,'currency'=>"SAR"]) }}</p>
                        </div>
                        </a>
                    </div>
                @endif
                @php $count++; @endphp
                @endforeach 
            </div>
            <div class="col-md-4 destinations_right">
                @php
                    $count = 1;
                @endphp
                @foreach ($cities as $city)
                @if($count == 5)

                @php
                    $avgPrice = $_price = $_pcount = 0;
                    foreach($city->properties as $p){
                        $_pcount++;
                        $_price += !empty($p->price) ? floatval($p->price) : 0;                        
                    }
                    $avgPrice = ceil($_price/( $_pcount ?: 1));
                @endphp
                
                    <div class="item-wrap">
                        <a href="{{ $city->url }}">
                            <div class="img_item">
                                  <img src="{{ RvMedia::getImageUrl($city->getMetaData('image', true), 'medium', false, RvMedia::getDefaultImage()) }}" class="img-fluid" alt="{{ $city->name }}">
                            </div>
                            <div class="content_box">
                                <h4>{{ $city->name }}</h4>
                                <p>{{ __('For an average of :currency :price per night',['price'=>$avgPrice,'currency'=>"SAR"]) }}</p>
                            </div>
                        </a>
                    </div> 
                @endif
                @php $count++; @endphp
                @endforeach 
            </div>

        </div>
    </div>
</div>