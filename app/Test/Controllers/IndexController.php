<?php

namespace App\Test\Controllers;

use Panglongxia\Core\Rpc\Client;

/**
 * Class IndexController
 * @Controller(prefix="/index")
 */
class IndexController
{
    /**
     * @RequestMapping(route="index")
     */
    public function index(){
        $client = new Client();
        $res = $client->service('info')->version('1.0')->method('info')->send(['t'=>'aa']);
        var_dump($res);
        return "index/index";
    }

    /**
     * @RequestMapping(route="test")
     */
    public function test(){
        return "index/test";
    }

}