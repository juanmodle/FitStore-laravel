@props(['name', 'type' => 'text', 'value' => null, 'id' => null])
@php($fieldId = $id ?? $name)
<input id="{{ $fieldId }}" name="{{ $name }}" type="{{ $type }}"
    @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
    aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
    @if ($errors->has($name)) aria-describedby="{{ $fieldId }}-error" @endif
    {{ $attributes->merge(['class' => 'field-input']) }}>
@error($name)
    <p class="field-error" id="{{ $fieldId }}-error">{{ $message }}</p>
@enderror
