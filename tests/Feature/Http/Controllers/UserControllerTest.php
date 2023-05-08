<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIfCreateUser()
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
}
