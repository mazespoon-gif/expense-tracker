<?php

namespace App\Livewire;

use App\Events\ExpenseAdded;
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

    #[Validate('required|in:School,Transport,Hobbies,Foods,Internet,Entertainment,Miscellaneous,Debt,Personal Care,Drinks')]
    public $category = '';

    public $showModal = false;

    public $chartView = 'monthly';

    public $categoryRange = 'all';

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

        try {
            event(new ExpenseAdded((int) Auth::id()));
        } catch (\Throwable $e) {
            report($e);
        }

        $this->dispatch('notify', message: 'Expense added successfully');
    }

    public function refreshCharts()
    {
        $this->dispatchCharts();
    }

    public function setChartView($view)
    {
        $this->chartView = in_array($view, ['monthly', 'weekly'], true) ? $view : 'monthly';

        $this->dispatchCharts();
    }

    public function setCategoryRange($range)
    {
        $this->categoryRange = in_array($range, ['all', 'week'], true) ? $range : 'all';

        $this->dispatchCharts();
    }

    public function closeModal()
    {
        $this->reset(['description', 'amount', 'category']);
        $this->showModal = false;
    }

    private function dispatchCharts(): void
    {
        $this->dispatch(
            'charts-updated',
            monthly: $this->monthlyChartData,
            category: $this->categoryChartData,
        );
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
    public function thisWeekExpenses()
    {
        return Expense::where('user_id', Auth::id())
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');
    }

    #[Computed]
    public function monthlyChartData()
    {
        if ($this->chartView === 'weekly') {
            return $this->buildWeeklyChartData();
        }

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

    private function buildWeeklyChartData(): array
    {
        $start = now()->startOfWeek();

        $data = Expense::where('user_id', Auth::id())
            ->whereBetween('created_at', [$start, now()->endOfWeek()])
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $labels = [];
        $totals = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $start->copy()->addDays($i);

            $labels[] = $day->format('D');
            $totals[] = (float) ($data[$day->format('Y-m-d')] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $totals,
        ];
    }

    #[Computed]
    public function categoryChartData()
    {
        $query = Expense::where('user_id', Auth::id());

        if ($this->categoryRange === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        $data = $query->select('category')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $data->pluck('category')->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ];
    }

    #[Computed]
    public function sparklineWeekData()
    {
        $start = now()->subDays(6)->startOfDay();
        $end = now()->endOfDay();

        $data = Expense::where('user_id', Auth::id())
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $labels = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $labels[] = $day->format('D');
            $totals[] = (float) ($data[$day->format('Y-m-d')] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $totals,
        ];
    }

    #[Computed]
    public function sparklineMonthData()
    {
        $start = now()->startOfMonth();
        $end = now()->endOfDay();

        $data = Expense::where('user_id', Auth::id())
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $labels = [];
        $totals = [];

        for ($i = 1; $i <= now()->daysInMonth; $i++) {
            $day = now()->setDay($i);
            $labels[] = (string) $i;
            $totals[] = (float) ($data[$day->format('Y-m-d')] ?? 0);
        }

        $prevStart = now()->subMonth()->startOfMonth();
        $prevEnd = now()->subMonth()->endOfMonth();
        $prevTotal = Expense::where('user_id', Auth::id())
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('amount');

        $currentTotal = array_sum($totals);
        $color = $currentTotal >= $prevTotal ? '#3b82f6' : '#ef4444';

        return [
            'labels' => $labels,
            'data' => $totals,
            'color' => $color,
        ];
    }

    #[Computed]
    public function sparklineTotalData()
    {
        return $this->sparklineWeekData;
    }

    #[Computed]
    public function sparklineCategoryData()
    {
        $topCategory = Expense::where('user_id', Auth::id())
            ->select('category')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first()?->category;

        if (! $topCategory) {
            return [
                'labels' => [],
                'data' => [],
                'color' => '#10b981',
                'category' => null,
            ];
        }

        $start = now()->subDays(6)->startOfDay();
        $end = now()->endOfDay();

        $data = Expense::where('user_id', Auth::id())
            ->where('category', $topCategory)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $labels = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $labels[] = $day->format('D');
            $totals[] = (float) ($data[$day->format('Y-m-d')] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $totals,
            'color' => '#10b981',
            'category' => $topCategory,
        ];
    }

    public function render()
    {
        return view('livewire.expense-tracker');
    }
}
