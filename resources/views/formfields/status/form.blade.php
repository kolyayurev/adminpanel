@if($field->getValue($model) || old($field->get('name')))
    <?php $checked = old($field->get('name'), $field->getValue($model)); ?>
@else
    <?php $checked = $field->get('default'); ?>
@endif
<div class="mb-3">
    <label for="{{ $field->getId() }}">{{ $field->get('label') }}</label>
    @include('adminpanel::multilingual.input-hidden-form')
    <br>
    <div class="btn-group btn-group-sm" role="group">
        <input type="radio"
               class="btn-check"
               name="{{ $field->get('name') }}"
               id="{{ $field->getId() }}_on"
               autocomplete="off"
               value="1"
               @checked($checked)
               @disabled($field->get('disabled'))
        >
        <label class="btn btn-outline-success" for="{{ $field->getId() }}_on">{{ trans($field->get('textOn')) }}</label>

        <input type="radio"
               class="btn-check"
               name="{{ $field->get('name') }}"
               id="{{ $field->getId() }}_off"
               autocomplete="off"
               value="0"
               @checked(!$checked)
               @disabled($field->get('disabled'))
        >
        <label class="btn btn-outline-secondary" for="{{ $field->getId() }}_off">{{ trans($field->get('textOff')) }}</label>
    </div>
    @if (!empty($field->get('instruction')))
        <x-adminpanel::instruction :text="$field->get('instruction')"></x-adminpanel::instruction>
    @endif
</div>

