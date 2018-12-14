This library is based on `mod_event_socket'module of `freeswitch', and supports `all versions of PHP`. `mod_event_socket` is a TCP based interface to control FreeSWITCH. The default values are to bind to `127.0.0.1` port `8021` and the default password is `ClueCon`。

中文文档参考：[README-zh.md](README-zh.md)


### Requirements and Installation

You can connect using any version of `php`, and `freeswitch` can be either a remote server or a local one.

- php
- freeswitch

### Quick start

Before using the `clone` online library, then run the code test `demo.php`.
```
git clone 
cd freeswith_php_esl

```
Run the test code to test:
```
> php demo.php

FreeSWITCH Version 1.9.0+git~20180619T173242Z~25e9376b29~64bit (git 25e9376 2018-06-19 17:32:42Z 64bit)
```

### API

#### 1、connect(ip,port,password)
Used to connect the `freeswitch` server and return the result as `true` or `false`; the method of use is as follows:
```
<?php
require_once 'freeSwitchEsl.php';

$freeswitch = new Freeswitchesl();
$connect = $freeswitch->connect("127.0.0.1","8021","ClueCon");
if ($connect) {
	echo "connect success";
}
```
If the login fails, the error message will be output, and the comment can be removed by itself.

#### 2、api(api comment,args)

The `API` command is executed by `ESL`, which returns the result of data execution. If you do not want to wait for data return or asynchronous execution, you can use the following `bgapi`:

```
$version = $freeswitch->api("version");
$status = $freeswitch->api("status");
$sofia = $freeswitch->api("sofia status");
```
More `api` commands of `freeswitch` can be executed:
```
$sofia = $freeswitch->api("show api");
```

#### 3、bgapi(api comment,args)

Similar to the `api comment` method, non-blocking mode asynchronous execution:
```
$originate = $freeswitch->bgapi("originate user/1000 &echo");
```

#### 4、events(sorts,args)

The `event` command is used to subscribe to events from `FreeSWITCH`. You can specify all events monitored on the same line, which should be separated by spaces; `sorts`is the return data type and `args` is the monitored event.
```
$status = $freeswitch->events("plain","ALL");
```

#### 5、recvEvent()

It is used in conjunction with `events(sorts,args)`, to obtain all data and return the original data of the server.
```
$received_parameters = $freeswitch->recvEvent();
```
##### a、serialize(received_parameters,type)

`serialize` which returns data in a specified format, is recommended to perform better in the same way as `events`, which has three types of `plain`, `json`and `xml`.
```
$serialize_info = $freeswitch->serialize($received_parameters,"xml");
```

##### b、getHeader(received_parameters,args)

The specified data returned can be retrieved, and empty if it does not exist.
```
$Event_Name = $freeswitch->getHeader($received_parameters,"Event-Name");
```

Example:
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

#### 6、disconnect()

Disconnect `socket` between `php` and `freeswitch`; it is recommended that it be disconnected after each use.
