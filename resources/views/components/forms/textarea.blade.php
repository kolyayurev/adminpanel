@props(['label'=>'','instruction'])
<div class="form-group">
    <label for="{{ $attributes->get('id') ?? name2id($attributes->get('name')) }}">{{ $label }}</label>
    {{ $multilangual ?? '' }}
    <textarea
        {{ $attributes->class(['form-control','is-invalid'=>$errors->has($attributes->get('name'))]) }}
        id="{{  $attributes->get('id') ?? name2id($attributes->get('name')) }}"
        placeholder="{{ $attributes->get('placeholder') ?? $label}}"
       >{!! $slot !!}</textarea>

    @error($attributes->get('name'))
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
    @if (!empty($instruction))
        <x-adminpanel::instruction :text="$instruction"></x-adminpanel::instruction>
    @endif
</div>
