<?php
require '../simple_html_dom.php';
header('Content-type: text/html; charset=gb2312');
function login_post($url, $cookie, $post)
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
    $rs = curl_exec($ch);
    curl_close($ch);
    return $rs;
}
function get_content_add_refer($url, $cookie, $refer)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_REFERER, $refer);
    $rs = curl_exec($ch);
    curl_close($ch);
    return $rs;
}

$cascookie = dirname(__FILE__) . '/cascookie.txt';
$jxglcookie = dirname(__FILE__) . '/jxglcookie.txt';
$jxdccookie = dirname(__FILE__) . '/jxdccookie.txt';

$lt = lt_get("http://cas.hdu.edu.cn/cas/login?service=http://jxgl.hdu.edu.cn/default.aspx", $cascookie);
$url1 = login_post("http://cas.hdu.edu.cn/cas/login", $cascookie, array('username' => '16184117', 'password' => md5('adsl1234'), 'lt' => $lt, 'service' => 'http://jxgl.hdu.edu.cn'));
get_cookie("http://jxgl.hdu.edu.cn", $jxglcookie);
get_cookie("http://jxdc.hdu.edu.cn", $jxdccookie);
get_content($url1, $jxglcookie);
$rs = get_content("http://cas.hdu.edu.cn/cas/login?service=http://jxdc.hdu.edu.cn/index.php", $cascookie);
preg_match('/window.location.href="(.*)"\+cookie;/', $rs, $match);
$url2 = $match[1];
get_content($url2, $jxdccookie);
get_content("http://jxgl.hdu.edu.cn/index.aspx", $jxglcookie);
$rs = get_content("http://cas.hdu.edu.cn/cas/login?service=http://jxgl.hdu.edu.cn/index.aspx", $cascookie);
preg_match('/window.location.href="(.*)"\+cookie;/', $rs, $match);
$url3 = $match[1];
$rs = get_content($url3, $jxglcookie);
preg_match('/(\d{8})/', $rs, $match);
$url4 = "http://jxgl.hdu.edu.cn/xs_main.aspx?xh=" . $match[1];
$content = get_content($url4, $jxglcookie);
$host = $content;

function get_lesson($content)
{
    $url5 = "http://jxgl.hdu.edu.cn/";
    preg_match_all('/<a href="(.*?)"/m', $content, $match);
    foreach ($match[1] as $i => $j) {
        if (preg_match('/xskbcx/', $j))                                                      //学生课表查询 xskbcx
        {
            echo "<br>";
            $url = $url5 . $j;
        }
    }
    $content = get_content_add_refer($url, $GLOBALS['jxglcookie'], $url);
    // echo $content;
    // $html =new simple_html_dom();
    // $html -> load($content);
    // echo $html;
    $lesson = array('日' => array(), '一' => array(), '二' => array(), '三' => array(), '四' => array(), '五' => array(), '六' => array());
    // print_r($lesson); 

    preg_match_all("/<td.*?>(.*?)<\/td>/", $content, $match);
    // print_r($match[0]);
    /*
    exp[0] 课程名称
    exp[1] 课程时间
    exp[2] 任课老师
    exp[3] 上课地点
    exp[4] 期末考试时间
    exp[5] 期末考试地点
     */
    foreach ($match[0] as $j => $k) {
        if (strlen($k) > 100) {
            if (strrpos('日', $k)) {
                $exp = explode('<br>', $k);
                array_push($lesson['日'], $exp);
            } else if (strrpos($k, '一')) {
                $exp = explode('<br>', $k);
                array_push($lesson['一'], $exp);
            } else if (strrpos($k, '二')) {
                $exp = explode('<br>', $k);
                array_push($lesson['二'], $exp);
            } else if (strrpos($k, '三')) {
                $exp = explode('<br>', $k);
                array_push($lesson['三'], $exp);
            } else if (strrpos($k, '四')) {
                $exp = explode('<br>', $k);
                array_push($lesson['四'], $exp);
            } else if (strrpos($k, '五')) {
                $exp = explode('<br>', $k);
                array_push($lesson['五'], $exp);
            } else if (strrpos($k, '六')) {
                $exp = explode('<br>', $k);
                array_push($lesson['六'], $exp);
            }
        }
    }
    return $lesson;
}
/**************************************************************
*
* 使用特定function对数组中所有元素做处理
* @param string &$array 要处理的字符串
* @param string $function 要执行的函数
* @return boolean $apply_to_keys_also 是否也应用到key上
* @access public
*
*************************************************************/
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
/**************************************************************
*
* 将数组转换为JSON字符串（兼容中文）
* @param array $array 要转换的数组
* @return string 转换得到的json字符串
* @access public
*
*************************************************************/
function JSON($array)
{
    arrayRecursive($array, 'urlencode', true);
    $json = json_encode($array);
    return urldecode($json);
}

print_r(JSON(get_lesson($host)));

?>