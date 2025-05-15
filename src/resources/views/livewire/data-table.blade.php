<div 
    class="data-table-container dark:bg-gray-800 dark:text-gray-100"
    x-data="{
        showFilters: false,
        showColumnSelector: false,
        showBulkActions: false,
        showRefreshOptions: false,
    }"
    wire:poll.{{ $refreshInterval }}s=""
>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <!-- Title and Search -->
        <div class="w-full sm:w-auto">
            @if($enableFeatures['search'])
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search..."
                    class="pl-10 pr-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                >
                <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            @if($enableFeatures['viewOptions'])
                <button 
                    @click="toggleView()"
                    class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <span x-text="displayView === 'table' ? 'Card View' : 'Table View'"></span>
                </button>
                
                @if($enableNestedView)
                <button 
                    @click="toggleNestedView()"
                    class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <span x-text="showNestedView ? 'Hide Details' : 'Show Details'"></span>
                </button>
                @endif
            @endif

            @if($enableFeatures['columnSelection'])
                <button 
                    @click="showColumnSelector = !showColumnSelector"
                    class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Columns
                </button>
            @endif

            @if($enableFeatures['filters'])
                <button 
                    @click="showFilters = !showFilters"
                    class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Filters
                </button>
            @endif

            @if($enableFeatures['refresh'])
                <button 
                    @click="showRefreshOptions = !showRefreshOptions"
                    class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Refresh
                </button>
            @endif
        </div>
    </div>

    <!-- Filters -->
    @if($enableFeatures['filters'])
    <div x-show="showFilters" x-transition class="mb-4 p-4 bg-white dark:bg-gray-700 rounded-lg shadow border border-gray-200 dark:border-gray-600">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($availableFilters as $filter)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $filter['label'] }}</label>
                    @if($filter['type'] === 'select')
                        <select 
                            wire:model.live="filters.{{ $filter['key'] }}" 
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">All</option>
                            @foreach($filter['options'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    @elseif($filter['type'] === 'daterange')
                        <div class="flex gap-2">
                            <input 
                                type="date" 
                                wire:model.live="filters.{{ $filter['key'] }}.from"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="From"
                            >
                            <input 
                                type="date" 
                                wire:model.live="filters.{{ $filter['key'] }}.to"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="To"
                            >
                        </div>
                    @else
                        <input 
                            type="{{ $filter['type'] ?? 'text' }}" 
                            wire:model.live="filters.{{ $filter['key'] }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    @endif
                </div>
            @endforeach
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <button 
                wire:click="resetFilters"
                class="px-3 py-1.5 bg-gray-200 dark:bg-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-500"
            >
                Reset
            </button>
        </div>
    </div>
    @endif

    <!-- Column Selector -->
    @if($enableFeatures['columnSelection'])
    <div x-show="showColumnSelector" x-transition class="mb-4 p-4 bg-white dark:bg-gray-700 rounded-lg shadow border border-gray-200 dark:border-gray-600">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($columns as $column)
                <label class="flex items-center space-x-2">
                    <input 
                        type="checkbox" 
                        wire:model.live="selectedColumns"
                        value="{{ $column['key'] }}"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700"
                    >
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $column['label'] }}</span>
                </label>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Refresh Options -->
    @if($enableFeatures['refresh'])
    <div x-show="showRefreshOptions" x-transition class="mb-4 p-4 bg-white dark:bg-gray-700 rounded-lg shadow border border-gray-200 dark:border-gray-600">
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Auto Refresh</label>
            <select 
                wire:model.live="refreshInterval" 
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
                @foreach($refreshOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <button 
                wire:click="refresh"
                class="mt-2 px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                Refresh Now
            </button>
        </div>
    </div>
    @endif

    <!-- Bulk Actions -->
    @if($enableFeatures['bulkActions'] && count($selectedRows) > 0)
    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-800 flex items-center justify-between">
        <div class="text-sm text-blue-800 dark:text-blue-200">
            <span class="font-medium">{{ count($selectedRows) }}</span> selected
        </div>
        <div class="flex gap-2">
            <select 
                wire:model="bulkAction"
                class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
            >
                <option value="">Bulk Actions</option>
                @foreach($bulkActions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <button 
                wire:click="applyBulkAction"
                class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                Apply
            </button>
        </div>
    </div>
    @endif

    <!-- Data Display -->
    @if($displayView === 'table')
        @include('AdvancedDataTable::livewire.partials.table-view')
    @else
        @include('AdvancedDataTable::livewire.partials.card-view')
    @endif

    <!-- Pagination -->
    @if($enableFeatures['pagination'])
        {{ $rows->links('AdvancedDataTable::livewire.partials.pagination') }}
    @endif
</div>