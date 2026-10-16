<?php

namespace App\Livewire;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ExpenseTracker extends Component
{
    #[Validate('required|string|max:255')]
    public $description = '';

    #[Validate('required|numeric|min:0.01')]
    public $amount = '';

    #[Validate('required|in:School,Transport,Hobbies,Foods,Internet,Entertainment,Miscellaneous,Debt,Personal Care')]
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

        $this->reset(['description', 'amount', 'category', 'showModal']);

        $this->dispatch('notify', message: 'Expense added successfully');
        $this->dispatch('expense-added');
    }

    #[Computed]
    public function totalExpenses()
    {
        return Expense::where('user_id', Auth::id())->sum('amount');
    }

    #[Computed]
    public function mostSpentCategory()
    {
        return Expense::where('user_id', Auth::id())
            ->select('category')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();
    }

    #[Computed]
    public function thisMonthExpenses()
    {
        return Expense::where('user_id', Auth::id())
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    #[Computed]
    public function monthlyChartData()
    {
        $monthExpression = DB::connection()->getDriverName() === 'sqlite'
            ? "CAST(strftime('%m', created_at) AS INTEGER)"
            : 'MONTH(created_at)';

        $data = Expense::where('user_id', Auth::id())
            ->selectRaw("{$monthExpression} as month, SUM(amount) as total")
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
    }

    #[Computed]
    public function categoryChartData()
    {
        $data = Expense::where('user_id', Auth::id())
            ->select('category')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $data->pluck('category')->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.expense-tracker');
    }
}
