<?php
namespace Panglongxia;
use Panglongxia\Core\Bean\BeanFactory;
use Panglongxia\Core\Http;
use Panglongxia\Core\Rpc\Rpc;

class App
{
    public function run($argv){
        $this->init(); //初始化
        try{
            switch ($argv[1]){
                case 'http:start':
                    Http::getInstance()->run();
                    break;
                case 'rpc:start':
                    Rpc::getInstance()->run();
                    break;

            }
        }catch (\Exception $exception){
            echo "FILE:".$exception->getFile()." Line:".$exception->getLine()." Message:".$exception->getMessage();
        }catch (\Throwable $throwable){
            echo "FILE:".$throwable->getFile()." Line:".$throwable->getLine()." Message:".$throwable->getMessage();
        }

    }

    /**
     * 初始化，载入配置
     */
    public function init()
    {
        define('ROOT_PATH', dirname(dirname(__DIR__))); //根目录
        define('APP_PATH', ROOT_PATH . '/app');
        define('CONFIG_PATH', ROOT_PATH.'/config');

        $beans = require APP_PATH."/Bean.php";
        foreach ($beans as $k => $v){
            BeanFactory::set($k, $v);//加入到容器中
        }
    }


}