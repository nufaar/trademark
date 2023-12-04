@props(['name', 'label', 'placeholder', 'type' => 'text', 'value' => '', 'property'])

<div class="form-group my-2">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input wire:model="{{ $property }}" type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        class="form-control @error($property)
            is-invalid
        @enderror"
        placeholder="Enter your current password" value="{{ $value }}">
    <x-maz-input-error error="{{ $property }}" />
</div>
