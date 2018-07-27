<?php
//!! 这里的 autoload.php 路径需要自己配置
require_once(__DIR__ . '/../../vendor/autoload.php');

use CAPTCHAReader\src\App\IndexController;

function check(){

    $start_time = microtime(true);//运行时间开始计时

    $indexController = new IndexController();

    $res = $indexController->entrance('checkcode.png','local', 'ZhengFangNormal');

    return $res;
}

// dump($res);

// $end_time = microtime(true);//计时停止

// echo '执行时间为：' . ($end_time - $start_time) . ' s' . "<br/>\n";

?>