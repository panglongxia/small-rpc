<?php


namespace App\Api\Controllers;

/**
 * Class IndexController
 * @Controller(prefix="/api/index")
 */
class IndexController
{
    /**
     * @RequestMapping(route="index")
     */
    public function index(){
        return "index/index";
    }
}