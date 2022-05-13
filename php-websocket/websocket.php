<?php
require __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\Connection\TcpConnection;

// 使用websocket协议监听6161端口
$worker = new Worker('websocket://0.0.0.0:6161');

//  当浏览器(包括用户手机浏览器和电脑浏览器)发来消息时的处理逻辑
$worker->onMessage = function(TcpConnection $connection, $data) {
    // 这个静态变量用来存储电脑浏览器的websocket连接，方便推送使用
    static $daping_connection = null;
    switch ($data) {
        // 发送 daping 字符串的是电脑浏览器，将其连接保存到静态变量中
        case 'daping':
            $daping_connection = $connection;
            break;
        // ping 是心跳数据，用来维持连接，只返回 pong 字符串，无需做其它处理
        case 'ping':
            $connection->send('pong');
            break;
        // 用户手机浏览器发来的祝福语
        default:
            // 直接使用电脑浏览器的连接将祝福语推送给电脑
            if ($daping_connection) {
                $daping_connection->send($data);
            }
    }
};
Worker::runAll();