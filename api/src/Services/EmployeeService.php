<?php

namespace App\Services;

use App\Http\Exceptions\BadRequestException;
use App\Http\Exceptions\UnknownException;
use App\Repositories\EmployeeRepository;

class EmployeeService
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository = new EmployeeRepository()
    )
    {
    }

    public function getList(): array
    {
        return $this->employeeRepository->listWithCompany();
    }

    /**
     * @throws BadRequestException
     * @throws UnknownException
     */
    public function updateEmail(int $id, string $email): void
    {
        $this->employeeRepository->updateEmail($id, $email);
    }

}