<?php

namespace App\Controllers;

use App\Http\Exceptions\MethodNotAllowedException;
use App\Services\CompanyService;

class CompanyController extends Controller
{
    public function __construct(private readonly CompanyService $companyService = new CompanyService())
    {
    }

    /**
     * @throws MethodNotAllowedException
     */
    public function salaries(): array
    {
        $this->verifyRequestMethod('GET');

        return $this->companyService->getAverageSalaries();

    }
}