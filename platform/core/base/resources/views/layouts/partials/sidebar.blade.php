@php 
$menus = dashboard_menu()->getAll();
if( @$_REQUEST['supper_admin_menu'] != 'Y'){
    $not_allowed = [
        'cms-plugins-blog' => false,
        'cms-core-page' => false,
        'cms-plugins-block' => true,
        // 'cms-plugins-location' => true,
        'cms-plugins-newsletter' => true,
        'cms-plugins-real-estate' => ['cms-plugins-real-estate-settings','cms-plugins-facility'],
        'cms-plugin-translation' => true,//['cms-plugin-translation-admin-translations'],
        // 'cms-plugins-consult' => true,
        'cms-plugins-package' => true,
        //'cms-plugins-contact' => true,
        'cms-plugins-payments' => true,
        // 'cms-core-media' => true,
        //'cms-core-appearance' => ['cms-core-theme'],
        'cms-core-appearance' => true,
        'cms-core-plugins' => true,
        'cms-core-settings' => [ 'cms-core-settings-general','cms-packages-slug-permalink','cms-core-settings-media'],
        // 'cms-core-platform-administration' => ['cms-core-role-permission','cms-core-system-information'],
    ];
    
    $nmenu = []; 
    foreach($menus as $idx => $mn){
      // var_dump($idx);
        if( isset($not_allowed[$idx]) ){
            $_data = $not_allowed[$idx];
            if( $_data === true)   
                unset($menus[$idx]);       
            else {
                if( is_array($_data) && is_array($mn['children']) ){
                    $smenu = [];
                    foreach($mn['children'] as $cidx => $smn){
                        if( !in_array(@$smn['id'],$_data) )
                            $smenu[$cidx] = $smn;
                           
                    }
                    if( !empty($smenu) ){
                        $_mn = $mn;
                        $_mn['children'] = $smenu;
                        $nmenu[$idx] = $_mn;
                    }
                } else
                    $nmenu[$idx] = $mn;
            } 
        } else {
            $nmenu[$idx] = $mn;
        }
    }
} else
    $nmenu = $menus;
@endphp
@foreach ($nmenu as $menu)
    @php $menu = apply_filters(BASE_FILTER_DASHBOARD_MENU, $menu); @endphp
    <li class="nav-item @if ($menu['active']) active @endif" id="{{ $menu['id'] }}">
        <a href="{{ $menu['url'] }}" class="nav-link nav-toggle">
            <i class="{{ $menu['icon'] }}"></i>
            <span class="title">
                {{ !is_array(trans($menu['name'])) ? trans($menu['name']) : null }}
                {!! apply_filters(BASE_FILTER_APPEND_MENU_NAME, null, $menu['id']) !!}</span>
            @if (isset($menu['children']) && count($menu['children'])) <span class="arrow @if ($menu['active']) open @endif"></span> @endif
        </a>
        @if (isset($menu['children']) && count($menu['children']))
            <ul class="sub-menu @if (!$menu['active']) hidden-ul @endif">
                @foreach ($menu['children'] as $item)
                    <li class="nav-item @if ($item['active']) active @endif" id="{{ $item['id'] }}">
                        <a href="{{ $item['url'] }}" class="nav-link">
                            <i class="{{ $item['icon'] }}"></i>
                            {{ trans($item['name']) }}
                            {!! apply_filters(BASE_FILTER_APPEND_MENU_NAME, null, $item['id']) !!}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach
