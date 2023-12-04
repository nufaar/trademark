@props(['error'])

@error($error)
    <div class="invalid-feedback">
        {{ $errors->first($error) }}
    </div>
@enderror
