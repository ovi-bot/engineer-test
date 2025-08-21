<?php

namespace App\Models;

class Company
{
    public function __construct(
        public ?int   $id,
        public string $name
    )
    {
    }
}