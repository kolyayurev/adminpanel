@props([
    'name',
    'label' => null,
    'image' => null,
    'crop' => null,
    'type' => null,
    'allowed' => null,
    'id' => null,
    'canUpload' => true,
    'canRemove' => 0,
    'col' => 12,
    'instruction' => null,
])
<div class="form-group">
    @php
        // crop может прийти массивом [ширина, высота] (дефолт form-blade) — приводим к строке "ш,в",
        // т.к. ниже используется как строка (explode и data-crop). PHP 8: explode() на массиве — TypeError.
        $crop = is_array($crop) ? implode(',', $crop) : (string) ($crop ?? '');
        $cropLabel = !empty($crop) ? explode(',', $crop)[0] .'x'. explode(',', $crop)[1] . 'px' : '';
    @endphp
    @if(!empty($label))
        <label for="{{ str_replace(['[',']'], ['_',''], $name) }}">{{ $label }}@if(!empty($cropLabel)),@endif {{ $cropLabel }}</label>
    @endif
    <div class="mb-3">
        <div class="input-group-prepend">
            @if($canUpload === true)
                <input {{ $attributes }} class="w-100 image-picker" id="{{ str_replace(['[',']'], ['_',''], $name) }}" name="{{ $name }}[file]" data-component-type="{{ $type }}" data-crop="{{ $crop }}" data-component="fileinput" data-remove-image="{{ $image }}" data-src-field="#{{ str_replace(['[',']'], ['_',''], $name) }}_src" data-name="{{ $name }}" data-label="{{ $label }}" data-allowed="{{ $allowed }}" type="file">
                <input type="hidden" name="{{ $name }}[crop]" value="{{ $crop }}">
                <input type="hidden" name="{{ $name }}[componentType]" value="{{ $type }}">
                <input type="hidden" name="{{ $name }}[componentId]" value="{{ $id }}">
            @endif
        </div>
    </div>
    <div class="image-group">
        @if (!empty($image))
            <label for="cropImageThumbnail">Текущее изображение</label><br>
            <img style="max-width: 200px; max-height: 200px" id="cropImageThumbnail" src="{{ APMedia::getImageThumbUrl($image) }}">
            <input type="hidden" name="{{ $name }}[filename]" value="{{ $image }}">
            <div class="mt-3">
                @if(!empty($canRemove))
                    <button type="button" class="btn btn-danger btn-xs" data-action="deleteItem" data-target=".image-group">
                        <i class="bi bi-trash3"></i>
                    </button>
                @endif
                @if ((!empty($image)) && (pathinfo($image, PATHINFO_EXTENSION) !== 'svg') && !empty($crop))
                    <button type="button" class="btn btn-primary btn-xs" data-action="cropImage" data-crop="{{ $crop }}" data-component-type="{{ $type }}" data-component-image="{{ $image }}">
                        <i class="bi bi-crop"></i>
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
