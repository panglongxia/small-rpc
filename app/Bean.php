<?php

return [
    'route' => function(){
        return Panglongxia\Core\Route\Route::getInstance();
    },
    'config' => function(){
        return Panglongxia\Core\Config::getInstance();
    },
    'annotation' => function(){
        return Panglongxia\Core\Annotation::getInstance();
    }
];