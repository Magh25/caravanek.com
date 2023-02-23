@if (!empty($property->images))
    <div class="property_block_wrap style-2">

        <h4 class="property_block_title px-4 pt-3 pb-2"> {{ __("Gallery") }}</h4>
            
        
        <div id="clSev" class="panel-collapse collapse show">
            <div class="block-body">
                <ul class="list-gallery-inline">
                    @foreach ($property->images as $index => $image)
                        <li>
                            <a href="{{ RvMedia::getImageUrl($image, null, false, RvMedia::getDefaultImage()) }}"
                                class="mfp-gallery">
                                <img 
                                    src="{{ get_image_loading() }}"
                                    data-src="{{ RvMedia::getImageUrl($image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                    class="img-fluid mx-auto lazy" alt="{{ $property->name }}-{{ $index }}" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
@endif
