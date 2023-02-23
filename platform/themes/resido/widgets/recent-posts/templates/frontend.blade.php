
@php 
     

     $addLink_Param = '';
     $supportedLocales = Language::getSupportedLocales();
     foreach ($supportedLocales as $localeCode => $properties){ 
         if ($localeCode == Language::getCurrentLocale()){
            
             $addLink_Param = '?language='.$properties['lang_code'];
             
         }
     }
      
 @endphp

<div class="single-widgets widget_thumb_post">
    <h4 class="title">{{ __($config['name']) }}</h4>
    <ul>
        @foreach (get_recent_posts($config['number_display']) as $post)
            <li>
            <span class="left">
				 <img class="img-thumbnail float-left mr-2"
                      data-src="{{ RvMedia::getImageUrl($post->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                      src="{{ RvMedia::getImageUrl($post->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                      width="90" alt="{{ $post->name }}">
			</span>
                <span class="right">
				<a class="feed-title" href="{{ $post->url }}{{$addLink_Param}}">{{ $post->name }}</a>
				<span class="post-date"><i class="ti-calendar"></i>{{ $post->created_at->format('d M, Y') }}</span>
			</span>
            </li>
        @endforeach
    </ul>
</div>
