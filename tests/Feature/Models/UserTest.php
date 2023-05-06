<?php

namespace Tests\Feature\Models;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateUser()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'login' => 'test',
            'password' => 'password',
            'cpf' => '12345678900',
        ]);

        $this->assertNotNull($user->id);
        $this->assertEquals($user->email, 'test@example.com');
        $this->assertEquals($user->login, 'test');
        $this->assertNotEquals($user->password, 'password');
        $this->assertEquals($user->cpf, '12345678900');
    }

    public function testUpdateUser()
    {
        $user = User::factory()->create();

        $user->email = 'new-email@example.com';
        $user->login = 'new-login';
        $user->cpf = '98765432100';
        $user->save();

        $this->assertEquals($user->email, 'new-email@example.com');
        $this->assertEquals($user->login, 'new-login');
        $this->assertEquals($user->cpf, '98765432100');
    }

    public function testDeleteUser()
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertSoftDeleted($user);
    }

    public function testUserCanBelongToCompany()
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        $user->company()->associate($company);
        $user->save();

        $this->assertEquals($user->company_id, $company->id);
    }
}
