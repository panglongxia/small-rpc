## 手写分布式小框架

学习分布式框架的笔记，仅供学习参考，很多数据并未进行验证

### 环境
>+ php >= 7.0
>+ swoole >= 4.0
>+ http端口 9501
>+ rpc端口:9200

### 测试方法

1.进入根目录下运行,启动http服务
```
php ./bin/panglognxia.php http:start
```
2.进入9200，启动rpc服务
```
php ./9200/bin/panglognxia.php rpc:start
```

3.访问查看控制台输出
```
http://127.0.0.1:9501/index/index
```

### 代码解析
1.http路由解析，这里是模仿的Swift 框架，路由是通过注解解析而成

解析源码参考 /framework/Panglongxia/Core/Annotation.php,
当然这里代码较为粗糙，耦合度较高,也并没做预防判断,统一写死为GET请求
```
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
```
解析后的路由为 /api/index/index

2.IOC容器
```
/framework/Panglongxia/Core/Bean/BeanFactory.php
```

3.http，rpc 服务
```
/framework/Panglongxia/Core/Http.php
/framework/Panglongxia/Core/Rpc/Rpc.php

#rpc客户端，简易封装
/framework/Panglongxia/Core/Rpc/Client.php
```
4.热重启,通过对比文件md5值
```
/framework/Panglongxia/Core/Reload.php
```
