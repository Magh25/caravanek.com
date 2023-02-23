@php 
    $user = auth('account')->user();  
@endphp
@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')
@section('content')
    <a href="{{url('account/my-bookings')}}" class="btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ trans('plugins/real-estate::consult.GoBack') }}</a>
    <br><br>
    <div class="dashboard-wraper">
        @if ($consult)
            <div class="row">
                <div class="col-md-4">
                    <p><strong>{{ trans('plugins/real-estate::consult.time') }}</strong>: <i>{{ $consult->created_at }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.consult_id') }}</strong>: <i>CR000{{ $consult->id }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.form_name') }}</strong>: <i>{{ $consult->name }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.Guests') }}</strong>: <i>{{ $consult->guests }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.from_date') }}</strong>: <i>{{ $consult->from_date }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.total_price') }}</strong>: <i>{{ $consult->total_price }}</i></p> 
                    @if(!empty($consult->unitstype))
                        <p><b>{{__("Booking Type")}}:</b> 
                        {{ $consult->bookingtype }}</p>
                    @endif
                    @if(!empty($consult->unitstype))
                        <p><b>{{__("Parking Spaces")}}:</b> 
                        {{ implode(" , ", array_map(function($a){ return $a->name; },(array)json_decode($consult->spaces))) }}</p>
                    @endif
                </div> 
                <div class="col-md-4">
                  <!--   <p><strong>{{ trans('plugins/real-estate::consult.email.header') }}</strong>: <i><a href="mailto:{{ $consult->email }}">{{ $consult->email }}</a></i></p> -->
                    <!-- <p><strong>{{ trans('plugins/real-estate::consult.phone') }}</strong>: <i>@if ($consult->phone) <a href="tel:{{ $consult->phone }}">{{ $consult->phone }}</a> @else N/A @endif</i></p> -->
                    <p><strong>{{ trans('plugins/real-estate::consult.to_date') }}</strong>: <i>{{ $consult->to_date }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.commission') }}</strong>: <i>{{ $consult->commission }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.no_nights') }}</strong>: <i>{{ $consult->no_nights }}</i></p>
                    <p><strong>{{ trans('plugins/real-estate::consult.status') }}</strong>: <i>{{ $consult->status }}</i></p>
                    @if( $consult->status == 'canceled')
                        <p><strong>{{ trans('plugins/real-estate::consult.cancel_reason') }}</strong>: <i>{{ $consult->cancel_reason }}</i></p>
                    @endif
                      <p><strong>{{ trans('plugins/real-estate::consult.vendor_id') }}</strong>: <i>{{ $consult->vendor_id }}</i></p>
                    @if(!empty($consult->unitstype))
                        <p><b>{{__("Units Type")}}:</b> 
                        {{ $consult->unitstype }}</p>
                    @endif
                </div>
                <div class="col-md-2"  style=" max-width: 100%; padding: 20px; border: 1px solid; "> 
                    <form name="update-status" method="post" action="{{url('account/my-bookings/edit/'.$consult->id)}}">
                    @csrf 
                        <div class="form-group">
                            <div class="widget-title">
                                <h4>
                                    <label for="status" class="control-label required" aria-required="true">{{ _('Status') }}</label>
                                </h4>
                            </div>
                            <select name="status" class="form-control" id='myselect'>
                               <option {{ $consult->status  =='approved' ? 'selected' : ''  }}   value="approved">Approved</option>
                               <option  {{ $consult->status  =='unread' ? 'selected' : ''  }} value="unread">Pending</option>
                               <option  {{ $consult->status  =='canceled' ? 'selected' : ''  }}  value="canceled">Canceled</option>
                               <option {{ $consult->status  =='completed' ? 'selected' : ''  }}   value="completed">Crompleted</option>
                            </select> 
                            <br/>
                            <button class="btn btn-theme-light-2" type="submit">{{ __('Update') }}</button>
                        </div>
                    </form> 
                </div>
            </div>
        @endif
    </div>
@endsection  