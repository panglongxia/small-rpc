<?php


namespace Panglongxia\Core\Rpc;

use Panglongxia\Core\Bean\BeanFactory;

/**
 * Class Client
 * @package Panglongxia\Core\Rpc
 * rpc 客户端
 */
class Client
{

    protected $version = '1.0';
    protected $serverName;
    protected $method;
    public function service($serName){
        $this->serverName = $serName;
        return $this;
    }

    public function version($version){
        $this->version = $version;
        return $this;
    }
    public function method($method){
        $this->method = $method;
        return $this;
    }

    /**
     * @param array $params
     * @return array
     * 发送操作
     * TODO:并没有验证数据的正确性
     */
    public function send(array $params){
        try {
            //使用json_rpc
            $req = [
                "jsonrpc" => $this->version,
                "flag" => sprintf("%s::%s::%s", $this->version,$this->serverName, $this->method),
                "params" => $params
            ];
            $data = json_encode($req);
            $config = BeanFactory::get('config')->get('services');
            $client = new \Swoole\Client(SWOOLE_SOCK_TCP);
            $rpcAddress = $config[$this->serverName]['v'.$this->version];
            if( !$client->connect($rpcAddress['host'], $rpcAddress['port'], -1) ){
                throw new \Exception("connect failed. Error:".$client->errCode);
            }
            $client->send($data);
            return  ['code' => 200, 'data' => $client->recv()];
        }catch (\Exception $exception){
           return ['code' => 500, 'msg' => $exception->getMessage()];
        }
    }

}