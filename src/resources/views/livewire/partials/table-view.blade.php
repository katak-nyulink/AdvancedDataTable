<div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                @if($enableFeatures['selectableRows'])
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    <input 
                        type="checkbox" 
                        wire:model="selectPage"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700"
                    >
                </th>
                @endif
                
                @foreach($visibleColumns as $column)
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    <div class="flex items-center">
                        <span>{{ $column['label'] }}</span>
                        @if($enableFeatures['sorting'] && ($column['sortable'] ?? true))
                            <button wire:click="sortBy('{{ $column['key'] }}')" class="ml-1 focus:outline-none">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </th>
                @endforeach
                
                @if($enableFeatures['bulkActions'])
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Actions
                </th>
                @endif
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($rows as $row)
                <tr 
                    class="{{ $isSelected($row->id) ? 'bg-blue-50 dark:bg-blue-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                    @if($enableFeatures['selectableRows']) 
                        wire:click="selectRow('{{ $row->id }}')" 
                        class="cursor-pointer"
                    @endif
                >
                    @if($enableFeatures['selectableRows'])
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input 
                            type="checkbox" 
                            wire:model="selectedRows"
                            value="{{ $row->id }}"
                            @click.stop
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700"
                        >
                    </td>
                    @endif
                    
                    @foreach($visibleColumns as $column)
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if(isset($column['format']) && is_callable($column['format']))
                            {{ $column['format']($row) }}
                        @else
                            {{ $row->{$column['key']} }}
                        @endif
                    </td>
                    @endforeach
                    
                    @if($enableFeatures['bulkActions'])
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button 
                            wire:click.stop="deleteRow({{ $row->id }})"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 mr-3"
                        >
                            Delete
                        </button>
                        <button 
                            wire:click.stop="editRow({{ $row->id }})"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                        >
                            Edit
                        </button>
                    </td>
                    @endif
                </tr>
                
                @if($enableNestedView && $showNestedView)
                <tr>
                    <td colspan="{{ count($visibleColumns) + ($enableFeatures['selectableRows'] ? 1 : 0) + ($enableFeatures['bulkActions'] ? 1 : 0) }}">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700">
                            <!-- Nested content here -->
                            <div class="pl-8">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Details</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Created At</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $row->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Updated At</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $row->updated_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ count($visibleColumns) + ($enableFeatures['selectableRows'] ? 1 : 0) + ($enableFeatures['bulkActions'] ? 1 : 0) }}" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        No records found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>