@props(['label'=>'','instruction','coords','address'])
<div class="form-group">
    @if (!empty($label))
        <label for="{{ $attributes->get('id') ?? name2id($attributes->get('name')) }}">{{ $label }}</label>
    @endif
    <input class="form-control" placeholder="Адрес" name="{{ $name }}[address]" id="address" value="{{ $address ?? ''  }}" type="{{ $fieldType }}">
    <div class="input-group">
        <input
            {{ $attributes->class(['form-control','is-invalid'=>$errors->has($attributes->get('name'))]) }}
            id="{{  $attributes->get('id') ?? name2id($attributes->get('name')) }}"
            placeholder="{{ $attributes->get('placeholder') ?? $label}}"
            name="{{ $attributes->get('name') }}[coords]"
            value="{{ $coords ?? '' }}"
            type="text">
        <div role="button" class="input-group-prepend" data-action="pickCoords" data-coords="{{ $coords ?? '' }}" data-address="{{ $address ?? ''  }}">
            <div class="input-group-text">
                <i class="bi bi-pin-map"></i>
            </div>
        </div>
    </div>
    @error($attributes->get('name'))
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
    @if (!empty($instruction))
        <x-adminpanel::instruction :text="$instruction"></x-adminpanel::instruction>
    @endif
</div>
