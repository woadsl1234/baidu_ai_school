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
            if (preg_match('/xskbcx/', $j))                                                      //ѧ���α�
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
        $lesson = array('��' => array(), 'һ' => array(), '��' => array(), '��' => array(), '��' => array(), '��' => array(), '��' => array());
        // print_r($lesson); 

        preg_match_all("/<td.*?>(.*?)<\/td>/", $content, $match);
        // print_r($match[0]);
        /*
        exp[0] �γ�����
        exp[1] �Ͽ�ʱ��
        exp[2] �ο���ʦ
        exp[3] �Ͽεص�
        exp[4] ����ʱ��
        exp[5] ���Եص�
        */
        foreach ($match[0] as $j => $k) {
            if (strlen($k) > 100) {
                if (strrpos('��', $k)) {
                    $exp = explode('<br>', $k);
                    array_push($lesson['��'], $exp);
                } else if (strrpos($k, 'һ')) {
                    $exp = explode('<br>', $k);
                    array_push($lesson['һ'], $exp);
                } else if (strrpos($k, '��')) {
                    $exp = explode('<br>', $k);
                    array_push($lesson['��'], $exp);
                } else if (strrpos($k, '��')) {
                    $exp = explode('<br>', $k);
                    array_push($lesson['��'], $exp);
                } else if (strrpos($k, '��')) {
                    $exp = explode('<br>', $k);
                    array_push($lesson['��'], $exp);
                } else if (strrpos($k, '��')) {
                    $exp = explode('<br>', $k);
                    array_push($lesson['��'], $exp);
                } else if (strrpos($k, '��')) {
                    $exp = explode('<br>', $k);
                    array_push($lesson['��'], $exp);
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
            if (preg_match('/xscjcx/', $j))                                                      //ѧ���α�
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
    $grade = array('ѧ��' => array(), 'ѧ��' => array(), '�γ̴���' => array(), '�γ�����' => array(), '�γ�����' => array(), '�γ̹���' => array(), 'ѧ��' => array(), '�ɼ�' => array(), '�����ɼ�' => array(), '�Ƿ�����' => array(), '����ѧԺ' => array(), '��ע' => array(), '������ע' => array());
    /*
    $match[1]ѧ��
    $match[2]ѧ��
    $match[3]�γ̴���
    $match[4]�γ�����
    $match[5]�γ�����
    $match[6]�γ̹���
    $match[7]ѧ��
    $match[8]�ɼ�
    $match[9]�����ɼ�
    $match[10]�Ƿ�����
    $match[11]����ѧԺ
    $match[12]��ע
    $match[13]������ע
    */
    foreach($match as $i => $j){
        $x = 1;
        $j = 1;
        
    }
}
get_gress($host);
?>