<?php

namespace App\Test\Controllers;

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
        return "index/index111122";
    }

    /**
     * @RequestMapping(route="test")
     */
    public function test(){
        return "index/test";
    }

}