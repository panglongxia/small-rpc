<?php


namespace Panglongxia\Core\Rpc;


use Panglongxia\Core\Bean\BeanFactory;
use Panglongxia\Core\Http;
use Panglongxia\Core\Reload;
use Panglongxia\Core\Singleton;
use Swoole\Timer;


class Rpc extends Http
{
    use Singleton;

    public function run()
    {
        BeanFactory::get('config')->load(); //加载配置
        $config = BeanFactory::get('config')->get('rpc');

        $this->server = new \Swoole\Server($config['host'], $config['port']);
        $this->server->set($config['setting']);

        $this->server->on('start', [$this, 'start']);
        $this->server->on('workerStart', [$this, 'workerStart']);
        $this->server->on('receive', [$this, 'receive']);
        $this->server->start(); //启动服务器
    }

    public function listen($server){
       
        $config = BeanFactory::get('config')->get('rpc');
        $server->addListener($config['host'], $config['port'],SWOOLE_SOCK_TCP);
        $server->set($config['setting']);
        $server->on('receive', [$this, 'receive']);
    }


    public function receive($server, int $fd, int $reactor_id, string $data)
    {
        try {
            $data = json_decode($data,true);
            $flagArr = explode("::",$data['flag']);

            $namespace = 'App\Rpc\V'.(int)$flagArr[0];
            $class = $namespace.'\\'.ucfirst($flagArr[1]);
            //var_dump($class,class_exists($class));
            $method = $flagArr[2];
            //var_dump(method_exists((new $class()),$method));
            $res = (new $class())->$method($data['params']);
            $returnData = ['code' => 1, 'data' => $res];
            $server->send($fd, json_encode($returnData));
        }catch (\Exception $exception){
            $server->send($fd, json_encode(['code' => 500, 'msg' => $exception->getMessage()]));
        }

    }

    public function start(){
        $reload = Reload::getInstance()->setWatchDirs([APP_PATH, CONFIG_PATH]);
        Timer::tick(3000, function () use ($reload) {
            if ($reload->isReload()) {
                $this->server->reload();
            }
        });

        $config = BeanFactory::get('config')->get("rpc");
        echo " *********************************************************************".PHP_EOL;
        echo sprintf("*RPC     | Listen: %s:%d, type: TCP, worker: %d  ",$config['host'],$config['port'],$config['setting']['worker_num']).PHP_EOL;

    }

}