<?php

namespace Tests\Unit;

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class PDOQueryBuilderTest extends TestCase
{
    public function testItCanCreateData()
    {
        $pdoConnection= new PDODatabaseConnection($this->getConfig());
        $PDOQueryBuilder= new PDOQueryBuilder($pdoConnection->connect());
        $data =[
            'name'=>'First Bug',
            'link'=>'www.link.com',
            'user' => 'Behnoosh',
            'email'=>'behnoosh@link.com'
        ];
       $result=$PDOQueryBuilder->table('bugs')->create($data);
       $this->assertIsInt($result);
       $this->assertGreaterThan(0,$result);
    }
    private function getConfig()
    {
        return Config::get('database','pdo_testing');
    }
}
