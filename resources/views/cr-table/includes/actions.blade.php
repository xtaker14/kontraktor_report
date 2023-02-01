<a href="{{ route('menu.cr-table.view-data') }}" class="btn btn-info btn-sm view-data" id="{{ $model->id }}"><i class="fas fa-eye"></i> View</a>
<x-utils.edit-button :href="route('menu.cr-table.edit', $model)" />
<a href="{{ route('menu.cr-table.view-data') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> field baru</a>
<a href="{{ route('menu.cr-table.view-data') }}" class="btn btn-info btn-sm"><i class="fas fa-hourglass"></i></a>
<a href="{{ route('menu.cr-field.list', ['table_id' => $model->id]) }}" class="btn btn-dark btn-sm"><i class="fas fa-tasks"></i> List Field</a>