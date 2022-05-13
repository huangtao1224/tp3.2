<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_TEMPLATE_SUFFIX'  => '.php',     // 默认模板文件后缀
	'URL_MODEL' => 0,		//设置URL模式
    'LANG_SWITCH_ON'     =>     true,    //开启语言包功能       
    'LANG_AUTO_DETECT'     =>     true, // 自动侦测语言
    'LANG_LIST'            =>    'en-us,zh-cn,zh-tw', //必须写可允许的语言列表
    'VAR_LANGUAGE'     => 'l', // 默认语言切换变量
	
    /* 数据库设置 */
    'DB_TYPE'               => 'mysqli',
    'DB_HOST'               => '127.0.0.1', // 服务器地址
    'DB_NAME'               => 'v61',          // 数据库名
    'DB_USER'               => 'root',      // 用户名
    'DB_PWD'                => 'root',          // 密码
    'DB_PREFIX'             => 'index_',    // 数据库表前缀	
);
