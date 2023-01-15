<?php
namespace Tests\Unit;

use App\Exceptions\ConfigFileNotFoundException;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGetFileContentsReturnArray()
    {
        $config=Config::getFileContents('database');
        $this->assertIsArray($config);
    }

    public function  testIfFileNotExistThrowException()
    {
        $this->expectException(ConfigFileNotFoundException::class);
        $config=Config::getFileContents('dummy');
    }

    public function testGetMethodReturnValid()
    {
        $config=Config::get('database','pdo');
        $exceptedData =[
            'driver' => 'mysql',
            'host'=> '127.0.0.1',
            'database'=>'bug_tracker',
            'db_user'=>'root',
            'db_password' => ''
        ];
        $this->assertEquals($config,$exceptedData);

    }
}
