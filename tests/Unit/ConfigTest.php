<?php
namespace Tests\Unit;

use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGetFileContentsReturnArray()
    {
        $config=Config::getFileContents('database');
        $this->assertIsArray($config);
    }
}
