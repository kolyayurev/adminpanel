@if($field->isEditable())
    <div class="btn-group btn-group-sm" role="group">
        <input type="radio"
               class="btn-check"
               data-action="editStatus"
               data-datatype="{{ $dataType->getSlug() }}"
               data-id="{{ $model->getKey()  }}"
               data-field="{{ $field->get('name') }}"
               name="{{ $field->get('name') }}{{ $model->getKey() }}"
               id="{{ $model->getKey() }}{{ $field->getId() }}_on"
               autocomplete="off"
               value="1"
            @checked($field->getValue($model))
            @disabled($field->get('disabled'))
        >
        <label class="btn btn-outline-success" for="{{ $model->getKey() }}{{ $field->getId() }}_on">{{ trans($field->get('textOn')) }}</label>

        <input type="radio"
               class="btn-check"
               data-action="editStatus"
               data-datatype="{{ $dataType->getSlug() }}"
               data-id="{{ $model->getKey()  }}"
               data-field="{{ $field->get('name') }}"
               name="{{ $field->get('name') }}{{ $model->getKey() }}"
               id="{{ $model->getKey() }}{{ $field->getId() }}_off"
               autocomplete="off"
               value="0"
            @checked(!$field->getValue($model))
            @disabled($field->get('disabled'))
        >
        <label class="btn btn-outline-secondary" for="{{ $model->getKey() }}{{ $field->getId() }}_off">{{ trans($field->get('textOff')) }}</label>
    </div>
@else
    @if($field->getValue($model))
        <span class="badge text-bg-primary">{{ trans($field->get('textOn')) }}</span>
    @else
        <span class="badge text-bg-secondary">{{ trans($field->get('textOff')) }}</span>
    @endif
@endif
