<?php
    // ini_set('max_execution_time', 3000);
    header('Content-type: text/html; charset=gb2312');
    function login_post($url,$cookie,$post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
        curl_setopt($ch, CURLOPT_REFERER, 'http://jwgl1.hznu.edu.cn/default2.aspx');  
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);  
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    function get_image($url, $cookie)
    {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    $rs = curl_exec($ch);
    curl_close($ch);
    return $rs;
    }
    $url = "http://jwgl1.hznu.edu.cn/CheckCode.aspx";

    $cookie = dirname(__FILE__) . '/jwglcookie.txt';
    $img = get_image($url,$cookie);
    $fp = fopen("checkcode.png","w");  
    fwrite($fp,$img);   
    fclose($fp);
    $result = shell_exec("/usr/bin/python2.7 predict.py 2>&1");
    print_r($result);
    echo("ret is $ret");
    $url = 'http://jwgl1.hznu.edu.cn/default2.aspx';
?>