@foreach ($categories as $category)
    <label class="checkbox-inline">
        <input name="categories[]" type="checkbox" value="{{ $category->id }}" @if (in_array($category->id, $selectedCategories)) checked @endif>{{ $category->name }}
    </label>&nbsp;
@endforeach
