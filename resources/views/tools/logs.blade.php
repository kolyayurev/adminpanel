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
                    <div>{{ ap_trans('tools.logs.file_too_big') }}</div>
                @else
                    <div id="logsApp" v-cloak>
                        <el-input v-model="search" placeholder="{{ ap_trans('common.search') }}" clearable size="small"
                                  class="mb-2" style="max-width: 280px"/>
                        <el-table :data="paged" border stripe default-expand-all-rows="false" style="width: 100%">
                            <el-table-column type="expand">
                                <template #default="{ row }">
                                    <pre v-if="row.stack" style="white-space: pre-wrap; margin: 0">@{{ row.stack }}</pre>
                                    <span v-else>—</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="level" label="{{ ap_trans('tools.logs.level') }}" sortable width="140">
                                <template #default="{ row }">
                                    <span :class="'text-' + row.level_class">@{{ row.level }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="context" label="{{ ap_trans('tools.logs.context') }}" sortable width="140"/>
                            <el-table-column prop="date" label="{{ ap_trans('tools.logs.date') }}" sortable width="180"/>
                            <el-table-column prop="text" label="{{ ap_trans('tools.logs.content') }}">
                                <template #default="{ row }">
                                    <div>@{{ row.text }}</div>
                                    <div v-if="row.in_file" class="text-muted small">@{{ row.in_file }}</div>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div class="d-flex justify-content-end mt-2">
                            <el-pagination v-model:current-page="page" v-model:page-size="perPage"
                                           :total="filtered.length" :page-sizes="[25, 50, 100]"
                                           layout="total, sizes, prev, pager, next"/>
                        </div>
                    </div>
                @endif
                <div class="mt-2">
                    @if($currentFile)
                        <a href="?download={{ base64_encode($currentFile) }}">
                            <i class="bi bi-download"></i> {{ ap_trans('tools.logs.download_file') }}</a>
                        -
                        <a id="delete-log" href="?del={{ base64_encode($currentFile) }}">
                            <i class="bi bi-trash3"></i> {{ ap_trans('tools.logs.delete_file') }}</a>
                        @if(count($files) > 1)
                            -
                            <a id="delete-all-log" href="?delall=true">
                                <i class="bi bi-trash3"></i> {{ ap_trans('tools.logs.delete_all_files') }}</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vue')
    @if($logs !== null)
        <script>
            createVueApp({
                data() {
                    return { logs: @json(array_values($logs)), search: '', page: 1, perPage: 50 }
                },
                computed: {
                    filtered() {
                        const s = this.search.toLowerCase()
                        return s ? this.logs.filter((l) => JSON.stringify(l).toLowerCase().includes(s)) : this.logs
                    },
                    paged() {
                        const start = (this.page - 1) * this.perPage
                        return this.filtered.slice(start, start + this.perPage)
                    },
                },
            }).mount('#logsApp');
        </script>
    @endif
@endpush

@push('end-body-scripts')
    <script>
        $(document).ready(function () {
            $('#delete-log, #delete-all-log').click(function () {
                return confirm('{{ ap_trans('messages.questions.are_you_sure') }}');
            });
        });
    </script>
@endpush
