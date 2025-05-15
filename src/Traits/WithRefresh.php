<?php

namespace Kataknyulink\AdvancedDataTable\Traits;

trait WithRefresh
{
    public $refreshInterval = 0;
    public $refreshOptions = [0, 5, 10, 30, 60];
    public $manualRefresh = false;

    public function initRefresh()
    {
        $this->refreshOptions = [
            0 => __('Off'),
            5 => __('5 seconds'),
            10 => __('10 seconds'),
            30 => __('30 seconds'),
            60 => __('1 minute'),
        ];
    }

    public function refresh()
    {
        $this->manualRefresh = true;
        $this->resetPage();
    }

    public function updatedRefreshInterval($value)
    {
        $this->resetPage();
    }
}
