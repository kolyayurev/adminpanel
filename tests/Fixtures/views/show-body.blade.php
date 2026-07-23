custom show body: {{ $model->getKey() }}
{{-- Кастомное тело может подмешивать свои Vue-инстансы — в модалке они должны попасть
     в ответ, своего @stack('vue') у неё нет. --}}
@push('vue')
    <script>window.customShowBodyMounted = true</script>
@endpush
