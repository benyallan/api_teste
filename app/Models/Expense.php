<?php

namespace App\Models;

use App\Enums\ExpenseStatus;
use App\Notifications\ExpenseApprovedNotification;
use App\Notifications\ExpenseRejectedNotification;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'requester_id',
        'approver_id',
        'company_id',
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

    protected $casts = [
        'status' => ExpenseStatus::class,
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
        $this->requester->notify(new ExpenseApprovedNotification($this));
    }

    public function reject(string $reason): void
    {
        $this->status = ExpenseStatus::REJECTED;
        $this->reason_for_rejection = $reason;
        $this->approval_date = now();
        $this->approver()->associate(auth()->user());
        $this->save();
        $this->requester->notify(new ExpenseRejectedNotification($this));
    }
}
