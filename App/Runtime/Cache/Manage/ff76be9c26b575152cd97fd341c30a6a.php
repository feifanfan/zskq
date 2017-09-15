<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>后台</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="baidu-site-verification" content="unXl91MyB6" />
	<link rel="stylesheet" type="text/css" href="/Data/static/bootstrap/3.3.5/css/bootstrap.min.css" media="screen">	
	<link rel='stylesheet' type="text/css" href="/App/Manage/View/Public/css/main.css" />
	<!-- 头部css文件|自定义  -->
	

	<script type="text/javascript" src="/Data/static/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="/Data/static/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
		<script src="/Data/static/js/html5shiv.min.js"></script>
		<script src="/Data/static/js/respond.min.js"></script>
    <![endif]-->
	
	<script type="text/javascript" src="/App/Manage/View/Public/js/jquery.form.min.js"></script>
	<script type="text/javascript" src="/Data/static/jq_plugins/layer/layer.js"></script>
	<script language="JavaScript">
	    <!--
	    var URL = '/admin.php?s=/Payment';
	    var APP	 = '/admin.php?s=';
	    var SELF='/admin.php?s=/Payment/edit/id/50';
	    var PUBLIC='/App/Manage/View/Public';
	    var data_path = "/Data";
		var tpl_public = "/App/Manage/View/Public";
	    //-->
	</script>
	<script type="text/javascript" src="/App/Manage/View/Public/js/common.js"></script> 
	<!-- 头部js文件|自定义 -->
	
	<script type="text/javascript" src="/App/Manage/View/Public/js/calendar.config.js"></script>
	<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.js"></script>
	<style type="text/css">
		.form-horizontal .form-group{
			float: left;
		}
		.col-sm-2{
			width: inherit;
		}
		.addmate,.addmethod{
			
			padding: 5px;
			background-color: #adbcdd;
			font-size: 20px;
			color: #fff;
			float: left;
			margin-top: 20px;
		}
	</style>

</head>
<body>
	<div class="xyh-content">
		
	<div class="row">
		<div class="col-lg-12">
			<h3 class="page-header"><em class="glyphicon glyphicon-cloud-upload"></em> 
			修改收款
		    </h3>
		</div>
		
	</div>


	<div class="row">
		<div class="col-lg-12">

				<form method='post' class="form-horizontal" id="form_do" name="form_do" action="<?php echo U('editPost');?>">
					<input type="hidden" name="id" value="<?php echo ($id); ?>">
					<div class="form-group">
						<label for="inputEmail" class="col-sm-2 control-label">病人姓名</label>
						<div class="col-sm-9">
							<input type="text" name="sick_name" id="inputsick_name" class="form-control" placeholder="病人姓名" required="required" value="<?php echo ($list["sick_name"]); ?>" />	
						</div>
					</div>
					<div class="form-group">
						<label for="inputPassword" class="col-sm-2 control-label">病人电话</label>
						<div class="col-sm-9">
							<input type="number" name="sick_phone" id="inputPassword" class="form-control" placeholder="病人电话" value="<?php echo ($list["sickphone"]); ?>" required="required" />		
						</div>
					</div>
					<br>
					<br>
					<br>
					<br>
					

					<div class="form-group">
						<label for="inputNickname" class="col-sm-2 control-label">诊疗项目</label>
						<div class="col-sm-9">
							<input type="text" name="project" id="inputproject" class="form-control" placeholder="诊疗项目" value="<?php echo ($list["project"]); ?>" />
						</div>
					</div>
					
					 <!-- <div class="form-group">
						<label for="inputValue" class="col-sm-2 control-label">会员组名</label>
						<div class="col-sm-9">
							<select name="groupid" class="form-control">
								<?php if(is_array($vlist)): foreach($vlist as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
							</select>		
						</div>
					</div>  -->
					<div class="form-group">
						<label for="inputEmail" class="col-sm-2 control-label">加工费</label>
						<div class="col-sm-9">
							<input type="text" name="process_cost" id="inputprocess_cost" class="form-control" placeholder="加工费" required="required" value="<?php echo ($list["process_cost"]); ?>" />	
						</div>
					</div>

				<?php if(is_array($a)): $i = 0; $__LIST__ = $a;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="form-group">
						<label for="inputEmail" class="col-sm-2 control-label">材料费</label>
						<div class="col-sm-9">
							<select name="material<?php echo ($vo["js"]); ?>" id="material<?php echo ($vo["js"]); ?>" class="form-control">
								<option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="inputEmail" class="col-sm-2 control-label">材料数量</label>
						<div class="col-sm-9">
							<input type="text" name="number<?php echo ($vo["js"]); ?>" id="inputpayfee" class="form-control" value="<?php echo ($vo["num"]); ?>" placeholder="数量" required="required" />
						</div>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
					<span id="materials"></span>
					<span class="addmate">+</span>
					<div class="form-group">
						<label for="inputEmail" class="col-sm-2 control-label">实缴费用</label>
						<div class="col-sm-9">
							<input type="text" name="pay_fee" id="inputpayfee" class="form-control" placeholder="填写缴纳的费用" required="required" value="<?php echo ($list["pay_fee"]); ?>"/>	
						</div>
					</div>
					<br>
					<br>
					<br>
					<br>
				<?php if(is_array($b)): $i = 0; $__LIST__ = $b;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="form-group">
						<label for="inputValue" class="col-sm-2 control-label">付款方式</label>
						<div class="col-sm-9">
							<select name="pay_method<?php echo ($vo["js"]); ?>" class="form-control">
								
								<option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option>
								
							</select>		
						</div>
					</div>
					<div class="form-group">
						<label for="inputValue" class="col-sm-2 control-label">该方式付款金额</label>
						<div class="col-sm-9">
							<input type="text" name="money<?php echo ($vo["js"]); ?>" value="<?php echo ($vo["money"]); ?>">		
						</div>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
				<span id="pay_method"></span>
				<span class="addmethod">+</span>
					<div class="form-group">
						<label for="inputValue" class="col-sm-2 control-label">诊治医生</label>
						<div class="col-sm-9">
							<select name="doctor" class="form-control">
								<?php if(is_array($doctor)): foreach($doctor as $key=>$v): ?><option value="<?php echo ($v["userid"]); ?>"><?php echo ($v["realname"]); ?></option><?php endforeach; endif; ?>
							</select>		
						</div>
					</div>
					<div class="form-group">
						<label for="inputpayee" class="col-sm-2 control-label">收款人</label>
						<div class="col-sm-9">
							<input type="text" name="payee" id="inputpayee" class="form-control" placeholder="收款人" required="required" value="<?php echo ($list["payee"]); ?>"/>	
						</div>
					</div>
						<br>
						<br>
						<br>
						<br>
						<br>
						<div class="form-group">
						<label for="paytime" class="col-sm-2 control-label">收款时间</label>
						<div class="col-sm-4">
							<input type="text" name="time" id="birthday" value="<?php echo ($list["time"]); ?>" class="form-control" style="width: 500%" placeholder="收款时间" />	
						</div>
					</div>
						<br>
					<br>
					<br>
					
						<script type="text/javascript">
		                    Calendar.setup({
		                        weekNumbers: true,
		                        inputField : "birthday",
		                        trigger    : "birthday",
		                        dateFormat: "%Y-%m-%d",
		                        showTime: true,
		                        minuteStep: 1,
		                        onSelect   : function() {this.hide();}
		                    });
		                </script>
					
					<div class="form-group">
						<label for="inputValue" class="col-sm-2 control-label">*本次收款提成规则</label>
						<div class="col-sm-9">
							<select name="bonus_type" id="bonus_type" class="form-control">
							<option value="">---请选择提成规则---</option>
								<?php if(is_array($bonus)): foreach($bonus as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"<?php if($v["id"] == $list['bonus_type']): ?>selected="selected"<?php endif; ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
							</select>
							<span style="color: red" id="bonus_tips"></span>		
						</div>
					</div>
						<br>
					<br>
					<br>
					<br><br><br>
					<div class="form-group">
						<label for="inputEmail" class="col-sm-2 control-label">备注</label>
						<div class="col-sm-9">
							<textarea type="text" name="remark" id="inputRemark" class="form-control"  required="required" ><?php echo ($list["remark"]); ?> </textarea>
						</div>
					</div>
					
					<div class="form-group">
						<label for="inputEmail" class="col-sm-2 control-label">是否欠款</label>
						<div class="col-sm-9">
							<input type="radio" name="isdebt" value="1"<?php if($list["isdebt"] == 1): ?>checked = "checked"<?php endif; ?>>是
							<input type="radio" name="isdebt" value="0" <?php if($list["isdebt"] == 0): ?>checked = "checked"<?php endif; ?>>否
						</div>
					</div>

					<div class="row margin-botton-large">
						<div class="col-sm-offset-2 col-sm-9">
							<div class="btn-group">							
								<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-saved"></i>
									保存
								</button>
								<button type="button" onclick="goUrl('<?php echo U('index',array('group' => $group));?>')" class="btn btn-default"> <i class="glyphicon glyphicon-chevron-left"></i>
									返回
								</button>
							</div>
						</div>
					</div>
				</form>
	
		</div>
	</div>
<script type="text/javascript">
	$(function(){
		$('#bonus_type').change(function(){
			var id = $("#bonus_type option:selected").val();
			$.ajax({
				url:'admin.php?s=/Payment/addAjax',
				type:'post',
				data:{'id':id},
				success:function(msg){
					if(msg.status!=1){
					$('#bonus_tips').text('*提示：'+msg.info);
				}else{
					$('#bonus_tips').text(msg.info);
				}
				}
			})
		})
		$('select[name="isintro"]').change(function(){
			var id = $('select[name="isintro"] option:selected').val();
			if(id==1){
				$('#introducer').show();
			}else{
				$('#introducer').hide();
			}

		})
		var count = <?php echo ($an); ?>;
		var num = <?php echo ($bn); ?>;
		$('.addmate').click(function(){
			var addd = '<div class="form-group"><label for="inputEmail" class="col-sm-2 control-label">材料费</label><div class="col-sm-9">	<select name="material'+count+'" id="material" class="form-control">	<option value="">---请选择材料---</option><?php if(is_array($material)): foreach($material as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["title"]); ?>--￥<?php echo ($v["price"]); ?></option><?php endforeach; endif; ?></select></div></div><div class="form-group"><label for="inputEmail" class="col-sm-2 control-label">材料数量</label><div class="col-sm-9"><input type="text" name="number'+count+'" id="inputpayfee" class="form-control" placeholder="数量" required="required" /></div></div>';
			$('#materials').append(addd);
			count++;
		})
		$('.addmethod').click(function(){
			var add1 = '<div class="form-group"><label for="inputEmail" class="col-sm-2 control-label">付款方式</label><div class="col-sm-9">	<select name="pay_method'+num+'" class="form-control"><?php if(is_array($paytype)): foreach($paytype as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?></select></div></div><div class="form-group">						<label for="inputValue" class="col-sm-2 control-label">该方式付款金额</label><div class="col-sm-9"><input type="text" name="money'+num+'" class="form-control"></div></div>';
			$('#pay_method').append(add1);
			num++;
		})
	})
</script>
		



			
	</div>	
</body>
</html>