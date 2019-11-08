<?php


namespace Panglongxia\Core;

/**
 * Trait Singleton
 * @package Panglongxia\Core
 * 单例模式
 */
trait Singleton
{
    private static $instance;
    private function __construct()
    {
    }
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance=new self();
        }
        return self::$instance;
    }

}