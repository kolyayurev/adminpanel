@extends('adminpanel::layouts.master')

@section('title',ap_trans('breadcrumbs.tools.logs'))

@section('page-header')
    <h1>
        <x-adminpanel::icon name="file-text"></x-adminpanel::icon>
        {{ ap_trans('breadcrumbs.tools.logs') }}
    </h1>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.tools.logs') }}
@endsection

@section('content')
    <div class="logs-page">
        <div class="row">
            <div class="col-sm-3 col-md-3">
                <div class="list-group">
                    @foreach($files as $file)
                        <a href="?log={{ base64_encode($file) }}"
                           class="list-group-item @if ($currentFile == $file) llv-active @endif">
                            <i class="bi bi-file-text"></i> {{$file}}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-9 col-md-9 table-container">
                @if ($logs === null)
                    <div>
                        {{ ap_trans('tools.logs.file_too_big') }}
                    </div>
                @else
                    <table id="table-log" class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{ ap_trans('tools.logs.level') }}</th>
                            <th>{{ ap_trans('tools.logs.context') }}</th>
                            <th>{{ ap_trans('tools.logs.date') }}</th>
                            <th>{{ ap_trans('tools.logs.content') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($logs as $key => $log)
                            <tr data-display="stack{{{$key}}}">
                                <td class="text-{{{$log['level_class']}}} level"><span
                                        class="glyphicon glyphicon-{{{$log['level_img']}}}-sign"
                                        aria-hidden="true"></span> &nbsp;{{$log['level']}}</td>
                                <td class="text">{{$log['context']}}</td>
                                <td class="date">{{{$log['date']}}}</td>
                                <td class="text">
                                    @if ($log['stack'])
                                        <a class="pull-right expand btn btn-default btn-xs"
                                           data-display="stack{{{$key}}}"><span
                                                class="glyphicon glyphicon-search"></span></a>
                                    @endif
                                    {{{$log['text']}}}
                                    @if (isset($log['in_file']))
                                        <br/>{{{$log['in_file']}}}
                                    @endif
                                    @if ($log['stack'])
                                        <div class="stack" id="stack{{{$key}}}"
                                             style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endif
                <div>
                    @if($currentFile)
                        <a href="?download={{ base64_encode($currentFile) }}"><span
                                class="glyphicon glyphicon-download-alt"></span>
                            {{ ap_trans('tools.logs.download_file') }}</a>
                        -
                        <a id="delete-log" href="?del={{ base64_encode($currentFile) }}"><span
                                class="glyphicon glyphicon-trash"></span> {{ ap_trans('tools.logs.delete_file') }}
                        </a>
                        @if(count($files) > 1)
                            -
                            <a id="delete-all-log" href="?delall=true"><span
                                    class="glyphicon glyphicon-trash"></span> {{ ap_trans('tools.logs.delete_all_files') }}
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('end-body-scripts')
    <script>
        $(document).ready(function () {
            $('.table-container tr').on('click', function () {
                $('#' + $(this).data('display')).toggle();
            });
            $('#table-log').DataTable({
                "order": [1, 'desc'],
                "stateSave": true,
                "language": {'url': '{{ adminpanel_asset('js/datatables/languages/ru_RU.json') }}' },
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data));
                },
                "stateLoadCallback": function (settings) {
                    var data = JSON.parse(window.localStorage.getItem("datatable"));
                    if (data) data.start = 0;
                    return data;
                }
            });

            $('#delete-log, #delete-all-log').click(function () {
                return confirm('{{ ap_trans('messages.questions.are_you_sure') }}');
            });
        });
    </script>
@endpush
