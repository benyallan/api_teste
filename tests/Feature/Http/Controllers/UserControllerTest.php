<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const FIELDS = [
        'id',
        'cpf',
        'login',
        'email',
    ];

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            \App\Models\User::factory()->create(),
        );
    }

    public function testCanCreateUser()
    {
        $data = [
            'cpf' => $this->faker->cpf,
            'login' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        $response = $this->postJson(route('users.store'), $data);

        $response->assertCreated();
        $response->assertJsonStructure(self::FIELDS);
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);
    }

    public function testCanUpdateUser()
    {
        $user = \App\Models\User::factory()->create();
        $data = [
            'cpf' => $this->faker->cpf,
            'login' => $this->faker->userName,
            'email' => $this->faker->email,
        ];

        $response = $this->putJson(route('users.update', $user->id), $data);

        $response->assertOk();
        $response->assertJsonStructure(self::FIELDS);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $data['email'],
        ]);
    }

    public function testCanDeleteUser()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', $user->id));

        $response->assertNoContent();
        $this->assertSoftDeleted($user);
    }

    public function testCanShowUser()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->getJson(route('users.show', $user->id));

        $response->assertOk();
        $response->assertJsonStructure(self::FIELDS);
    }
}
