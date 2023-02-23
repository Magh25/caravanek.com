@php 
    $user = auth('account')->user();  
@endphp
@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')
@section('content')
    <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ trans('plugins/real-estate::consult.GoBack') }}</a>
    <br><br>
    <div class="dashboard-wraper">
        @if ($consult)
            <div class="row">
                <div class="col-md-6">
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.time') }}</strong>: <i>{{ $consult->created_at }}</i></p>
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.consult_id') }}</strong>: <i>CR000{{ $consult->id }}</i></p>
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.form_name') }}</strong>: <i>{{ $consult->name }}</i></p>
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.Guests') }}</strong>: <i>{{ $consult->guests }}</i></p>                   
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.from_date') }}</strong>: <i>{{ $consult->from_date }}</i></p>
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.total_price') }}</strong>: <i>{{ $consult->total_price }}</i></p> 
                    @if(!empty($consult->unitstype))
                        <p class="booking-ctm-class"><b>{{__("Booking Type")}}:</b> 
                        {{ $consult->bookingtype }}</p>
                    @endif
                    @if(!empty($consult->unitstype))
                        <p class="booking-ctm-class"><b>{{__("Parking Spaces")}}:</b> 
                        {{ implode(" , ", array_map(function($a){ return $a->name; },(array)json_decode($consult->spaces))) }}</p>
                    @endif
                </div> 
                <div class="col-md-6">
                    <!-- <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.email.header') }}</strong>: <i><a href="mailto:{{ $consult->email }}">{{ $consult->email }}</a></i></p>
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.phone') }}</strong>: <i>@if ($consult->phone) <a href="tel:{{ $consult->phone }}">{{ $consult->phone }}</a> @else N/A @endif</i></p> -->
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.commission') }}</strong>: <i>{{ $consult->commission }}</i></p>
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.no_nights') }}</strong>: <i>{{ $consult->no_nights }}</i></p>
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.status') }}</strong>: <i>{{ $consult->status }}</i></p>
                    @if( $consult->status == 'canceled')
                        <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.cancel_reason') }}</strong>: <i>{{ $consult->cancel_reason }}</i></p>
                    @endif
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.vendor_id') }}</strong>: <i>{{ $consult->vendor_id }}</i></p>
                    <p class="booking-ctm-class"><strong>{{ trans('plugins/real-estate::consult.to_date') }}</strong>: <i>{{ $consult->to_date }}</i></p>
                     @if(!empty($consult->unitstype))
                        <p class="booking-ctm-class"><b>{{__("Units Type")}}:</b> 
                        {{ $consult->unitstype }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection 

<style type="text/css">
    .booking-ctm-class i,.booking-ctm-class strong {
        color: #2f6cb5;
    }
</style>