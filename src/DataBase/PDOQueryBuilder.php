<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;

class PDOQueryBuilder
{
    protected $table;
    protected $connection;
    public function __construct(DatabaseConnectionInterface $connect)
    {
        $this->connection=$connect->getConnection();
    }
    public function table(string $table)
    {
        $this->table=($table);
        return $this;
    }
    public function  create(array $data)
    {
        $placeHolder=[];
        foreach ($data as $column => $value)
        {
            $placeHolder[]='?';
        }
        $fields=implode(',',array_keys($data));
        $placeHolder=implode(',',$placeHolder);
        $sql="INSERT INTO {$this->table} ({$fields}) value ({$placeHolder})";

        $query= $this->connection->prepare($sql);
        $query->execute(array_values($data));
        return (int)$this->connection->lastInsertId();
    }

}