<?php

namespace App\Livewire;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseRecords extends Component
{
    use WithPagination;

    private function invalidateCache(): void
    {
        $userId = Auth::id();
        Cache::forget('expenses_' . $userId . '_total');
        Cache::forget('expenses_' . $userId . '_most_category');
        Cache::forget('expenses_' . $userId . '_this_month');
        Cache::forget('expenses_' . $userId . '_monthly_chart');
        Cache::forget('expenses_' . $userId . '_category_chart');
    }

    public $showEditModal = false;
    public $showDeleteModal = false;
    public $editingExpenseId = null;
    public $deletingExpenseId = null;
    
    // Form fields
    public $description = '';
    public $amount = '';
    public $category = '';

    protected $rules = [
        'description' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'category' => 'required|string|in:School,Transport,Hobbies,Foods,Internet,Entertainment,Miscellaneous,Debt',
    ];

    public function openEditModal($expenseId)
    {
        $expense = Expense::forUser(Auth::id())->findOrFail($expenseId);
        
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

        $expense = Expense::forUser(Auth::id())->findOrFail($this->editingExpenseId);
        $expense->update([
            'description' => $this->description,
            'amount' => $this->amount,
            'category' => $this->category,
        ]);

        $this->invalidateCache();
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
        $expense = Expense::forUser(Auth::id())->findOrFail($this->deletingExpenseId);
        $expense->delete();

        $this->invalidateCache();
        $this->closeDeleteModal();
        $this->dispatch('notify', message: 'Expense deleted successfully!');
    }

    public function render()
    {
        $expenses = Expense::forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.expense-records', [
            'expenses' => $expenses,
        ]);
    }
}
