<div id="select-post-language">
    <table class="select-language-table">
        <tbody>
            <tr>
                <td class="active-language">
                    {!! language_flag($currentLanguage->lang_flag, $currentLanguage->lang_name) !!}
                </td>
                <td class="translation-column">
                    <div class="ui-select-wrapper">
                        <select name="language" id="post_lang_choice" class="ui-select">
                            @foreach($languages as $language)
                                @if ( empty($related) || !array_key_exists(json_encode([$language->lang_code]), $related))
                                    <option value="{{ $language->lang_code }}" @if ($language->lang_code == $currentLanguage->lang_code) selected="selected" @endif data-flag="{{ $language->lang_flag }}">{{ $language->lang_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <svg class="svg-next-icon svg-next-icon-size-16">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                        </svg>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    jQuery(function($){
        $(document).on("change","#post_lang_choice",function(){
            window.location.href = $(this).closest('form').attr('action')+'?language='+$(this).val();
        });
    });
</script>
@if (count($languages) > 1 && 0)
    <div><strong>{{ trans('plugins/language::language.translations') }}</strong>
        <div id="list-others-language">
            @foreach($languages as $language)
                @if ($language->lang_code != $currentLanguage->lang_code)
                    {!! language_flag($language->lang_flag, $language->lang_name) !!}
                    @if (array_key_exists($language->lang_code, $related))
                        <a href="{{ Route::has($route['edit']) ? route($route['edit'], $related[$language->lang_code]) : '#' }}"> {{ $language->lang_name }} <i class="fa fa-edit"></i></a>
                        <br>
                    @else
                        @php
                            $queryString ='ref_from=' . (!empty($args[0]) && $args[0]->id ? $args[0]->id : 0) . '&ref_lang=' .session('editing_language');
                            $currentQueryString = remove_query_string_var(Request::getQueryString(), ['ref_from', 'ref_lang']);
                            if (!empty($currentQueryString)) {
                                $queryString = $currentQueryString . '&' . $queryString;
                            }
                        @endphp
                        <a href="{{ Route::has($route['create']) ? route($route['create']) : '#' }}?{{ $queryString }}"> {{ $language->lang_name }} <i class="fa fa-plus"></i></a>
                        <br>
                    @endif
                @endif
            @endforeach
        </div>
    </div>

    <input type="hidden" id="lang_meta_created_from" name="ref_from" value="{{ Request::get('ref_from') }}">
    <input type="hidden" id="reference_id" value="{{ $args[0] && $args[0]->id ? $args[0]->id : 0 }}">
    <input type="hidden" id="reference_type" value="{{ $args[1] }}">
    <input type="hidden" id="route_create" value="{{ Route::has($route['create']) ? route($route['create']) : '#' }}">
    <input type="hidden" id="route_edit" value="{{ Route::has($route['edit']) ? route($route['edit'], $args[0] && $args[0]->id ? $args[0]->id : '') : '#' }}">
    <input type="hidden" id="language_flag_path" value="{{ BASE_LANGUAGE_FLAG_PATH }}">

    <div data-change-language-route="{{ route('languages.change.item.language') }}"></div>

    {!! Form::modalAction('confirm-change-language-modal', trans('plugins/language::language.confirm-change-language'), 'warning', trans('plugins/language::language.confirm-change-language-message'), 'confirm-change-language-button', trans('plugins/language::language.confirm-change-language-btn')) !!}
@endif

@push('header')
    <meta name="ref_from" content="{{ (!empty($args[0]) && $args[0]->id ? $args[0]->id : 0) }}">
    <meta name="ref_lang" content="{{ $currentLanguage->lang_code }}">
@endpush