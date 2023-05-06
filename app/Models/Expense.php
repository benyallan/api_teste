<?php

namespace App\Models;

use App\Enums\ExpenseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'requester',
        'approver',
        'company',
        'status',
        'description',
        'value',
        'date',
        'approval_date',
        'reason_for_rejection',
    ];

    protected $dates = [
        'date',
        'approval_date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === ExpenseStatus::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === ExpenseStatus::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === ExpenseStatus::REJECTED;
    }

    public function approve(): void
    {
        $this->status = ExpenseStatus::APPROVED;
        $this->approval_date = now();
        $this->approver()->associate(auth()->user());
        $this->save();
    }

    public function reject(string $reason): void
    {
        $this->status = ExpenseStatus::REJECTED;
        $this->reason_for_rejection = $reason;
        $this->approval_date = now();
        $this->approver()->associate(auth()->user());
        $this->save();
    }
}