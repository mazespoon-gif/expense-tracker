<?php

namespace App\Livewire;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Calendar extends Component
{
    public $selectedDate = null;

    public $viewMonth;

    public $viewYear;

    public $monthName;

    public $showMonthPicker = false;

    public $showYearPicker = false;

    public $calendarWeeks = [];

    public $dayExpenses = [];

    public $dayTotal = 0;

    public function mount()
    {
        $this->viewMonth = now()->month;
        $this->viewYear = now()->year;
        $this->refreshCalendar();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->showMonthPicker = false;
        $this->showYearPicker = false;
        $this->refreshDayData();
    }

    public function clearSelection()
    {
        $this->selectedDate = null;
        $this->showMonthPicker = false;
        $this->showYearPicker = false;
        $this->refreshDayData();
    }

    public function prevMonth()
    {
        $this->viewMonth--;
        if ($this->viewMonth < 1) {
            $this->viewMonth = 12;
            $this->viewYear--;
        }
        $this->selectedDate = null;
        $this->showMonthPicker = false;
        $this->showYearPicker = false;
        $this->refreshCalendar();
    }

    public function nextMonth()
    {
        $this->viewMonth++;
        if ($this->viewMonth > 12) {
            $this->viewMonth = 1;
            $this->viewYear++;
        }
        $this->selectedDate = null;
        $this->showMonthPicker = false;
        $this->showYearPicker = false;
        $this->refreshCalendar();
    }

    public function toggleMonthPicker()
    {
        $this->showMonthPicker = ! $this->showMonthPicker;
        $this->showYearPicker = false;
    }

    public function toggleYearPicker()
    {
        $this->showYearPicker = ! $this->showYearPicker;
        $this->showMonthPicker = false;
    }

    public function closeMonthPicker()
    {
        $this->showMonthPicker = false;
        $this->showYearPicker = false;
    }

    public function setMonth($month)
    {
        $this->viewMonth = (int) $month;
        $this->selectedDate = null;
        $this->showMonthPicker = false;
        $this->showYearPicker = false;
        $this->refreshCalendar();
    }

    public function setYear($year)
    {
        $this->viewYear = (int) $year;
        $this->selectedDate = null;
        $this->showMonthPicker = false;
        $this->showYearPicker = false;
        $this->refreshCalendar();
    }

    private function refreshCalendar(): void
    {
        $this->monthName = \Carbon\Carbon::create($this->viewYear, $this->viewMonth, 1)->format('F Y');
        $this->calendarWeeks = $this->buildWeeks();
        $this->refreshDayData();
    }

    private function refreshDayData(): void
    {
        if (! $this->selectedDate) {
            $this->dayExpenses = [];
            $this->dayTotal = 0;
            return;
        }

        $expenses = Expense::where('user_id', Auth::id())
            ->whereDate('created_at', $this->selectedDate)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'description', 'amount', 'category', 'created_at']);

        $this->dayExpenses = $expenses->toArray();
        $this->dayTotal = $expenses->sum('amount');
    }

    private function buildWeeks(): array
    {
        $firstDay = \Carbon\Carbon::create($this->viewYear, $this->viewMonth, 1);
        $lastDay = $firstDay->copy()->endOfMonth();

        $expenseDates = Expense::where('user_id', Auth::id())
            ->whereBetween('created_at', [$firstDay->startOfDay(), $lastDay->endOfDay()])
            ->get()
            ->map(fn($e) => $e->created_at->format('Y-m-d'))
            ->toArray();

        $start = $firstDay->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $end = $lastDay->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

        $weeks = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $day = $current->copy()->addDays($i);
                $dayDate = $day->format('Y-m-d');
                $week[] = [
                    'date' => $dayDate,
                    'day_number' => $day->day,
                    'is_current_month' => $day->month === $this->viewMonth && $day->year === $this->viewYear,
                    'is_selected' => $this->selectedDate === $dayDate,
                    'is_today' => $day->isToday(),
                    'has_expense' => in_array($dayDate, $expenseDates),
                    'display_name' => $day->format('D'),
                ];
            }
            $weeks[] = $week;
            $current->addDays(7);
        }

        return $weeks;
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}
