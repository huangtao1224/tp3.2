<?php
	define('APP_NAME','Admin');
	define('APP_PATH','./Admin/');
	define('APP_ROOT','./Admin/Tpl/');
	define('APP_DEBUG',TRUE);
	/*
	echo $_SESSION['user_id'].'---';
	if($_SESSION['user_id']=='' && $f!='login')
	{
		showmsg('你还没有登录，请先登录!','index.php?m=login&a=index',1000,0);
		exit;
	}
	*/
	require './ThinkPHP/ThinkPHP.php';

	