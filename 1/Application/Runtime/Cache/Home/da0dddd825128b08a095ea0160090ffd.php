<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="author" content="www.frebsite.nl" />
		<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />
		<title>金币广场</title>
			<!--Bootstrap-->
		<!-- 新 Bootstrap 核心 CSS 文件 -->
      <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
		
		<!--引入highcharts-->
		<script src="http://cdn.hcharts.cn/jquery/jquery-1.8.3.min.js" type="text/javascript"></script>
        <script src="http://cdn.hcharts.cn/highcharts/highcharts.js" type="text/javascript"></script> 	
	    <script src="http://code.highcharts.com/highcharts-3d.js" type="text/javascript"></script> 
	    <script src="http://code.highcharts.com/highcharts-more.js"></script>
	    <script type="text/javascript" src="/wyu34coin/1/Public/Js/collapse.js" ></script>
		<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
		<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<!--Bootstrap日期-->	
	<link href="/wyu34coin/1/Public/Css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
	<script src="/wyu34coin/1/Public/Js/bootstrap-datetimepicker.js" charset="UTF-8"></script>	
	<script type="text/javascript" src="/wyu34coin/1/Public/Js/bootstrap-datetimepicker.zh-TW.js" charset="UTF-8"></script>
		<script>	
		function getRandStr(){
	      $name=new Array('黄飞鸿','郭靖','猪八戒','孙悟空','唐僧','沙僧','白龙马','灰太狼',
			'江流','唐三藏','陈玄奘','金蝉子','孙行者','美猴王','齐天大圣','斗战胜佛',
	      	'猪悟能','猪刚烈','顾昂谋','王武','张三','李四','唐太宗','卷帘大将',
	      	'金身罗汉','魏征','沙悟净','程咬金','刘洪飞','徐茂公','秦叔宝','李元吉',
	      	'李世明','','李元霸','李自成','杨广','尹文娇','马三宝','张旺','杨奇',
	      	'聂风','步惊云','雄霸','断浪','小灰灰','小炼炼'
			);
			//parseInt(Math.random()*(45+1),10);

		$num = Math.floor(Math.random()*(45+1));  			
		return $name[$num];
    }
	  //根据日期取出10个用户的记录（包括自己）
	   	 function getAllRecordInfo($date){   	 	
	  	    $.post(
	   	     	 	"/wyu34coin/1/index.php/Home/User/getAllRecordByDate",
	   	     	 	{
	   	     	 	  coinDate:$date,
	   	     	 	  opid:'<?php echo ($opid); ?>'
	   	     	 	},	   	     	 	 
	   	     	     function(data,status){	
	   	     	     	//如果data为空，即表示当天连同用户自己在内，都没有时间记录！
	   	     	     	if(data==""){
	   	     	     		$("div#myTime").empty();  //清空数据
	   	     	     	    $("div#others").empty();  //清空数据	
		   	     	     	$("div#others").append("<center><h4>"+$date+"<br/>没有任何人的时间记录哦！<br/>包括你自己</h4></center>"+
		   	     	     	                      "<hr style='border:1px solid #2C3E50'/>");
		   	     	     	return;
	   	     	     	  }
	   	     	     	
	   	     	     	var length=data.length; 
	   	     	     	//alert(data[2]['Likes'].length);
	   	     	     	//如果当天有当前用户的记录
	   	     	     	if (data[length-1]) {
		   	     	     	var guiltfreeplay=Number(data[length-1].guiltfreeplay)*0.5;
		   	     	     	var rest=Number(data[length-1].rest)*0.5;	   	     	     	
		   	     	     	var mandatorywork=Number(data[length-1].mandatorywork)*0.5;
		   	     	     	var qualitywork=Number(data[length-1].qualitywork)*0.5;
		   	     	     	var procrastination=Number(data[length-1].procrastination)*0.5;
		   	     	     	var $MycoinNum=guiltfreeplay+rest+mandatorywork+qualitywork+procrastination;
		   	     	     } else{		   	     	  
		   	     	     	var guiltfreeplay=0;
		   	     	     	var rest=0;	   	     	     	
		   	     	     	var mandatorywork=0;
		   	     	     	var qualitywork=0;
		   	     	     	var procrastination=0;
		   	     	     	var $MycoinNum=0;	   	     	     	
		   	     	     }
		   	     	     
		   	     	     var OtherGuiltfreeplay = new Array();
		   	     	     var OtherRest = new Array();
		   	     	     var OtherMandatorywork = new Array();
		   	     	     var OtherQualitywork = new Array();
		   	     	     var OtherProcrastination = new Array();
		   	     	     var $coinNum = new Array();  
		   	     	     var nickName = new Array();
		   	     	     var coindaydes = new Array();
		   	     	     var $title = new Array();	   	     	     
		   	     	     var picNum;
		   	     	     if(length==1){
		   	     	       var arrLength=0;
		   	     	     }else{
		   	     	     	//var arrLength=Number(length)-2;
		   	     	     	var arrLength=Number(length)-1;
		   	     	     }
		   	     	    
		   	      $("div#myTime").empty();  //清空数据	 	     
		   	      $("div#others").empty();  //清空数据		   	     
	   	     	for (var i=0;i<=arrLength;i++) { 
	   	     	  	if(data[i]){
	   	     	  	//当length为零时，代表当前使用的只有一个用户而已，
	   	     	     if (length==1) {   	     	     	        
	   	     	     			 nickName[i]=(data[i].nickname)?data[i].nickname:"匿名";
	   	     	     			 coindaydes[i]=data[i].coindaydes;
	   	     	     			 $title[i]="这一天只有你记录了时间哦！";   	     	     			 
	   	     	     		} else{	   	     	     			
		   	     	     		    if (data[i]&&i!=arrLength) {
					   	     	     	 OtherGuiltfreeplay[i]=Number(data[i].guiltfreeplay)*0.5;
					   	     	     	 OtherRest[i]=Number(data[i].rest)*0.5;	   	     	     	
					   	     	     	 OtherMandatorywork[i]=Number(data[i].mandatorywork)*0.5;
					   	     	     	 OtherQualitywork[i]=Number(data[i].qualitywork)*0.5;
					   	     	     	 OtherProcrastination[i]=Number(data[i].procrastination)*0.5;
					   	     	     	 $coinNum[i]=OtherGuiltfreeplay[i]+OtherRest[i]+OtherMandatorywork[i]+OtherQualitywork[i]+OtherProcrastination[i];
						   	     	     //当用户没有昵称时，显示“匿名”
				   	     	     		if(!data[i].nickname){
				   	     	     				nickName[i] =getRandStr();
				   	     	     			}else{
				   	     	     				nickName[i] =data[i].nickname;
				   	     	     			}	   	     	     			 
				   	     	     			 coindaydes[i]=data[i].coindaydes;
				   	     	     			 $title[i]=nickName[i]+" 当天一共记录了"+($coinNum[i])+"小时";			   	     	     		        	     	     		    
		   	     	     		    
		   	     	     		    }else{
						   	     	     	 OtherGuiltfreeplay[i]=0;
						   	     	     	 OtherRest[i]=0;	   	     	     	
						   	     	     	 OtherMandatorywork[i]=0;
						   	     	     	 OtherQualitywork[i]=0;
						   	     	     	 OtherProcrastination[i]=0; 
						   	     	     	 $coinNum[i]=0;
			   	     	                    }
							   	     	
	   	     	     		}
					           			   	     	     		
	   	     	       picNum=i+1;//图片标号
	   	     	       var $div="others";
	   	     	       if (i==arrLength) {
	   	     	       		   	     	       	
		   	     	       $div="myTime";
		   	     	       nickName[i]=(data[i].nickname)?data[i].nickname:"匿名";
	   	     	     	   coindaydes[i]=data[i].coindaydes;
	   	     	       }	  	     	       
	   	     	       	$("div#"+$div+"").append("<span><img src='https://dn-coding-net-production-avatar.qbox.me/Fruit-"+picNum+".png?imageMogr2/thumbnail/60' alt='头像' class='img-circle'>"+
					    ""+nickName[i]+"</span>"+
					    "<div id='Histogram"+i+"' style='min-width:80px;height:100%;'></div>"+
					    "<div style='background-color: #DBC59E;'>"+$date+"小结:<span>"+coindaydes[i]+"</span></div>"+
					    "<div id='"+i+"'><span style='display: none;' class='glyphicon glyphicon-heart'></span></div>"+
					    "<div style='background-color:#CDCDCD; border-radius: 3px;' name='comment"+i+"'></div>"+
					    "<div class='input-group' style='margin-top: 3px;'>"+
	                    "<input name='comment"+i+"' class='form-control' size='16' type='text' value='' placeholder='我也来说一句……'>"+
	                    "<span id='"+i+"'  name='comment' class='input-group-addon'><span class='glyphicon glyphicon-comment'></span></span>"+
						"<span id='"+i+"'  name='zan' class='input-group-addon'><span class='glyphicon glyphicon-thumbs-up'></span></span>"+
	                    "</div>"+
					    "<hr style='border:1px solid #2C3E50'/>");
	   	     	      	   	     	     
					    
					    //为点赞用的<span>元素添加存储数据值，
					    $("span[id="+i+"]").data('id',i);
		   	     	    $("span[id="+i+"]").data('commtentId',data[i].id);
		   	     	    //如果该消息有人点赞则将点赞的人显示出来
		   	     	    if(data[i]['Likes']){
		   	     	    	$("div[id="+i+"] span").css("display","inline-block");
		   	     	    	for(var j=0;j<data[i]['Likes'].length;j++){
		   	     	    		  $("div[id="+i+"]").append(data[i]['Likes'][j]+"、");
		   	     	    	}					      
		   	     	    }	
		   	     	    //如果该消息有人评论，则显示出来
		   	     	    if(data[i]['commentators']){	     	    	
		   	     	    	for(var j=0;j<data[i]['commentators'].length;j++){
		   	     	    		$("div[name=comment"+i+"]").append("<font color='#0088CC'><b>"+data[i]['commentators'][j]+":</b></font>"+data[i]['Discuss'][j]+"<br/>");
		   	     	    	
		   	     	    	}					      
		   	     	    }	
					           //报表
					    if (i==arrLength) {
					    	   $('#Histogram'+i+'').highcharts({   //图表展示容器，与div的id保持一致
								        credits:{
								        	enabled:false
								        },
								        chart: {
								            type: 'column'  ,//指定图表的类型，默认是折线图（line）						            
								        },
								        title: {
								            text:  "我的时间记录"  //指定图表标题
								        },
								        subtitle: {
										    text: '我当天一共记录了'+$MycoinNum+'小时'
										},
								        xAxis: {
								            categories: ['开心的玩', '休息', '工作学习','被迫忙活','拖延浪费']   //指定x轴分组
								        },
								        yAxis: {
								            title: {
								                text: '时间（h）'                  //指定y轴的标题
								            }
								        },
								        tooltip: {
								            valueDecimals: 2,		            
								            valueSuffix: '小时'
						               },
								        series: [{                                 //指定数据列
								            name: '我的时间',  	                         //数据列名
						   		            data: [{y:guiltfreeplay,color:"#7CB5EC"},
								                   {y:rest,color:"#90ED7D"},
								                   {y:qualitywork,color:"#E4D354"},
								                   {y:mandatorywork,color:"#F7A35C"},
								                   {y:procrastination,color:"#F45B5B"}]
						   		                 }]                   						        
								    });
		   	     	   
	   	     	       } else{
	   	     	       	     $('#Histogram'+i+'').highcharts({   //图表展示容器，与div的id保持一致
								        credits:{
								        	enabled:false
								        },
								        chart: {
								            type: 'column'  ,//指定图表的类型，默认是折线图（line）						            
								        },
								        title: {
								            text:   $title[i]  //指定图表标题
								        },
								        subtitle: {
										    text: '我当天一共记录了'+$MycoinNum+'小时'
										},
								        xAxis: {
								            categories: ['开心的玩', '休息', '工作学习','被迫忙活','拖延浪费']   //指定x轴分组
								        },
								        yAxis: {
								            title: {
								                text: '时间（h）'                  //指定y轴的标题
								            }
								        },
								        tooltip: {
								            valueDecimals: 2,		            
								            valueSuffix: '小时'
						               },
								        series: [{                                 //指定数据列
								            name: nickName[i],  	                         //数据列名
						   		            data: [{y:OtherGuiltfreeplay[i],color:"#7CB5EC"},
								                   {y:OtherRest[i],color:"#90ED7D"},
								                   {y:OtherQualitywork[i],color:"#E4D354"},
								                   {y:OtherMandatorywork[i],color:"#F7A35C"},
								                   {y:OtherProcrastination[i],color:"#F45B5B"}]
						   		                 },{                                 //指定数据列
								            name: '我的时间',  	                         //数据列名
						   		            data: [{y:guiltfreeplay,color:"#434348"},
								                   {y:rest,color:"#434348"},
								                   {y:qualitywork,color:"#434348"},
								                   {y:mandatorywork,color:"#434348"},
								                   {y:procrastination,color:"#434348"}]                        //数据
								                  }]                        //数据							        
								    });
	   	     	              }
						      
						}else{
							$("div#myTime").append("<span><img src='https://dn-coding-net-production-avatar.qbox.me/Fruit-10.png?imageMogr2/thumbnail/60' alt='头像' class='img-circle'>"+
					    "我的时间</span><center><h4>"+$date+"<br/>没有你的时间记录哦！<br/>下滑看看别人的时间记录吧^_^</h4></center>"+
		   	     	     	                      "<hr style='border:1px solid #2C3E50'/>");
						}
	   	     	     }
	   	     	           //点赞时触发
	   	     	          $("span[name=zan]").click(function(){		   	     	          	
				           	$id=$(this).data('id'); //获取当前页面被点赞内容的序号
				           	 $.post(
				           	 	    "/wyu34coin/1/index.php/Home/User/good",
				           	 	    {
				           	 	    	id:$(this).data('commtentId'),				           	 	    
				           	 	    	opid:'<?php echo ($opid); ?>'
				           	 	    },
				           	 	    function(data,status){	
				           	 	    	if (data==1) {
					           	 	    	$("div[id="+$id+"] span").css("display","inline-block");
					           	 	    	$("div[id="+$id+"]").append("<?php echo ($nickname); ?>、");
				           	 	    	} else{
				           	 	    		alert("你已赞过！");
				           	 	    	}			           	 	    	
				           	 	    }
				           	 );
				           });
				           
				           //评论时触发
				           $("span[name=comment]").click(function(){  
				           
					           	var $id=$(this).data('id');  //获取当前页面被点赞内容的序号	
					            var $comment=$("input[name=comment"+$id+"]").val(); //获取评论框的内容
					             //如果输入的内容不为空，则可以提交评论内容
					            if($comment){
					             $.post(
					           	 	    "/wyu34coin/1/index.php/Home/User/comment",
					           	 	    {
					           	 	    	id:$(this).data('commtentId'),
					           	 	        discuss:$comment,
					           	 	    	opid:'<?php echo ($opid); ?>'
					           	 	    },
					           	 	    function(data,status){	
					           	 	    	if (data==1) {
						           	 	    	$("div[name=comment"+$id+"]").append("<font color='#0088CC'><b><?php echo ($nickname); ?>:</b></font>"+$comment+"<br/>"); //将评论的内容添加到评论区内
						                        $("input[name=comment"+$id+"]").val("");  //清空评论输入框
					           	 	    	} else{
					           	 	    		return;
					           	 	    	}
						           	});					             	
					            }				         				             
				           });
	   	     	    });
	   	     }    
	   	     	
  $(function(){
	
			  getAllRecordInfo('<?php echo ($date); ?>');//取出某一天自己的金币记录  
			  
		 //显示日期选择
		     $('.form_date').datetimepicker({
		        language:  'zh-TW',
		        weekStart: 1,		      
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				minView: 2,
				forceParse: 0,
				
		    });	
		    //当选择器隐藏时被触发
		    $('.form_date').datetimepicker().on('hide', function(){
		    	var $date;
		    	$date=$("input[class=form-control]").val();
		    	if($date){	    	
		    		getAllRecordInfo($date);
		    	}           
            });	             
           
	});
		</script>
	</head>
	<body>
	<div id="top">
	<nav class="navbar navbar-default navbar-fixed-top" style="background-color: #0088BB;">
	  <div class="container-fluid">
	    <div class="navbar-header">   
	      	<button class="btn btn-info navbar-btn" style="margin-left: 3%;" id="ooo">
	      		<a href="/wyu34coin/1/index.php/Home/User/homePage?opid=<?php echo ($opid); ?>">
	        <span class="glyphicon glyphicon-home"> 主页</span>
	           </a>
	       </button>
	    </div>
	  </div>
	</nav>
	</div>	
	
	<div id="content" style="margin-top: 3%;">
		<div class="" >	  
		    <img  class="img-responsive center-block" max-width: 100%; height:70%;display: block; src="http://cdn005.wuse.com/images/bg.jpg" />	 
		</div>
		
		
		<!--移动端-->      
		<div id="SOHUCS" sid="<?php echo ($sid); ?>"></div>
		<script id="changyan_mobile_js" charset="utf-8" type="text/javascript" 
			src="http://changyan.sohu.com/upload/mobile/wap-js/changyan_mobile.js?client_id=cys2CIwJ3&conf=prod_2102ff91ff6241d516548c4a08de9543">
		</script>
		
		
		<!--日历-->
		<div id="date" class="container" style="margin-top: 3%;">			
		<form action="" class="form-horizontal"  role="form">		
		    <div class="form-group">
                <label for="dtp_input2" class="col-xs-2 control-label ">日期</label>
                <div class="input-group date form_date col-xs-9" data-date="" data-date-format="yyyy/m/d" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
            </div>
	    </form> 
		</div>
		



		<div id="users"  class="container-fluid" style="margin-top: 5%;">	
			<div id="myTime">
				
			</div>
			<div id="others">
				
			</div>
		</div>
		        
       <div id="footer">
       	
		<center>
			<address>
			  <strong>关注34枚金币公众号：</strong><br>
			  wyu34coin<br><br><br><br><br>		
			</address>
		</center>
	</div> 
	</div>

	</body>
</html>