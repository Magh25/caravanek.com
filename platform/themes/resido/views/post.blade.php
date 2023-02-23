
@php 
     

    $addLink_Param = '';
    $supportedLocales = Language::getSupportedLocales();
    foreach ($supportedLocales as $localeCode => $properties){ 
        if ($localeCode == Language::getCurrentLocale()){
           
            $addLink_Param = '?language='.$properties['lang_code'];
            
        }
    }
     
@endphp

<style>
       

        /*Apple btn style start here*/
        iframe {
            width: 300px !important;
            height: 200px !important;
        }
         
        
        /*Apple btn style end here*/
    </style>
<!-- ============================ Page Title Start================================== -->
<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h1 class="ipt-title">{{ $post->name }}</h1>
                <span class="ipn-subtitle"></span>
            </div>
        </div>
    </div>
</div>
<!-- ============================ Page Title End ================================== -->

<!-- ============================ Agency List Start ================================== -->
<section class="blog-page gray-simple">

    <div class="container">

        <div class="row">
            <div class="col text-center">
                <div class="sec-heading center">
                    {!! Theme::partial('breadcrumb') !!}
                </div>
            </div>
        </div>

        <!-- row Start -->
        <div class="row">
            <!-- Blog Detail -->
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="blog-details single-post-item format-standard">
                    <div class="post-details">
                        <div class="post-featured-img">
                            <img class="img-fluid"
                                src="{{ RvMedia::getImageUrl($post->image, 'large', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $post->name }}">
                        </div>

                        <div class="post-top-meta">
                            {!! Theme::partial('post-meta', compact('post')) !!}
                        </div>
                        <h2 class="post-title">{{ $post->name }}</h2>

                        <div>
                            {!! clean($post->content) !!}
                        </div>
                        <br>
                        <ul class="breadcrumb">

                                <li class=" active"> {{ __("The written content represents the author's point of view and Caravanak bears no responsibility direction")}}</li>
                                 

                                
                            
                        </ul>

                        <div class="auther_details mt-4">
                            <img src="{{ !empty($post->author->author->url) ? RvMedia::getImageUrl($post->author->author->url, 'thumb') : '/storage/user.jpg' }}"
                                    alt="{{ $post->author->name }}">
                            <h4>
                                {{ __("About the author") }}: {{ ucwords($post->author->name) }}
                            </h4>
                            <span>{{ __("Last update") }}:  {{($post->updated_at)}}</span>
                                    
                        </div>
                        <div class="post-bottom-meta">
                            <div class="post-tags">
                                <h4 class="pbm-title">{{ __('Tags') }}</h4>
                                @if ($post->tags->count())
                                    <ul class="list">
                                        @foreach ($post->tags as $tag)
                                            <li>
                                                <a href="{{ $tag->url }}">{{ $tag->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="post-share">
                                {!! Theme::partial('share', ['title' => __('Share this post'), 'description' => $post->description]) !!}
                                
        
                            </div>
                            <!-- --------/.like------------ -->
                            @if (auth('account')->check())
                            <div class="post-share like "  >   
                                 
                                    {!! Form::open(['route' => 'public.post.like', 'method' => 'post', 'class' => '']) !!}
                                     
                                    <input type="hidden" name="able_id" value="{{ $post->id }}">
                                    <input type="hidden" name="able_type" value="{{ get_class($post) }}">
                                    <button type="submit" >
                                        <a  title="{{ __('like') }}" rel="nofollow"><i class="fa fa-thumbs-up fa-3x" aria-hidden="false"></i></a>
                                    </button>
                                    <span class="ml-2" >{{$post->Likes()->count()}}</span> 
                                    {!! Form::close() !!} 
                            </div>
                            @endif
                            <!-- --------./like------------ -->

                        </div>

                    </div>
                </div>

                @php $relatedPosts = get_related_posts($post->id, 2); @endphp
                
                 <!-- ----------- start comment ------------- -->
                <div class="property_block_wrap style-2">
                    @if(isset($post->Comments->comment))
                    <h4 class="property_block_title px-4 pt-3">{{ __('All Comments') }}</h4>
                    @endif
                    @foreach ($post->Comments as $com) 
                        @if($com->status == 'published')
                        <div class="auther_details mt-2">
                            <img src="{{ !empty($com->account->avatar->url) ? RvMedia::getImageUrl($com->account->avatar->url, 'thumb') : '/storage/user.jpg' }}"
                                    alt="{{ $com->account->name }}" width="50" height="20">
                            <h4>
                              
                               
                                {{ $com->account->name }}

                            </h4>
                            <span class="mt-2"> {{$com->comment}} </span>
                            <span class="mt-2">{{ __("Last update") }}:  {{($com->updated_at)}}</span>
                                    
                        </div>
                        @endif
                    @endforeach
                        
                </div>
                <!-- ----------- end comment ------------- -->

                <!-- ----------- start comment ------------- -->
                <div class="property_block_wrap style-2">
               
                    <h4 class="property_block_title px-4 pt-3">{{ __('Write a Comment') }}</h4>
                    @if (!auth('account')->check())
                        <p class="text-danger px-4 pt-1">{{ __('Please') }} <a class="text-danger" href="{{ route('public.account.login') }}">{{ __('login') }}</a> {{ __('to write review!') }}</p>
                    @endif
                    
                    <div id="clTen" class=" ">
                        <div class="block-body">
                            {!! Form::open(['route' => 'public.comment.create', 'method' => 'post', 'class' => 'form--review-product']) !!}
                            <input type="hidden" name="able_id" value="{{ $post->id }}">
                            <input type="hidden" name="able_type" value="{{ get_class($post) }}">
                            <div class="row py-3">
                                
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <textarea name="comment" class="form-control ht-80" placeholder="{{ __('Write a Comment') }}" @if (!auth('account')->check()) disabled @endif></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <button class="btn btn-theme-light-2 rounded" type="submit" @if (!auth('account')->check()) disabled @endif>{{ __('Submit Comment') }}</button>
                                    </div>
                                </div>

                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <!-- ----------- end comment ------------- -->



                @if ($relatedPosts->count())
                    <div class="blog-details single-post-item format-standard">
                        <h4><strong>{{ __('Related posts') }}:</strong></h4>
                        <div class="blog-container">
                            <div class="row">
                                @foreach ($relatedPosts as $relatedItem)
                                    <div class="col-md-6 col-sm-6 container-grid">
                                        <div class="blog-wrap-grid">
                                            <div class="blog-thumb">
                                                <a href="{{ $relatedItem->url }}{{$addLink_Param}}" class="linkdetail">
                                                    <div class="blii">
                                                        <div class="img">
                                                            <img class="img-fluid thumb lazy"
                                                                data-src="{{ RvMedia::getImageUrl($relatedItem->image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                                                src="{{ get_image_loading() }}"
                                                                alt="{{ $relatedItem->name }}">
                                                        </div>

                                                    </div>
                                                </a>
                                            </div>
                                            <div class="blog-body">
                                                <div class="blog-title">
                                                    <a href="{{ $relatedItem->url }}{{$addLink_Param}}">
                                                        <h4 class="bl-title">{{ $relatedItem->name }}</h2>
                                                    </a>
                                                    <div class="post-meta">
                                                        <p class="d-inline-block">
                                                            {{ $relatedItem->created_at->format('d M, Y') }}</p>
                                                        - <p class="d-inline-block"><i class="fa fa-eye"></i>
                                                            {{ number_format($relatedItem->views) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="blog-excerpt">
                                                    <p>{{ Str::words($relatedItem->description, 40) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Single blog Grid -->
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                {!! dynamic_sidebar('primary_sidebar') !!}
            </div>
        </div>
    </div>
</section>
<script>
$(function() {
    $("div.post-details").find("iframe").css("width", "100%");
});
</script>

<!-- -->
