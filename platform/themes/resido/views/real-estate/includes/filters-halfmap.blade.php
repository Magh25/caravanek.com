@php
    $layout = theme_option('properties_page_layout');

    $request = (object)request()->input();

    $cond = "p.moderation_status = 'approved'";
    if( !empty($category_id) )
        $cond .= ' AND p.category_id ='.(int)$category->id;
    
    if( !empty($type->id) )
        $cond .= ' AND p.type_id ='.(int)$type->id;

    $price_data = DB::select("SELECT MIN(price) min_price, MAX(price) max_price FROM `re_properties` p WHERE ".$cond);

    $category_ids = !empty($request->category_ids) ? $request->category_ids : [];
    if( !empty($category_ids) && is_string($category_ids) ) $category_ids = explode(',',$category_ids);
    
    $rating = (int)@$request->rating;

    $all_max_price = ceil(@$price_data[0]->max_price);
    $all_min_price = floor(@$price_data[0]->min_price);

    $min_price = intval(@$request->min_price);
    $max_price = intval(@$request->max_price);
    if( $min_price < $all_min_price ) $min_price = $all_min_price;
    if( $max_price <= 0 || $max_price > $all_max_price ) $max_price = $all_max_price;

    $isParking = !empty($type->is_fixable);
    $isCategory = !empty($category->id);
    if($isCategory) $isParking = true;
    $flr_categories = [];
    if( !$isParking ){
        $flr_categories = DB::select("SELECT c.id,c.name FROM `re_categories` c WHERE c.status = 'published' AND c.id IN (SELECT distinct(p.category_id) FROM `re_properties` p WHERE p.moderation_status = 'approved' AND p.category_id > 0 ".(!empty($type->id) ? ' AND type_id= '.(int)$type->id : '').") order by c.order ASC");

        if( !empty($flr_categories) ){
            $langCode = Language::getCurrentLocale();
            $categories_lang = [];

            $_categories_lang = \DB::select("SELECT `reference_id`, `field`, `value`  FROM `language_meta` WHERE `reference_type` = 'Botble\\\RealEstate\\\Models\\\Category' AND `lang_meta_code` = '$langCode' AND `reference_id` IN (".implode(",",array_map(function($c){ return $c->id; },$flr_categories)).")");
            foreach($_categories_lang as $rl){
                $categories_lang[$rl->reference_id][$rl->field] = $rl->value;
            }

            foreach($flr_categories as $idx => $row){
                if( !empty($categories_lang[$row->id]['name']) )
                    $flr_categories[$idx]->name = $categories_lang[$row->id]['name'];
            }
        }
    }

    $property_types = DB::select("SELECT * FROM `re_property_types` ");

    
    Theme::asset()->add('jqueryui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css');
    Theme::asset()->container('footer')->add('jqueryui-js', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
   
@endphp

<div class="daterangepicker-container filter_form">
<div class="row">
    
<!-- location
latlng
pickup
dropoff
guests -->
@if(isset($type->is_Accessory))
    <div class="col-md-10 col-10">
        <div class="box_filed">
            <span class="icon">
                <!-- <img src="/themes/resido/img/search.png"> -->
                <!-- <i class="fa fa-search" aria-hidden="true"></i> -->
            </span>    
             
            <input type="text" name="keyword" id="keyword" value="{{isset($request->keyword) ? $request->keyword : ''}}"class="form-control ht-80" placeholder="{{ __('Search') }}"  />
        </div>
    </div>
    @else
    <div class="col-md-4 col-4">
        <div class="box_filed">
            <span class="icon"><img alt="{{ __('Latest Advertisements') }}" src="/themes/resido/img/location.png"></span>
            <input type="text" value="{{isset($request->location) ? $request->location : ''}}" id="location" id="all_location" class="field_googlemap_autocomplete" data-target=".googlemap_latlng" name="location" placeholder="{{ __('Enter pickup location or address') }}">
            <input type="hidden" class="googlemap_latlng" {{isset($request->latlng) ? '' : 'disabled'}}
            value="{{isset($request->latlng) ? $request->latlng : ''}}" name="latlng" />
        </div>
    </div>
    <div class="col-md-2 col-2">
        <div class="box_filed">
            <span class="icon"><img  alt="{{ __('Latest Advertisements') }}"  src="/themes/resido/img/date-piker.png"></span>
            <input type="text" class="field_daterangepicker field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start" name="pickup" value="{{isset($request->pickup) ? $request->pickup : ''}}" placeholder="{{ __('Pick Up') }}">
        </div>
    </div>
    <div class="col-md-2 col-2">
        <div class="box_filed">
            <span class="icon"><img   alt="{{ __('Latest Advertisements') }}" src="/themes/resido/img/date-piker.png"></span>    
            <input type="text" class="field_daterangepicker field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" value="{{isset($request->dropoff) ? $request->dropoff : ''}}" placeholder="{{ __('Drop Off') }}">
        </div>
    </div>
    <div class="col-md-2 col-2">
        <div class="box_filed"> 
            <span class="text">{{ __('Advertising type') }}</span>
            <select name="guests" id="num_adults"> 
                
                <option value="" >----- </option> 
                @foreach ($property_types as $typ)
                    <option value="{{$typ->id}} " > {{__($typ->name)}} </option> 
                @endforeach  
                 
            </select>
        </div>
    </div>
    @endif

    
     



    <div class="col-md-2 col-2 elem-group">
        
        <button type="submit" class="btn_filter_submit">{{ __('Search') }}</button>
    </div>
</div>
<div class="row filter_buttons mt-3">
    @if( $all_min_price > 0 && $all_min_price != $all_max_price)
    <div class="dropdown col-6 col-md-2">
      <button class="dropdown-toggle {{ $all_min_price != $min_price || $all_max_price != $max_price ? 'active':''}}" type="button" id="filtter_price" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
        Price
      </button>
      <div class="dropdown-menu filter_price p-4">
        <div class="row"><div class="col-6">
            <span>{{__("Min Price")}}</span>
            <input type="number" name="min_price" class="field_min_price filter-item" value="{{$min_price}}" />
        </div><div class="col-6">
            <span>{{__("Max Price")}}</span>
            <input type="number" name="max_price" class="field_max_price filter-item" value="{{$max_price}}" />
        </div></div>
        <div class="price-filter-range" data-max_price="{{$all_max_price}}" data-min_price="{{$all_min_price}}" data-field_min=".field_min_price" data-field_max=".field_max_price" aria-labelledby="filtter_price"></div>
        <div class="text-right mt-3">
            <button class="clear snd" type="button">{{__("Clear")}}</button> <button type="submit" class="apply snd">{{__("Apply")}}</button>
        </div>
      </div>
    </div>
    @endif
    @if (!$isParking && !empty($flr_categories))
    <div class="dropdown col-6 col-md-2 ps-0">
      <button class="dropdown-toggle {{ !empty($category_ids) ? 'active':''}}" type="button" id="filter_class" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         <!-- class -->
      </button>
      <div class="dropdown-menu itms" aria-labelledby="filter_class">
            @foreach($flr_categories as $frow)
            <div class="dropdown-item{{ in_array($frow->id,$category_ids) ? ' active' : ''}}">
                <input id="filter_class_{{$frow->id}}" type="checkbox" {{ in_array($frow->id,$category_ids) ? 'checked' : ''}} name="category_ids[]" value="{{$frow->id}}"> {{$frow->name}}
            </div>
            @endforeach
      </div>
    </div>
    @endif
    <div class="dropdown col-6 col-md-2 ps-0">
      <button class="dropdown-toggle {{ $rating > 1 ? 'active':''}}" type="button" id="filter_rating" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Rating
      </button>
      <div class="dropdown-menu itms" aria-labelledby="filter_rating">
        <div class="dropdown-item {{ $rating == 0 ? ' active' : ''}}" >
            <input id="filter_rating" value="" {{ $rating == '' ? ' checked' : ''}} type="radio" name="rating" /> 
            <span>All Review</span>
        </div>
        @for($si = 1;$si<=5; $si++)
            <div class="dropdown-item{{ $rating == $si ? ' active' : ''}}">
                <input id="filter_rating_{{$si}}" value="{{$si}}" {{ $rating == $si ? ' checked' : ''}} type="radio" name="rating" /> 
                @for($_si = 1;$_si<=$si; $_si++)
                <i class="fa fa-star"></i> 
                @endfor
            </div>
        @endfor
      </div>
    </div>
    <div class="col-6 col-md-2 ps-0 pt-2">
        <button class="clear_all clear snd">{{__("Clear All")}}</button>
    </div>
</div>


</div>
