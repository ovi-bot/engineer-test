<?php

namespace Tests\Database;

use App\Database\Mysql;
use PDO;
use PHPUnit\Framework\TestCase;

class MysqlTest extends TestCase
{
    public function testSetConnectionInjectsAndReturnsSameInstance(): void
    {
        $pdo = $this->createMock(PDO::class);
        Mysql::setConnection($pdo);

        $this->assertSame($pdo, Mysql::connection());
        Mysql::setConnection(null);
    }
}
