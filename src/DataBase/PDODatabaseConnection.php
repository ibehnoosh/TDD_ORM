<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exceptions\DatabaseConnectionException;
use PDO;
use PDOException;

class PDODatabaseConnection implements DatabaseConnectionInterface
{

    protected $connection;
    protected $config;
    public function __construct(array $config)
    {
        $this->config=$config;
    }

    public function connect()
    {
        $dsn=$this->generateDsn($this->config);
        try {
            $this->connection=new PDO(...$dsn);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
        } catch(PDOException $e) {
            throw new DatabaseConnectionException();
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function generateDsn(array $config)
    {
        $dsn="{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        return [$dsn, $config['db_user'],$config['db_password']];
    }
}