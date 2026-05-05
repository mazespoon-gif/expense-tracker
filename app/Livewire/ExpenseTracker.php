<?php

namespace App\Livewire;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ExpenseTracker extends Component
{
    private function getCacheKey(string $suffix): string
    {
        return 'expenses_' . Auth::id() . '_' . $suffix;
    }

    private function invalidateCache(): void
    {
        $userId = Auth::id();
        Cache::forget('expenses_' . $userId . '_total');
        Cache::forget('expenses_' . $userId . '_most_category');
        Cache::forget('expenses_' . $userId . '_this_month');
        Cache::forget('expenses_' . $userId . '_monthly_chart');
        Cache::forget('expenses_' . $userId . '_category_chart');
    }
    #[Validate('required|string|max:255')]
    public $description = '';

    #[Validate('required|numeric|min:0.01')]
    public $amount = '';

    #[Validate('required|in:School,Transport,Hobbies,Foods,Internet,Entertainment,Miscellaneous,Debt')]
    public $category = '';

    public $showModal = false;

    public function save()
    {
        $this->validate();

        Expense::create([
            'user_id' => Auth::id(),
            'description' => $this->description,
            'category' => $this->category,
            'amount' => $this->amount,
        ]);

        $this->invalidateCache();

        $this->reset(['description', 'amount', 'category', 'showModal']);

        $this->dispatch('notify', message: 'Expense added successfully');
        $this->dispatch('expense-added');


    }

    #[Computed]
    public function totalExpenses()
    {
        return Cache::rememberForever($this->getCacheKey('total'), function () {
            return Expense::forUser(Auth::id())->sum('amount');
        });
    }

    #[Computed]
    public function mostSpentCategory()
    {
        return Cache::rememberForever($this->getCacheKey('most_category'), function () {
            return Expense::forUser(Auth::id())
                ->select('category')
                ->selectRaw('SUM(amount) as total')
                ->groupBy('category')
                ->orderByDesc('total')
                ->first();
        });
    }

    #[Computed]
    public function thisMonthExpenses()
    {
        return Cache::rememberForever($this->getCacheKey('this_month'), function () {
            return Expense::forUser(Auth::id())
                ->currentMonth()
                ->sum('amount');
        });
    }

    #[Computed]
    public function monthlyChartData()
    {
        return Cache::rememberForever($this->getCacheKey('monthly_chart'), function () {
            $data = Expense::forUser(Auth::id())
                ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $months = [];
            $totals = [];

            for ($i = 1; $i <= 12; $i++) {
                $months[] = date('M', mktime(0, 0, 0, $i, 1));
                $totals[] = $data[$i] ?? 0;
            }

            return [
                'labels' => $months,
                'data' => $totals,
            ];
        });
    }

    #[Computed]
    public function categoryChartData()
    {
        return Cache::rememberForever($this->getCacheKey('category_chart'), function () {
            $data = Expense::forUser(Auth::id())
                ->select('category')
                ->selectRaw('SUM(amount) as total')
                ->groupBy('category')
                ->orderByDesc('total')
                ->get();

            return [
                'labels' => $data->pluck('category')->toArray(),
                'data' => $data->pluck('total')->toArray(),
            ];
        });
    }

    public function render()
    {
        return view('livewire.expense-tracker');
    }
}
