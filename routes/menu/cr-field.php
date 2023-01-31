<?php

use App\Http\Controllers\CrFieldController;
use App\Models\CrField;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'cr-field',
    'as' => 'cr-field.',
    'middleware' => 'role:'.config('boilerplate.access.role.admin'),
], function () {
    Route::get('create-field-table/{table_id}', [CrFieldController::class, 'create_field_table'])->name('create-field-table');
    Route::post('preview-field/{table_id}', [CrFieldController::class, 'preview_field'])->name('preview-field');
    Route::post('save-preview-field', [CrFieldController::class, 'save_preview_field'])->name('save-preview-field');
    Route::get('final-preview/{table_id}', [CrFieldController::class, 'final_preview'])->name('final-preview');
    Route::get('save-final-preview', [CrFieldController::class, 'save_final_preview'])->name('save-final-preview');

    Route::group(['prefix' => '{table_id}'], function () {
        Route::get('create', [CrFieldController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail, CrField $field) {
            $trail->parent('menu.cr-table.index')
                ->push(__('Create Field'), route('menu.cr-field.create', ['table_id' => $field->table_id]));
        });

        Route::post('/', [CrFieldController::class, 'store'])->name('store');

        Route::get('edit', [CrFieldController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Categories $categories) {
                $trail->parent('admin.auth.categories.index')
                    ->push(__('Editing :categories', ['categories' => $categories->name]), route('admin.auth.categories.edit', $categories));
            });

        Route::patch('/', [CrFieldController::class, 'update'])->name('update');
        Route::delete('/', [CrFieldController::class, 'destroy'])->name('destroy');
    });
});