<?php if (!defined('THINK_PATH')) exit();?><!--<!DOCTYPE html>-->
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="author" content="www.frebsite.nl" />
		<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />

		<title>主页</title>

		<link type="text/css" rel="stylesheet" href="/wyu34coin/1/Public/Css/demo.css" />
		<link type="text/css" rel="stylesheet" href="/wyu34coin/1/Public/Css/jquery.mmenu.all.css" />

		<script src="http://cdn.hcharts.cn/jquery/jquery-1.8.3.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="/wyu34coin/1/Public/Js/jquery.mmenu.min.all.js"></script>
		
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
			<link rel="stylesheet" href="/wyu34coin/1/Public/Css/jquery.mobile-1.3.2.my.css" />
		<!--<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>-->
		
		<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
		<style type="text/css">
			/*#menu2 {
				min-width: none;
				max-width: none;
				width: 100%;
				height: 70%;
			}*/
			.bg-color{
				background-color: #46C6A9;
			}
			.mm-listview li span {
			   font-size: 16px;
			}
			.mm-listview li span small {
			    font-size: 12px;
			}
			.mm-listview li span img {
			   border-radius: 50px;
			   width: 50px;
			   height: 50px;
			   margin: -5px 10px -5px -5px;
			   float: left;
			}
			.mm-listview li:after {
			    left: 75px !important;
			}
		</style>
		<script type="text/javascript">
		
		//“完善信息”-显示提示框，隐藏表单
		function displayTips(){
			$("div#tips_dialog").css("display","block");  //显示提示框
			$("form#renewForm").css("display","none");    //隐藏表单
		}
		
		//“完善信息”-显示用户信息框，隐藏表单和提示框
		function displayUserInfo(){
			$("div#tips_dialog").css("display","none");	
		    $("form#renewForm").css("display","none");
			$("div#UserInfo").css("display","block");	//显示用户信息框
		}
		//“完善信息”-显示表单，隐藏用户信息框和提示框
		function displayFrom(){
			$("div#tips_dialog").css("display","none");
         	$("div#UserInfo").css("display","none");	
           	$("form#renewForm").css("display","block");
		}
		//对话框提示
	function tips(data){	
		if(data=="成功更新信息！"||data=="修改成功！"){  
			//更新信息
			      $("div#tips_dialog p").html("<center>"+data+"</center>"); //提示修改成功
				   displayTips();
			       
			       //获取信息
			       $nickName=$("input#nickname").val();		   
			       $gender= $('input:radio:checked').val();
				   $gender=($gender == 1) ? "男" : "女";
				   //更新左侧菜单用户的信息
				   $("span#nickname p").text($nickName);
				   $("span#nickname small").text($gender);
			    $("button#back").click(function(){		         			         	
		         	//分配信息
		         	$("div#UserInfo li:eq(0) span:eq(1)").html($nickName);
		            $("div#UserInfo li:eq(1) span:eq(1)").html($gender);
		            $("input#modify").val("modify");
		         	displayUserInfo();
		         });
			  
            }else{  
            	$("div#tips_dialog p").html("<center>"+data+"</center>");//提示信息
				displayTips();
				//触动返回键
	              $("button#back").click(function(){			         	
			           	displayFrom(); //显示表单
			         });  
            }
            //更新验证码
            var newUrl=$("img#code").attr("src")+'?'+Math.random();
                  $("img#code").attr("src",newUrl);
		
     }
	
        
        
        //js执行
	$(function() {
		//控制左侧菜单
		$('nav#menu').mmenu({                
               "extensions": ["theme-dark", "effect-listitems-slide"],
	            iconPanels	: {
				add 		: true,
				visible		: 1
			   },counters	: true,
              
                 "slidingSubmenus": false,             
               });
              //控制“完善信息”页面 
            $('nav#renewInfo').mmenu({
               	"extensions": ["theme-dark", "effect-listitems-slide"],
               	"navbar": {
                  "title": "完善信息",
                  add 		: false
               },"navbars": [
                  {
                     "position": "top"
                  }
               ],
	               "offCanvas": {
	                  "position": "right"
	               }
               });
                    
            //表单提交 ，完善信息
           $("#submit").click(function(){ 
             var formData = $("#renewForm").serialize();                 
                //使用ajax提交表单
                $.post("/wyu34coin/1/index.php/Home/User/doRenew",
		  	       formData,  	     
		  	       function(data){
		  	       	 tips(data);
		  	       	
		  	       });		  	        
                return false; 	
            });  
            
            //如果用户已经完善了信息，则直接显示信息            
            if('<?php echo ($nickName); ?>'){
        	   displayUserInfo();	//显示用户信息框
	         	$("input#modify").val("modify");
            }
            //触发修改按钮-“完善信息”
            $("button#modify").click(function(){
            		$("input[name=modify]").val("modify"); //提示值为修改
            	 //获取信息
			       $nickName=$("div#UserInfo li:eq(0) span:eq(1)").text();				       
			       $("input#nickname").val($nickName);	
			       $("div#modifyInfo").remove();	//删除密码输入和验证码输入		  
            	//显示修改框
            	 displayFrom();	         	
            });
                      
 });
		</script>
	</head>
	<body>
		<div id="page">
			<div class="header bg-color">
				<a href="#menu"></a>
				Thirty-four gold coins
			</div>
			 <div id="content" style=" padding: 15px">
                 <ul data-role="listview" data-inset="true" >
		      <li>
			        <a href="/wyu34coin/1/index.php/Home/User/recordToday?opid=<?php echo ($opid); ?>#record" data-ajax="false">
			        <img src="http://ww1.sinaimg.cn/mw690/e3ab1e9bgw1ey9mcguoecg2028028wea.gif"/>
			        <h2>记录今天</h2>
			        <p>早上7点~晚上24点，每半个小时为一枚金币，赶快开始记录自己的时间吧</p>
			        </a>
		      </li>
		      <li>
			        <a href="/wyu34coin/1/index.php/Home/User/dayRecord?opid=<?php echo ($opid); ?>"  data-ajax="false">
			        <img src="http://ww4.sinaimg.cn/mw690/e3ab1e9bgw1ewv9y8l93uj20280280ss.jpg">
			        <h2>每日记录</h2>
			        <p>查看自己某一天的时间记录，还记得那年那天那时你在干嘛吗？</p>
			        </a>
		      </li>
		       <li>
			        <a href="/wyu34coin/1/index.php/Home/User/weekCoinShow?opid=<?php echo ($opid); ?>"  data-ajax="false">
			        <img src="http://ww2.sinaimg.cn/mw690/e3ab1e9bgw1ewv9y97p2gj2028028jrf.jpg">
			        <h2>一周金币秀</h2>
			        <p>统计自己过去每一周的时间利用情况</p>
			        </a>
		      </li>
		       <li>
			        <a href="/wyu34coin/1/index.php/Home/User/goldSquare?opid=<?php echo ($opid); ?>" data-ajax="false">
			        <img src="http://ww4.sinaimg.cn/mw690/e3ab1e9bgw1ewv9y7l83cj2028028wek.jpg">
			        <h2>金币广场</h2>
			        <p>看看别人的时间去哪了吧！</p>
			        </a>
		      </li>
		       <li>
			        <a href="http://m.ac.qq.com">
			        <img src="http://ww4.sinaimg.cn/mw690/e3ab1e9bgw1ewv9y89p7ej20280283yl.jpg">
			        <h2>漫画时间</h2>
			        <p>点击进入你的漫画世界吧</p>
			        </a>
		      </li>
		       <li>
			        <a href="#renewInfo">
			        <img src="http://ww2.sinaimg.cn/mw690/e3ab1e9bgw1ewv9y8vxu4j2028028dfw.jpg">
			        <h2>完善信息</h2>
			        <p>更改匿名||PC端查看记录</p>
			        </a>
		      </li>
		      
		    </ul>
    
		      </div>
		      <div class="footer bg-color" data-position="fixed">
		         Time is my life
		      </div>
		      
			<nav id="menu">				
				<ul>
					<li>
			         <span id="nickname">
			            <img src="http://lorempixel.com/60/60/people/1/" />
			            <p><?php echo ($nickName? $nickName:"匿名"); ?></p>
			            <small><?php echo ($gender); ?></small>
			         </span>
			       </li>
					<li><a>我的金币</a></li>
					<li><a href="#contact">金币记录</a>
						<ul>
							<li><a>一共记录了<?php echo ($coinNumber); ?>枚金币</a></li>
							<li><a>记录了<?php echo ($coinTime); ?>小时</a></li>
							<li><a>已记录<?php echo ($recordDayNum); ?>天</a></li>
						</ul>
					</li>
					<li><a href="#about">时间类型:</a>
						<ul>
							<li style="color: #E4D354;"><a>Quality Work(工作学习)</a></li>
							<li style="color: #F7A35C;"><a>Mandatory Work(被迫忙活)</a>
								
							<li style="color: #90ED7D;"><a>Rest(休息时间)</a></li>
							<li style="color: #7CB5EC;"><a>Guilty Free Play(开心的玩)</a></li>
							<li style="color: #F45B5B;"><a>Procrastination(拖延浪费)</a></li>
						</ul>
					</li>
					<li><a>关于我们</a>
						<ul>
							<center><p>时间团队  座右铭：Time is my life！</p></center>
						</ul>
					</li>
					<li><a> </a></li>
					<li><a>今天是<?php echo ($date); ?>  星期<?php if($weekDay == 1): ?>一
												<?php elseif($weekDay == 2): ?>二
												<?php elseif($weekDay == 3): ?>三
												<?php elseif($weekDay == 4): ?>四
												<?php elseif($weekDay == 5): ?>五
												<?php elseif($weekDay == 6): ?>六
												<?php elseif($weekDay == 7): ?>日<?php endif; ?></a></li>
				</ul>				
			</nav>			
		</div>
		<!--完善信息页-->
		<nav id="renewInfo">
			<div id="renew">					
				<div data-role = "content">							
					<form id="renewForm" >			                           
	                  <input type="text" name="nickname" id="nickname" value="" placeholder="昵称" />  	   
				      <fieldset data-role="controlgroup" >
				      <legend>性别：</legend>
				        <label for="male">男</label>
				        <input type="radio" name="gender" id="male" value="1" checked/>
				        <label for="female">女</label>
				        <input type="radio" name="gender" id="female" value="0"/>	
				      </fieldset>
	                     <div id="modifyInfo">
	                     	<input type="password" name="password" id="password" value="" placeholder="密码"/> 	                      
		                    <input type="password" name="pwd" id="pwd" value="" placeholder="确认密码"/> 		                    		                  
		                    <input type="text" name="checkCode" id="checkCode" value="" placeholder="验证码"/> 
		                    <center>
		                    <img id="code" src="/wyu34coin/1/index.php/Home/Public/code" onclick="this.src=this.src+'?'+Math.random()"/><br />         
		                    </center>
	                     </div>	                                        
	                    <input type="hidden" name="opid" value="<?php echo ($opid); ?>" />
	                    <input type="hidden" name="modify" value=""/>
	                    <button data-theme="b" id="submit" type="submit">确认</button>  	  
					</form>
					<!--完善信息提示框-->
						<div id="tips_dialog" style="display: none;">											
								<center>
									<div id="tips">
										<h3><p></p></h3>
									</div>															
								</center>
							<button id="back">返回</button>
						</div>
						<!--用户信息框-->
						<div id="UserInfo" style="display: none;">		     	
						   	<ul>
						   		<li>
						   			<span>昵称:</span>
						   			<span><?php echo ($nickName); ?></span>
						   		</li>
						   		<li>
						   			<span>性别:</span><span><?php echo ($gender); ?></span>
						   		</li>
						   	</ul>
					   	  <button id="modify">修改</button>
					   </div>
				</div>			
						
		   </div>
		     
		</nav>
		
		
		
	</body>
</html>