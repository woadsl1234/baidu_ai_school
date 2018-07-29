<?php
    error_reporting(E_ALL);
    require_once(__DIR__ . '/../vendor/autoload.php');
    use CAPTCHAReader\src\App\IndexController;
    
    function check(){
    
        // $start_time = microtime(true);//运行时间开始计时
    
        $indexController = new IndexController();
    
        $res = $indexController->entrance('checkcode.png','local', 'ZhengFangNormal');
    
        return $res;
    }
    function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }

            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
    }
    function login_post($url,$cookie,$post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
        curl_setopt($ch, CURLOPT_REFERER, $url); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));  
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    function JSON($array)
    {
        arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }
    function hdu_login_post($url, $cookie, $post)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        $rs = curl_exec($curl);
        $a = preg_match('/window.location.href="(.*)"\+cookie;/', $rs, $match);
        if ($a != 0) {
            return $match[1];
        }
        curl_close($curl);
    }
    
    function lt_get($url, $cookie)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
        $rs = curl_exec($curl);
        curl_close($curl);
        preg_match('<input type="hidden" name="lt" value="(.*)" />', $rs, $match);
        return $match[1];
    }
    
    function get_cookie($url, $cookie)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_exec($ch);
        curl_close($ch);
    }
    function get_content($url, $cookie)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        $rs = curl_exec($ch);
        curl_close($ch);
        return $rs;
    }
    function array_T($arr){
        for($i=0; $i<count($arr); $i++) {
            for($j=0; $j<count($arr[$i]);$j++)
            {
                $a[$j][$i] =$arr[$i][$j];
            }
        }
        return $a;
    }
    function post_content($url, $cookie, $post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //重要，抓取跳转后数据
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));  //post提交数据
        $rs = curl_exec($ch);
        curl_close($ch);
        return $rs;
    }
    function get_image($url, $cookie){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        $rs = curl_exec($ch);
        curl_close($ch);
        return $rs;
    }

    function VIEWSTATE_get($url, $cookie){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
        $rs = curl_exec($curl);
        curl_close($curl);
        preg_match('<input type="hidden" name="__VIEWSTATE" value="(.*)" />', $rs, $match);
        return $match[1];
    }