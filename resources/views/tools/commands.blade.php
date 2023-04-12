@extends('adminpanel::layouts.master')

@section('title',ap_trans('breadcrumbs.tools.commands'))

@section('page-header')
    <h1>
        <x-adminpanel::icon name="terminal"></x-adminpanel::icon>
        {{ ap_trans('breadcrumbs.tools.commands') }}
    </h1>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.tools.commands') }}
@endsection

@section('content')
<div class="commands-page">
    @if($artisanOutput)
        <pre class="commands-page__output">
        <i class="close-output bi bi-x">{{ ap_trans('tools.commands.clear_output') }}</i>
            <span class="commands-page__output-caption">{{ ap_trans('tools.commands.command_output') }}:</span>{{ trim(trim($artisanOutput,'"')) }}
        </pre>
    @endif
    <div class="commands-page__list">
        @foreach($commands as $command)
            <div class="command" data-command="{{ $command->name }}">
                <code>php artisan {{ $command->name }}</code>
                <small>{{ $command->description }}</small><i class="bi bi-terminal"></i>
                <form action="{{ route('adminpanel.tools.commands.post') }}" class="command__form" method="POST">
                    {{ csrf_field() }}
                    <input type="text" name="args" autofocus class="form-control"
                           placeholder="{{ ap_trans('tools.commands.additional_args') }}">
                    <input type="submit" class="btn btn-primary pull-right delete-confirm"
                           value="{{ ap_trans('tools.commands.run_command') }}">
                    <input type="hidden" name="command" id="hidden_cmd" value="{{ $command->name }}">
                </form>
            </div>
        @endforeach
    </div>
</div>


@endsection

@push('end-body-scripts')
    <script>
        $(document).ready(function () {
            $('.command').click(function () {
                $(this).find('.command__form').slideDown();
                $(this).addClass('--more-args');
                $(this).find('input[type="text"]').focus();
            });

            $('.close-output').click(function () {
                $('.commands-page pre').slideUp();
            });
        });
    </script>
@endpush
