<?php
namespace Home\Controller;
use Weixin\Wechat;

 class WechatController extends Wechat{
 		
    /**
     * 用户关注时触发，回复「欢迎关注」
     *
     * @return void
     */
    protected function onSubscribe() {
      	$items=array();
		$items[] = new NewsResponseItem(
		  "欢迎关注 34枚金币时间管理公众号-点击进入完善个人信息",        // 图文消息标题
		  "记录自己的时间\n
		  --记录今天\n
		   -早上7点~晚上24点，每半个小时为一枚金币,记录自己的时间去向\n
		  --每日记录\n
		   -查看自己过去某一天的时间记录\n
		  --一周金币秀\n
		   -查看自己过去每一周的时间利用情况\n
		  --金币广场\n
		   -对比别人的时间去向",  // 图文消息描述
		  "http://ww1.sinaimg.cn/bmiddle/e3ab1e9bgw1eveyva5qj0j21hc0xc0wq.jpg",       // 图片链接
		  "http://wyu34coin.sinaapp.com/index.php/Home/User/homePage?opid=".$this->getRequest('FromUserName').""           // 点击图文消息跳转链接
		);
		$this->responseNews($items);
    }

    /**
     * 用户已关注时,扫描带参数二维码时触发，回复二维码的EventKey (测试帐号似乎不能触发)
     *
     * @return void
     */
    protected function onScan() {
      $this->responseText('二维码的EventKey：' . $this->getRequest('EventKey'));
    }

    /**
     * 用户取消关注时触发
     *
     * @return void
     */
    protected function onUnsubscribe() {
      // 「悄悄的我走了，正如我悄悄的来；我挥一挥衣袖，不带走一片云彩。」
    }

    /**
     * 上报地理位置时触发,回复收到的地理位置
     *
     * @return void
     */
    protected function onEventLocation() {
      $this->responseText('收到了位置推送：' . $this->getRequest('Latitude') . ',' . $this->getRequest('Longitude'));
    }

    /**
     * 收到文本消息时触发，回复收到的文本消息内容
     *
     * @return void
     */
     	
		
    protected function onText() {

    	$a="测试中噢哦！！";
    	if(strpos($this->getRequest('content'),"记录")===0&&strlen($this->getRequest('content'))==strlen('记录')){
    		$a="指令为:'记录'+你所要记录的内容";
    	}
		if(strpos($this->getRequest('content'),'记录')===0&&strlen($this->getRequest('content'))!=strlen('记录')){
			$a=substr($this->getRequest('content'),strpos($this->getRequest('content'),'记录')+strlen('记录'));
		}
    $this->responseText($a);
//  	if(($this->getRequest('content'))=="记录"){
//  		$this->responseText("收到了你的记录");
//  	}else{
//  	$items=array();
//		$items[] = new NewsResponseItem(
//		  "登陆34枚金币主页",        // 图文消息标题
//		  "记录自己的时间\n
//		  --记录今天\n
//		   -早上7点~晚上24点，每半个小时为一枚金币,记录自己的时间去向\n
//		  --每日记录\n
//		   -查看自己过去某一天的时间记录\n
//		  --一周金币秀\n
//		   -查看自己过去每一周的时间利用情况\n
//		  --金币广场\n
//		   -对比别人的时间去向",  // 图文消息描述
//		  "http://ww1.sinaimg.cn/bmiddle/e3ab1e9bgw1eveyva5qj0j21hc0xc0wq.jpg",       // 图片链接
//		  "http://wyu34coin.sinaapp.com/index.php/Home/User/homePage?opid=".$this->getRequest('FromUserName').""           // 点击图文消息跳转链接
//		);
//
//		$this->responseNews($items);
//  	}
   
    }
 

    /**
     * 收到图片消息时触发，回复由收到的图片组成的图文消息
     *
     * @return void
     */
    protected function onImage() {
      $items = array(
        new NewsResponseItem('标题一', '描述一', $this->getRequest('picurl'), $this->getRequest('picurl')),
        new NewsResponseItem('标题二', '描述二', $this->getRequest('picurl'), $this->getRequest('picurl')),
      );

      $this->responseNews($items);
    }

    /**
     * 收到地理位置消息时触发，回复收到的地理位置
     *
     * @return void
     */
    protected function onLocation() {
     // $num = 1 / 0;
      // 故意触发错误，用于演示调试功能

      $this->responseText('收到了位置消息：' . $this->getRequest('location_x') . ',' . $this->getRequest('location_y'));
    }

    /**
     * 收到链接消息时触发，回复收到的链接地址
     *
     * @return void
     */
    protected function onLink() {
      $this->responseText('收到了链接：' . $this->getRequest('url'));
    }

    /**
     * 收到语音消息时触发，回复语音识别结果(需要开通语音识别功能)
     *
     * @return void
     */
    protected function onVoice() {
      $this->responseText('亲！你说的是不是：' . $this->getRequest('Recognition'));
    }

    /**
     * 收到自定义菜单消息时触发，回复菜单的EventKey
     *
     * @return void
     */
    protected function onClick() {
      $this->responseText('你点击了菜单：' . $this->getRequest('EventKey'));
    }

    /**
     * 收到未知类型消息时触发，回复收到的消息类型
     *
     * @return void
     */
    protected function onUnknown() {
      $this->responseText('收到了未知类型消息：' . $this->getRequest('msgtype'));
    }
	
	
	public function myserver(){
		echo "oko";
//		 $wechart=new \Home\Controller\WechatController(array(
//		      'token' => "weixin",
//		      'aeskey' => $encodingAesKey,
//		      'appid' => $appId,
//		      'debug' => $debugMode
//		));
//	     $wechart->run();	
 	}
			
		
 }


