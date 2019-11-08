<?php


namespace Panglongxia\Core;


use Panglongxia\Core\Route\Route;

class Annotation
{
    use Singleton;

    public function loadAnnotations()
    {
//        $dirs = glob(APP_PATH . '/Test/Controllers/*');
        $dirs = $this->tree(APP_PATH,'Controller');

        if( !empty($dirs) ){
            foreach ($dirs as $file){
                $fileName=explode('/',$file);
                $className=explode('.',end($fileName))[0];
                $fileContent=file_get_contents($file,false, null, 0,500);
                preg_match('/namespace\s(.*)/i', $fileContent, $nameSpace);
                if(isset($nameSpace[1])){
                    $nameSpace=str_replace([' ',';','"'],'',$nameSpace[1]);
                    $className=trim($nameSpace)."\\".$className;
                    $obj=new  $className;

                    $reflect = new \ReflectionClass($obj);
                    $classDocComment = $reflect->getDocComment(); //类注解
                    preg_match('/@Controller\((.*)\)/i', $classDocComment, $prefix);
                    $prefix =str_replace("\"","",explode("=",$prefix[1])[1]);//清除掉引号

                    foreach ($reflect->getMethods() as $method){
                        $methodDocComment = $method->getDocComment();//获取方法注解
                        preg_match('/@RequestMapping\((.*)\)/i', $methodDocComment, $suffix);
                        $suffix=str_replace("\"","",explode("=",$suffix[1])[1]); //清除掉引号
                        $routeInfo = [
                            'routePath' =>$prefix."/".$suffix,
                            'handle' => $reflect->getName()."@".$method->getName()
                        ];
                        Route::addRoute('GET', $routeInfo);
                    }
                }
            }
        }
    }

    private function tree($dir, $filter){
        $dirs = glob($dir."/*");
        $files = [];
        foreach ($dirs as $dir){
            if(is_dir($dir)){
                $res = $this->tree($dir, $filter);
                foreach ($res as $v){
                    $files[] = $v;
                }
            }else{
                //判断是否是控制器
                if( stristr($dir, $filter) ){
                    $files[] = $dir;
                }
            }
        }
        return $files;
    }

}