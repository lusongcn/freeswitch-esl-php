<?php
require_once 'freeSwitchEsl.php';
$freeswitch = new Freeswitchesl();
$connect = $freeswitch->connect("127.0.0.1","8021","ClueCon");
if ($connect) {
	$version = $freeswitch->api("version");
	var_dump($version);
	// plain、json和xml三种;监听内容可以添加ALL，多个可以用空格隔开
	// $status = $freeswitch->events("plain","ALL");
	// while (true) {
	// 	$received_parameters = $freeswitch->recvEvent();
	// 	if (!empty($received_parameters)) {
	// 		// 格式化参数,第一个参数为recvEvent的参数，第二个位您想要返回的参数
	// 		$info = $freeswitch->serialize($received_parameters,"xml");
	// 		// 监听指定字段
	// 		$Event_Name = $freeswitch->getHeader($received_parameters,"Event-Name");
	// 		echo $Event_Name;
	// 		$uuid = $freeswitch->getHeader($received_parameters,"Channel-Call-UUID");
	// 		var_dump($uuid);
	// 		if (!empty($uuid)) {
	// 			$freeswitch->executeAsync("playback","local_stream://moh",$uuid);
	// 			$freeswitch->executeAsync("hangup","",$uuid);
	// 		}
	// 	}
	// }
}
$freeswitch->disconnect();