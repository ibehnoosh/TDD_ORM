<?php

namespace Tests\Unit;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\PDODatabaseConnection;
use App\Helpers\Config;
use PDO;
use PHPUnit\Framework\TestCase;

class PDODatabaseConnectionTest extends TestCase
{
    public function testPDODatabaseConnectionImplementDatabaseConnectionInterface()
    {
        $config= $this->getConfig();
        $pdoConnection= new PDODatabaseConnection($config);
        $this->assertInstanceOf(DatabaseConnectionInterface::class, $pdoConnection);
    }
    public function testConnectFuncShouldBeConnectToDatabase()
    {
        $config= $this->getConfig();
        $pdoConnection= new PDODatabaseConnection($config);
        $pdoConnection->connect();
        $this->assertInstanceOf(PDO::class,$pdoConnection->getConnection());
    }
    private function getConfig()
    {
        return Config::get('database','pdo_testing');
    }
}
