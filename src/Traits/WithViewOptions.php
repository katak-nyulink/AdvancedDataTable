<?php

namespace Kataknyulink\AdvancedDataTable\Traits;

trait WithViewOptions
{
    public function toggleView()
    {
        $this->displayView = $this->displayView === 'table' ? 'card' : 'table';
    }

    public function toggleNestedView()
    {
        $this->showNestedView = !$this->showNestedView;
    }
}
