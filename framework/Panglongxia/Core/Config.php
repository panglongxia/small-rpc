<?php


namespace Panglongxia\Core;


class Config
{
    private static $configMap=[];

    use Singleton;

    public  function load(){
        $files=glob(CONFIG_PATH."/*.php");
        if(!empty($files)){
            foreach ($files as $dir=>$fileName){
                self::$configMap+=include $fileName;
            }
        }
    }
    public  function get($key){
        if(isset(self::$configMap[$key])){
            return self::$configMap[$key];
        }
        return false;
    }
}