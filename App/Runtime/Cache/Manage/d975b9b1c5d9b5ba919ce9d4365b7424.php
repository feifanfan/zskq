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
	    var URL = '/admin.php?s=/Setbonus';
	    var APP	 = '/admin.php?s=';
	    var SELF='/admin.php?s=/Setbonus/index';
	    var PUBLIC='/App/Manage/View/Public';
	    var data_path = "/Data";
		var tpl_public = "/App/Manage/View/Public";
	    //-->
	</script>
	<script type="text/javascript" src="/App/Manage/View/Public/js/common.js"></script> 
	<!-- 头部js文件|自定义 -->
	
</head>
<body>
	<div class="xyh-content">
		
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header"><em class="glyphicon glyphicon-cloud-upload"></em> 
            <?php echo ($type); ?>         
            </h3>
        </div>
        
    </div>

    <div class="row margin-botton">
        <div class="col-sm-6 column">
            <div class="btn-group btn-group-md">
                <button class="btn btn-primary" type="button" onclick="goUrl('<?php echo U('add');?>')"><em class="glyphicon glyphicon-plus-sign"></em> 添加提成办法</button>
                <button class="btn btn-default" type="button" onclick="doGoSubmit('<?php echo U('sort');?>','form_do')"><em class="glyphicon glyphicon-th-list"></em> 更新排序</button>
                <!-- <button class="btn btn-default" type="button" onclick="doGoSubmit('<?php echo U('qk');?>','form_do')"><em class="glyphicon glyphicon-th-list"></em> 更新快捷面板</button> -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <form action="" method="post" id="form_do" name="form_do">
                <div class="table-responsive">
                    <table class="table table-hover xyh-table-bordered-out">
                        <thead>
                            <tr class="active">
                                <th>编号</th>
                                <th>提成办法名称</th>
                                <th>科室名称</th>
                                <th>医生等级</th>
                                <th>排序</th>
                                <th>提成办法</th>
                                <th class="text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($cate)): foreach($cate as $key=>$v): ?><tr>
                            <td><?php echo ($v["id"]); ?></td>
                            <td><?php echo ($v["name"]); ?></td>
                            <td class="aleft"><?php echo ($v["officename"]); ?></td>

                            <td>
                            <span class="label label-success">
                            <?php if($v['level'] == 1): ?>等级一
                            <?php elseif($v['level'] == 2): ?>等级二
                            <?php elseif($v['level'] == 3): ?>等级三
                            <?php elseif($v['level'] == 4): ?>等级四
                            <?php elseif($v['level'] == 5): ?>等级五<?php endif; ?>
                            </span>
                            </td>   

                            <td><input type="text" name="sortlist[<?php echo ($v["id"]); ?>]" value="<?php echo ($v["sort"]); ?>" id="sortlist" size="5" class="xyh-form-control" /></td>
                            <td><?php echo ($v["boundary"]); ?>元以下<?php echo ($v["downper"]); ?>%，以上<?php echo ($v["upper"]); ?>%</td>
                            <td class="text-right">
                                
                                    <!-- <a href="<?php echo U('add',array('pid' => $v['id']));?>" class="label label-info">添加子菜单</a> -->
                                    <a href="<?php echo U('edit',array('id' => $v['id']));?>" class="label label-success">修改</a>
                                    <a href="javascript:;" onclick="toConfirm('<?php echo U('delbonus', array('id' => $v['id']));?>', '确实要删除吗？')" class="label label-danger">删除</a>
                               
                            </td>
                        </tr><?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            
        </div>
    </div>

    
			
	</div>	
</body>
</html>