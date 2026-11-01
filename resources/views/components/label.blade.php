@props(['for'])
<label for="{{ $for }}" {{ $attributes->merge(['class' => 'field-label']) }}>{{ $slot }}</label>
