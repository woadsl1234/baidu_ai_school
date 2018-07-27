<?php
// require '../simple_html_dom.php';
header('Content-type: text/html; charset=gb2312');
require '../config.php';
require_once('../json.php');

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

if(isset($_GET['cjcx'])){
    function get_lesson($content)
    {
        $url5 = "http://jxgl.hdu.edu.cn/";
        preg_match_all('/<a href="(.*?)"/m', $content, $match);
        foreach ($match[1] as $i => $j) {
            if (preg_match('/xskbcx/', $j))                                                      //学生课表
            {
                // echo "<br>";
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
        exp[1] 上课时间
        exp[2] 任课老师
        exp[3] 上课地点
        exp[4] 考试时间
        exp[5] 考试地点
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
    print_r(JSON(get_lesson($host)));
}
function get_gress($content){
    $url5 = "http://jxgl.hdu.edu.cn/";
        preg_match_all('/<a href="(.*?)"/m', $content, $match);
        foreach ($match[1] as $i => $j) {
            if (preg_match('/xscjcx/', $j))                                                      //学生课表
            {
                // echo "<br>";
                $url = $url5 . $j;
            }
        }
    // echo $url;
    $content = get_content_add_refer($url, $GLOBALS['jxglcookie'], $url);
    // echo $content;
    $re = "/<td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td>/";
    preg_match_all($re, $content, $match);
    // print_r($match);
    // print_r(JSON($match));
    $grade = array('学年' => array(), '学期' => array(), '课程代码' => array(), '课程名称' => array(), '课程性质' => array(), '课程归属' => array(), '学分' => array(), '成绩' => array(), '补考成绩' => array(), '是否重修' => array(), '开课学院' => array(), '备注' => array(), '补考备注' => array());
    /*
    $match[1]学年
    $match[2]学期
    $match[3]课程代码
    $match[4]课程名称
    $match[5]课程性质
    $match[6]课程归属
    $match[7]学分
    $match[8]成绩
    $match[9]补考成绩
    $match[10]是否重修
    $match[11]开课学院
    $match[12]备注
    $match[13]补考备注
    */
    foreach($match as $i => $j){
        $x = 1;
        $j = 1;
        
    }
}
get_gress($host);
?>