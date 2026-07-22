<x-adminpanel::editable-cell :field="$field" :dataType="$dataType" :model="$model">
    <div>{{ Str::limit($field->getValue($model), 50) }}</div>
</x-adminpanel::editable-cell>
