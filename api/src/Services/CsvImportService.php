<?php

namespace App\Services;

use App\Http\Exceptions\BadRequestException;
use App\Models\Employee;
use App\Repositories\CompanyRepository;
use App\Repositories\EmployeeRepository;
use SplFileObject;

class CsvImportService
{
    private array $expectedHeader = ['company name', 'employee name', 'email address', 'salary'];

    public function __construct(
        private readonly CompanyRepository  $companyRepository = new CompanyRepository(),
        private readonly EmployeeRepository $employeeRepository = new EmployeeRepository()
    )
    {
    }

    /**
     * @throws BadRequestException
     */
    public function importFromFile(string $path): void
    {
        $file = $this->loadFile($path);

        $this->validateHeader($file);

        // Simple in-memory cache to avoid repeated lookups/creates per company name
        $companyCache = [];

        while (!$file->eof()) {
            $row = $file->fgetcsv();
            if ($row === false || $row === [null]) {
                continue;
            }

            if (count($row) < 4) {
                continue;
            }

            $companyName = trim((string)$row[0]);
            $employeeName = trim((string)$row[1]);
            $employeeEmail = trim((string)$row[2]);
            $employeeSalary = trim((string)$row[3]);

            if ($companyName === '' || $employeeName === '' || $employeeEmail === '' || $employeeSalary === '') {
                continue;
            }

            if (!filter_var($employeeEmail, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            if (!is_numeric($employeeSalary)) {
                continue;
            }

            if (isset($companyCache[$companyName])) {
                $companyId = $companyCache[$companyName];
            } else {
                $existingCompany = $this->companyRepository->findByName($companyName);

                if ($existingCompany === null) {
                    $companyId = $this->companyRepository->getOrCreateIdByName($companyName);
                } else {
                    $companyId = (int)$existingCompany->id;
                }

                $companyCache[$companyName] = $companyId;
            }

            $this->employeeRepository->upsertByEmail(
                new Employee(
                    null,
                    $companyId,
                    $employeeName,
                    $employeeEmail,
                    (int)$employeeSalary
                )
            );
        }
    }

    /**
     * @throws BadRequestException
     */
    private function loadFile(string $path): SplFileObject
    {
        $file = new SplFileObject($path);
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

        if ($file->eof()) {
            throw new BadRequestException('File is empty');
        }

        return $file;
    }

    /**
     * @throws BadRequestException
     */
    private function validateHeader(SplFileObject $file): void
    {
        $header = $file->fgetcsv();
        if ($header === false || count($header) < 4) {
            throw new BadRequestException('Bad header');
        }

        $normalized = array_map(static function ($h) {
            return strtolower(trim((string)$h));
        }, $header);

        foreach ($this->expectedHeader as $idx => $col) {
            if (!isset($normalized[$idx]) || $normalized[$idx] !== $col) {
                throw new BadRequestException('Bad header');
            }
        }
    }
}