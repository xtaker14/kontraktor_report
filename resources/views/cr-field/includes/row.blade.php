<x-livewire-tables::bs4.table.cell>
    {{ $row->name }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->slug_name }}
</x-livewire-tables::bs4.table.cell>


<x-livewire-tables::bs4.table.cell>
    @include('cr-field.includes.actions', ['model' => $row])
</x-livewire-tables::bs4.table.cell>
