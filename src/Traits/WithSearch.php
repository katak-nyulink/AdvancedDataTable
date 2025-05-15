<?php

namespace Kataknyulink\AdvancedDataTable\Traits;

trait WithSearch
{
    public $search = '';

    public function applySearch($query)
    {
        return $query->where(function ($q) {
            foreach ($this->columns as $column) {
                if (($column['searchable'] ?? true) && isset($column['key'])) {
                    // Jika kolom memiliki relasi (ada tanda dot)
                    if (str_contains($column['key'], '.')) {
                        $this->applyRelationSearch($q, $column['key']);
                    } else {
                        $q->orWhere($column['key'], 'LIKE', '%' . $this->search . '%');
                    }
                }
            }
        });
    }

    protected function applyRelationSearch($query, $relationColumn)
    {
        $parts = explode('.', $relationColumn);
        $relation = implode('.', array_slice($parts, 0, -1));
        $column = end($parts);

        $query->orWhereHas($relation, function ($q) use ($column) {
            $q->where($column, 'LIKE', '%' . $this->search . '%');
        });
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
