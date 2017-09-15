<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- 可选的 Bootstrap 主题文件（一般不用引入） -->
	<!-- <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->

	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>

<div class="container-fluid">
  <div class="row">
  <div class="col-xs-3 col-md-3"></div>
  <div class="col-xs-6 col-md-6" style="text-align: center;font-size: 30px;font-weight: 4">经济郑氏口腔收费单据</div>
  <div class="col-xs-3 col-md-3"></div>
</div>
<div class="row">
  <div class="col-xs-3 col-md-3"></div>
  <div class="col-xs-2 col-md-2" style="text-align: right;"><?php echo ($res["sick_name"]); ?></div>
  <div class="col-xs-2 col-md-2"></div>
  <div class="col-xs-2 col-md-2">编号:</div>
  <div class="col-xs-3 col-md-3"></div>
</div>
<div class="row">
  <div class="col-xs-2 col-md-2"></div>
  <div class="col-xs-2 col-md-2">收费项目名称</div>
  <div class="col-xs-2 col-md-2">数量</div>
  <div class="col-xs-2 col-md-2">单价</div>
  <div class="col-xs-2 col-md-2">合计</div>
  <div class="col-xs-2 col-md-2"></div>
</div>
<div class="row">
  <div class="col-xs-2 col-md-2"></div>
  <div class="col-xs-2 col-md-2"><?php echo ($res["project"]); ?></div>
  <div class="col-xs-2 col-md-2">1</div>
  <div class="col-xs-2 col-md-2"><?php echo ($res["pay_fee"]); ?></div>
  <div class="col-xs-2 col-md-2"><?php echo ($res["pay_fee"]); ?></div>
  <div class="col-xs-2 col-md-2"></div>
</div>
<div style="width: 100%;height: 100px;"></div>
<?php if(is_array($pay)): $i = 0; $__LIST__ = $pay;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="row"><div class="col-xs-3 col-md-3"></div><div class="col-xs-3 col-md-3"><?php echo ($vo["pay_name"]); ?>：<?php echo (sprintf('%.2f',$vo["money"])); ?></div></div><?php endforeach; endif; else: echo "" ;endif; ?>
<div class="row">
  <div class="col-xs-2 col-md-2"></div>
  <div class="col-xs-3 col-md-3"><?php echo ($res["bigmoney"]); ?></div>
  <?php if(is_array($pay)): $i = 0; $__LIST__ = $pay;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="col-xs-3 col-md-3"></div><?php endforeach; endif; else: echo "" ;endif; ?>
  <div class="col-xs-1 col-md-1"></div>
</div>
<div class="row">
  <div class="col-xs-1 col-md-1"></div>
  <div class="col-xs-10 col-md-10">备注：</div>
  
</div>
<hr>
<div class="row">
  <div class="col-xs-1 col-md-1"></div>
  <div class="col-xs-3 col-md-3">医生：<?php echo ($res["doctor_name"]); ?></div>
  <div class="col-xs-3 col-md-3">收款人：<?php echo ($res["payee"]); ?></div>
  <div class="col-xs-3 col-md-3"><?php echo (date("Y-m-d",$res["time"])); ?></div>
  <div class="col-xs-1 col-md-1"></div>
</div>
<div class="row">
  <div class="col-xs-1 col-md-1"></div>
  <div class="col-xs-11 col-md-11">郑氏口腔--您身边的口腔专家</div>
</div>
<div class="row">
  <div class="col-xs-1 col-md-1"></div>
  <div class="col-xs-11 col-md-11">爱牙热线：0536--296398</div>
</div>
</div>
	
</body>
</html>