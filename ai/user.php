<?php 
header('Content-type: text/html; charset=gb2312');
require_once('config.php');
    class User 
    {
        private $schools = array('杭州电子科技大学' => 'http://jxgl.hdu.edu.cn/', '杭州师范大学' => 'http://jwgl1.hznu.edu.cn/', '浙江农林大学' => 'http://115.236.84.162/');
        private $lesson;
        private $grades;

        function __construct($school, $username, $password)
        {
            $url = $this -> schools[$school];
            echo $url;
            if(!strcmp($school, '杭州电子科技大学'))
            {
                $content = $this -> hdu_login($url, $username, $password);
            }
            echo $content;
            // $lesson = $this -> take_lesson($url, $content);
            // var_dump($lesson);
        }
        private function simple_login($url1, $username, $password){
            $url = $url1.'default2.aspx';
            $cookie = dirname(__FILE__) . '/jwglcookie.txt';
            $viewstate = VIEWSTATE_get($url,$cookie);
            // echo $viewstate;
            $url = $url1.'CheckCode.aspx';
            $img = get_image($url,$cookie);
            $fp = fopen("checkcode.png","w");  
            fwrite($fp,$img);   
            fclose($fp);
            require_once('checkpng.php');
            $res = check();
            // echo $res;
            $url = $url1.'default2.aspx';
            $post=array(
                '__VIEWSTATE'=>$viewstate,
                'txtUserName'=>$username,
                'TextBox2'=>$password,
                'txtSecretCode'=>$res,
                'RadioButtonList1'=>'学生',
                'Button1'=>'登录',
                'lbLanguage'=>'',
                'hidPdrs'=>'',
                'hidsc'=>''
            );
            $content = login_post($url, $cookie, $post);
            return $content;
        }
        private function hdu_login($url, $username, $password){     //我杭电就是牛逼
            $cascookie = dirname(__FILE__) . '/cascookie.txt';
            $jxglcookie = dirname(__FILE__) . '/jxglcookie.txt';
            $jxdccookie = dirname(__FILE__) . '/jxdccookie.txt';

            $lt = lt_get("http://cas.hdu.edu.cn/cas/login?service=http://jxgl.hdu.edu.cn/default.aspx", $cascookie);
            $url1 = login_post("http://cas.hdu.edu.cn/cas/login", $cascookie, array('username' => $username, 'password' => md5($password), 'lt' => $lt, 'service' => 'http://jxgl.hdu.edu.cn'));
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
            return $content;
        }
        private function take_lesson($url, $content)
        {
            $url5 = $url;
            preg_match_all('/<a href="(.*?)"/m', $content, $match);
            foreach ($match[1] as $i => $j) {
                if (preg_match('/xskbcx/', $j))                                                      //学生课表
                {
                    // echo "<br>";
                    $url = $url5 . $j;
                }
            }
            $content = get_content($url, $GLOBALS['jxglcookie']);
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
        public function get_lesson(){
            return $this->lesson;
        }
        public function get_grades(){
            return $this->grades;
        }
        public function get_kaoshi(){
            return $this->kaoshi;
        }
        public function get_schools(){
            return $this->schools;
        }
    }
    
    echo "asda";
    $x = new User('杭州电子科技大学', '16184117', 'adsl1234');
    // var_dump($x -> get_lesson());
    // var_dump($x -> get_schools());
    print_r($x -> get_lesson());

?>
