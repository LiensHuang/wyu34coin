<?php
	/*
 * 微信上的登陆模块o
 */
namespace Home\Controller;
use Think\Controller;

 class RenewController extends Controller {
	public function renew(){
		$opid=I('opid');	
       
		$this->assign('opid',$opid);//接受URL上传来的opid
		$this->display();
	}
	
	public function doRenew(){
		$checkCode=I('checkCode');//接收用户验证码
		//判断验证码是否输入正确
		$verify = new \Think\Verify();
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
		        $User->add();
				echo "成功更新信息！";
			}
		}else{
			echo "验证码错误！";
		}
	}
}
	
?>
