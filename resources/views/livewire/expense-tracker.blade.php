<div x-data="{ 
    showToast: false, 
    toastMessage: '',
    showModal: $wire.entangle('showModal'),
    monthlyData: {{ json_encode($this->monthlyChartData) }},
    categoryData: {{ json_encode($this->categoryChartData) }},
    sparklineTotalData: {{ json_encode($this->sparklineTotalData) }},
    sparklineCategoryData: {{ json_encode($this->sparklineCategoryData) }},
    sparklineMonthData: {{ json_encode($this->sparklineMonthData) }},
    sparklineWeekData: {{ json_encode($this->sparklineWeekData) }},
    monthlyChart: null,
    categoryChart: null,
    sparklineTotalChart: null,
    sparklineCategoryChart: null,
    sparklineMonthChart: null,
    sparklineWeekChart: null,
    init() {
        this.$nextTick(() => {
            this.initMonthlyChart();
            this.initCategoryChart();
            this.initSparklines();
        });
        
        $wire.on('notify', (data) => {
            this.toastMessage = data.message || 'Notification';
            this.showToast = true;
            setTimeout(() => this.showToast = false, 3000);
        });

        $wire.on('charts-updated', (data) => {
            this.monthlyData = data.monthly;
            this.categoryData = data.category;
            this.$nextTick(() => {
                this.initMonthlyChart();
                this.initCategoryChart();
                this.initSparklines();
            });
        });

        // Reinitialize charts when modal closes
        this.$watch('showModal', (value) => {
            if (!value) {
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.initMonthlyChart();
                        this.initCategoryChart();
                        this.initSparklines();
                    }, 100);
                });
            }
        });
    },
    initMonthlyChart() {
        const ctx = document.getElementById('monthlyChart');
        if (!ctx) return;
        
        if (this.monthlyChart) {
            this.monthlyChart.destroy();
            this.monthlyChart = null;
        }
        const strayMonthly = Chart.getChart(ctx);
        if (strayMonthly) strayMonthly.destroy();
        
        this.monthlyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: this.monthlyData.labels,
                datasets: [{
                    label: 'Expenses (₱)',
                    data: this.monthlyData.data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    },
    initCategoryChart() {
        const ctx = document.getElementById('categoryChart');
        if (!ctx) return;
        
        if (this.categoryChart) {
            this.categoryChart.destroy();
            this.categoryChart = null;
        }
        const strayCategory = Chart.getChart(ctx);
        if (strayCategory) strayCategory.destroy();
        
        const colors = [
            '#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', 
            '#06b6d4', '#ec4899', '#6b7280', '#ef4444'
        ];
        
        this.categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: this.categoryData.labels,
                datasets: [{
                    data: this.categoryData.data,
                    backgroundColor: colors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return label + ': ₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    },
    initSparklines() {
        const sparklineConfig = (data, color, segmentColors = null) => ({
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.data,
                    borderColor: segmentColors || color,
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                animation: false
            }
        });

        const totalCtx = document.getElementById('sparkline-total');
        if (totalCtx) {
            if (this.sparklineTotalChart) { this.sparklineTotalChart.destroy(); this.sparklineTotalChart = null; }
            const stray = Chart.getChart(totalCtx);
            if (stray) stray.destroy();
            this.sparklineTotalChart = new Chart(totalCtx, sparklineConfig(this.sparklineTotalData, '#3b82f6'));
        }

        const catCtx = document.getElementById('sparkline-category');
        if (catCtx) {
            if (this.sparklineCategoryChart) { this.sparklineCategoryChart.destroy(); this.sparklineCategoryChart = null; }
            const stray = Chart.getChart(catCtx);
            if (stray) stray.destroy();
            this.sparklineCategoryChart = new Chart(catCtx, sparklineConfig(this.sparklineCategoryData, '#10b981'));
        }

        const monthCtx = document.getElementById('sparkline-month');
        if (monthCtx) {
            if (this.sparklineMonthChart) { this.sparklineMonthChart.destroy(); this.sparklineMonthChart = null; }
            const stray = Chart.getChart(monthCtx);
            if (stray) stray.destroy();
            this.sparklineMonthChart = new Chart(monthCtx, sparklineConfig(this.sparklineMonthData, this.sparklineMonthData.color || '#3b82f6'));
        }

        const weekCtx = document.getElementById('sparkline-week');
        if (weekCtx) {
            if (this.sparklineWeekChart) { this.sparklineWeekChart.destroy(); this.sparklineWeekChart = null; }
            const stray = Chart.getChart(weekCtx);
            if (stray) stray.destroy();
            const weekColors = this.sparklineWeekData.data.map((v, i) => {
                if (i === 0) return '#6b7280';
                return v > this.sparklineWeekData.data[i - 1] ? '#10b981' : (v < this.sparklineWeekData.data[i - 1] ? '#ef4444' : '#6b7280');
            });
            this.sparklineWeekChart = new Chart(weekCtx, sparklineConfig(this.sparklineWeekData, '#3b82f6', weekColors));
        }
    }
}">
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
        <!-- Header with Add Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Expense Tracker</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Track and manage your expenses
                </p>
            </div>
            <flux:button wire:click="$set('showModal', true)" variant="primary" icon="plus">
                Add Expense
            </flux:button>
        </div>

        <!-- Summary Cards - 2 columns on desktop, stacked on mobile -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Total Expenses Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-3 sm:p-4 border border-gray-200 dark:border-zinc-700 min-w-0">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Expenses</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1 truncate">
                            ₱{{ number_format($this->totalExpenses, 2) }}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-2 sm:p-3 flex-shrink-0 ml-3">
                        <flux:icon.banknotes class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-300" />
                    </div>
                </div>
                <div class="mt-3 w-full overflow-hidden">
                    <canvas id="sparkline-total" class="w-full h-6"></canvas>
                </div>
            </div>

            <!-- Most Spent Category Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-3 sm:p-4 border border-gray-200 dark:border-zinc-700 min-w-0">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Most Spent Category</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1 truncate">
                            {{ $this->mostSpentCategory?->category ?? 'N/A' }}
                        </p>
                        @if($this->mostSpentCategory)
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">
                                ₱{{ number_format($this->mostSpentCategory->total, 2) }}
                            </p>
                        @endif
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 rounded-full p-2 sm:p-3 flex-shrink-0 ml-3">
                        <flux:icon.chart-bar class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-300" />
                    </div>
                </div>
                <div class="mt-3 w-full overflow-hidden">
                    <canvas id="sparkline-category" class="w-full h-6"></canvas>
                </div>
            </div>

            <!-- This Month's Expenses Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-3 sm:p-4 border border-gray-200 dark:border-zinc-700 min-w-0">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">This Month's Expenses</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1 truncate">
                            ₱{{ number_format($this->thisMonthExpenses, 2) }}
                        </p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ now()->format('F Y') }}
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-2 sm:p-3 flex-shrink-0 ml-3">
                        <flux:icon.calendar class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-300" />
                    </div>
                </div>
                <div class="mt-3 w-full overflow-hidden">
                    <canvas id="sparkline-month" class="w-full h-6"></canvas>
                </div>
            </div>

            <!-- This Week's Expenses Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-3 sm:p-4 border border-gray-200 dark:border-zinc-700 min-w-0">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">This Week's Expenses</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1 truncate">
                            ₱{{ number_format($this->thisWeekExpenses, 2) }}
                        </p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ now()->startOfWeek()->format('M j') }} – {{ now()->endOfWeek()->format('M j') }}
                        </p>
                    </div>
                    <div class="bg-amber-100 dark:bg-amber-900 rounded-full p-2 sm:p-3 flex-shrink-0 ml-3">
                        <flux:icon.calendar-date-range class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600 dark:text-amber-300" />
                    </div>
                </div>
                <div class="mt-3 w-full overflow-hidden">
                    <canvas id="sparkline-week" class="w-full h-6"></canvas>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="flex flex-col lg:flex-row gap-4 sm:gap-6">
            <!-- Monthly/Weekly Line Chart -->
            <div class="flex-1 bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200 dark:border-zinc-700">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $chartView === 'weekly' ? 'Weekly Expenses' : 'Monthly Expenses' }}
                    </h2>
                    <div class="flex rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden text-xs flex-shrink-0">
                        <button
                            type="button"
                            wire:click="setChartView('monthly')"
                            class="px-3 py-1.5 font-medium transition {{ $chartView === 'monthly' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-zinc-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-zinc-700' }}"
                        >
                            Monthly
                        </button>
                        <button
                            type="button"
                            wire:click="setChartView('weekly')"
                            class="px-3 py-1.5 font-medium transition border-l border-gray-200 dark:border-zinc-700 {{ $chartView === 'weekly' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-zinc-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-zinc-700' }}"
                        >
                            Weekly
                        </button>
                    </div>
                </div>
                <div class="relative w-full" style="height: 300px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Category Distribution Doughnut Chart -->
            <div class="flex-1 bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200 dark:border-zinc-700">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                        Expense Distribution by Category
                    </h2>
                    <div class="flex rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden text-xs flex-shrink-0">
                        <button
                            type="button"
                            wire:click="setCategoryRange('all')"
                            class="px-3 py-1.5 font-medium transition {{ $categoryRange === 'all' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-zinc-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-zinc-700' }}"
                        >
                            All Time
                        </button>
                        <button
                            type="button"
                            wire:click="setCategoryRange('week')"
                            class="px-3 py-1.5 font-medium transition border-l border-gray-200 dark:border-zinc-700 {{ $categoryRange === 'week' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-zinc-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-zinc-700' }}"
                        >
                            This Week
                        </button>
                    </div>
                </div>
                <div class="relative w-full flex justify-center" style="height: 300px;">
                    <canvas id="categoryChart" style="max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <flux:modal name="add-expense" wire:model="showModal">
        <flux:heading size="lg">Add New Expense</flux:heading>
        
        <flux:separator class="my-4" />
        
        <form wire:submit="save" class="space-y-4">
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
                <flux:button type="button" wire:click="closeModal" variant="ghost">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Save Expense</flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>
