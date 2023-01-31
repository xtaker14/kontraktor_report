<a href="{{ route('menu.cr-table.view-data') }}" class="btn btn-primary btn-sm view-data" id="{{ $model->id }}"><i class="fas fa-eye"></i> View</a>
<x-utils.edit-button :href="route('menu.cr-table.edit', $model)" />
<x-utils.delete-button :href="route('menu.cr-table.destroy', $model)" />