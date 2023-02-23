<div class="property-listing property-2 listing-map">
    <div class="listing-img-wrapper">
        <span class="prt-types {{ $property->type_slug }}">{{ $property->type_name }}</span>
        <div class="list-single-img">
            <a href="{{ $property->url }}">
                <img src="{{ $property->image_thumb }}" class="img-fluid mx-auto" alt="{{ $property->name }}">
            </a>
        </div>
    </div>
    <div class="listing-detail-wrapper pb-0">
        <div class="listing-short-detail">
            <div class="listing-price-fx">
                <h6 class="listing-card-info-price price-prefix">{{ $property->price_html }}</span></h6>
            </div>
            <h4 class="listing-name">
                <a href="{{ $property->url }}" title="{{ $property->name }}">{{ $property->name }}</a>
            </h4>
            <p>{{ $property->city_name }}</p>
        </div>
    </div>
</div>
