<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	$site =M('site')->find();
	$p=pg('p')==''?1:pg('p');
	$classify_id=get_classify_id();
	$content_id=pg('content_id');
	$recursive_classify_id=recursive_classify_id($classify_id,3)==''?3:recursive_classify_id($classify_id,3);
	$mobile_url='mobile.php?'.$_SERVER["QUERY_STRING"];
?>
<title><?php echo $site['title'];?></title>
<meta name="keywords" content="<?php echo $site['keywords'];?>" />
<meta name="description" content="<?php echo $site['description'];?>" />
<link rel="shortcut icon" href="/favicon.ico" />
<meta http-equiv="Cache-Control" content="no-siteapp" /> <!-- 禁止百度转码 -->
<meta name="Baiduspider" content="noarchive"> <!-- 禁止百度快照缓存 -->
<link rel="stylesheet" type="text/css" href="css/swiper-bundle.min.css">
<script src="<?php echo APP_ROOT;?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="<?php echo APP_ROOT;?>js/jquery.form.js" type="text/javascript"></script>
<script src="<?php echo APP_ROOT;?>js/common.js" type="text/javascript"></script>
<script src="<?php echo APP_ROOT;?>js/jquery.SuperSlide.2.1.1.js" type="text/javascript"></script>
<script src="<?php echo APP_ROOT;?>js/laydate/laydate.js" type="text/javascript"></script>
<script src="<?php echo APP_ROOT;?>js/layer/layer.js" type="text/javascript"></script>
<script src="js/swiper-bundle.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/alertPopShow/alertPopShow.js"></script>
</head>
<body>
<?php if(file_exists('mobile.php')){?>
<script type="text/javascript">
function IsPC()
{  
	var userAgentInfo = navigator.userAgent;  
	var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");  
	var flag = true;  
	for (var v = 0; v < Agents.length; v++) {  
	   if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }  
	}  
	return flag;  
}            
if(!IsPC())window.location.href="<?php echo $mobile_url;?>";
</script>
<?php }?>
