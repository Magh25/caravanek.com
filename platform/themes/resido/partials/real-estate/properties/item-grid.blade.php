@php
    $img_slider = isset($img_slider) ? $img_slider : true;
    $is_lazyload = isset($lazyload) ? $lazyload : true;
@endphp

<div class="property-listing property-2 {{ $class_extend ?? '' }}"
     data-lat="{{ $property->latitude }}"
     data-long="{{ $property->longitude }}">
    <div class="listing-img-wrapper">
        <span class="prt-types {{ $property->type->slug }}">{{ $property->type_name }}</span>
            
        <div class="list-img-slide">
                    
            <div class="click @if(!$img_slider) not-slider @endif">
                @foreach ($property['images'] as $image)
                    <div>
                        <a href="{{ $property->url }}">
                            @if($is_lazyload)
                            <img src="{{ get_image_loading() }}"
                                data-src="{{ RvMedia::getImageUrl($image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                class="img-fluid mx-auto lazy" alt="{{ $property->name }}"/>
                            @else
                            <img src="{{ RvMedia::getImageUrl($image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                class="img-fluid mx-auto" alt="{{ $property->name }}"/>
                            @endif
                        </a>
                    </div>
                    @if(!$img_slider) @break @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="listing-detail-wrapper">
        <div class="listing-short-detail-wrap">
            <div class="listing-short-detail">
                <div class="list-price">
                    <div class="rating">
                    @php  
                        $rvw_avg = (float)$property->reviews_avg;
                        $rvw_md = (int)explode(".",number_format($rvw_avg,'2','.',''))[1];
                        $rvw_avg = $rvw_md >= 50 ? ceil($rvw_avg) : floor($rvw_avg);
                        for($si = 1; $si<=5; $si++){
                            echo '<i class="fa fa-star '.( $rvw_avg >= $si  ? 'act': '').'" aria-hidden="true"></i>';
                        }
                    @endphp        
                    </div>
                    <h6 class="listing-card-info-price">
                        {{ $property->price_html }}
                    </h6>
                    
                </div>
                @php 
                    $addUrl = '';
                    if(isset($_GET['pickup'])){
                        $pickup = $_GET['pickup'];
                        $dropoff = $_GET['dropoff'];
                        $guests = $_GET['guests'];
                        $addUrl = '?pickup='.$pickup.'&dropoff='.$dropoff.'&guests='.$guests; 
                    }
                @endphp        
                <h4 class="listing-name">
                    <a href="{{ $property->url.$addUrl }}" class="prt-link-detail"
                       title="{{ $property->name }}">{!! clean($property->name) !!}</a>
                </h4>
                <p>
                    {{ $property->getLocation() }} 
                </p>
                <p> 
                    {{ $property->getFeatureValues(1) }}  
                </p>
            </div>
        </div>
    </div>
</div> 