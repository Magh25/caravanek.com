@php 
    $user = auth('account')->user();  
@endphp
@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')
@section('content')
    <a href="{{url('account/booked-by-me')}}" class="btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ trans('plugins/real-estate::consult.GoBack') }}</a>
    <br><br>
    <div class="dashboard-wraper">
        @if ($consult)
            <div class="row">
                @if( $consult->status !== 'canceled')
                <div class="col-md-4">
                @else
                <div class="col-md-6">
                @endif
                    <p><strong>{{ trans('plugins/real-estate::consult.time') }}</strong>: <i>{{ $consult->created_at }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.consult_id') }}</strong>: <i>CR000{{ $consult->id }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.form_name') }}</strong>: <i>{{ $consult->name }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.Guests') }}</strong>: <i>{{ $consult->guests }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.from_date') }}</strong>: <i>{{ $consult->from_date }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.total_price') }}</strong>: <i>{{ $consult->total_price }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.vendor_id') }}</strong>: <i>{{ $consult->vendor_id }}</i></p>
                     @if(!empty($consult->unitstype))
                        <p><b>{{__("Booking Type")}}:</b> 
                        {{ $consult->bookingtype }}</p>
                    @endif
                    @if(!empty($consult->unitstype))
                        <p><b>{{__("Parking Spaces")}}:</b> 
                        {{ implode(" , ", array_map(function($a){ return $a->name; },(array)json_decode($consult->spaces))) }}</p>
                    @endif
                </div> 
                @if( $consult->status !== 'canceled')
                <div class="col-md-4">
                @else
                <div class="col-md-6">
                @endif
                    <p><strong>{{ trans('plugins/real-estate::consult.email.header') }}</strong>: <i><a href="mailto:{{ $consult->email }}">{{ $consult->email }}</a></i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.phone') }}</strong>: <i>@if ($consult->phone) <a href="tel:{{ $consult->phone }}">{{ $consult->phone }}</a> @else N/A @endif</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.to_date') }}</strong>: <i>{{ $consult->to_date }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.commission') }}</strong>: <i>{{ $consult->commission }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.no_nights') }}</strong>: <i>{{ $consult->no_nights }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.status') }}</strong>: <i>{{ $consult->status }}</i></p>
                    @if( $consult->status == 'canceled')
                        <p><strong>{{ trans('plugins/real-estate::consult.cancel_reason') }}</strong>: <i>{{ $consult->cancel_reason }}</i></p>
                    @endif
                     @if(!empty($consult->unitstype))
                        <p><b>{{__("Units Type")}}:</b> 
                        {{ $consult->unitstype }}</p>
                    @endif
                </div>
                @if( $consult->status !== 'canceled')
                <div class="col-md-2"> 
                    

                    <button data-toggle="modal" id="smallButton" data-target="#smallModal" class="btn btn-theme-light-2" type="submit">{{ __('Cancel Booking') }}</button>

                    <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content"> 
                                <div class="modal-body" style="padding: 0.5em 3em !important;" id="smallBody">
                                   <form id="formId" name="update-status" method="post" action="{{url('account/booked-by-me/edit/'.$consult->id )}}">
                                    @csrf
                                        <div class="form-group"> 
                                            <input type="hidden" name="status" value="canceled">  
                                            <br/> 
                                            <label> {{ __('Please share your the reason of the cancellation booking') }}</label>
                                            <textarea placeholder='{{ __("I have a cancelled my plan. which I won`t be able to visit") }}' rows="10" cols="50" required name="cancel_reason"></textarea>
                                            <button  class="btn btn-theme-light-2" type="submit">{{ __('Cancel Booking') }}</button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </div>
                    </div>



                    
                </div>
                @endif
            </div>
        @endif
    </div>
@endsection 
<script type="text/javascript">
    $('#formId').submit(function (event){
        event.preventDefault();
        console.log(event);
    });
</script>