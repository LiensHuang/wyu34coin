<?php
namespace Weixin\Model;
use Weixin\Wechat;
use Weixin\NewsResponseItem;
/**
 * 微信公众平台 PHP SDK 示例文件
 *
 * @author NetPuter <netputer@gmail.com>
 */

  //require('./WechatModel.class.php');

  /**
   * 微信公众平台演示类
   */
  class ServerModel extends Wechat{

    /**
     * 用户关注时触发，回复「欢迎关注」
     *
     * @return void
     */
    protected function onSubscribe() {
    	$this->responseText("欢迎关注-还在测试噢，尽请期待！");
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
     * 根据用户发送的消息内容，做出回复判断,返回要回复的内容
     *
     * @return void
     */		
     protected function checkContent($text,$opid){
     
        //如果用户只发送“记录”两个字，则提示用户记录的指令，如果要记录的内容，则返回要加记录的内容
     	if(strpos($text,"记录")===0&&strlen($text)==strlen('记录')){
    		return "指令为:\n记录+你所要记录的内容\n+\n类型+时间类型(目前的类型只有:玩,休息,工作学习,被迫忙活,拖延)\n+\n地点+你所在地点\n\n例如：\n记录我在学习英语\n类型工作学习\n地点图书馆";
			
    	}else if(strpos($text,'记录')===0&&strlen($text)!=strlen('记录')){
			$coinDes=substr($text,strpos($text,'记录')+strlen('记录'),strpos($text,'类型')-6);
		    $coinType=substr($text,strpos($text,'类型')+strlen('类型'),strpos($text,'地点')-strpos($text,'类型')-6);
		    $coinLocation=substr($text,strpos($text,'地点')+strlen('记录'));		
			
			if(ctype_space($coinDes)||ctype_space($coinType)||ctype_space($coinLocation)||$coinLocation==""){
				return "输入为空 或者 输入有误噢！";
			}
			      
			if(strpos($coinType,"玩")===0){
				$coinType="l";	     
			}elseif (strpos($coinType,"休息")===0) {
				$coinType="m";	        
			}elseif (strpos($coinType,"工作学习")===0) {
				$coinType="n";	       
			}elseif (strpos($coinType,"被迫忙活")===0) {
				$coinType="o";	       
			}elseif (strpos($coinType,"拖延")===0) {
				$coinType="p";	        
			}else{
				return "指令输入有误噢(时间类型)！";
			}
			
			$coinNum=""; //用于记录是第几枚金币
			$nowTime=date('Gi',time());   //当前的时间，格式为：2030 表示20:30			
			$start=700;$end=730;	 		
			for ($i=1; $i <=34 ; $i++) {			 
				if($start<=$nowTime&&$nowTime<$end){
					  $coinNum=$i; //记录是第$i枚金币					  
					  break;
				}else{
					$start=$end;
					if(!($i%2==0)){
						//循环34次，如果$i是基数，则加70，否则加30
						$end=$start+70;
					}else{
						$end=$start+30;
					}				
				}				
			}

			//如果时间过了24点，或者还没到早上7点
			if($coinNum==""){
				return "主人，小的已睡，到点了再帮您记录啦！";
			}
				//准备条件,到数据库去查询，看看用户是否记录的某个时间段的金币，coinNum表示的是一个时间段代号
				$condition=array(
				 'opid'=>$opid,
				 'date'=>date('Y/n/j',time()),
				 'coinNum'=>$coinNum
				   );
				$Information=M('Information');//对应information表，实例化一个对象
				
			if ($Information->where($condition)->find()){
				return "该时间段你已经记录过了！";
			}else{
																				
			    $data=array(
			    'opid'=>$opid,
			    'date'=>date('Y/n/j',time()),
			    'coinNum'=>$coinNum,
			    'coinDes'=>$coinDes,
			    'coinType'=>$coinType,
			    'coinLocation'=>$coinLocation
				); 				
				$Information->add($data);
				
					    $record=M('Record');       //实例化record对象
					    //创建条件，			 			
						$map['coinDate'] = date('Y/n/j',time()); 
						$map['opid'] = $opid;	
					if(!$record->where($map)->find()){			
//						 $data=array(
//						       'opid'=>$opid,
//						       'coinDate'=>date('Y/n/j',time()),
//						 );
						 //$record->create($map);
						 $record->add($map);       //opid,coinDate添加进入coin_record表
					}	 	
						//对应的金币类型加1
						 switch ($coinType) {
						 	case 'l': $record->where($map)->setInc('guiltFreePlay');
						 		
						 		break;
							case 'm':$record->where($map)->setInc('rest');
						 		
						 		break;	
						 	case 'n':$record->where($map)->setInc('mandatoryWork');
						 		
						 		break;
							case 'o':$record->where($map)->setInc('qualityWork');
						 		
						 		break;
							case 'p':$record->where($map)->setInc('procrastination');
						 		
						 		break;
						 	default:
						 		
						 		break;
						 }	
         	   return 1;			    
			}			
		}else{
			return 2;
		}
     
	 }
    /**
     * 收到文本消息时触发，回复收到的文本消息内容
     *
     * @return void
     */		
    protected function onText() {
		
		  $replyContent=$this->checkContent($this->getRequest('content'),$this->getRequest('FromUserName'));
		    if($replyContent=='1'){
		    	$items=array();
				$items[] = new NewsResponseItem(
				  "已经帮你做记录了-点击查看吧",        // 图文消息标题
				  "记录自己的时间",  // 图文消息描述
				  "http://ww1.sinaimg.cn/bmiddle/e3ab1e9bgw1eveyva5qj0j21hc0xc0wq.jpg",       // 图片链接
				  "http://wyu34coin.sinaapp.com/index.php/Home/User/homePage?opid=".$this->getRequest('FromUserName').""           // 点击图文消息跳转链接
				);
		
				$this->responseNews($items);
		    }elseif ($replyContent=="2") {
		    	$items=array();
				$items[] = new NewsResponseItem(
				  "登陆34枚金币主页",        // 图文消息标题
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
		    }else{
		    	$this->responseText($replyContent);
		    }
     
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
     
     /**
     * 收到微信小视频类型消息时触发
     *
     * @return void
     */
	protected function onVideo() {
		$this->responseText('收到了微信小视频');
	}
     
    protected function onUnknown() {
      $this->responseText('收到了未知类型消息：' . $this->getRequest('msgtype'));
    }

  }

//$wechat = new ServerModel(array(
//    'token' => "weixin",
//    'aeskey' => $encodingAesKey,
//    'appid' => $appId,
//    'debug' => $debugMode
//));
//$wechat->run();
