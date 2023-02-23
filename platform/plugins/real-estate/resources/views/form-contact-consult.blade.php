{!! Form::open(['route' => 'public.send.consult', 'method' => 'POST', 'class' => 'contact-form', 'id' => 'contactForm']) !!}
<?php 
    
    $addons = [];
    $spaces = [];
    $unitstype = [];
    $is_fixable = @DB::table('re_property_types')->select('is_fixable')->where('id', '=', (int)@$data->type_id)->get()->toArray()[0]->is_fixable == 1;

    $no_spaces = $avail_spaces = $booked_spaces = 0;
    if( $is_fixable) 
        $no_spaces = (int)@$data->no_of_spaces ?: 1;


    if( !empty($data->spaces) && $is_fixable){
        foreach($data->spaces as $ad){
            if( trim(@$ad['name']) != ''){
                $spaces[] = ['name'=>$ad['name'], 'available'=>$ad['available'] ,'price'=>!empty($ad['price']) && is_numeric($ad['price']) ? number_format($ad['price'],2,'.',''): '0.00'];        
            }
        }
    }

   

    if( !empty($data->addons) && $is_fixable){
        foreach($data->addons as $ad){
            if( trim(@$ad['name']) != ''){
                $addons[] = ['name'=>$ad['name'],'price'=>!empty($ad['price']) && is_numeric($ad['price']) ? number_format($ad['price'],2,'.',''): '0.00'];        
            }
        }
    }


    if( !empty($data->unitstype) && $is_fixable){
        foreach($data->unitstype as $ad){ 
            if( trim(@$ad['unitstype']) != ''){
                $unitstype[] = ['unitstype'=>$ad['unitstype']];        
            }
        }
    }

    $request = request()->input();
    $userId = auth('account')->check() ? auth('account')->id() : 0; 
    $property_id = (int)$data->id;
    $MyBooking = false;

    $bookedDates = [];
    $AllBookings = DB::select("SELECT * FROM re_consults WHERE property_id = $property_id AND status IN ('unread','approved') AND '".date("Y-m-d")."' < `to_date`");

    foreach($AllBookings as $bk){
        $bk_dates = getAllBookedDates(trim($bk->from_date),trim($bk->to_date));
        $bookedDates = array_merge($bookedDates,$bk_dates);
    }
    $bookedDates = array_unique($bookedDates);

    if( $userId ){
        $MyBooking = DB::select("SELECT * FROM re_consults WHERE property_id = $property_id AND user_id = $userId AND status IN ('unread','approved') AND '".date("Y-m-d")."' < `to_date`");
        $MyBooking = !empty($MyBooking[0]->id) ? $MyBooking[0] : false;
    }

    function getAllBookedDates($date1, $date2, $format = 'Y-m-d' ) {
        $dates = array();
        $t1 = strtotime($date1);
        $t2 = strtotime($date2);
        while( $t1 < $t2 ) {
            $dates[] = date($format, $t1);
            $t1 = strtotime('+1 day', $t1);
        }
        return $dates;
    }
    
    $fullName = '';
    $phone = '';
    $email = '';
    if($userId && auth('account')->user()){
        $first_name = auth('account')->user()->first_name;
        $last_name = auth('account')->user()->last_name;
        $email = auth('account')->user()->email;
        $phone = auth('account')->user()->phone;
        $fullName = $first_name.' '.$last_name;
    }
?>

@php 
    $pickup = '';
    $dropoff = '';
    $guests = '';
    if(isset($_GET['pickup'])){
        $pickup = $_GET['pickup'];
        $dropoff = $_GET['dropoff'];
        $guests = $_GET['guests'];
    }
@endphp  
@if( $MyBooking)
    <div class="booked_details">
    <p>{{__("You already booked this property, below are booking details:")}}</p>
    <p><b>{{__("Booking ID")}}:</b> #{{ $MyBooking->id }}</p>
    <p>
        <b>{{__("Booked dates")}}:</b> {{ date("d/m/Y",strtotime($MyBooking->from_date))}} - {{ date("d/m/Y",strtotime($MyBooking->to_date))}}
    </p>
    <p><b>{{__($is_fixable ? "No of Spaces" : "No of Guests")}}:</b> {{ $MyBooking->guests }}</p>
    @if($is_fixable && !empty($MyBooking->addons) )
    <p><b>{{__("Addons")}}:</b> 
    {{ implode(" , ", array_map(function($a){ return $a->name; },(array)json_decode($MyBooking->addons))) }}</p>

    @if(!empty($MyBooking->unitstype))
        <p><b>{{__("Parking Spaces")}}:</b> 
        {{ implode(" , ", array_map(function($a){ return $a->name; },(array)json_decode($MyBooking->spaces))) }}
        <p><b>{{__("Units Type")}}:</b> 
        {{ $MyBooking->unitstype }}</p>
        <p><b>{{__("Booking Type")}}:</b> 
        {{ $MyBooking->bookingtype }}</p>
    @endif
    </p>
    @endif
    <p><b>{{__("Total Price")}}:</b> SAR{{ number_format($MyBooking->total_price,2,'.',',') }}</p>
    </div>
@else
<div class="row daterangepicker-container filter_form property_form {{$is_fixable ? 'fixable_property' : ''}}" data-dates="{{ implode(",",$bookedDates) }}">
    <input type="hidden" name="data_id" value="{{ $property_id }}">
    <input type="hidden" name="user_id" value="{{ $userId }}">
     
    @if( $userId )   
        <input type="hidden" name="name" value="{{$fullName}}" >
        <input type="hidden" name="phone" value="{{$phone}}">
        <input type="hidden" name="email" value="{{$email}}">
        <input type="hidden" name="subject" value=""/>
        <input type="hidden" name="content" value="BOOKING"/>
    @endif
    <div class="form-group col-sm-9">
        <div class="row">
            <div class="col-6">
                <label>{{ __('Dates') }}</label>
                <div class="box_filed">
                    <span class="icon"><img src="/themes/resido/img/date-piker.png"></span>
                    <input type="text" class="field_daterangepicker field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start" name="pickup" value="{{isset($request->pickup) ? $request->pickup : $pickup }}" placeholder="{{ __('Pick Up') }}">
                    <!-- <input type="text" class="field_daterangepicker field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start" name="pickup" value="{{isset($request->pickup) ? $request->pickup : date("d/m/Y",strtotime("+0 day",time()))}}" placeholder="{{ __('Pick Up') }}"> -->
                </div>
            </div>
            <div class="col-6">
                <label>&nbsp;</label>
                <div class="box_filed">
                    <span class="icon"><img src="/themes/resido/img/date-piker.png"></span>    
                    <input type="text" class="field_daterangepicker field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" value="{{isset($request->dropoff) ? $request->dropoff : $dropoff }}" placeholder="{{ __('Drop Off') }}">
                    <!-- <input type="text" class="field_daterangepicker field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" value="{{isset($request->dropoff) ? $request->dropoff : date("d/m/Y",strtotime("+1 day",time()))}}" placeholder="{{ __('Drop Off') }}"> -->
                </div>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-3">
        <label for="guests">{{ __($is_fixable ? "Spaces" : 'Guests') }}</label>
        <div class="box_filed">
            <span class="icon"><img src="/themes/resido/img/guests-icon.png"></span>
            <select name="guests" id="guests" class="form-control {{$is_fixable ? 'fixable_slc_no_spaces' : ''}}" data-availspaces="{{$avail_spaces}}">
            @php
                for($gi = 1; $gi <= 10; $gi++)
                    echo '<option value="'.$gi.'" '.(@$guests == $gi ? 'selected' : '').'>'.$gi.($gi == 10 ? '+' :'').'</option>';
            @endphp
            </select>
        </div>
    </div>


    @if (!empty($unitstype))  
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-6">
                <label for="guests">{{ __('Unit Type') }}</label>
                <div class="box_filed">
                    <select name="unitstype">
                        @foreach($unitstype as $idx => $unittype)
                            <option value="{{$unittype['unitstype']}}">{{$unittype['unitstype']}}</option>  
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <label for="guests">{{ __('Booking Type') }}</label>
                <div class="box_filed">
                    <input class="form-check-input" value="{{ floatval($data->price) }}" type="radio" name="bookingtype" id="Hourly">
                    <label class="form-check-label" for="Hourly"> 
                       {{ __('Hourly/Daily')}}
                    </label> 
                    <input class="form-check-input" value="{{ floatval($data->monthly_price) }}" type="radio" name="bookingtype" id="monthly">
                    <label class="form-check-label" for="monthly">
                       {{ __('monthly')}}
                    </label>
                </div>
            </div>
        </div>
    </div>
    @endif 



    @if( !empty($addons) )
    <div class="form-group col-sm-12">
        <label for="guests">{{ __('Addons') }}</label>
        @foreach($addons as $idx => $addon)
        <div class="box_property_addon">
            <label for="chb_property_addon_{{$idx}} {{ app()->getLocale() == 'ar' ? 'ar-type' : ''}} ">
                <input type="checkbox" id="chb_property_addon_{{$idx}}" name="addons[]" value="{{$addon['name']}}|^|{{$addon['price']}}" data-price="{{ floatval($addon['price']) }}">
                {{$addon['name']}} 
            </label>
            <span class="price">SAR{{number_format(floatval($addon['price']),2,'.',',')}}</span>
        </div>
        @endforeach
    </div>
    @endif

    @if( !empty($spaces) )
    <div class="form-group col-sm-12">
        <label for="guests">{{ __('Parking Spaces') }}</label>
        @foreach($spaces as $idx => $space)
        <div class="box_property_space">
            <label for="chb_property_space_{{$idx}} {{ app()->getLocale() == 'ar' ? 'ar-type' : ''}} ">
                <input type="radio" id="chb_property_space_{{$idx}}" name="spaces" value="{{$space['name']}}|^|{{$space['price']}}" data-price="{{ floatval($space['price']) }}">
                {{$space['name']}} 
            </label>
            <span class="price">SAR{{number_format(floatval($space['price']),2,'.',',')}}</span>
        </div>
        @endforeach
    </div>
    @endif

    <div class="property_calculated_content col-sm-12" data-price="{{floatval($data->price)}}">
        <div class="row mt-2">
            <div class="col-7">SAR<span class="one_total">{{number_format($data->price,2,'.',',')}}</span> x <span class="nights">1</span> {{__("nights")}}</div>
            <div class="col-5 text-right">SAR<span class="price">{{ number_format($data->price,2,'.',',')}}</span></div>
        </div>
        <div class="row mt-2">
            <div class="col-7">{{__("Taxes and Fees")}}</div>
            <div class="col-5 text-right">SAR<span>0.00</span></div>
        </div>
        <div class="row mt-2">
            <div class="col-7"><b>{{__("Total")}}</b></div>
            <div class="col-5 text-right">SAR<span class="total">{{ number_format($data->price,2,'.',',')}}</span></div>
        </div>
    </div>
</div>
<p class="text-center">{{__("Payment Method: Only Cash on Location")}}</p>
<div class="mt-4">
    @if (setting('enable_captcha') && is_plugin_active('captcha'))
        <div class="form-group">
            {!! Captcha::display() !!}
        </div>
    @endif
    @if (auth('account')->check())
        <div class="form-group">
            <button class="btn btn-black btn-md rounded full-width" type="submit">{{ __('Send Message') }}</button>
        </div>
     @else
        <div class="form-group">
            <a href="{{ url('login?redirect_to=' . urlencode(request()->url())) }}">
                <button class="btn btn-black btn-md rounded full-width" type="button">{{ __('Send Message') }}</button>
            </a>
        </div>
    @endif
    <p class="grey text-center">{{ __('You won’t be charged yet') }}</p>
</div>
    <div class="clearfix"></div>
    <div class="alert alert-success text-success text-left" style="display: none;">
        <span></span>
    </div>
    <div class="alert alert-danger text-danger text-left" style="display: none;">
        <span></span>
    </div>
</div>
@endif
{!! Form::close() !!}  