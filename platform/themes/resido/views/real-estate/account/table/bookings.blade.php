@php 
    $user = auth('account')->user();  
@endphp
@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')

@section('content')
    {!! $consultTable->render(Theme::getThemeNamespace() . '::views.real-estate.account.table.base-booking'); !!}
@endsection 