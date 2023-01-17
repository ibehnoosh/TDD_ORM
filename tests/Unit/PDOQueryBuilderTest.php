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
       $this->queryBuilder->truncatAllTable();
       $this->insertIntoDb();
       $result=$this->queryBuilder
            ->table('bugs')
            ->where('user', 'Behnoosh')
            ->update(['email' => 'euruse@gmail.com']);
        $this->assertEquals(1, $result);
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
        $this->queryBuilder->truncatAllTable();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();

        $result= $this->queryBuilder
            ->table('bugs')
            ->where('user', 'Behnoosh')
            ->delete();
        $this->assertEquals(4, $result);
    }
    public function testItCanFetchData()
    {
        $this->multipleInsertIntoDB(10, ['user'=>'moshtagh']);
        $result= $this->queryBuilder
            ->table('bugs')
            ->where('user', 'moshtagh')
            ->get();
        $this->assertIsArray($result);
        $this->assertCount(10, $result);
    }
    public function testItCanFetchSpecificColumns()
    {
        $this->multipleInsertIntoDB(10, ['name'=>'Golbarg']);
        $result= $this->queryBuilder
            ->table('bugs')
            ->where('name', 'Golbarg')
            ->get(['name','user']);

        $this->assertIsArray($result);
        $this->assertObjectHasAttribute('name',$result[0]);
        $this->assertObjectHasAttribute('user',$result[0]);
        $result=json_decode(json_encode($result[0]),true);
        $this->assertEquals(['name','user'],array_keys($result));
    }
    public function testItCanGetFirstRow()
    {
        $this->multipleInsertIntoDB(10, ['name'=>'Golbarg']);
        $result= $this->queryBuilder
            ->table('bugs')
            ->where('name', 'Golbarg')
            ->first();
        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('name',$result);
        $this->assertObjectHasAttribute('user',$result);

    }
    public function testItCanFindWithId()
    {
        $id=$this->insertIntoDb(['name'=>'For find']);
        $result=$this->queryBuilder
            ->table('bugs')
            ->find($id);
        $this->assertIsObject($result);
        $this->assertEquals('For find',$result->name);
    }
    public function testItCanFindBy()
    {
        $id=$this->insertIntoDb(['name'=>'For find By']);
        $result=$this->queryBuilder
            ->table('bugs')
            ->findBy('name','For find By');
        $this->assertIsObject($result);
        $this->assertEquals($id,$result->id);
    }
    private function getConfig()
    {
        return Config::get('database','pdo_testing');
    }
    private function multipleInsertIntoDB($count,$options=[])
    {
        for($i=1; $i<=$count;$i++){
            $this->insertIntoDb($options);
        }
    }
    public function tearDown(): void
    {
        $this->queryBuilder->rollback();
        parent::tearDown();
    }
}
