<?php

namespace Tests\Unit;

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class PDOQueryBuilderTest extends TestCase
{
    private $queryBuilder;
    public function setUp(): void
    {
        $pdoConnection= new PDODatabaseConnection($this->getConfig());
        $this->queryBuilder= new PDOQueryBuilder($pdoConnection->connect());
        $this->queryBuilder->beginTransaction();
        parent::setUp();
    }
    private function insertIntoDb($options =[])
    {
        $data =array_merge([
            'name'=>'First Bug',
            'link'=>'www.link.com',
            'user' => 'Behnoosh',
            'email'=>'behnoosh@link.com'
        ] , $options);
        $result=$this->queryBuilder->table('bugs')->create($data);
        return $result;
    }

    public function testItCanCreateData()
    {
       $result=$this->insertIntoDb();
       $this->assertIsInt($result);
       $this->assertGreaterThan(0,$result);
    }
    public function testItCanUpdateData()
    {
       $this->insertIntoDb();
       $result=$this->queryBuilder
            ->table('bugs')
            ->where('user', 'Behnoosh')
            ->update(['email' => 'euruse@gmail.com']);
        $this->assertEquals(2, $result);
    }

    public function testItCanUpdateWithMultipleWhere()
    {
        $this->insertIntoDb();
        $this->insertIntoDb(['user'=>'Ehsan']);
        $result=$this->queryBuilder
            ->table('bugs')
            ->where('user','Ehsan')
            ->where('link','www.link.com')
            ->update(['name'=>'ehsan sh']);

        $this->assertEquals(1,$result);
    }

    public function testItCanDeleteRecord()
    {
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();

        $result= $this->queryBuilder
            ->table('bugs')
            ->where('user', 'Behnoosh')
            ->delete();
        $this->assertEquals(6, $result);
    }
    private function getConfig()
    {
        return Config::get('database','pdo_testing');
    }
    public function tearDown(): void
    {
        $this->queryBuilder->rollback();
        parent::tearDown();
    }
}
