@props(['tag'=> 'div', 'dataType' => null ,'model'=> null , 'field' => null])
{{-- TODO: customization access control  --}}
<{{$tag}}
    @if($field->columnIsEditable())
        class="editable-cell"
        data-action="editCell"
        data-datatype="{{ $dataType->getSlug() }}"
        data-id="{{ $model->getKey()  }}"
        data-field="{{ $field->get('name') }}"
    @endif
    >
    <button class="btn btn-primary btn-sm editable-cell__button"><i class="bi bi-pencil"></i></button>
    {{ $slot }}
</{{$tag}}>

