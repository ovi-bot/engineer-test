<?php

namespace App\Repositories;

use App\Models\Company;
use Override;
use PDO;

class CompanyRepository extends Repository
{
    public function findByName(string $name): ?Company
    {
        $stmt = $this->db->prepare('SELECT id, name FROM company WHERE name = ? LIMIT 1');
        $stmt->execute([$name]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Company((int)$row['id'], (string)$row['name']);
    }

    public function getOrCreateIdByName(string $name): int
    {
        $stmt = $this->db->prepare('INSERT INTO company (name) VALUES (:name) ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)');
        $stmt->execute([':name' => $name]);

        return (int)$this->db->lastInsertId();
    }

    public function getAverageSalaries(): array
    {
        $sql = <<<SQL
            SELECT
                c.id,
                c.name,
                AVG(e.salary) AS average_salary
            FROM company c
            LEFT JOIN employee e ON e.company_id = c.id
            GROUP BY c.id, c.name
            ORDER BY c.name ASC
        SQL;

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['id'] = (int)$row['id'];
            $row['average_salary'] = $row['average_salary'] !== null ? (float)$row['average_salary'] : null;
        }

        return $rows;
    }

    #[Override]
    public function truncate(): void
    {
        $this->db->exec('TRUNCATE TABLE company');
    }
}