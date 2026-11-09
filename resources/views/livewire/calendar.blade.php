<div class="space-y-6">
    <!-- Calendar Header -->
    <div class="flex items-center justify-between bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 p-4 sm:p-6">
        <div>
            <button
                wire:click="prevMonth"
                class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-zinc-700 rounded-md transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span class="hidden sm:inline">Prev</span>
            </button>
        </div>

        <div class="text-center group relative" wire:click="toggleMonthPicker">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
                {{ $monthName }}
            </h2>

            <!-- Month Picker Dropdown -->
            <div class="absolute z-50 mt-2 w-60 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-gray-200 dark:border-zinc-700 p-2
                @if(!$showMonthPicker && !$showYearPicker) hidden @endif">
                <div class="p-2">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100 dark:border-zinc-700">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Change Month</span>
                        <button
                            wire:click.stop="toggleYearPicker"
                            class="text-xs font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                        >
                            Choose Year
                        </button>
                    </div>

                    <div class="grid grid-cols-4 gap-1">
                        @php
                            $months = [
                                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                                9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec',
                            ];
                        @endphp
                        @foreach($months as $num => $name)
                            <button
                                wire:click.stop="setMonth({{ $num }})"
                                class="px-2 py-1.5 text-sm rounded-md text-center
                                    @if($viewMonth == $num)
                                        bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white
                                    @else
                                        text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700 @endif
                                "
                            >
                                {{ $name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Year Picker Dropdown -->
            <div class="absolute z-50 mt-2 w-60 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-gray-200 dark:border-zinc-700 p-2
                @if(!$showYearPicker) hidden @endif">
                <div class="p-2">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100 dark:border-zinc-700">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Change Year</span>
                        <button
                            wire:click.stop="toggleMonthPicker"
                            class="text-xs font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                        >
                            Choose Month
                        </button>
                    </div>

                    <div class="grid grid-cols-3 gap-1 max-h-60 overflow-y-auto">
                        @php
                            $startYear = $viewYear - 5;
                            $endYear = $viewYear + 5;
                        @endphp
                        @for($y = $endYear; $y >= $startYear; $y--)
                            <button
                                wire:click.stop="setYear({{ $y }})"
                                class="px-2 py-1.5 text-sm rounded-md text-center
                                    @if($viewYear == $y)
                                        bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white
                                    @else
                                        text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700 @endif
                                "
                            >
                                {{ $y }}
                            </button>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div>
            <button
                wire:click="nextMonth"
                class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-zinc-700 rounded-md transition-colors"
            >
                <span class="hidden sm:inline">Next</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 p-4 sm:p-6">
        <div class="grid grid-cols-7 text-center mb-3 border-b border-gray-200 dark:border-zinc-700 pb-2">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $day }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-0.5">
            @foreach($calendarWeeks as $week)
                @foreach($week as $day)
                    <button
                        wire:click="selectDate('{{ $day['date'] }}')"
                        class="h-20 w-full flex flex-col items-center justify-center text-sm font-medium rounded-lg transition-all duration-150 border border-transparent
                            @if(!$day['is_current_month'])
                                text-gray-300 dark:text-zinc-600 hover:bg-transparent border-transparent
                            @elseif($day['is_selected'])
                                bg-gray-100 dark:bg-zinc-700 text-gray-900 dark:text-white border border-gray-200 dark:border-zinc-600 hover:bg-gray-200 dark:hover:bg-zinc-600
                            @elseif($day['is_today'])
                                bg-gray-50 dark:bg-zinc-800/30 text-gray-900 dark:text-white font-bold border border-gray-200 dark:border-zinc-700 relative
                            @elseif($day['has_expense'])
                                bg-amber-50 dark:bg-amber-900/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 hover:bg-gray-50 dark:hover:bg-zinc-700
                            @else
                                text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-zinc-700 border-transparent @endif
                        "
                    >
                        <span class="block leading-none text-gray-900 dark:text-white">{{ $day['day_number'] }}</span>
                        @if($day['has_expense'] && !$day['is_selected'])
                            <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-amber-500 dark:bg-amber-400 rounded-full"></span>
                        @endif
                    </button>
                @endforeach
            @endforeach
        </div>
    </div>

    @if($selectedDate)
        <!-- Expense List Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 p-4 sm:p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Expenses for {{ $selectedDate }}</h2>
                <button
                    wire:click="clearSelection"
                    class="flex items-center gap-1.5 text-sm font-medium text-gray-400 hover:text-gray-600 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-zinc-700 rounded-md px-3 py-1.5 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Clear
                </button>
            </div>

            @if(count($dayExpenses) > 0)
                <div class="space-y-2">
                    @foreach($dayExpenses as $expense)
                        <div class="flex items-center justify-between p-3 border-b border-gray-100 dark:border-zinc-700 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $expense['description'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ $expense['category'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    ₱{{ number_format($expense['amount'], 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No expenses for {{ $selectedDate }}</p>
                </div>
            @endif
        </div>

        <!-- Daily Total Card -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg shadow-sm p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-200 uppercase tracking-wider">Total Amount for the Day</h3>
                    <p class="text-xs text-blue-600 dark:text-blue-300 uppercase tracking-wider mt-1">{{ $selectedDate }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400">
                        ₱{{ number_format($dayTotal, 2) }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
