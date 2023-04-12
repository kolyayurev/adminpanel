@extends('adminpanel::layouts.master')

@section('title', $dataType->getTitleForOrder())

@section('page-header')
    <h1>{{ $dataType->getTitleForOrder() }}</h1>
    {{--    @include('adminpanel::multilingual.language-selector')--}}
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.datatype.'.$dataType->getSlug().'.order',$dataType) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <p class="panel-title" style="color:#777">{{ ap_trans('common.drag_drop_info') }}</p>

            <div class="dd">
                <ol class="dd-list">
                    @foreach ($results as $result)
                        <li class="dd-item" data-id="{{ $result->getKey() }}">
                            <div class="dd-handle">
                                <i class="bi bi-list"></i>&nbsp;<span>{{ $result->{$dataType->getOrderDisplayColumn()} }}</span>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

        </div>
    </div>
@stop

@push('end-body-scripts')
    <script>
        $(document).ready(function () {
            $('.dd').nestable({
                maxDepth: 1
            });

            /**
             * Reorder items
             */
            $('.dd').on('change', function (e) {
                $.post('{{ route('adminpanel.'.$dataType->getSlug().'.order') }}', {
                    order: JSON.stringify($('.dd').nestable('serialize')),
                }, function (data) {
                    toastr.success("{{ ap_trans('messages.success.updated_order') }}");
                });
            });
        });
    </script>
@endpush
