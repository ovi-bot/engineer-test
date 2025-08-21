<?php

namespace Tests\Models;

use App\Models\Employee;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    public function testEmployeeConstructsAndExposesProperties(): void
    {
        $e = new Employee(
            id: 1,
            companyId: 2,
            name: 'Jane',
            email: 'jane@example.com',
            salary: 100000
        );

        $this->assertSame(1, $e->id);
        $this->assertSame(2, $e->companyId);
        $this->assertSame('Jane', $e->name);
        $this->assertSame('jane@example.com', $e->email);
        $this->assertSame(100000, $e->salary);
    }

    public function testEmployeeIdCanBeNull(): void
    {
        $e = new Employee(
            id: null,
            companyId: 2,
            name: 'New Hire',
            email: 'new@example.com',
            salary: 50000
        );

        $this->assertNull($e->id);
    }
}
