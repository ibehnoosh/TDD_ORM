<?php

namespace Tests\Unit;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\PDODatabaseConnection;
use App\Exceptions\DatabaseConnectionException;
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

    public function testConnectFunctionShouldReturnValidInstance()
    {
        $config= $this->getConfig();
        $pdoConnection= new PDODatabaseConnection($config);
        $pdoConnection->connect();
        $this->assertInstanceOf(PDODatabaseConnection::class,$pdoConnection);
        return $pdoConnection;
    }

    /**
     * @return void
     * @throws DatabaseConnectionException
     * @depends testConnectFunctionShouldReturnValidInstance
     */
    public function testConnectFuncShouldBeConnectToDatabase($pdoConnection)
    {
        $this->assertInstanceOf(PDO::class,$pdoConnection->getConnection());
    }
    public function testItThrowExceptionIfConfigIsInvalid()
    {
        $this->expectException(DatabaseConnectionException::class);
        $config= $this->getConfig();
        $config['database']='dummy';
        $pdoConnection= new PDODatabaseConnection($config);
        $pdoConnection->connect();
    }
    private function getConfig()
    {
        return Config::get('database','pdo_testing');
    }
}
