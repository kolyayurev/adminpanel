@php
    $edit = !is_null($model?->getKey());
@endphp
{{-- В полноэкранном режиме селектор языка уже в page-header формы; здесь — только для
     модалки, где своего page-header нет. --}}
@if(($modal ?? false) && ($isModelTranslatable ?? false))
    @include('adminpanel::multilingual.language-selector')
@endif
<form
        method="post"
        action="{{ route('adminpanel.'.$dataType->getSLug().'.'.($edit?'update':'store'), $model->getKey()) }}"
        class="form-create-edit"
        enctype="multipart/form-data"
        @if($modal ?? false) data-modal="1" @endif
>
    @csrf
    @if($edit)
        @method('PUT')
    @endif
    @php
        $fields = $dataType->getFormFields(($edit?'edit':'create'));
    @endphp
    @include('adminpanel::blocks.layout.index',['blocks' => $dataType->getLayout(),'content' => $bodyTemplate ?? 'adminpanel::datatypes.partials.block-body' ])
    @include('adminpanel::datatypes.partials.submit')
</form>
{{-- Поля рисуют свои Vue-инстансы через @push('vue'); в полноэкранном режиме их выводит
     общий @stack('vue') в layouts/master.blade.php. В модалке своего стека в ответе нет —
     выводим его прямо здесь, только для модального рендера (иначе задвоится в полной странице). --}}
@if($modal ?? false)
    @stack('vue')
@endif
