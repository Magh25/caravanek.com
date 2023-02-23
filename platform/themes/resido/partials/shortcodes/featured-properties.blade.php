@php
    $addLink = '';
    if( Request::segment(1) == 'en'){
        $addLink = '/en';
    }
@endphp
<div class="top_picks_sec">
    <div class="container">
        <div class="row">
            <div class="heading">
                <h2>{{ __('Destinations our Renters Love') }}</h2>
            </div>
            <div class="view_right">
                <a href="{{ $addLink }}/listing">{{ __('Show all') }}<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
            </div>              
        </div>
        <div class="row travel_pick">
            <div class="pick_wrap_row">
                @foreach($properties as $property)
                    <div class="col-md-4 pick_wrap">
                        {!! Theme::partial('real-estate.properties.item-list', compact('property')) !!}
                    </div>  
                @endforeach               
            </div>
        </div>
    </div>
</div>