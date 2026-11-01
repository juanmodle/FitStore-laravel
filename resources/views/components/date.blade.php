@props(['name', 'value' => null])
<x-input :name="$name" type="date" :value="$value" {{ $attributes }} />
