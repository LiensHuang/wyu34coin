<?php
namespace Weixin\Controller;
use Think\Controller;

 class WechatController extends Controller{
 	
   //	微信上发送的消息都会被转发到这里来
   	public function myserver(){
		 $wechart=new \Weixin\Model\ServerModel(array(
		      'token' => "weixin",
		      'aeskey' => $encodingAesKey,
		      'appid' => $appId,
		      'debug' => $debugMode
		));
	$wechart->run();
	
   	}

 }


