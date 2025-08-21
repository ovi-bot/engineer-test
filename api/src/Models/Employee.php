<?php

namespace App\Models;

class Employee
{
    public function __construct(
        public ?int   $id,
        public int    $companyId,
        public string $name,
        public string $email,
        public int    $salary)
    {
    }
}