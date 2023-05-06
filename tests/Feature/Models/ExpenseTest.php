<?php

namespace Tests\Feature\Models;

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
        $user = User::factory()->create();

        $expense = Expense::factory()->create([
            'description' => 'Test Expense',
            'value' => 100.00,
            'date' => '2021-01-01',
            'user_id' => $user->id,
        ]);

        $this->assertNotNull($expense->id);
        $this->assertEquals($expense->description, 'Test Expense');
        $this->assertEquals($expense->value, 100.00);
        $this->assertEquals($expense->date, '2021-01-01');
        $this->assertEquals($expense->user_id, $user->id);
    }

    public function testUpdateExpense()
    {
        $user = User::factory()->create();

        $expense = Expense::factory()->create();

        $expense->description = 'New Test Expense';
        $expense->value = 200.00;
        $expense->date = '2021-02-02';
        $expense->user_id = $user->id;
        $expense->save();

        $this->assertEquals($expense->description, 'New Test Expense');
        $this->assertEquals($expense->value, 200.00);
        $this->assertEquals($expense->date, '2021-02-02');
        $this->assertEquals($expense->user_id, $user->id);
    }

    public function testDeleteExpense()
    {
        $expense = Expense::factory()->create();

        $expense->delete();

        $this->assertSoftDeleted($expense);
    }
}
