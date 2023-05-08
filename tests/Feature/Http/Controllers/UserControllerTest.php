<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCanCreateUser()
    {
        Sanctum::actingAs(
            \App\Models\User::factory()->create(),
        );
        $data = [
            'cpf' => $this->faker->cpf,
            'login' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        $response = $this->postJson(route('users.store'), $data);

        $response->assertCreated();
        $response->assertJsonStructure(['id', 'cpf', 'login', 'email']);
    }

    public function testCanUpdateUser()
    {
        Sanctum::actingAs(
            \App\Models\User::factory()->create(),
        );
        $user = \App\Models\User::factory()->create();
        $data = [
            'cpf' => $this->faker->cpf,
            'login' => $this->faker->userName,
            'email' => $this->faker->email,
        ];

        $response = $this->putJson(route('users.update', $user->id), $data);

        $response->assertOk();
        $response->assertJsonStructure(['id', 'cpf', 'login', 'email']);
    }

    public function testCanDeleteUser()
    {
        Sanctum::actingAs(
            \App\Models\User::factory()->create(),
        );
        $user = \App\Models\User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', $user->id));

        $response->assertNoContent();
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function testCanShowUser()
    {
        Sanctum::actingAs(
            \App\Models\User::factory()->create(),
        );
        $user = \App\Models\User::factory()->create();

        $response = $this->getJson(route('users.show', $user->id));

        $response->assertOk();
        $response->assertJsonStructure(['id', 'cpf', 'login', 'email']);
    }
}
