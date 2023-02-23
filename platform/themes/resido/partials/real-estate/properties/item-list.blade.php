@php
    $is_lazyload = isset($lazyload) ? $lazyload : true;
@endphp
<?php 
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
    $segments = $uri_segments['1'];
?>
@if(0 && $segments === '')

<div class="travel_box">
    <div class="pick_img">
            <span class="prt-types {{ $property->type->slug }}">{{ $property->type_name }}</span>
                
        <a href="{{ $property->url }}" class="more-btn">
            <img src="{{ RvMedia::getImageUrl($property->image ?? '', 'medium', false, RvMedia::getDefaultImage()) }}" class="img-fluid" alt="{{ $property->name }}" ></a>
    </div>
    <div class="contant_pick">
        <div class="price_row">
            <div class="price">
                {{ $property->price_html }}
            </div>
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
        </div>
        <h3 class="pick_title">
            <a href="{{ $property->url }}" class="more-btn">   
                 {!! clean($property->name) !!}
             </a>
        </h3>
        <p>
            {{ $property->getLocation() }} 
        </p>
        <p> 
            {{ $property->getFeatureValues(1) }}
        </p>
    </div>
</div>

@else  

  <div class="property-listing property-1" data-lat="{{ $property->latitude }}"
    data-long="{{ $property->longitude }}">
    <div class="listing-img-wrapper">
        <span class="prt-types {{ $property->type->slug }}">{{ $property->type_name }}</span>
            
        <a href="{{ $property->url }}">
            @if($is_lazyload)
            <img src="{{ get_image_loading() }}"
                data-src="{{ RvMedia::getImageUrl($property->image ?? '', 'medium', false, RvMedia::getDefaultImage()) }}"
                class="img-fluid mx-auto lazy" alt="{{ $property->name }}"/>
            @else
            <img src="{{ RvMedia::getImageUrl($property->image ?? '', 'medium', false, RvMedia::getDefaultImage()) }}"
                class="img-fluid mx-auto" alt="{{ $property->name }}"/>
            @endif
        </a>
    </div>

    <div class="listing-content"> 
        <div class="listing-detail-wrapper-box">
            <div class="listing-detail-wrapper">
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
                    <h4 class="listing-name">
                        <a href="{{ $property->url }}" class="prt-link-detail"
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
</div>
@endif