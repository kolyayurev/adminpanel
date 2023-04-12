@props(['label'=>null,'instruction'])

<div @class(["form-check form-switch",'is-invalid'=>$errors->has($attributes->get('name'))])>
    <input type="hidden" name="{{ $attributes->get('name') }}" value="0">
    <input {{ $attributes->class(['form-check-input ']) }}
           type="checkbox"
           role="switch"
           id="{{ $attributes->get('id') ?? name2id($attributes->get('name')) }}"
    >
    @if (!empty($label))
    <label class="form-check-label"
           for="{{ $attributes->get('id') ?? name2id($attributes->get('name')) }}">
        {{ $label }}
    </label>
    @endif
</div>

@error($attributes->get('name'))
<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
@enderror
@if (!empty($instruction))
    <x-adminpanel::instruction :text="$instruction"></x-adminpanel::instruction>
@endif






