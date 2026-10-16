<?php

namespace App\Livewire;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseRecords extends Component
{
    use WithPagination;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $editingExpenseId = null;

    public $deletingExpenseId = null;

    // Form fields
    public $description = '';

    public $amount = '';

    public $category = '';

    // Search and sort
    public $search = '';

    public $categoryFilter = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    protected $rules = [
        'description' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'category' => 'required|string|in:School,Transport,Hobbies,Foods,Internet,Entertainment,Miscellaneous,Debt,Personal Care',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $allowed = ['description', 'category', 'amount', 'created_at'];

        if (! in_array($field, $allowed, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = $field === 'created_at' ? 'desc' : 'asc';
        }

        $this->resetPage();
    }

    public function openEditModal($expenseId)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($expenseId);

        $this->editingExpenseId = $expenseId;
        $this->description = $expense->description;
        $this->amount = $expense->amount;
        $this->category = $expense->category;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingExpenseId = null;
        $this->reset(['description', 'amount', 'category']);
        $this->resetValidation();
    }

    public function updateExpense()
    {
        $this->validate();

        $expense = Expense::where('user_id', Auth::id())->findOrFail($this->editingExpenseId);
        $expense->update([
            'description' => $this->description,
            'amount' => $this->amount,
            'category' => $this->category,
        ]);

        $this->closeEditModal();
        $this->dispatch('notify', message: 'Expense updated successfully!');
    }

    public function openDeleteModal($expenseId)
    {
        $this->deletingExpenseId = $expenseId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingExpenseId = null;
    }

    public function deleteExpense()
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($this->deletingExpenseId);
        $expense->delete();

        $this->closeDeleteModal();
        $this->dispatch('notify', message: 'Expense deleted successfully!');
    }

    public function render()
    {
        $search = trim($this->search);

        $sortField = in_array($this->sortField, ['description', 'category', 'amount', 'created_at'], true)
            ? $this->sortField
            : 'created_at';
        $sortDirection = in_array($this->sortDirection, ['asc', 'desc'], true) ? $this->sortDirection : 'desc';

        $query = Expense::where('user_id', Auth::id())
            ->when($this->categoryFilter !== '' && in_array($this->categoryFilter, Expense::$categories, true), function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('amount', 'like', "%{$search}%");
                });
            });

        // MySQL sorts ENUM columns by declaration order, so cast category
        // to a string to get a proper A->Z sort.
        if ($sortField === 'category') {
            $query->orderByRaw("CAST(category AS CHAR) {$sortDirection}");
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $expenses = $query->paginate(10);

        return view('livewire.expense-records', [
            'expenses' => $expenses,
        ]);
    }
}
