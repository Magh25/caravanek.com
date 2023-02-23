@if ($consult)
    <div class="row">
        <div class="col-md-6">
            <p><strong>{{ trans('plugins/real-estate::consult.time') }}</strong>: <i>{{ $consult->created_at }}</i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.consult_id') }}</strong>: <i>CR000{{ $consult->id }}</i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.form_name') }}</strong>: <i>{{ $consult->name }}</i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.Guests') }}</strong>: <i>{{ $consult->guests }}</i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.from_date') }}</strong>: <i>{{ $consult->from_date }}</i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.total_price') }}</strong>: <i>{{ $consult->total_price }}</i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.vendor_id') }}</strong>: <i>{{ $consult->vendor_id }}</i></p>
            @if( $consult->status == 'canceled')
                <p><strong>{{ trans('plugins/real-estate::consult.cancel_reason') }}</strong>: <i>{{ $consult->cancel_reason }}</i></p>
            @endif
            @if(!empty($consult->unitstype))
                <p><b>{{__("Booking Type")}}:</b> 
                {{ $consult->bookingtype }}
            @endif
            @if(!empty($consult->unitstype))
                <p><b>{{__("Parking Spaces")}}:</b> 
                {{ implode(" , ", array_map(function($a){ return $a->name; },(array)json_decode($consult->spaces))) }}
            @endif
        </div> 
        <div class="col-md-6">
            <p><strong>{{ trans('plugins/real-estate::consult.email.header') }}</strong>: <i><a href="mailto:{{ $consult->email }}">{{ $consult->email }}</a></i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.phone') }}</strong>: <i>@if ($consult->phone) <a href="tel:{{ $consult->phone }}">{{ $consult->phone }}</a> @else N/A @endif</i></p>
            @if ($consult->property_id) 
                <p><strong>{{ trans('plugins/real-estate::consult.property') }}</strong>: <a href="{{ $consult->property->url }}" target="_blank"><i>{{ $consult->property->name }}</i></a></p>
            @endif
            <p><strong>{{ trans('plugins/real-estate::consult.to_date') }}</strong>: <i>{{ $consult->to_date }}</i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.commission') }}</strong>: <i>{{ $consult->commission }}</i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.no_nights') }}</strong>: <i>{{ $consult->no_nights }}</i></p>
            <p><strong>{{ trans('plugins/real-estate::consult.status') }}</strong>: <i>{{ $consult->status }}</i></p> 
            <p><strong>{{ trans('plugins/real-estate::consult.content') }}</strong>: <i>{{ $consult->content ? $consult->content : '...' }}</i></p>
            @if(!empty($consult->unitstype))
                <p><b>{{__("Units Type")}}:</b> 
                {{ $consult->unitstype }}
            @endif 
        </div>
    </div>
@endif
