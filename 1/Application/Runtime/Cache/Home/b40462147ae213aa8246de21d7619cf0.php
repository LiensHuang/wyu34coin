<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="author" content="www.frebsite.nl" />
		<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />		
		<title>我的时间相册</title>
		
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
	
	</head>
	<body>
		<div id="top">
			<nav class="navbar navbar-default navbar-fixed-top" style="background-color: #0088BB;">
				  <div class="container-fluid">
					    <div class="navbar-header">   
					      	<button class="btn btn-info navbar-btn" style="margin-left: 3%;">
					      		<a href="/wyu34coin/1/index.php/Home/User/homePage?opid=<?php echo ($opid); ?>">
					        <span class="glyphicon glyphicon-home"> 主页</span>
					           </a>
					       </button>
					    </div>
				  </div>
			</nav>
		</div>
		
		<div id="content">
			
		</div>
		
		<div id="footer">
			
		</div>
	</body>
</html>