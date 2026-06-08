{{--
    Поле загрузки изображения. Используется formfields/image/form.blade.php.

    JS-обвязка прогрессивная: input помечен data-component="fileinput"
    (bootstrap-fileinput) и несёт data-crop-* с целевыми размерами кадрирования.
    Без JS деградирует до обычного <input type="file"> с превью текущей картинки.
--}}
@props([
    'name',
    'label' => '',
    'instruction' => '',
    'image' => null,
    'crop' => [1920, 1080],
    'canRemove' => false,
    'required' => false,
    'disabled' => false,
    'col' => 12,
])

@php
    $id = $attributes->get('id') ?? name2id($name);
    $crop = is_array($crop) ? array_values($crop) : [1920, 1080];
    // Текущее значение — путь в хранилище или абсолютный URL.
    $preview = filled($image)
        ? (filter_var($image, FILTER_VALIDATE_URL) ? $image : APMedia::getUrl($image))
        : null;
@endphp

<div class="form-group">
    @if (!empty($label))
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    @if ($preview)
        <div class="mb-2">
            <img src="{{ $preview }}" alt="{{ $label }}" class="img-thumbnail" style="max-width:200px;height:auto">
        </div>
    @endif

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $id }}"
        accept="image/*"
        data-component="fileinput"
        data-allowed='["jpg","jpeg","png","webp","gif","svg"]'
        data-crop-width="{{ $crop[0] ?? '' }}"
        data-crop-height="{{ $crop[1] ?? '' }}"
        @required($required && ! $preview)
        @disabled($disabled)
        {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}
    />

    @if ($preview && $canRemove)
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" value="1"
                   name="{{ $name }}_remove" id="{{ $id }}_remove" @disabled($disabled)>
            <label class="form-check-label" for="{{ $id }}_remove">Удалить изображение</label>
        </div>
    @endif

    @error($name)
        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
    @enderror

    @if (!empty($instruction))
        <x-adminpanel::instruction :text="$instruction"></x-adminpanel::instruction>
    @endif
</div>
