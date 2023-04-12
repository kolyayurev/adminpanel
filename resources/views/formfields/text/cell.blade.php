<x-adminpanel::editable-cell :field="$field" :dataType="$dataType" :model="$model">
    @include('adminpanel::multilingual.input-hidden-cell')
    <div>{{  Str::limit($field->getValue($model),50) }}</div>
</x-adminpanel::editable-cell>

