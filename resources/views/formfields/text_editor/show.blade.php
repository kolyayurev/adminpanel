<div class="form-group mb-2 border-bottom">
    <h4>{{ $field->get('label') }}</h4>
    @include('adminpanel::multilingual.input-hidden-show')
    <p>{!! $field->getValue($model) !!}</p>
</div>
