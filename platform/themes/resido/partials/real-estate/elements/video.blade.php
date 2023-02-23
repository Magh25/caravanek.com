@if (!empty($property->video_url))
    <div class="property_block_wrap style-2">
        <h4 class="property_block_title px-4 pt-3 pb-2">{{ __('Property video') }}</h4>

        <div id="clFour" class="panel-collapse collapse">
            <div class="block-body">
                <div class="property_video">
                    <div class="thumb">
                        <img class="pro_img w-100" src="{{ get_image_from_video_property($object) }}"
                            alt="{{ $object->name }}">
                        <div class="overlay_icon">
                            <div class="bb-video-box">
                                <div class="bb-video-box-inner">
                                    <div class="bb-video-box-innerup">
                                        <a href="{{ $object->video_url }}" id="popup-youtube"
                                            class="theme-cl popup-youtube"><i class="ti-control-play"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
