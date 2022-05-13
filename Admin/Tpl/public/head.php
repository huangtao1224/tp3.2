<?php
$user=M('user')->where('user_id='.session('user')['user_id'])->find();
if(session('version_id')=='')session('version_id',1);
$version_id=session('version_id');

if(session('version_classify_id')=='')session('version_classify_id',1);
$version_classify_id=session('version_classify_id');

$recursive_classify_id=pg('recursive_classify_id')==''?$version_classify_id:pg('recursive_classify_id');
if(empty($user)) {
	showmsg2('请先登录',U('login/index'));
	die();
}
$function_switch = M('function_switch')->where(array('function_switch_id'=>1))->find();
$user_page = M('user_page')->where(array('user_id'=>$user['user_id']))->select();
foreach($user_page as $k=>$v) {
	$page_arr[]=$v['page'];
}
$site = M('site')->find();
$p=pg('p')==''?1:pg('p');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo APP_ROOT;?>css/bootstrap-icons.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo APP_ROOT;?>css/main.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo APP_ROOT;?>css/coloris.min.css" rel="stylesheet" type="text/css" />
<!--    <link rel="stylesheet" href="js/layui/css/layui.css">-->
    <script src="<?php echo APP_ROOT;?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script charset="utf-8" src="ThinkPHP/Extend/Vendor/kindeditor-4.1.7/kindeditor.js"></script>
    <script src="<?php echo APP_ROOT;?>js/common.js" type="text/javascript"></script>
    <script src="<?php echo APP_ROOT;?>js/jquery.SuperSlide.2.1.1.js" type="text/javascript"></script>
    <script src="<?php echo APP_ROOT;?>js/jquery.form.js" type="text/javascript"></script>
    <script src="<?php echo APP_ROOT;?>js/layui/layui.js"></script>
    <script src="<?php echo APP_ROOT;?>js/jquery.cookie.js"></script>
    <script src="<?php echo APP_ROOT;?>js/swfupload.js" type="text/javascript"></script>
    <script src="<?php echo APP_ROOT;?>js/fileprogress.js" type="text/javascript"></script>
    <script src="<?php echo APP_ROOT;?>js/handlers.js" type="text/javascript"></script>
    <script src="<?php echo APP_ROOT;?>js/coloris.min.js" type="text/javascript"></script>
<!--<!--<script src="--><?php ////echo APP_ROOT;?><!--<!--js/ZeroClipboard.js" type="text/javascript"></script>-->

<title><?php echo $site['company_name'];?>-后台管理系统</title>
<!-- 返回上一页并刷新页面 -->
<script>
	if(window.name != "bencalie"){
	    location.reload();
	    window.name = "bencalie";
	}
	else{
	    window.name = "";
	}

</script>
</head>
<body>
