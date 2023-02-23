@foreach ($ptypes as $type)
    <label class="checkbox-inline">
        <input name="types[]" type="checkbox" value="{{ $type->id }}" @if (in_array($type->id, $selectedTypes)) checked @endif>{{ $type->name }}
    </label>&nbsp;
@endforeach
