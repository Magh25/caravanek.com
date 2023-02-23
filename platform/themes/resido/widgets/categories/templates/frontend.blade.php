@php 
     

     $addLink_Param = '';
     $supportedLocales = Language::getSupportedLocales();
     foreach ($supportedLocales as $localeCode => $properties){ 
         if ($localeCode == Language::getCurrentLocale()){
            
             $addLink_Param = '?language='.$properties['lang_code'];
             
         }
     }
      
 @endphp

<!-- Categories -->
<div class="single-widgets widget_category">
    <h4 class="title">{{ $config['name'] }}</h4>
    <ul>
        @foreach(get_categories(['select' => ['categories.id', 'categories.name']]) as $category)
            <li><a href="{{ $category->url }}{{$addLink_Param}}" class="text-dark">{{ $category->name }}</a></li>
        @endforeach
    </ul>
</div>

