
此功能库基于`freeswitch`的`mod_event_socket`模块开发，支持`所有版本PHP`；`mod_event_socket`是一个基于`TCP`的接口来控制`FreeSWITCH`。默认值是绑定到`127.0.0.1`端口`8021`，默认密码是`ClueCon`。


### 一、环境配置

你可以使用任何版本的`php`进行连接，`freeswitch`可以是远程服务器也可以是本地。

- php
- freeswitch

### 二、快速开始
使用前先`clone`线上库，然后运行代码测试`Demo`即可。
```
git clone 
cd freeswith_php_esl

```
然后运行测试代码即可测试:
```
> php demo.php

FreeSWITCH Version 1.9.0+git~20180619T173242Z~25e9376b29~64bit (git 25e9376 2018-06-19 17:32:42Z 64bit)
```

### 三、API列表

#### 1、connect(ip,port,password)
用于连接`freeswitch`服务器,返回结果为`true`或`false`;使用方法如下：
```
<?php
require_once 'freeSwitchEsl.php';

$freeswitch = new Freeswitchesl();
$connect = $freeswitch->connect("127.0.0.1","8021","ClueCon");
if ($connect) {
	echo "connect success";
}
```
如果登陆失败会输出错误信息,可以自己去掉注释。

#### 2、api(api comment,args)

通过`ESL`执行`API`命令，此`API`会返回数据执行结果，如果不想等待数据返回或者异步执行可以直接使用下方`bgapi`：
```
$version = $freeswitch->api("version");
$status = $freeswitch->api("status");
$sofia = $freeswitch->api("sofia status");
```
更多`freeswitch`的`api`命令可以执行：
```
$sofia = $freeswitch->api("show api");
```

#### 3、bgapi(api comment,args)

与`api(api comment)`方法相同，非阻塞模式异步执行：
```
$originate = $freeswitch->bgapi("originate user/1000 &echo");
```

#### 4、execute(comment,args,uuid)

与`api(api comment)`方法相同，异步执行：
```
$originate = $freeswitch->bgapi("originate user/1000 &echo");
```
execute

#### 5、executeAsync(comment,args,uuid)

与`api(api comment)`方法相同，异步执行：
```
$originate = $freeswitch->bgapi("originate user/1000 &echo");
```
execute

#### 6、events(sorts,args)

`event`命令用于订阅来自`FreeSWITCH`的事件。您可以在同一行上指定监听的所有的事件，它们应该用空格分隔；`sorts`即返回数据类型,`args`即监听的事件。
```
$status = $freeswitch->events("plain","ALL");
```

#### 7、recvEvent()

与`events(sorts,args)`配套使用，用于获取所有数据,返回服务器的原始数据。
```
$received_parameters = $freeswitch->recvEvent();
```
##### a、serialize(received_parameters,type)

`serialize`即按住指定格式返回数据，建议与`events`监听的类型一样，这样处理性能更加优秀；`type`有`plain、json`和`xml`三种类型。
```
$serialize_info = $freeswitch->serialize($received_parameters,"xml");
```

##### b、getHeader(received_parameters,args)

可以获取返回的指定数据,如果不存在则返回空。
```
$Event_Name = $freeswitch->getHeader($received_parameters,"Event-Name");
```

使用例子：
```
<?php
require_once 'freeSwitchEsl.php';

$freeswitch = new Freeswitchesl();
$connect = $freeswitch->connect("127.0.0.1","8021","ClueCon");
if ($connect) {
	$status = $freeswitch->events("json","ALL");
	while (true) {
		$received_parameters = $freeswitch->recvEvent();
		if (!empty($received_parameters)) {
			$info = $freeswitch->serialize($received_parameters,"json");
			var_dump($info);
			$Event_Name = $freeswitch->getHeader($received_parameters,"Event-Name");
			echo $Event_Name;
		}
	}
}
```

#### 7、disconnect()

断开`php`与`freeswitch`之间的`socket`；建议每次使用后都需要断开。
