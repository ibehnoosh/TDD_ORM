<?php
namespace App\Helpers;

use App\Exceptions\ConfigFileNotFoundException;

Class Config
{
    public static function getFileContents(string $filename)
    {
        $filePath=realpath(__DIR__."/../Configs/".$filename.".php");
        if(!$filePath){
            throw  new ConfigFileNotFoundException();
        }
        $fileContents= require $filePath;
        return $fileContents;
    }
    public static  function  get(string $filename, string $key=null)
    {
        $fileContents= self::getFileContents($filename);
        if(is_null($key)) return $fileContents;
        return $fileContents[$key] ?? null;
    }
}
