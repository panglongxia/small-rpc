<?php

namespace Panglongxia\Core\Route;

class Route
{
    /**
     * @Example
     * GET=>[
     *      [
     *          routePath => '/index/test',
     *          handle => App\Test\IndexController@index
     *      ]
     * ]
     * @var array
     */
    private static $route=[];

    protected static $instance=null;

    //不允许在外部实例化
    private function __construct()
    {
    }

    /**
     * @return Route|null
     * 单例模式
     */
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }


    public static function getRouteAll(){
        return self::$route;
    }

    public static function addRoute($method, $routeInfo){
        self::$route[$method][] = $routeInfo;
    }


    public static function dispatch($method, $pathInfo){
        switch ($method){
            case 'GET':
                foreach (self::$route[$method] as $v){
                    //判断路径是否在注册的路由上
                    if($pathInfo==$v['routePath']){
                        $handle=explode("@",$v['handle']);
                        $class=$handle[0];
                        $method=$handle[1];
                        return (new $class)->$method();
                    }
                }
                break;
            case 'POST':
                break;
        }
    }
}