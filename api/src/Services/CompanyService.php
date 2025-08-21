<?php

namespace App\Services;

use App\Repositories\CompanyRepository;

class CompanyService
{
    public function __construct(
        private readonly CompanyRepository $companyRepository = new CompanyRepository()
    )
    {
    }

    public function getAverageSalaries(): array
    {
        return $this->companyRepository->getAverageSalaries();
    }
}