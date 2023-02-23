<div class="d-flex">
    <div class="blii">
        <img class="lazy" src="{{ $property->image_thumb }}" height="100" width="100" alt="{{ $property->name }}">
    </div>
    <div class="infomarker">
        <h4><a href="{{ $property->url }}" target="_blank">{{ $property->name }}</a></h4>
        <div class="lists_property_price">
            <div class="lists_property_price_value">
                <h5>{{ $property->price_html }}</h5>
            </div>
        </div>
        <div><i class="ti-location-pin"></i> {{ $property->location }}, {{ $property->city_name }}</div>
    </div>
</div>
