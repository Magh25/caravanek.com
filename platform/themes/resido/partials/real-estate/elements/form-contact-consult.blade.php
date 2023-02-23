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


    //---------------------------------  
    $property_type_commission_fees = (($data->price * $data->type->commission)/100);
    $property_type_taxes = (($data->price * 15)/100);

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
    $pickup = date("d/m/Y");
    $dropoff = date("d/m/Y", strtotime('tomorrow'));
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
    <p><b>{{__("Booking ID")}}:</b><br> #{{ $MyBooking->id }}</p>
    @if($data->type->is_Accessory != 1)
    <p>
        <b>{{__("Booked dates")}}:</b><br> {{ date("d/m/Y",strtotime($MyBooking->from_date))}} - {{ date("d/m/Y",strtotime($MyBooking->to_date))}}
    </p> 
    <p><b>{{__($is_fixable ? "No of Spaces" : "No of Guests")}}:</b><br> {{ $MyBooking->guests }}</p>
    @else
    <p><b>{{__("count")}}:  {{ $MyBooking->guests }}</b>
    @endif
    @if($is_fixable && !empty($MyBooking->addons) )
    <p><b>{{__("Addons")}}:</b> <br>
    {{ implode(" , ", array_map(function($a){ return $a->name; },(array)json_decode($MyBooking->addons))) }}</p>

    @if(!empty($MyBooking->unitstype))
        <p><b>{{__("Parking Spaces")}}:</b> <br>
        {{ implode(" , ", array_map(function($a){ return $a->name; },(array)json_decode($MyBooking->spaces))) }}
        <p><b>{{__("Units Type")}}:</b> <br>
        {{ $MyBooking->unitstype }}</p>
        <p><b>{{__("Booking Type")}}:</b> <br>
        {{ $MyBooking->bookingtype }}</p>
    @endif
    </p>
    @endif
    <p><b>{{__("Total Price")}}:</b><br> SAR{{ number_format($MyBooking->total_price,2,'.',',') }}</p>
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


    <!-- -------------/. by magh--------- -->
    @if($data->type->is_Accessory != 1)
        
        @if (empty($unitstype))  
        <div class="col-lg-12 form-group">
            <label for="guests">{{ __('Booking Type') }} </label>
            <div class="row">
                <div class="col-12">
                    <div class="bookingtype">
                        <label class="form-check-label" for="Hourly"> 
                            <input class="stv-radio-tab" checked  data-price="{{ floatval($data->price) }}"   value="Daily" type="radio" name="bookingtype" id="Hourly">
                        <span>SAR{{ floatval($data->price) }}</span>&nbsp;  {{ __('Daily')}}
                        </label> &nbsp;&nbsp;
                        <br>
                        <label class="form-check-label" for="monthly">
                            <input class="stv-radio-tab" data-price="{{ floatval($data->monthly_price) }}"     value="Monthly" type="radio" name="bookingtype" id="monthly">
                            <span>SAR{{ floatval($data->monthly_price) }}</span>&nbsp;  {{ __('Monthly')}}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @endif


        <div class="form-group col-sm-9">
            <div class="row">
                <div class="col-6">
                    <label>{{ __('Dates') }}</label>
                    <div class="box_filed start_date">
                        <span class="icon"><img src="/themes/resido/img/date-piker.png"></span>
                        <input type="text" id="pickupDate" class="field_daterangepicker field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start" name="pickup" value="{{isset($request->pickup) ? $request->pickup : $pickup }}" placeholder="{{ __('Pick Up') }}">
                        <!-- <input type="text" class="field_daterangepicker field_daterangepicker_1" data-field=".field_daterangepicker_2" data-type="start" name="pickup" value="{{isset($request->pickup) ? $request->pickup : date("d/m/Y",strtotime("+0 day",time()))}}" placeholder="{{ __('Pick Up') }}"> -->
                    </div>
                </div>
                <div class="col-6">
                    <label>&nbsp;</label>
                    <div class="box_filed end_date">
                        <span class="icon"><img src="/themes/resido/img/date-piker.png"></span>    
                        <input type="text" id="dropoffDate" class="field_daterangepicker field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" value="{{isset($request->dropoff) ? $request->dropoff : $dropoff }}" placeholder="{{ __('Drop Off') }}">
                        <!-- <input type="text" class="field_daterangepicker field_daterangepicker_2" data-field=".field_daterangepicker_1" data-type="end" name="dropoff" value="{{isset($request->dropoff) ? $request->dropoff : date("d/m/Y",strtotime("+1 day",time()))}}" placeholder="{{ __('Drop Off') }}"> -->
                    </div>
                </div>
            </div>
        </div>  


        <div class="form-group col-sm-3">
            <label for="guests">{{ __($is_fixable ? "Spaces" : 'Guests') }}</label>
            <div class="box_filed">
                <span class="icon"><img src="/themes/resido/img/guests-icon.png"></span>
                <select name="guests" id="guests" class="form-control guests {{$is_fixable ? 'fixable_slc_no_spaces' : ''}}" data-availspaces="{{$avail_spaces}}">
                @php
                    for($gi = 1; $gi <= 10; $gi++)
                        echo '<option value="'.$gi.'" '.(@$guests == $gi ? 'selected' : '').'>'.$gi.($gi == 10 ? '+' :'').'</option>';
                @endphp
                </select>
            </div>
        </div> 

    @else 


        <!--  type="hidden"  -->
        <input  type="hidden" id="pickupDate"  class="field_daterangepicker field_daterangepicker_1"  data-field=".field_daterangepicker_2" data-type="start" name="pickup" value="{{isset($request->pickup) ? $request->pickup : $pickup }}"  > 
        <input type="hidden" id="dropoffDate" class="field_daterangepicker field_daterangepicker_2"   data-field=".field_daterangepicker_1" data-type="end" name="dropoff" value="{{isset($request->dropoff) ? $request->dropoff : $dropoff }}"  >

        <input type="hidden"   data-price="{{ floatval($data->monthly_price) }}"     value="Hourly"  name="bookingtype" id="Hourly">
 
        <div class="form-group col-sm-9">
            <div class="row">
                 
                <div class="col-12">
                    <label>&nbsp;{{ __('count') }}    </label>
                    <div class="  "> 
                        <select name="guests" id="count_guests" class="form-control count_guests guests {{$is_fixable ? 'fixable_slc_no_spaces' : ''}}" data-availspaces="{{$avail_spaces}}">
                            @php
                                for($gi = 1; $gi <= 10; $gi++)
                                    echo '<option value="'.$gi.'" '.(@$guests == $gi ? 'selected' : '').'>'.$gi.'</option>';
                            @endphp
                        </select>   
                    </div>
                </div>
            </div>
        </div>  

         
        <!-- --------------- -->
 

    @endif
    <!-- -------------./ by magh--------- -->


    @if (!empty($unitstype))  
    <div class="form-group col-sm-12">
        <label for="guests">{{ __('Unit Type') }}</label>
        <div class="box_filed_unit">
            <select name="unitstype">
                @foreach($unitstype as $idx => $unittype)
                    <option value="{{$unittype['unitstype']}}">{{$unittype['unitstype']}}</option>  
                @endforeach
            </select>
        </div>
    </div>
    @endif 

    @if( !empty($spaces) )
    <div class="form-group col-sm-12">
        <label for="guests">{{ __('Parking Spaces') }}</label>
        @foreach($spaces as $idx => $space)
        <div class="box_property_space">
            <input type="radio" id="chb_property_space_{{$idx}}" name="spaces" value="{{$space['name']}}|^|{{$space['price']}}" data-price="{{ floatval($space['price']) }}" checked="{{ $idx == 1 ? 'checked' : '' }}">
            <label class="form-check-label {{ app()->getLocale() == 'ar' ? ' ar-type' : ''}} " for="chb_property_space_{{$idx}}">
                {{$space['name']}} 
            </label>
            <span class="price">SAR{{number_format(floatval($space['price']),2,'.',',')}}</span>
        </div>
        @endforeach
    </div>
    @endif

    @if( !empty($addons) )
    <div class="form-group col-sm-12">
        <label for="guests">{{ __('Addons') }}</label>
        @foreach($addons as $idx => $addon)
        <div class="box_property_addon">
            <label for="chb_property_addon_{{$idx}}{{ app()->getLocale() == 'ar' ? ' ar-type' : ''}} ">
                <input type="checkbox" id="chb_property_addon_{{$idx}}" name="addons[]" value="{{$addon['name']}}|^|{{$addon['price']}}" data-price="{{ floatval($addon['price']) }}">
                {{$addon['name']}} 
            </label>
            <span class="price">SAR{{number_format(floatval($addon['price']),2,'.',',')}}</span>
        </div>
        @endforeach
    </div>
    @endif 

    <div class="property_calculated_content col-sm-12"  data-fees="{{$data->type->commission}}" data-price="{{floatval($data->price)}}">
        <div class="row mt-2">
            <div class="col-7"><span class="one_total">{{number_format($data->price,2,'.',',')}}</span> x <span class="nights">1</span>
                <span class="booking-type">
                     @if($data->type->slug != 'Accessory') 
                     <span class="Day_c ">{{__("Day")}} </span>
                     @else
                     @endif
                     <span class="Monthly_c hide">{{__("Monthly")}} </span>
                     <span class="Daily_c hide">{{__("Daily")}} </span>
                </span>  
            </div>
            <div class="col-5 text-right">SAR<span class="price">{{ number_format($data->price,2,'.',',')}}</span></div>
        </div>
        <!-- --------------- -->
        <div class="row mt-2">
            <div class="col-7">{{__("Fees")}} </div>
            <div class="col-5 text-right"> <span class="fees">{{ number_format($property_type_commission_fees,2,'.',',') }}</span></div>
        </div>

        <div class="row mt-2">
            <div class="col-7">{{__("Taxes")}} </div>
            <div class="col-5 text-right"> <span class="taxes">{{ number_format($property_type_taxes,2,'.',',') }}</span></div>
        </div>
        <!-- --------------- -->


        <div class="row mt-2">
            <div class="col-7"><b>{{__("Total")}}</b></div>
            <div class="col-5 text-right">SAR<span class="total">{{ number_format(($data->price + $property_type_commission_fees + $property_type_taxes),2,'.',',')}}</span></div>
        </div>
    </div>
</div> 
<div class="mt-4">



@if(true)
    
    @if (setting('enable_captcha') && is_plugin_active('captcha'))
        <div class="form-group">
            {!! Captcha::display() !!}
        </div>
    @endif


    @if (auth('account')->check())

        <div class="form-group">
            <button class="btn btn-black btn-md rounded full-width" type="submit">
            @if($data->type->is_Accessory != 1)
                {{ __('Send Message') }}
            @else
                {{ __('Buy Now') }}
            @endif
            </button>
        </div>
    @else
        <div class="form-group">
            <a href="{{ url('login?redirect_to=' . urlencode(request()->url())) }}">
                <button class="btn btn-black btn-md rounded full-width" type="button"> 
                    @if($data->type->is_Accessory != 1)
                        {{ __('Send Message') }}
                    @else
                        {{ __('Buy Now') }}
                    @endif
                </button>
            </a>
        </div>
    @endif 

@endif

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
 