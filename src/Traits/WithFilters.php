<?php

namespace Kataknyulink\AdvancedDataTable\Traits;

trait WithFilters
{
    public $filters = [];
    public $availableFilters = [];

    public function applyFilters($query)
    {
        foreach ($this->filters as $key => $value) {
            if (empty($value)) continue;

            $filter = collect($this->availableFilters)->firstWhere('key', $key);

            if ($filter) {
                $query = $this->applyFilter($query, $filter, $value);
            }
        }

        return $query;
    }

    protected function applyFilter($query, $filter, $value)
    {
        $type = $filter['type'] ?? 'text';
        $column = $filter['column'];

        // Jika filter berdasarkan relasi
        if (str_contains($column, '.')) {
            return $this->applyRelationFilter($query, $column, $type, $value);
        }

        return match ($type) {
            'select' => $query->where($filter['column'], $value),
            'date' => $query->whereDate($filter['column'], $value),
            'daterange' => $this->handleDateRange($query, $filter['column'], $value),
            default => $query->where($filter['column'], 'LIKE', '%' . $value . '%'),
        };
    }

    protected function applyRelationFilter($query, $relationColumn, $type, $value)
    {
        $parts = explode('.', $relationColumn);
        $relation = implode('.', array_slice($parts, 0, -1));
        $column = end($parts);

        return $query->whereHas($relation, function ($q) use ($column, $type, $value) {
            return match ($type) {
                'select' => $q->where($column, $value),
                'date' => $q->whereDate($column, $value),
                'daterange' => isset($value['from'])
                    ? $q->whereDate($column, '>=', $value['from'])
                    ->when(isset($value['to']), fn($q) => $q->whereDate($column, '<=', $value['to']))
                    : $q->when(isset($value['to']), fn($q) => $q->whereDate($column, '<=', $value['to'])),
                default => $q->where($column, 'LIKE', '%' . $value . '%'),
            };
        });
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }
}
