<?php

namespace Tests\Feature\Models;

use App\Enums\ExpenseStatus;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Expense;
use App\Models\User;

class ExpenseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateExpense()
    {
        $date = now();
        $requester = User::factory()->create();
        $approver = User::factory()->create();

        $expense = Expense::factory()->create([
            'requester_id' => $requester->id,
            'approver_id' => $approver->id,
            'company_id' => $requester->company->id,
            'value' => 100.00,
            'date' => $date,
            'status' => ExpenseStatus::REJECTED,
            'approval_date' => $date,
            'reason_for_rejection' => 'reason for rejection',
        ]);

        $this->assertNotNull($expense->id);
        $this->assertEquals($expense->requester_id, $requester->id);
        $this->assertEquals($expense->approver_id, $approver->id);
        $this->assertEquals($expense->company_id, $requester->company->id);
        $this->assertEquals($expense->value, 100.00);
        $this->assertEquals($expense->date, $date);
        $this->assertEquals($expense->status, ExpenseStatus::REJECTED);
        $this->assertNotNull($expense->approval_date);
        $this->assertEquals($expense->reason_for_rejection, 'reason for rejection');
    }

    public function testUpdateExpense()
    {
        $expense = Expense::factory()->create();

        $expense->update([
            'value' => 200.00,
            'status' => ExpenseStatus::APPROVED,
        ]);

        $this->assertEquals($expense->value, 200.00);
        $this->assertEquals($expense->status, ExpenseStatus::APPROVED);
    }

    public function testDeleteExpense()
    {
        $expense = Expense::factory()->create();

        $expense->delete();

        $this->assertSoftDeleted($expense);
    }

    public function testApproveExpense()
    {
        $expense = Expense::factory()->create();

        $expense->approve();

        $this->assertEquals($expense->status, ExpenseStatus::APPROVED);
        $this->assertNotNull($expense->approval_date);
    }

    public function testRejectExpense()
    {
        $expense = Expense::factory()->create();

        $expense->reject('reason for rejection');

        $this->assertEquals($expense->status, ExpenseStatus::REJECTED);
        $this->assertNotNull($expense->approval_date);
        $this->assertEquals($expense->reason_for_rejection, 'reason for rejection');
    }
}
