<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'category',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static $categories = [
        'School',
        'Transport',
        'Hobbies',
        'Foods',
        'Internet',
        'Entertainment',
        'Miscellaneous',
        'Debt',
        'Personal Care',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
