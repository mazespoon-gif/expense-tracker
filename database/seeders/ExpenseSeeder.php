<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenses = [
            ['description' => 'Lunch at restaurant', 'category' => 'Foods', 'amount' => 25.50],
            ['description' => 'Monthly internet bill', 'category' => 'Internet', 'amount' => 60.00],
            ['description' => 'Gas for car', 'category' => 'Transport', 'amount' => 45.00],
            ['description' => 'New textbook', 'category' => 'School', 'amount' => 120.00],
            ['description' => 'Netflix subscription', 'category' => 'Entertainment', 'amount' => 15.99],
            ['description' => 'Gaming keyboard', 'category' => 'Hobbies', 'amount' => 89.99],
            ['description' => 'Credit card payment', 'category' => 'Debt', 'amount' => 200.00],
            ['description' => 'Office supplies', 'category' => 'Miscellaneous', 'amount' => 35.75],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
}
