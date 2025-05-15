<?php

namespace Kataknyulink\AdvancedDataTable\Traits;

trait WithAdvancedPagination
{
    public $perPage = 10;
    public $pageOptions = [10, 25, 50, 100];

    public function updatedPerPage($value)
    {
        $this->resetPage();
    }

    public function getPaginationView()
    {
        return 'advanced-data-table::pagination';
    }

    public function getPaginationTheme()
    {
        return 'bootstrap-4';
    }

    public function getPaginationClass()
    {
        return 'pagination justify-content-center';
    }
}
