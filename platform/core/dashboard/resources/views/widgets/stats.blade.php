@if (empty($widgetSetting) || $widgetSetting->status == 1)
    @if( !($widget->name == 'widget_total_plugins' || $widget->name == 'widget_total_pages' ||$widget->name == 'widget_total_themes' ))
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <a class="dashboard-stat dashboard-stat-v2" style="background-color: {{ $widget->color }}; color: #fff" href="{{ $widget->route }}">
            <div class="visual">
                <i class="{{ $widget->icon }}" style="opacity: .1;"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $widget->statsTotal }}">0</span>
                </div>
                <div class="desc"> {{ $widget->title }} </div>
            </div>
        </a>
    </div>
    @endif
@endif
