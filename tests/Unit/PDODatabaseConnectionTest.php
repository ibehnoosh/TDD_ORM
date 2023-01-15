<?php

namespace Tests\Unit;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\PDODatabaseConnection;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class PDODatabaseConnectionTest extends TestCase
{
    public function testPDODatabaseConnectionImplementDatabaseConnectionInterface()
    {
        $config= $this->getConfig();
        $pdoConnection= new PDODatabaseConnection();
        $this->assertInstanceOf(DatabaseConnectionInterface::class, $pdoConnection);
    }
    private function getConfig()
    {
        return Config::get('database','pdo_testing');
    }
}
