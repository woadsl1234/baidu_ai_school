<?php
    // ini_set('max_execution_time', 3000);
    header('Content-type: text/html; charset=gb2312');
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
    function login_post($url,$cookie,$post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
        curl_setopt($ch, CURLOPT_REFERER, 'http://115.236.84.162/default2.aspx'); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));  
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
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

    $url = 'http://115.236.84.162/default2.aspx';
    $cookie = dirname(__FILE__) . '/jwglcookie.txt';
    $viewstate = VIEWSTATE_get($url,$cookie);
    // echo $viewstate;
    $url = "http://115.236.84.162/CheckCode.aspx";
    $img = get_image($url,$cookie);
    $fp = fopen("checkcode.png","w");  
    fwrite($fp,$img);   
    fclose($fp);
    require_once('checkpng.php');
    $res = check();
    echo $res;
    $url = 'http://115.236.84.162/default2.aspx';
    $post=array(
        '__VIEWSTATE'=>$viewstate,
        'txtUserName'=>'201620020214',
        'TextBox2'=>'xlj123**',
        'txtSecretCode'=>$res,
        'RadioButtonList1'=>'ѧ��',
        'Button1'=>'��¼',
        'lbLanguage'=>'',
        'hidPdrs'=>'',
        'hidsc'=>''
    );
    $content = login_post($url, $cookie, $post);
    // echo $content;

    // $url = 'http://115.236.84.162/xs_main.aspx?xh=201620020214';
    // $html = get_content($result, $cookie);
    // // echo $html;

    $url5 = "http://115.236.84.162/";
    preg_match_all('/<a href="(.*?)"/m', $content, $match);
    // var_dump($match);
    foreach ($match[1] as $i => $j) {
        if (preg_match('/xskbcx/', $j))                                                      //ѧ���α�
        {
            // echo "<br>";
            $url = $url5 . $j;
            // echo '\n'.$j.'\n';
        }
    }
    // echo $url;
    $content = get_content_add_refer($url, $GLOBALS['cookie'], $url);
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
     print_r($lesson);
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

function JSON($array)
{
    arrayRecursive($array, 'urlencode', true);
    $json = json_encode($array);
    return urldecode($json);
}

// print_r(JSON(get_lesson($host)));
?>