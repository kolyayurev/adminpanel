<form
    class="editable-cell__form"
    data-action="updateCell"
    data-datatype="{{ $dataType->getSlug() }}"
>
    <input type="hidden" name="{{ $model->getKeyName() }}" value="{{ $model->getKey() }}">
    {!! $field->render($dataType,$model) !!}
    <div class="editable-cell__form-buttons btn-group">
        <button class="btn btn-success" type="submit"><i class="bi bi-check"></i></button>
        <button class="btn btn-danger" type="reset"><i class="bi bi-x"></i></button>
    </div>
</form>
