@props(['label'=>null,'instruction'])


<input type="hidden" name="{{ $attributes->get('name') }}" value="0">
<input
    {{ $attributes->class(['form-check-input','is-invalid'=>$errors->has($attributes->get('name'))]) }}
       id="{{ $attributes->get('id') ?? name2id($attributes->get('name')) }}"
       placeholder="{{ $attributes->get('placeholder') ?? $label}}"
        >
@if (!empty($label))
    <label class="form-check-label" for="{{ $attributes->get('id') ?? name2id($attributes->get('name')) }}">{{ $label }}</label>
@endif
    @if ($attributes->get('type') !== 'hidden')
        @error($attributes->get('name'))
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    @endif

@if (!empty($instruction))
    <x-adminpanel::instruction :text="$instruction"></x-adminpanel::instruction>
@endif
