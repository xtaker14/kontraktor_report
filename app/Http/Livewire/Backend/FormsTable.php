<?php

namespace App\Http\Livewire\Backend;

use App\Models\CrTable;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

/**
 * Class FormTable.
 */
class FormsTable extends DataTableComponent
{
    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return CrTable::when(
                $this->getFilter('search'), fn ($query, $term) => $query->search($term)
            )->when(
                $this->getFilter('type'), fn ($query, $term) => $query->where('type', $term)
            )->when(
                $this->getFilter('is_menu'), fn ($query, $term) => $query->where('is_menu', $term)
            )->when(
                $this->getFilter('is_module'), fn ($query, $term) => $query->where('is_module', $term)
            );
    }
    
    public function filters(): array
    {
        return [
            'type' => Filter::make('Is Type')
                ->select([
                    '' => 'Any',
                ]),
            'is_menu' => Filter::make('Is Menu')
                ->select([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),
            'is_module' => Filter::make('Is Module')
                ->select([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make(__('Kode Form'), 'slug')
                ->sortable(),
            Column::make(__('Nama Form'), 'name')
                ->sortable(),
            Column::make(__('Actions')),
        ];
    }

    public function rowView(): string
    {
        return 'cr-table.includes.row';
    }
}
