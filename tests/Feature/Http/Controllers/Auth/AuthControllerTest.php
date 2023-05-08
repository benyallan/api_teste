<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test if a user can authenticate with valid credentials.
     *
     * @return void
     */
    public function testUserCanAuthenticateWithValidCredentials()
    {
        $password = $this->faker->password(8);
        $user = User::factory()->create([
            'password' => $password,
        ]);

        $response = $this->postJson(URL::route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type']);
    }

    /**
     * Test if a user cannot authenticate with invalid credentials.
     *
     * @return void
     */
    public function testUserCannotAuthenticateWithInvalidCredentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson(URL::route('login'), [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }
}
