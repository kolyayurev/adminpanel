@props(['label'=>'','instruction','mode'=>null,'options'=>[],'selected'])

<div class="form-group">
    @if (!empty($label))
        <label for="{{ $attributes->get('id') ?? name2id($attributes->get('name')) }}">{{ $label }}</label>
    @endif
        {{ $multilangual ?? '' }}
        {{ $afterLabel ?? '' }}
    <br>
    <select
        {{ $attributes->class(['form-control w-100 select2'.(!is_null($mode)?'-'.$mode:''),'is-invalid'=>$errors->has($attributes->get('name'))]) }}
        id="{{  $attributes->get('id') ?? name2id($attributes->get('name')) }}"
        data-component="select2">
        {{ $slot }}
        @foreach ($options as $key => $val)
            <option @if (($attributes->get('multiple')) && ((in_array($key, old($attributes->get('name')) ?? [])) ||
                (is_array($selected) && empty(old($attributes->get('name'))) && in_array($key, $selected))) ||
                (old($attributes->get('name')) == $key) || ((empty(old($attributes->get('name')))) && ($key == $selected)))
                    selected @endif value="{{ $key }}">{{ $val }}
            </option>
        @endforeach
    </select>
    @error($attributes->get('name'))
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
    @if (!empty($instruction))
        <x-adminpanel::instruction :text="$instruction"></x-adminpanel::instruction>
    @endif

</div>
