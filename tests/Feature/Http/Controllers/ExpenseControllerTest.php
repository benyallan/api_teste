<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const FIELDS = [
        'id',
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

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            \App\Models\User::factory()->create(),
        );
    }

    public function testCanCreateExpense()
    {
        $expense = \App\Models\Expense::factory()->make();

        $response = $this->postJson(
            route('expenses.store'),
            [
                'description' => $expense->description,
                'value' => $expense->value,
                'date' => $expense->date,
            ]
        );

        $response->assertCreated();
        $response->assertJsonStructure(self::FIELDS);
        $this->assertDatabaseHas('expenses', [
            'description' => $expense->description,
            'value' => $expense->value,
            'date' => $expense->date,
        ]);
    }

    public function testCanUpdateExpense()
    {
        $expense = \App\Models\Expense::factory()->create();

        $response = $this->putJson(
            route('expenses.update', $expense->id),
            [
                'description' => $expense->description,
                'value' => $expense->value,
                'date' => $expense->date,
            ]
        );

        $response->assertOk();
        $response->assertJsonStructure(self::FIELDS);
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'description' => $expense->description,
            'value' => $expense->value,
            'date' => $expense->date,
        ]);
    }

    public function testCanDeleteExpense()
    {
        $expense = \App\Models\Expense::factory()->create();

        $response = $this->deleteJson(route('expenses.destroy', $expense->id));

        $response->assertNoContent();
        $this->assertSoftDeleted($expense);
    }

    public function testCanShowExpense()
    {
        $expense = \App\Models\Expense::factory()->create();

        $response = $this->getJson(route('expenses.show', $expense->id));

        $response->assertOk();
        $response->assertJsonStructure(self::FIELDS);
    }

    public function testCanApproveExpense()
    {
        $expense = \App\Models\Expense::factory()->create();

        $response = $this->postJson(route('expenses.approve', $expense->id));

        $response->assertOk();
        $response->assertJsonStructure(self::FIELDS);
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'status' => \App\Enums\ExpenseStatus::APPROVED,
        ]);
    }

    public function testCanRejectExpense()
    {
        $expense = \App\Models\Expense::factory()->create();

        $response = $this->postJson(
            route('expenses.reject', $expense->id),
            [
                'reason_for_rejection' => $this->faker->sentence,
            ]
        );

        $response->assertOk();
        $response->assertJsonStructure(self::FIELDS);
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'status' => \App\Enums\ExpenseStatus::REJECTED,
        ]);
    }

    public function testCanListExpenses()
    {
        \App\Models\Expense::factory()
            ->count(3)
            ->forUser(Auth::user())
            ->create();

        $response = $this->getJson(route('expenses.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => self::FIELDS,
        ]);
        $response->assertJsonCount(3);
    }
}
