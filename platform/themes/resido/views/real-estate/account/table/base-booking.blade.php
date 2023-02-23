<div class="dashboard-wraper">
    <div class="page-content">
        <div class="table-wrapper">
           <!--  @if ($table->isHasFilter())
                <div class="table-configuration-wrap"
                     @if (request()->has('filter_table_id')) style="display: block;" @endif>
                    <span class="configuration-close-btn btn-show-table-options"><i class="fa fa-times"></i></span>
                    {!! $table->renderFilter() !!}
                </div>
            @endif -->
            <form>
                <div class="table-configuration-wrap"  style="display: block;">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" id="location" name="name" placeholder="Name, Property ..." value="{{app('request')->input('name') }}" class="form-control input-sm" style="height:auto !important;" autocomplete="off">
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
                                    <option value="null">{{__('Property Type')}}</option>
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
        </div>
    </div>
</div>
@include('core/table::modal')
@include('core/table::partials.modal-item', [
    'type' => 'info',
    'name' => 'modal-confirm-renew',
    'title' => __('Renew confirmation'),
    'content' => __('Are you sure you want to renew this property, it will takes 1 credit from your credits'),
    'action_name' => __('Yes'),
    'action_button_attributes' => [
        'class' => 'button-confirm-renew',
    ],
])

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

@php
    Theme::asset()->usePath(false)->add('table-css', asset('vendor/core/core/table/css/table.css'));
    Theme::asset()->usePath(false)->add('datatables-css', asset('/vendor/core/core/base/libraries/datatables/media/css/dataTables.bootstrap.min.css'));
    Theme::asset()->usePath(false)->add('datatables-buttons-css', asset('/vendor/core/core/base/libraries/datatables/extensions/Buttons/css/buttons.bootstrap.min.css'));
    Theme::asset()->usePath(false)->add('datatables-extensions-css', asset('/vendor/core/core/base/libraries/datatables/extensions/Responsive/css/responsive.bootstrap.min.css'));
    Theme::asset()->usePath()->add('base-core-css', 'css/account.css');
@endphp

<style type="text/css">
    .table-wrapper .table-configuration-wrap{
        margin-bottom: 0px !important;
    }
</style>
<script type="text/javascript">
    function changeDate(event) {
        if(event.target.value != ''){
            if(event.target.name == 'to'){
                jQuery('#from').attr('required','required');
            }else{
                jQuery('#to').attr('required','required');
            }
        }
    }
</script>