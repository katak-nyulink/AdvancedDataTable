<?php

namespace Kataknyulink\AdvancedDataTable\Traits;

trait WithSelectableRows
{
    public function selectRow($id)
    {
        if (in_array($id, $this->selectedRows)) {
            $this->selectedRows = array_diff($this->selectedRows, [$id]);
        } else {
            $this->selectedRows[] = $id;
        }
    }

    public function isSelected($id)
    {
        return in_array($id, $this->selectedRows);
    }
}
