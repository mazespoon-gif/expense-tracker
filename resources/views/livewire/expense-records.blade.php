<div x-data="{
    showToast: false,
    toastMessage: ''
}" x-init="
    $wire.on('notify', (data) => {
        toastMessage = data.message || 'Notification';
        showToast = true;
        setTimeout(() => showToast = false, 3000);
    });
">
    <!-- Toast Notification - Top Right -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-100%]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-100%]"
         class="fixed top-4 right-4 z-[100]"
         style="display: none;">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span x-text="toastMessage"></span>
        </div>
    </div>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Expense Records</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View all your expense entries
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-gray-200 dark:border-zinc-700">
            <flux:table :paginate="$expenses">
                <flux:table.columns>
                    <flux:table.column>Description</flux:table.column>
                    <flux:table.column>Category</flux:table.column>
                    <flux:table.column>Amount</flux:table.column>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column class="[--align:start]">Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($expenses as $expense)
                        <flux:table.row :key="$expense->id">
                            <flux:table.cell class="font-medium text-gray-900 dark:text-white" style="padding-left: 10px;">
                                {{ $expense->description }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge 
                                    size="sm" 
                                    :color="
                                        match($expense->category) {
                                            'School' => 'blue',
                                            'Transport' => 'green',
                                            'Hobbies' => 'purple',
                                            'Foods' => 'orange',
                                            'Internet' => 'cyan',
                                            'Entertainment' => 'pink',
                                            'Miscellaneous' => 'zinc',
                                            'Debt' => 'red',
                                            'Personal Care' => 'yellow',    
                                            default => 'zinc'
                                        }
                                    "
                                    inset="top bottom"
                                >
                                    {{ $expense->category }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell variant="strong">
                                ₱{{ number_format($expense->amount, 2) }}
                            </flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">
                                {{ $expense->created_at->format('M d, Y') }}
                            </flux:table.cell>
                            <flux:table.cell class="[--align:start]">
                                <div class="flex items-center justify-start gap-2">
                                    <flux:button wire:click="openEditModal({{ $expense->id }})" variant="ghost" size="sm" icon="pencil" inset="top bottom">
                                        <span class="hidden sm:inline">Edit</span>
                                    </flux:button>
                                    <flux:button wire:click="openDeleteModal({{ $expense->id }})" variant="ghost" size="sm" icon="trash" inset="top bottom" class="text-red-600 hover:text-sky-700">
                                        <span class="hidden sm:inline">Delete</span>
                                    </flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <flux:icon.document-text class="w-8 h-8 text-gray-300 dark:text-gray-600 mb-2" />
                                    <p class="text-base font-medium text-gray-900 dark:text-white">No expenses found</p>
                                    <p class="text-sm mt-1 text-gray-500 dark:text-gray-400">Start by adding your first expense from the dashboard.</p>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>

    <!-- Edit Expense Modal -->
    <flux:modal name="edit-expense" wire:model="showEditModal">
        <flux:heading size="lg">Edit Expense</flux:heading>
        
        <flux:separator class="my-4" />
        
        <form wire:submit="updateExpense" class="space-y-4">
            <flux:field>
                <flux:label>Description</flux:label>
                <flux:input 
                    wire:model="description" 
                    placeholder="Enter expense description"
                />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>Amount</flux:label>
                <flux:input 
                    wire:model="amount" 
                    type="number" 
                    step="0.01" 
                    placeholder="Enter amount"
                />
                <flux:error name="amount" />
            </flux:field>

            <flux:field>
                <flux:label>Category</flux:label>
                <flux:select wire:model="category" placeholder="Select a category">
                    <option value="">Select a category</option>
                    @foreach(App\Models\Expense::$categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="category" />
            </flux:field>

            <flux:separator class="my-4" />

            <div class="flex justify-end gap-2">
                <flux:button type="button" wire:click="closeEditModal" variant="ghost">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Update Expense</flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-expense" wire:model="showDeleteModal">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                <svg class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <flux:heading size="lg">Delete Expense</flux:heading>
            
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Are you sure you want to delete this expense? This action cannot be undone.
            </p>

            <div class="mt-6 flex justify-center gap-3">
                <flux:button wire:click="closeDeleteModal" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="deleteExpense" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
