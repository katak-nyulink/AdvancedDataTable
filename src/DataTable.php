<?php

namespace Kataknyulink\AdvancedDataTable;

use Livewire\Component;
use Livewire\WithPagination;
use Kataknyulink\AdvancedDataTable\Traits\WithAdvancedPagination;
use Kataknyulink\AdvancedDataTable\Traits\WithBulkActions;
use Kataknyulink\AdvancedDataTable\Traits\WithColumnSelection;
use Kataknyulink\AdvancedDataTable\Traits\WithFilters;
use Kataknyulink\AdvancedDataTable\Traits\WithRefresh;
use Kataknyulink\AdvancedDataTable\Traits\WithSearch;
use Kataknyulink\AdvancedDataTable\Traits\WithSelectableRows;
use Kataknyulink\AdvancedDataTable\Traits\WithSorting;
use Kataknyulink\AdvancedDataTable\Traits\WithViewOptions;


class DataTable extends Component
{
    use WithPagination,
        WithAdvancedPagination,
        WithBulkActions,
        WithColumnSelection,
        WithFilters,
        WithRefresh,
        WithSearch,
        WithSelectableRows,
        WithSorting,
        WithViewOptions;

    public $model;
    public $columns = [];
    public $perPageOptions = [10, 25, 50, 100];
    public $displayView = 'table'; // 'table' or 'card'
    public $showNestedView = false;
    public $enableNestedView = false;
    public $enableFeatures = [
        'search' => true,
        'pagination' => true,
        'sorting' => true,
        'columnSelection' => true,
        'filters' => true,
        'bulkActions' => true,
        'selectableRows' => true,
        'viewOptions' => true,
        'refresh' => true,
        'nestedView' => false,
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'perPage',
        'sortField',
        'sortDirection',
        'selectedColumns',
        'filters',
        'selectedRows',
        'displayView',
        'showNestedView',
    ];

    public function mount($model, $columns, $enableFeatures = [])
    {
        $this->model = $model;
        $this->columns = $columns;
        $this->enableFeatures = array_merge($this->enableFeatures, $enableFeatures);

        $this->initTraits();
    }

    protected function initTraits()
    {
        if ($this->enableFeatures['columnSelection']) {
            $this->initColumnSelection();
        }

        if ($this->enableFeatures['bulkActions']) {
            $this->initBulkActions();
        }

        if ($this->enableFeatures['refresh']) {
            $this->initRefresh();
        }
    }

    public function getRowsQueryProperty()
    {
        $query = $this->model::query();

        // Apply search
        if ($this->enableFeatures['search'] && $this->search) {
            $query = $this->applySearch($query);
        }

        // Apply filters
        if ($this->enableFeatures['filters'] && count($this->filters) > 0) {
            $query = $this->applyFilters($query);
        }

        // Apply sorting
        if ($this->enableFeatures['sorting'] && $this->sortField) {
            $query = $this->applySorting($query);
        }

        return $query;
    }

    public function getRowsProperty()
    {
        $query = $this->rowsQuery;

        // Apply pagination
        if ($this->enableFeatures['pagination']) {
            return $query->paginate($this->perPage);
        }

        return $query->get();
    }

    public function render()
    {
        return view('advanced-datatable::livewire.data-table', [
            'rows' => $this->rows,
            'visibleColumns' => $this->getVisibleColumns(),
        ]);
    }

    public function __toString()
    {
        return view('advanced-datatable::livewire.data-table', [
            'model' => $this->model,
            'columns' => $this->columns,
            'enableFeatures' => $this->enableFeatures,
            'availableFilters' => $this->availableFilters
        ])->render();
    }
}
