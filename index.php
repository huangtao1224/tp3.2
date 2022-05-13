<?php
//探测浏览器，自动打开
if(file_exists('mobile.php')){
    $is_mobile = is_mobile();
    //判断是否需要跳转
    if($is_mobile){
        header("Location:/mobile.php"); exit();
    }
}

$cache_switch = true;//缓存开关
$cache_switch && $cache_switch = !is_sync_requet();
$cache_timeout = 10;//缓存过期时间，单位秒
$__cache_name = 'Index/Runtime/Cache/'. md5($_SERVER['REQUEST_URI']).'.html';
if($cache_switch && file_exists($__cache_name)){
    clearstatcache();
    $file_time    = filemtime($__cache_name);
    $current_time = time();
    if( ($file_time+$cache_timeout) > $current_time ){
        echo file_get_contents($__cache_name); exit;
    }else{
        @unlink($__cache_name);
    }
}
$cache_switch && ob_start('cache_callback');
	define('APP_NAME','Index');
	define('APP_PATH','./Index/');
	define('APP_ROOT','./Index/Tpl/');
	define('APP_DEBUG',TRUE);
	define('REWRITE',FALSE);
	require './ThinkPHP/ThinkPHP.php';
$cache_switch && ob_end_flush();



function is_mobile(){
    // 先检查是否为wap代理，准确度高
    if(isset($_SERVER['ALL_HTTP']) && stristr($_SERVER['HTTP_VIA'],"wap")){
        return true;
    }
    // 检查浏览器是否接受 WML.
    if(strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0){
        return true;
    }
    //检查USER_AGENT
    if(preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])){
        return true;
    }
    //检查其他标志性header头信息
    if(isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_X_WAP_CLIENTID']) || isset($_SERVER['HTTP_WAP_CONNECTION']) || isset($_SERVER['HTTP_PROFILE'])){
        return true;
    }
    //探测移动网关代理
    if(isset($_SERVER['HTTP_X_MOBILE_GATEWAY'])){
        return true;
    }
    if(isset($_SERVER['ALL_HTTP']) && strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false){
        return true;
    }
    return false;
}
function is_sync_requet()
{
    $isPost =  isset($_SERVER['REQUEST_METHOD']) && (strtolower($_SERVER['REQUEST_METHOD'])=='post');
    $isAjax = ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax'])) ? true : false;
    return ($isPost || $isAjax);

}
function cache_callback($buffer){
    global $__cache_name;
    file_put_contents($__cache_name, $buffer);
    return $buffer;
}