<?php

namespace Tests\Repositories;

use App\Database\Mysql;
use App\Http\Exceptions\BadRequestException;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use PDO;
use PDOStatement;
use PDOException;
use PHPUnit\Framework\TestCase;

class EmployeeRepositoryTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        Mysql::setConnection(null);
    }

    public function testUpsertByEmailExecutesWithExpectedParams(): void
    {
        $employee = new Employee(null, 1, 'Alice', 'alice@example.com', 90000);

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->callback(function ($params) use ($employee) {
                return $params[':company_id'] === $employee->companyId
                    && $params[':name'] === $employee->name
                    && $params[':email'] === $employee->email
                    && $params[':salary'] === $employee->salary;
            }));

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        Mysql::setConnection($pdo);

        $repo = new EmployeeRepository();
        $repo->upsertByEmail($employee);
    }

    public function testListWithCompanyCastsTypes(): void
    {
        $rows = [
            [
                'id' => '10',
                'company_id' => '5',
                'company_name' => 'ACME',
                'name' => 'Bob',
                'email' => 'bob@example.com',
                'salary' => '123456',
            ],
        ];

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn($rows);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('query')->willReturn($stmt);

        Mysql::setConnection($pdo);

        $repo = new EmployeeRepository();
        $out = $repo->listWithCompany();

        $this->assertIsArray($out);
        $this->assertSame(10, $out[0]['id']);
        $this->assertSame(5, $out[0]['company_id']);
        $this->assertSame(123456, $out[0]['salary']);
        $this->assertSame('ACME', $out[0]['company_name']);
    }

    public function testUpdateEmailSuccess(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with([
            ':id' => 42,
            ':email' => 'new@example.com',
        ]);
        $stmt->method('rowCount')->willReturn(1);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        Mysql::setConnection($pdo);

        $repo = new EmployeeRepository();
        $repo->updateEmail(42, 'new@example.com');

        $this->addToAssertionCount(1); // reached here without exception
    }
}
