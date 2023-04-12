<?php
use KY\AdminPanel\Http\Controllers\AdminPanelController;
use KY\AdminPanel\Http\Controllers\DocsController;
use KY\AdminPanel\Http\Controllers\AuthController;
use KY\AdminPanel\Http\Controllers\MediaController;
use KY\AdminPanel\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;
use KY\AdminPanel\Http\Controllers\ToolsController;

Route::group(['as' => 'adminpanel.'], function () {

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'postLogin'])->name('postlogin');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');


    Route::group(['middleware' => 'admin.user'], function (){
        Route::get('/', [AdminPanelController::class, 'dashboard'])->name('dashboard');

        Route::group([
            'as'     => 'tools.',
            'prefix' => 'tools',
        ], function () {
            Route::get('/', [ToolsController::class, 'index'])->name('index');
            Route::get('control-panel', [ToolsController::class, 'controlPanel'])->name('control-panel');
            Route::get('commands', [ToolsController::class, 'commands'])->name('commands');
            Route::post('commands', [ToolsController::class, 'commands'])->name('commands.post');
            Route::get('logs', [ToolsController::class, 'logs'])->name('logs');
            Route::get('docs', [DocsController::class, 'index'])->name('docs');
            Route::post('docs', [DocsController::class, 'content'])->name('docs.content');
        });

        $dataTypes = AdminPanel::getDataTypes();
        foreach ($dataTypes as $dataType)
        {
            Route::get($dataType->getSlug().'/table', [$dataType->getController(), 'table'])->name($dataType->getSlug().'.table');
            Route::get($dataType->getSlug().'/{id}/restore', [$dataType->getController(),'restore'])->name($dataType->getSlug().'.restore');
            Route::get($dataType->getSlug().'/relation', [$dataType->getController(),'relation'])->name($dataType->getSlug().'.relation');
            Route::get($dataType->getSlug().'/order', [$dataType->getController(),'order'])->name($dataType->getSlug().'.order');
            Route::post($dataType->getSlug().'/order', [$dataType->getController(),'updateOrder'])->name($dataType->getSlug().'.update-order');
            Route::post($dataType->getSlug().'/edit-field', [$dataType->getController(),'editField'])->name($dataType->getSlug().'.edit-field');
            Route::post($dataType->getSlug().'/update-field', [$dataType->getController(),'updateField'])->name($dataType->getSlug().'.update-field');
            Route::resource($dataType->getSlug(), $dataType->getController());
        }

        Route::get('settings/{name}/relation', [SettingController::class,'relation'])->name('settings.relation');
        Route::get('settings/{name}', [SettingController::class, 'index'])->name('settings');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::group([
            'as'     => 'media.',
            'prefix' => 'media',
        ], function () {
            Route::get('/', [MediaController::class,'index'])->name('index');
            Route::post('files', [MediaController::class,'files'])->name('files');
            Route::post('new-folder',[MediaController::class,'newFolder'])->name('new-folder');
            Route::post('delete-file-folder', [MediaController::class,'delete'])->name('delete');
            Route::post('move-file', [MediaController::class,'move'])->name('move');
            Route::post('rename-file',[MediaController::class,'rename'])->name('rename');
            Route::post('upload', [MediaController::class,'upload'])->name('upload');
            Route::post('crop', [MediaController::class,'crop'])->name('crop');
        });

    });
    Route::group([
        'as'     => 'ajax.',
        'prefix' => 'ajax',
    ], function () {
        Route::post('get-modal', [AdminPanelController::class, 'getModal']);
    });
    //Asset Routes
    //TODO: replace to out admin prefix routes file
    Route::get('assets', [AdminPanelController::class, 'assets'])->name('assets');
});
