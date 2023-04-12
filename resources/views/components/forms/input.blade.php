@props(['label'=>'','instruction','inputGroup'=>false,'required'=>false])

@if ($attributes->get('type') !== 'hidden')<div class="form-group ">@endif
@if (!empty($label))
    <label for="{{ $attributes->get('id') ?? name2id($attributes->get('name')) }}">{{ $label }}</label>
@endif
{{ $multilangual ?? '' }}
{{ $afterLabel ?? '' }}
@if ($inputGroup) <div @class(["input-group",'is-invalid'=>$errors->has($attributes->get('name'))])> @endif
{{ $before ?? '' }}
<input
    {{ $attributes->class(['form-control','is-invalid'=>$errors->has($attributes->get('name'))]) }}
       id="{{  $attributes->get('id') ?? name2id($attributes->get('name')) }}"
       placeholder="{{ $attributes->get('placeholder') ?? $label}}"
       @if($required) required @endif
        />

{{ $after ?? '' }}
@if ($inputGroup) </div> @endif
@if ($attributes->get('type') !== 'hidden')
    @error($attributes->get('name'))
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
@endif



@if (!empty($instruction))
    <x-adminpanel::instruction :text="$instruction"></x-adminpanel::instruction>
@endif

@if ($attributes->get('type') !== 'hidden')</div>@endif
