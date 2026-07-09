@php($mount = 'chartWidgetApp_'.$widget->getSlug())

<div class="card mt-3">
    <div class="card-header">{{ $widget->getTitle() }}</div>
    <div class="card-body">
        <div id="{{ $mount }}" v-cloak>
            <v-chart-widget widget-slug="{{ $widget->getSlug() }}"></v-chart-widget>
        </div>
    </div>
</div>

@push('vue')
    <script>
        createVueApp({}).mount('#{{ $mount }}');
    </script>
@endpush
