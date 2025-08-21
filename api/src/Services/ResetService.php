<?php

namespace App\Services;

use App\Database\Mysql;
use App\Repositories\CompanyRepository;
use App\Repositories\EmployeeRepository;
use PDO;

class ResetService
{
    private PDO $db;
    private CompanyRepository $companyRepository;
    private EmployeeRepository $employeeRepository;

    public function __construct()
    {
        $this->db = Mysql::connection();
        $this->companyRepository = new CompanyRepository();
        $this->employeeRepository = new EmployeeRepository();
    }

    public function resetAll(): void
    {
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 0');

        $this->employeeRepository->truncate();
        $this->companyRepository->truncate();

        $this->db->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}