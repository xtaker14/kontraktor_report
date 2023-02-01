<?php

use App\Http\Controllers\CrTableController;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'cr-table',
    'as' => 'cr-table.',
    'middleware' => 'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('/', [CrTableController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Manajemen Form'), route('menu.cr-table.index'));
        });

    Route::get('view-data', [CrTableController::class, 'view_data'])->name('view-data');

    Route::get('create', [CrTableController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('menu.cr-table.index')
                ->push(__('Buat Form Baru'), route('menu.cr-table.create'));
        });

    Route::post('/', [CrTableController::class, 'store'])->name('store');

    Route::group(['prefix' => '{table_id}'], function () {
        Route::get('edit', [CrTableController::class, 'edit'])
            ->name('edit');
            // ->breadcrumbs(function (Trail $trail, Categories $categories) {
            //     $trail->parent('admin.auth.categories.index')
            //         ->push(__('Editing :categories', ['categories' => $categories->name]), route('admin.auth.categories.edit', $categories));
            // });

        Route::patch('/', [CrTableController::class, 'update'])->name('update');
        Route::delete('/', [CrTableController::class, 'destroy'])->name('destroy');
    });
});