<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const FIELDS = [
        'id',
        'cnpj',
        'business_name',
        'corporate_name',
    ];

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            \App\Models\User::factory()->create(),
        );
    }

    public function testCanCreateCompany()
    {
        $data = [
            'cnpj' => $this->faker->cnpj,
            'business_name' => $this->faker->company,
            'corporate_name' => $this->faker->company,
        ];

        $response = $this->postJson(route('companies.store'), $data);

        $response->assertCreated();
        $response->assertJsonStructure(self::FIELDS);
        $this->assertDatabaseHas('companies', [
            'business_name' => $data['business_name'],
            'corporate_name' => $data['corporate_name'],
        ]);
    }

    public function testCanUpdateCompany()
    {
        $company = \App\Models\Company::factory()->create();
        $data = [
            'cnpj' => $this->faker->cnpj,
            'business_name' => $this->faker->company,
            'corporate_name' => $this->faker->company,
        ];

        $response = $this->putJson(route('companies.update', $company->id), $data);

        $response->assertOk();
        $response->assertJsonStructure(self::FIELDS);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'business_name' => $data['business_name'],
            'corporate_name' => $data['corporate_name'],
        ]);
    }

    public function testCanDeleteCompany()
    {
        $company = \App\Models\Company::factory()->create();

        $response = $this->deleteJson(route('companies.destroy', $company->id));

        $response->assertNoContent();
        $this->assertSoftDeleted($company);
    }

    public function testCanShowCompany()
    {
        $company = \App\Models\Company::factory()->create();

        $response = $this->getJson(route('companies.show', $company->id));

        $response->assertOk();
        $response->assertJsonStructure(self::FIELDS);
    }
}
