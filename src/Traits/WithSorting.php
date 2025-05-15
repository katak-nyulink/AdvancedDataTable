<?php

namespace Kataknyulink\AdvancedDataTable\Traits;

trait WithSorting
{
    public $sortField = '';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function applySorting($query)
    {
        // Jika sorting berdasarkan relasi (ada tanda dot)
        if (str_contains($this->sortField, '.')) {
            return $this->applyRelationSorting($query);
        }

        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    protected function applyRelationSorting($query)
    {
        $parts = explode('.', $this->sortField);
        $relation = implode('.', array_slice($parts, 0, -1));
        $column = end($parts);

        return $query->with([$relation => function ($q) use ($column) {
            $q->orderBy($column, $this->sortDirection);
        }]);
    }
}
