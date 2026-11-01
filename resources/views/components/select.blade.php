@props(['name', 'options', 'value' => null])
<select id="{{ $name }}" name="{{ $name }}"
    aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
    @if ($errors->has($name)) aria-describedby="{{ $name }}-error" @endif
    {{ $attributes->merge(['class' => 'field-input']) }}>
    @foreach ($options as $optionValue => $label)
        <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $label }}</option>
    @endforeach
</select>
@error($name)
    <p class="field-error" id="{{ $name }}-error">{{ $message }}</p>
@enderror
