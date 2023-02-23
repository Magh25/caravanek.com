
@php 
     

     $addLink_Param = '';
     $supportedLocales = Language::getSupportedLocales();
     foreach ($supportedLocales as $localeCode => $properties){ 
         if ($localeCode == Language::getCurrentLocale()){
            
             $addLink_Param = '?language='.$properties['lang_code'];
             
         }
     }
      
 @endphp

@if ($posts->count() > 0)
                            {!! Form::open(['route' => 'public.search', 'method' => 'get', 'class' => '']) !!}
                                                        
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <input type="text" name="q" class="form-control ht-80" placeholder="{{ __('Search') }}"  />
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <button class="btn btn-theme-light-2 rounded" type="submit"  >{{ __('Search') }}</button>
                                        </div>
                                    </div>

                                </div>
                            {!! Form::close() !!}
    <div class="row">
           

        @foreach($posts as $post)
            <div class="col-lg-4 col-md-6">
                <div class="blog-wrap-grid">
                    <div class="blog-thumb">
                        <a href="{{ $post->url }}{{$addLink_Param}}">
                            <img
                                data-src="{{ RvMedia::getImageUrl($post->image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                src="{{ get_image_loading() }}"
                                alt="{{ $post->name }}" class="img-fluid thumb lazy">
                        </a>
                    </div>

                    <div class="blog-info">
                        {!! Theme::partial('post-meta', compact('post')) !!}
                    </div>

                    <div class="blog-body">
                        <h4 class="bl-title">
                            <a href="{{ $post->url }}{{$addLink_Param}}" title="{{ $post->name }}">
                                {{ $post->name }}
                            </a>
                        </h4>
                        <p>{{ Str::words($post->description, 50) }}</p>
                        <a href="{{ $post->url }}{{$addLink_Param}}" class="bl-continue">{{ __('Continue') }}</a>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
    <br>

    <div class="colm10 col-sm-12">
        <nav class="d-flex justify-content-center pt-3">
            {!! $posts->withQueryString()->links() !!}
        </nav>
    </div>
@endif
