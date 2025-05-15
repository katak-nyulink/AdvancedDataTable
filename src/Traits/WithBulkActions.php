<?php

namespace Kataknyulink\AdvancedDataTable\Traits;

trait WithBulkActions
{
    public $selectedRows = [];
    public $selectPage = false;
    public $bulkActions = [];

    public function initBulkActions()
    {
        $this->bulkActions = [
            'delete' => __('Delete Selected'),
            'export' => __('Export Selected'),
        ];
    }

    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->selectedRows = $this->rows->pluck('id')->map(fn($id) => (string)$id);
        } else {
            $this->selectedRows = [];
        }
    }

    public function applyBulkAction($action)
    {
        if ($action === 'delete' && count($this->selectedRows)) {
            $this->model::whereIn('id', $this->selectedRows)->delete();
            $this->selectedRows = [];
            $this->selectPage = false;
            $this->dispatch('notify', __('Selected items deleted successfully.'));
        }
    }
}
