<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'book_id', 'status', 'borrow_date', 'due_date', 'return_date', 'duration_days'];

    protected $casts = [
        'borrow_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function getLateDaysAttribute(): int
    {
        $compareDate = $this->return_date ? $this->return_date : Carbon::today();

        if ($compareDate->greaterThan($this->due_date)) {
            return $this->due_date->diffInDays($compareDate);
        }

        return 0;
    }

    public function getFineAmountAttribute(): int
    {
        $dailyFine = 5000;
        return $this->late_days * $dailyFine;
    }
}
