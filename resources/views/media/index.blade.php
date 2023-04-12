@extends('adminpanel::layouts.master')

@section('title', ap_trans('common.media'))

@section('page-header')
    <h1>
        <x-adminpanel::icon name="cast"></x-adminpanel::icon>
        {{ ap_trans('common.media') }}
    </h1>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.media') }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div id="filemanager">
            <v-media-manager
                base-path="{{ config('adminpanel.media.path', '/') }}"
                :show-folders="{{ config('adminpanel.media.show_folders', true) ? 'true' : 'false' }}"
                :allow-upload="{{ config('adminpanel.media.allow_upload', true) ? 'true' : 'false' }}"
                :allow-move="{{ config('adminpanel.media.allow_move', true) ? 'true' : 'false' }}"
                :allow-delete="{{ config('adminpanel.media.allow_delete', true) ? 'true' : 'false' }}"
                :allow-create-folder="{{ config('adminpanel.media.allow_create_folder', true) ? 'true' : 'false' }}"
                :allow-rename="{{ config('adminpanel.media.allow_rename', true) ? 'true' : 'false' }}"
                :allow-crop="{{ config('adminpanel.media.allow_crop', true) ? 'true' : 'false' }}"
                ></v-media-manager>
        </div>
    </div>
</div>
@stop

@push('vue')
<script>
        createVueApp().mount('#filemanager');
</script>
@endpush
