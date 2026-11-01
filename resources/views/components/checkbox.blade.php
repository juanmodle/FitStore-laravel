@props(['name', 'checked' => false])
<label class="check-label" for="{{ $name }}">
    <input id="{{ $name }}" name="{{ $name }}" type="checkbox" value="1"
        @checked(old($name, $checked)) {{ $attributes }}>
    <span>{{ $slot }}</span>
</label>
@error($name)
    <p class="field-error">{{ $message }}</p>
@enderror
