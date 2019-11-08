<?php


namespace Panglongxia\Core;

use Panglongxia\Core\Bean\BeanFactory;
use Panglongxia\Core\Rpc\Rpc;
use Swoole\Http\Server;
use Swoole\Timer;

class Http
{
    use Singleton;
    /**
     * @var Server
     */
    protected $server;


    public function run()
    {
        BeanFactory::get('config')->load(); //加载配置
        $config = BeanFactory::get('config')->get('http');

        $this->server = new Server($config['host'], $config['port']);
        $this->server->set($config['setting']);
        if (isset($config['rpcEnable']) && (int)$config['rpcEnable']===1){
           Rpc::getInstance()->listen($this->server);
        }
        $this->server->on('start', [$this, 'start']);
        $this->server->on('workerStart', [$this, 'workerStart']);
        $this->server->on('request', [$this, 'request']);
        $this->server->start(); //启动服务器
    }

    /**
     * 只有主进程触发,热重启
     */
    public function start()
    {
        //监控配置文件目录与APP目录
        $reload = Reload::getInstance()->setWatchDirs([APP_PATH, CONFIG_PATH]);
        Timer::tick(3000, function () use ($reload) {
            if ($reload->isReload()) {
                $this->server->reload();
            }
        });

        $config = BeanFactory::get('config')->get("http");
        echo "***********************************************************************" . PHP_EOL;
        echo sprintf("*HTTP     | Listen: %s:%d, type: TCP, worker: %d  ", $config['host'], $config['port'], $config['setting']['worker_num']) . PHP_EOL;
        if (isset($config['rpcEnable']) && (int)$config['rpcEnable']===1){
            $config = BeanFactory::get('config')->get("rpc");
            echo sprintf("*RPC      | Listen: %s:%d, type: TCP, worker: %d  ",$config['host'],$config['port'],$config['setting']['worker_num']).PHP_EOL;
            echo "***********************************************************************" . PHP_EOL;
        }

    }

    /**
     * @param $server
     * @param $workerId
     * 每个worker 都会触发
     */
    public function workerStart($server, $workerId)
    {
        BeanFactory::get('annotation')->loadAnnotations(); //载入路由的注解
        BeanFactory::get('config')->load(); //载入配置文件
    }

    public function request($request, $response)
    {
        $path_info = $request->server['path_info'];
        $method = $request->server['request_method'];
        //$res=\Six\Core\Route\Route::dispatch($method,$path_info);
        $res = BeanFactory::get('route')::dispatch($method, $path_info);
        $response->end($res);
    }


}