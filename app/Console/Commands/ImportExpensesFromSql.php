<?php

namespace App\Console\Commands;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Console\Command;

class ImportExpensesFromSql extends Command
{
    protected $signature = 'expenses:import-from-sql';

    protected $description = 'Import expenses data from expenses_tracker.sql file for mazespoon@gmail.com';

    public function handle()
    {
        $this->info('Starting import process...');

        // Find the user
        $user = User::where('email', 'mazespoon@gmail.com')->first();
        if (! $user) {
            $this->error('User mazespoon@gmail.com not found!');

            return 1;
        }

        $this->info("Found user: {$user->name} (ID: {$user->id})");

        // Step 1: Delete all existing expenses for this user only
        $this->info('Deleting existing expenses for this user...');
        Expense::forUser($user->id)->delete();
        $this->info('Existing data cleared.');

        // Step 2: Read and parse SQL file
        $sqlFile = base_path('expenses_tracker.sql');
        if (! file_exists($sqlFile)) {
            $this->error('SQL file not found: '.$sqlFile);

            return 1;
        }

        $sqlContent = file_get_contents($sqlFile);

        // Parse INSERT statements
        preg_match_all(
            "/INSERT INTO `expenses` \(`id`, `descriptions`, `category`, `amount`, `dates`\) VALUES\s+(.+?);/s",
            $sqlContent,
            $matches
        );

        if (empty($matches[1])) {
            $this->error('No INSERT statements found in SQL file');

            return 1;
        }

        // Step 3: Process and insert data
        $count = 0;
        $valuesString = $matches[1][0];

        // Split individual value rows
        preg_match_all('/\((\d+),\s*\'([^\']*)\',\s*\'([^\']*)\',\s*(\d+),\s*\'([^\']*)\'\)/', $valuesString, $rows, PREG_SET_ORDER);

        $validCategories = Expense::$categories;
        $insertData = [];

        foreach ($rows as $row) {
            $description = $row[2];
            $category = $row[3];
            $amount = $row[4];
            $date = $row[5];

            // Validate category exists in our allowed list
            if (! in_array($category, $validCategories)) {
                $this->warn("Skipping invalid category '{$category}' for: {$description}");

                continue;
            }

            $insertData[] = [
                'user_id' => $user->id,
                'description' => $description,
                'category' => $category,
                'amount' => $amount,
                'created_at' => $date,
                'updated_at' => $date,
            ];

            $count++;

            if (count($insertData) >= 500) {
                Expense::insert($insertData);
                $insertData = [];
            }
        }

        if (! empty($insertData)) {
            Expense::insert($insertData);
        }

        $this->info("Successfully imported {$count} expenses for {$user->email}!");
        $this->info('Import complete.');

        return 0;
    }
}
