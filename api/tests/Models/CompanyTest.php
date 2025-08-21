<?php

namespace Tests\Models;

use App\Models\Company;
use PHPUnit\Framework\TestCase;

final class CompanyTest extends TestCase
{
    public function testConstructWithIdAndName(): void
    {
        $company = new Company(10, 'ACME');

        $this->assertSame(10, $company->id);
        $this->assertSame('ACME', $company->name);
    }

    public function testConstructWithNullId(): void
    {
        $company = new Company(null, 'Globex');

        $this->assertNull($company->id);
        $this->assertSame('Globex', $company->name);
    }
}