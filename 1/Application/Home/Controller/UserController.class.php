<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller{
	public function homePage(){
		$opid=I('opid');	      
		$User=M('User');
		
		$where['opid']=$opid;
		$UserData=$User->where($where)->find();//取出用户信息
		
		$Record=M('Record');
		$recordDayNum=$Record->where($where)->Count();
	
		$this->assign('nickName',$UserData['nickname']);
		$gender=($UserData['gender']== '1'? '男' : '女') ;
		
		$this->assign('gender',$gender);  //性别
		$this->assign('opid',$opid);//接受URL上传来的opid
		$this->assign('date',date('Y年n月j日',time()));
		$this->assign('date2',date('Y-m-d',time()));
		$this->assign('weekDay',date('N',time()));				
			
		$this->assign('coinNumber',$UserData['coinnumber']);  //显示记录了几枚金币
		$this->assign('coinTime',$UserData['coinnumber']*0.5); //记录的小时数目
		$this->assign('recordDayNum',$recordDayNum); //记录的天数
							
		$this->display();
	}
	
	//接收并添加金币信息
	public function receiveCoinText(){
		    			
		     $information=M("Information");   //实例化information对象（对应金币信息表）
		     $num=I('num');
			 $coinType=I('coinType');	
			 $timeSlot=I('timeSlot');
			 $timeSlot=substr($timeSlot,0,strpos($timeSlot,' ')); //截掉后面的英文字符	    
			 $recordTime=I('date',date('Y/n/j',time()),'htmlspecialchars'); //今天的时间 格式：2015/10/1
			
			 $information->create();          //创建information数据对象！
			 $information->date=$recordTime;  //记录时间	
			 $information->timeSlot=$timeSlot;       
			 $information->add();      //插入表coin_information
			 				 
			 $record=M('Record');       //实例化record对象
		    //创建条件，			 			
			$map['coinDate'] = $recordTime; 
			$map['opid'] = I('opid');	
		if(!$record->where($map)->find()){			
			 $data=array(
			       'opid'=>I('opid'),
			       'coinDate'=>$recordTime,
			       'week'=>date('N',time())  //星期中的第几天（PHP 5.1.0 新加） 1（表示星期一）到 7（表示星期天） 
			 );
			 $record->create($data);
			 $record->add();       //opid,coinDate添加进入coin_record表
		}	 	 	
			//对应的金币类型加1
			 $coinType=I('coinType');
			 switch ($coinType) {
			 	case 'l': $record->where($map)->setInc('guiltFreePlay');
			 		
			 		break;
				case 'm':$record->where($map)->setInc('rest');
			 		
			 		break;	
			 	case 'n':$record->where($map)->setInc('qualityWork');
			 		
			 		break;
				case 'o':$record->where($map)->setInc('mandatoryWork');
			 		
			 		break;
				case 'p':$record->where($map)->setInc('procrastination');
			 		
			 		break;
			 	default:
			 		
			 		break;
			 }	
			 //记录的金币总数加1	
			 $User=M('User');
			 $User->where('opid="'.$map['opid'].'"')->setInc('coinNumber');			 	   
	}

    //获取某一天的information表的信息，返回json数据
    public function getCoinInformationByJson(){
    	$mark=I('mark'," ",'htmlspecialchars');
		
    	$information=M("Information");   //实例化information对象（对应金币信息表）
    	$where['opid']=I('opid');
		$where['date']=I('coinDate',date('Y/n/j',time()),'htmlspecialchars');
		
		$coinInformation=$information->where($where)->order('coinNum asc')->select();   //注意：数据库取出的所有字段名都会变成小写的，如coinDes1变成coindes1
	   
	   //如果$mark=="dayRecord"则表示是dayRecord页面要数据
	   if($mark=="dayRecord"){
	   	  $this->ajaxReturn($coinInformation); //返回json格式数据对象
	   }else{
		   	$Record=M('Record');
			$where['coinDate']=$where['date'];  //record表里的日期字段叫coinDate		
			$recordData=$Record->where($where)->find(); 
				   
		    $coinInformation[]=$recordData['coindaydes'];  //将对应日期的今日小结数据添加到其中
			
			$this->ajaxReturn($coinInformation); //返回json格式数据对象					
	   }
	    
    }
	
    /*
	 * 
	 * 获取information表一周的信息
	 * 
	 */
 
   public function getWeekCoinInfo(){       	
		$time=I('coinDate',date('Y/n/j',time()),'htmlspecialchars');
				
		$weekStart=$this->getWeekStart($time);		
		$weekEnd=date('Y/n/j',strtotime("$weekStart +6 days"));	
		
		$where['opid']=I('opid');
		$where['date']=array('between',array($weekStart,$weekEnd));	
			
		$InfoView=D("InfoView");   //实例化InfoView视图对象（对应金币信息表和record表）
		//echo $InfoView->fetchSql(true)->where($where)->order('coinType,week')->select();
		$WeekInfo=$InfoView->where($where)->order('coinType,week,coinNum')->select();
		
		//var_dump($WeekInfo);
		$this->ajaxReturn($WeekInfo);
   }

	//保存今日小结
	public function saveCoinDayDes(){
		$opid=I('opid');
		$coinDayDes=I('coinDayDes');
		$recordTime=I('date',date('Y/n/j',time()),'htmlspecialchars'); //今天的时间 格式：2015/10/1 //今天的时间 格式：2015/10/1
		$record=M('Record');       //实例化record对象
		    //创建条件，			 			
			$map['coinDate'] = $recordTime; 
			$map['opid'] = I('opid');	
		if($record->where($map)->find()){			
			 $data['coinDayDes']=$coinDayDes;			
			 $record->where($map)->save($data);       //coinDayDes保存进入coin_record表
		}
     echo"今日小结成功！";
	}
	
	//每日记录
	public function dayRecord(){
		$opid=I('opid');
		$time=I('date',date('Y/n/j',time()),'htmlspecialchars');
					
		    //创建条件，			 			
			$map['coinDate'] = $time; 
			$map['opid'] = I('opid');	
		$Record=M('Record');       //实例化record对象
		$recordData=$Record->where($map)->find();
		
		$coinNum=$recordData['guiltfreeplay']+$recordData['rest']+$recordData['mandatorywork']+$recordData['qualitywork']+$recordData['procrastination'];
		$recordData['coindate']= isset($recordData['coindate']) ? $recordData['coindate'] : date('Y/n/j',time());
		$this->assign('coinNum',$coinNum);
		$this->assign('date',$recordData['coindate']);
		$this->assign('opid',$opid);//接受URL上传来的opid
		$this->display();
	}

  //根据用户选择的日期，获取record表的信息，
	public function getRecordByDate(){
        $Record=M('Record');       //实例化record对象
    	$where['opid']=I('opid');	
		$where['coinDate']=I('coinDate'); //今天的时间 格式：2015/10/1
		$coinRecord=$Record->where($where)->find();   //注意：数据库取出的所有字段名都会变成小写的，如coinDes1变成coindes1
		$this->ajaxReturn($coinRecord); //返回json格式数据对象
				
	}
    //根据用户选择的日期，获取record表的信息(获取多个用户，本用户的信息放在数组最后)，
	public function getAllRecordByDate(){
        $Record=D("Record");       //实例化record对象
    	$where['opid']=I('opid');	
		$where['coinDate']=I('coinDate',date('Y/n/j',time()-24*3600),'htmlspecialchars'); //今天的时间 格式：2015/10/1
		//$where['coinDate']=""
		//根据日期，取出opid不等于正在使用的用户的opid的所有数据
	   $coinRecord=$Record->relation(true)->limit(10)->where('opid!="'.$where['opid'].'" AND coindate="'.$where["coinDate"].'" ')->select();   //注意：数据库取出的所有字段名都会变成小写的，如coinDes1变成coindes1
	   //根据日期，取出opid等于某一值的数据
	   $opidRecord=$Record->relation(true)->where('opid="'.$where['opid'].'" AND coindate="'.$where["coinDate"].'" ')->find();
	 
	   $coinRecord[]=$opidRecord;  //将取出的单一的数据，加到$coinRecord数组最后，保证该数据在数组的最后
       
	   
        //实例化一个点赞视图模型
	   $GoogView=D('RecordView');
	   $goodRecord = $GoogView->where($where['coinDate'])->select();//取出当天点赞人的昵称
        
        //实例化一个评论视图模型
        $CommentView=D('CommentView'); 
		$commentRecord=$CommentView->where($where['coinDate'])->select(); //取出当天评论人的昵称和评论内容
	   
	   for($i=0;$i<count($coinRecord);$i++){
	   	  //为每个消息添加一个存放评论人的数组
	   	  if($coinRecord[count($coinRecord)-1]){
	   	  	$this->addkey($coinRecord[$i], array('key'=>'commentators', 'val'=>array()));
	   	  }	   	  	   	   
	   	   //将点赞的人的昵称对比他们点赞的消息内容，插入数组中
	   	   for($j=0;$j<count($goodRecord);$j++){
	   	   	  if($coinRecord[$i]['id']==$goodRecord[$j]['id']){
	   	   	  	  if($goodRecord[$j]['nickname']){
	   	   	  	  	$coinRecord[$i]['Likes'][]=$goodRecord[$j]['nickname'];			
	   	   	  	  }else{
	   	   	  	  	$coinRecord[$i]['Likes'][]="匿名";
	   	   	  	  }	   	   	       
	   	      }
	   	   }
		   
		   //将评论人和评论内容
		   for($k=0;$k<count($commentRecord);$k++){
		   	   if($coinRecord[$i]['id']==$commentRecord[$k]['id']){
			   	  if($commentRecord[$k]['commentators']){
			   	  	$coinRecord[$i]['commentators'][]=$commentRecord[$k]['commentators'];
			   	  }else{
			   	  	$coinRecord[$i]['commentators'][]="匿名";
			   	  }
				  //插入评论的内容
				  $coinRecord[$i]['Discuss'][]=$commentRecord[$k]['discuss'];
			   }
		   }			   	   	   
	   }
	   
	     
		//var_dump($coinRecord);
		$this->ajaxReturn($coinRecord); //返回json格式数据对象
				
	}
	
	//一周金币秀(显示界面)
	public function weekCoinShow(){
		$opid=I('opid');
		$time=I('date',date('Y/n/j',time()),'htmlspecialchars');
		$this->assign('opid',$opid);//接受URL上传来的opid
        $this->assign('date',$time);
		$this->display();
	}
	
	
	public function getWeekCoinRecord(){
		$opid=I('opid');
		$time=I('coinDate',date('Y/n/j',time()),'htmlspecialchars');
		$weekStart=$this->getWeekStart($time);		
		$weekEnd=date('Y/n/j',strtotime("$weekStart +6 days"));	
		
	    //创建条件，			 						
		$map['opid'] = I('opid');	
		$map['coinDate']  = array('between',array($weekStart,$weekEnd));
			
		$Record=M('Record');       //实例化record对象
		$recordData=$Record->where($map)->order('coinDate asc')->select();
		//var_dump($recordData);
		$this->ajaxReturn($recordData);
	   
	}
	//通过某一个具体日期，获取一年中某一周的开始日期
	public function getWeekStart($date) {
		
		if($date){
			$sdefaultDate =$date;
		}else{
			$sdefaultDate =$time;
		}
			
		//$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
		
		$first=1;
		
		//获取当前周的第几天 周日是 0 周一到周六是 1 - 6
		
		$w=date('w',strtotime($sdefaultDate));
		
		//获取本周开始日期，如果$w是0，则表示周日，减去 6 天
		
		$weekStart=date('Y/n/j',strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days'));		
			
		return $weekStart;

	}
	
	//通过post请求，获取一周的开始日期和结束日期
	public function getWeekStartAndEnd(){
		$time=I('coinDate',date('Y/n/j',time()),'htmlspecialchars');
		$weekStart=$this->getWeekStart($time);
		$weekEnd=date('Y/n/j',strtotime("$weekStart +6 days"));	
		
		$week=array("weekStart"=>$weekStart,"weekEnd"=>$weekEnd);
		echo json_encode($week);
	}
	
	//显示“记录今天”
	public function recordToday(){
		$opid=I('opid');	      
		$this->assign('opid',$opid);//接受URL上传来的opid
		$this->assign('date',date('Y年n月j日',time()));
		$this->assign('date2',date('Y-m-d',time()));				
		$this->display();
	}
	//显示金币广场
	public function goldSquare(){
		$opid=I('opid');	   
		$User=M('User');
		$name=$User->where('opid="'.$opid.'"')->getField('nickname');
		$nickname = ($name) ? $name:"匿名" ;
		
		$this->assign('opid',$opid);//接受URL上传来的opid
		//$this->assign('date',date('Y/n/j',time()-24*3600));//显示昨天的金币广场	
		$this->assign('date',I('coinDate',date('Y/n/j',time()-24*3600),'htmlspecialchars'));//显示昨天的金币广场	
		$this->assign('today',date('Y/n/j',time()));  //显示今天的日期
		$this->assign('sid',date('Y/n/j',time()-24*3600));	
		$this->assign('nickname',$nickname);	
		//$this->assign('id',I('opid'));  
		
		$this->display();
	}
	
  
	  //更新信息
	  public function doRenew(){
		$checkCode=I('checkCode');//接收用户验证码
		$where['opid']=I('opid');
		$mark=I('modify');  //判断是否是完善信息还是修改信息的标志
		//判断验证码是否输入正确
		$verify = new \Think\Verify();
		if($mark){
			//修改信息			
			$rules = array(
				     array('nickname','require','请输入昵称！'), 				     
				);				
				$User = M("User"); // 实例化User对象
				if (!$User->validate($rules)->create()){
				     // 如果创建失败 表示验证没有通过 输出错误提示信息
				     exit($User->getError());
				}else{
					$User->where($where)->save(); // 根据条件更新记录
					echo "修改成功！";
				}
		}else{
			//完善信息
			 //检验验证码	  
			if($verify->check($checkCode)){
				$rules = array(
				     array('nickname','require','请输入昵称！'), 
				     array('nickname','','该昵称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一				 			
				     array('password','require','请输入密码！'), 
				     array('pwd','require','请确认密码！'), 
				     array('pwd','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
				);
				
				$User = M("User"); // 实例化User对象
				if (!$User->validate($rules)->create()){
				     // 如果创建失败 表示验证没有通过 输出错误提示信息
				     exit($User->getError());
				}else{
					$User->password=md5($User->password);//加密用户密码！
				    $User->uptime=time(); //添加更新信息的时间戳
			        $User->where($where)->save();
					echo "成功更新信息！";
				}
			}else{
				echo "验证码错误！";
			}
		}		   
	} 

    /*
	 * 
	 * 金币广场点赞
	 * 
	 * */
	 
	public function good(){
		$LIKES=M('Likes');//实例化点赞对象
		$where['informationId']=I('id');//被点赞内容的id
		$where['opid']=I('opid');       //点赞人
		
		if(!$LIKES->where($where)->find()){
			$where['pointTime']=time();
			$LIKES->add($where);
			echo "1";//点赞成功！
		}else{
			echo "0";//已经点赞过了
		}

	} 
	
	/*
	 * 
	 * 金币广场 针对某一条消息进行评论
	 * */
	 public function comment(){
	 	$Comment=M('Discuss');          //实例化评论表对象
		$where['coinDaydesId']=I('id');//被评论内容的id
		$where['discuss']=I('discuss');     //评论内容	
		$where['commentatorOpid']=I('opid');     //评论人
		$where['discussTime']=time();		
		//如果插入到数据库成功，则返回1
		if($Comment->add($where)){	
			echo "1";//评论成功！
		}else{
			echo "0";//评论失败
		}
	 }
	 
	 
	//在二维数组里添加元素的添加方法：
	public function addkey(&$val,$param) {
		$val[$param['key']] = $param['val'];
	}
     
   //测试方法
  public function test(){
		
    	$this->display();
    } 
   public function times(){
		
    	$this->display();
    } 
    public function record(){
		
    	$this->display();
    } 
	
	 public function shiping(){
		
    	$this->display();
    } 
	  public function tupian(){
		
    	$this->display();
    } 
	  public function yuying(){
		
    	$this->display();
    } 
	  
}


?>