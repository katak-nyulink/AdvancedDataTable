<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($rows as $row)
        <div 
            class="bg-white dark:bg-gray-700 rounded-lg shadow border border-gray-200 dark:border-gray-600 overflow-hidden {{ $isSelected($row->id) ? 'ring-2 ring-blue-500' : '' }}"
            @if($enableFeatures['selectableRows']) 
                wire:click="selectRow('{{ $row->id }}')" 
                class="cursor-pointer"
            @endif
        >
            @if($enableFeatures['selectableRows'])
            <div class="p-2 border-b border-gray-200 dark:border-gray-600 flex justify-end">
                <input 
                    type="checkbox" 
                    wire:model="selectedRows"
                    value="{{ $row->id }}"
                    @click.stop
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700"
                >
            </div>
            @endif
            
            <div class="p-4">
                @foreach($visibleColumns as $column)
                    @if($loop->first)
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                            @if(isset($column['format']) && is_callable($column['format']))
                                {{ $column['format']($row) }}
                            @else
                                {{ $row->{$column['key']} }}
                            @endif
                        </h3>
                    @else
                        <div class="mb-1">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $column['label'] }}</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                @if(isset($column['format']) && is_callable($column['format']))
                                    {{ $column['format']($row) }}
                                @else
                                    {{ $row->{$column['key']} }}
                                @endif
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
            
            @if($enableFeatures['bulkActions'])
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-600 border-t border-gray-200 dark:border-gray-600 flex justify-end gap-2">
                <button 
                    wire:click.stop="deleteRow({{ $row->id }})"
                    class="text-sm text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                >
                    Delete
                </button>
                <button 
                    wire:click.stop="editRow({{ $row->id }})"
                    class="text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                >
                    Edit
                </button>
            </div>
            @endif
        </div>
    @empty
        <div class="col-span-full py-8 text-center text-gray-500 dark:text-gray-400">
            No records found
        </div>
    @endforelse
</div>