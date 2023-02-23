
<div class="table-wrapper">
    @php  
        $controller = Route::currentRouteName(); 

        $property_types = \DB::table('re_property_types')->select()->get()->toArray();

        $cities = \DB::table('cities')->select()->get()->toArray();


    @endphp 


    @if($controller == "consult.index" || $controller == "commission.index")
        <form>
                <div class="table-configuration-wrap"  style="display: block;">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" id="location" name="name" placeholder="Name, Vendor Name, Property ..." value="{{app('request')->input('name') }}" class="form-control input-sm" style="height:auto !important;" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <input type="date" onchange="changeDate(event);" id="to" name="to" placeholder="" value="{{app('request')->input('to') }}" class="form-control input-sm" style="height:auto !important;" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <input type="date" onchange="changeDate(event);" value="{{app('request')->input('from') }}" id="from" name="from" placeholder="Name, Country...." class="form-control input-sm" style="height:auto !important;" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <div class="box_filed">
                                <select name="property_type" class="form-control" id="num_adults">
                                    <option value="null">{{__('Ad Type')}}</option>
                                    <option value="Parking Spot" {{app('request')->input('property_type') == "Parking Spot" ? "selected" : ""}} value="">{{__('Parking Spot')}}</option>
                                    <option value="Vehicle" {{app('request')->input('property_type') == "Vehicle" ? "selected" : ""}}  value="">{{__('Vehicle')}}</option>
                                    <option value="Accessory" {{app('request')->input('property_type') == "Accessory" ? "selected" : ""}} value="">{{__('Accessory')}}</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet light portlet-no-padding">
                    <div class="portlet-title">
                        <div class="caption">
                            <div class="wrapper-action">
                                @if ($table->isHasFilter())
                                    <button type="submit" class="btn btn-primary">{{ trans('core/table::general.filters') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div
                            class="table-responsive @if ($actions) table-has-actions @endif @if ($table->isHasFilter()) table-has-filter @endif"
                            style="overflow-x: inherit">
                            @section('main-table')
                                {!! $dataTable->table(compact('id', 'class'), false) !!}
                            @show
                        </div>
                    </div>
                </div>
            </form>
    @else
        @if ($table->isHasFilter())
            <div class="table-configuration-wrap" @if (request()->has('filter_table_id')) style="display: block;" @endif>
                <span class="configuration-close-btn btn-show-table-options"><i class="fa fa-times"></i></span>
                {!! $table->renderFilter() !!}
            </div>
        @endif
        @if($controller !== "property.index")
        <div class="portlet light bordered portlet-no-padding">
            <div class="portlet-title">
                <div class="caption">
                    <div class="wrapper-action">
                        @if ($actions)
                            <div class="btn-group">
                                <a class="btn btn-secondary dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ trans('core/table::table.bulk_actions') }}
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach ($actions as $action)
                                        <li>
                                            {!! $action !!}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($table->isHasFilter())
                            <button class="btn btn-primary btn-show-table-options">{{ trans('core/table::table.filters') }}</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive @if ($actions) table-has-actions @endif @if ($table->isHasFilter()) table-has-filter @endif">
                    @section('main-table')
                        {!! $dataTable->table(compact('id', 'class'), false) !!}
                    @show
                </div>
            </div>
        @endif
    @endif
    @if($controller == "property.index")
        <form>
            <div class="table-configuration-wrap"  style="display: block;">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" id="location" name="name" placeholder="Name ..." value="{{app('request')->input('name') }}" class="form-control input-sm" style="height:auto !important;" autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <select  name="type_id" class="form-control" autocomplete="off">
                            <option value="">Select Listing type</option>
                            @foreach($property_types as $value )
                                <option {{ app('request')->input('type_id') == $value->id ? "selected" : "" }} value="{{$value->id}}">
                                    {{ $value->name }}
                                </option> 
                            @endforeach 
                        </select> 
                    </div>
                    <div class="col-md-3">
                        <select  name="city_id" class="form-control" autocomplete="off">
                            <option value="">Select City</option>
                            @foreach($cities as $city )
                                <option {{ app('request')->input('city_id') == $city->id ? "selected" : "" }} value="{{$city->id}}">
                                    {{ $city->name }}
                                </option> 
                            @endforeach 
                        </select> 
                    </div>
                </div>
            </div>
            <div class="portlet light portlet-no-padding">
                    <div class="portlet-title">
                        <div class="caption">
                            <div class="wrapper-action">
                                @if ($table->isHasFilter())
                                    <button type="submit" class="btn btn-primary">{{ trans('core/table::general.filters') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div
                            class="table-responsive @if ($actions) table-has-actions @endif @if ($table->isHasFilter()) table-has-filter @endif"
                            style="overflow-x: inherit">
                            @section('main-table')
                                {!! $dataTable->table(compact('id', 'class'), false) !!}
                            @show
                        </div>
                    </div>
                </div>
        </form>
    @endif
    </div>
</div>
@include('core/table::modal')

@push('footer')
    {!! $dataTable->scripts() !!}
@endpush
