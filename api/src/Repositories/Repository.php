<?php

namespace App\Repositories;

use App\Database\Mysql;
use PDO;

abstract class Repository
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Mysql::connection();
    }

    abstract public function truncate(): void;
}