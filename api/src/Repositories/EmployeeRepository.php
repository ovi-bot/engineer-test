<?php

namespace App\Repositories;

use App\Http\Exceptions\BadRequestException;
use App\Http\Exceptions\UnknownException;
use App\Models\Employee;
use Override;
use PDO;
use PDOException;
use Throwable;

class EmployeeRepository extends Repository
{
    public function upsertByEmail(Employee $employee): void
    {
        $stmt = $this->db->prepare('INSERT INTO employee (company_id, name, email, salary) VALUES (:company_id, :name, :email, :salary) ON DUPLICATE KEY UPDATE company_id = VALUES(company_id), name = VALUES(name), salary = VALUES(salary), updated_at = CURRENT_TIMESTAMP');
        $stmt->execute([
            ':company_id' => $employee->companyId,
            ':name' => $employee->name,
            ':email' => $employee->email,
            ':salary' => $employee->salary,
        ]);
    }

    public function listWithCompany(): array
    {
        $sql = <<<SQL
            SELECT
                e.id,
                e.company_id,
                c.name AS company_name,
                e.name,
                e.email,
                e.salary
            FROM employee e
            INNER JOIN company c ON c.id = e.company_id
            ORDER BY c.name, e.name, e.id
        SQL;

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['id'] = (int)$row['id'];
            $row['company_id'] = (int)$row['company_id'];
            $row['salary'] = (int)$row['salary'];
        }

        return $rows;
    }

    /**
     * @throws BadRequestException
     * @throws UnknownException
     */
    public function updateEmail(int $id, string $email): void
    {
        try {
            $stmt = $this->db->prepare('UPDATE employee SET email = :email, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
            $stmt->execute([
                ':id' => $id,
                ':email' => $email,
            ]);

            if ($stmt->rowCount() === 0) {
                throw new BadRequestException('Email already in use or cannot update');
            }
        } catch (PDOException $e) {
            throw new BadRequestException('Email already in use or cannot update');
        } catch (Throwable $e) {
            throw new UnknownException();
        }
    }

    #[Override]
    public function truncate(): void
    {
        $this->db->exec('TRUNCATE TABLE employee');
    }
}