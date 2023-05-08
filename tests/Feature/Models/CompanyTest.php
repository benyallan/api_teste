<?php

namespace Tests\Feature\Models;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateCompany()
    {
        $company = Company::factory()->create([
            'cnpj' => '12345678901234',
            'business_name' => 'Company Test',
            'corporate_name' => 'Company Test LTDA',
        ]);

        $this->assertNotNull($company->id);
        $this->assertEquals($company->cnpj, '12345678901234');
        $this->assertEquals($company->business_name, 'Company Test');
        $this->assertEquals($company->corporate_name, 'Company Test LTDA');
    }

    public function testUpdateCompany()
    {
        $company = Company::factory()->create();

        $company->cnpj = '98765432101234';
        $company->business_name = 'New Company Test';
        $company->corporate_name = 'New Company Test LTDA';
        $company->save();

        $this->assertEquals($company->cnpj, '98765432101234');
        $this->assertEquals($company->business_name, 'New Company Test');
        $this->assertEquals($company->corporate_name, 'New Company Test LTDA');
    }

    public function testDeleteCompany()
    {
        $company = Company::factory()->create();

        $company->delete();

        $this->assertSoftDeleted($company);
    }
}
