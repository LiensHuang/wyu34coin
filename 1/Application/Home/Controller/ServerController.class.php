<?php
namespace Home\Controller;
use Think\Controller;

 class ServerController extends Controller{
 	
   //	微信上发送的消息都会被转发到这里来
   	public function myserver(){

		 $wechart=new \Home\Model\MyweixinModel(array(
		      'token' => "weixin",
		      'aeskey' => $encodingAesKey,
		      'appid' => $appId,
		      'debug' => $debugMode
		));
	$wechart->run();
	
   	}

 }


