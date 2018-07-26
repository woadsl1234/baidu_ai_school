<?php
require 'simple_html_dom.php';
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
        if (preg_match('/xskbcx/', $j))                                                      //????¦Á??? xskbcx
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
    $lesson = array('??' => array(), '?' => array(), '??' => array(), '??' => array(), '??' => array(), '??' => array(), '??' => array());
    // print_r($lesson);

    preg_match_all("/<td.*?>(.*?)<\/td>/", $content, $match);
    // print_r($match[0]);
    /*
    exp[0] ???????
    exp[1] ??????
    exp[2] ??????
    exp[3] ??¦Å??
    exp[4] ??????????
    exp[5] ?????????
     */
    foreach ($match[0] as $j => $k) {
        if (strlen($k) > 100) {
            if (strrpos('??', $k)) {
                $exp = explode('<br>', $k);
                array_push($lesson['??'], $exp);
            } else if (strrpos($k, '?')) {
                $exp = explode('<br>', $k);
                array_push($lesson['?'], $exp);
            } else if (strrpos($k, '??')) {
                $exp = explode('<br>', $k);
                array_push($lesson['??'], $exp);
            } else if (strrpos($k, '??')) {
                $exp = explode('<br>', $k);
                array_push($lesson['??'], $exp);
            } else if (strrpos($k, '??')) {
                $exp = explode('<br>', $k);
                array_push($lesson['??'], $exp);
            } else if (strrpos($k, '??')) {
                $exp = explode('<br>', $k);
                array_push($lesson['??'], $exp);
            } else if (strrpos($k, '??')) {
                $exp = explode('<br>', $k);
                array_push($lesson['??'], $exp);
            }
        }
    }
    return $lesson;
}
/**************************************************************
 *
 * ??????function?????????????????????
 * @param string &$array ???????????
 * @param string $function ???§Ö????
 * @return boolean $apply_to_keys_also ????????key??
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
 * ??????????JSON????????????????
 * @param array $array ??????????
 * @return string ????????json?????
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