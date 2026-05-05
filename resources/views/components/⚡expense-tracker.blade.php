<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Expense;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use ArielMejiaDev\LarapexChart\Facades\LarapexChart;

class ExpenseTracker extends Component
{
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
            'description' => $this->description,
            'category' => $this->category,
            'amount' => $this->amount,
        ]);

        $this->reset(['description', 'amount', 'category', 'showModal']);
        
        $this->dispatch('expense-added');
        $this->dispatch('open-toast', [
            'message' => 'Expense added successfully',
            'type' => 'success'
        ]);
    }

    #[Computed]
    public function totalExpenses()
    {
        return Expense::sum('amount');
    }

    #[Computed]
    public function mostSpentCategory()
    {
        return Expense::select('category')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();
    }

    #[Computed]
    public function thisMonthExpenses()
    {
        return Expense::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    #[Computed]
    public function monthlyExpensesChart()
    {
        $data = Expense::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = [];
        $totals = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('F', mktime(0, 0, 0, $i, 1));
            $totals[] = $data[$i] ?? 0;
        }

        return LarapexChart::lineChart()
            ->setTitle('Monthly Expenses')
            ->setXAxis($months)
            ->setDataset([
                [
                    'name' => 'Expenses',
                    'data' => $totals,
                ]
            ])
            ->setOptions([
                'responsive' => true,
                'maintainAspectRatio' => false,
            ]);
    }

    #[Computed]
    public function categoryDistributionChart()
    {
        $data = Expense::select('category')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $categories = array_keys($data);
        $totals = array_values($data);

        return LarapexChart::pieChart()
            ->setTitle('Expense Distribution by Category')
            ->setLabels($categories)
            ->setDataset($totals)
            ->setOptions([
                'responsive' => true,
                'maintainAspectRatio' => false,
            ]);
    }

    public function render()
    {
        return view('livewire.expense-tracker');
    }
};
?>

<div>
    {{-- It is not the man who has too little, but the man who craves more, that is poor. - Seneca --}}
</div>