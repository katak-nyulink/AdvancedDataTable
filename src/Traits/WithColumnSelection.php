<?php

namespace Kataknyulink\AdvancedDataTable\Traits;

trait WithColumnSelection
{
    public $selectedColumns = [];
    public $showColumnSelection = false;

    public function initColumnSelection()
    {
        $this->selectedColumns = collect($this->columns)
            ->filter(fn($column) => $column['visible'] ?? true)
            ->pluck('key')
            ->toArray();
    }

    public function toggleColumn($columnKey)
    {
        if (in_array($columnKey, $this->selectedColumns)) {
            $this->selectedColumns = array_diff($this->selectedColumns, [$columnKey]);
        } else {
            $this->selectedColumns[] = $columnKey;
        }
    }

    public function getVisibleColumnsProperty()
    {
        return collect($this->columns)
            ->filter(fn($column) => in_array($column['key'], $this->selectedColumns))
            ->toArray();
    }
}
