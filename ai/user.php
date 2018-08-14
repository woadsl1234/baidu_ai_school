<?php 
    // echo "asda";
    header('Content-type: text/html; charset=gb2312');
    require_once('config.php');
    class User 
    {
        private $schools;
        private $lesson;
        private $grades;
        private $kaoshi;
        private $cascookie;
        private $jxdccookie;
        private $cookie;

        function __construct($school, $username, $password)
        {   $this->schools = array('���ݵ��ӿƼ���ѧ' => 'http://jxgl.hdu.edu.cn/', '����ʦ����ѧ' => 'http://jwgl1.hznu.edu.cn/', '�㽭ũ�ִ�ѧ' => 'http://115.236.84.162/');
            $url = $this -> schools[$school];
            $this -> cascookie = dirname(__FILE__) . '/cascookie.txt';
            $this -> cookie = dirname(__FILE__) . '/cookie.txt';
            $this -> jxdccookie = dirname(__FILE__) . '/jxdccookie.txt';
            // echo $url;
            if(!strcmp($school, '���ݵ��ӿƼ���ѧ'))
            {
                $content = $this -> hdu_login($url, $username, $password);
            }
            else
            {
                $content = $this -> simple_login($url, $username, $password);
            }
            // echo $content;
            $this -> lesson = $this -> take_lesson($url, $content, $this -> cookie);
            $this -> grades = $this -> take_grades($url, $content, $this -> cookie);
            // $this -> kaoshi = $this -> take_kaoshi($url, $content, $this -> cookie, '2017-2018', '2');

            // print_r(JSON($this -> lesson));
        }
        private function simple_login($url1, $username, $password){
            $url = $url1.'default2.aspx';
            // echo $url;
            $viewstate = VIEWSTATE_get($url,$this->cookie);
            // echo $viewstate;
            $url = $url1.'CheckCode.aspx';
            $img = get_image($url,$this->cookie);
            $fp = fopen("checkcode.png","w");  
            fwrite($fp,$img);   
            fclose($fp);
            $res = check();
            // echo $res;
            $url = $url1.'default2.aspx';
            $post=array(
                '__VIEWSTATE'=>$viewstate,
                'txtUserName'=>$username,
                'TextBox2'=>$password,
                'txtSecretCode'=>$res,
                'RadioButtonList1'=>'ѧ��',
                'Button1'=>'��¼',
                'lbLanguage'=>'',
                'hidPdrs'=>'',
                'hidsc'=>''
            );
            $content = login_post($url, $this->cookie, $post);
            // print_r($content);
            return $content;
        }
        private function hdu_login($url, $username, $password){     //�Һ������ţ��
            $lt = lt_get("http://cas.hdu.edu.cn/cas/login?service=http://jxgl.hdu.edu.cn/default.aspx", $this->cascookie);
            $url1 = hdu_login_post("http://cas.hdu.edu.cn/cas/login", $this->cascookie, array('username' => $username, 'password' => md5($password), 'lt' => $lt, 'service' => 'http://jxgl.hdu.edu.cn'));
            get_cookie("http://jxgl.hdu.edu.cn", $this->cookie);
            get_cookie("http://jxdc.hdu.edu.cn", $this->jxdccookie);
            get_content($url1, $this->cookie);
            $rs = get_content("http://cas.hdu.edu.cn/cas/login?service=http://jxdc.hdu.edu.cn/index.php", $this->cascookie);
            preg_match('/window.location.href="(.*)"\+cookie;/', $rs, $match);
            $url2 = $match[1];
            get_content($url2, $this->jxdccookie);
            get_content("http://jxgl.hdu.edu.cn/index.aspx", $this->cookie);
            $rs = get_content("http://cas.hdu.edu.cn/cas/login?service=http://jxgl.hdu.edu.cn/index.aspx", $this->cascookie);
            preg_match('/window.location.href="(.*)"\+cookie;/', $rs, $match);
            $url3 = $match[1];
            $rs = get_content($url3, $this->cookie);
            preg_match('/(\d{8})/', $rs, $match);
            $url4 = "http://jxgl.hdu.edu.cn/xs_main.aspx?xh=" . $match[1];
            $content = get_content($url4, $this->cookie);
            return $content;
        }
        private function take_grades($url, $content, $cookie){
            $url5 = $url;
            preg_match_all('/<a href="(.*?)"/m', $content, $match);
            foreach ($match[1] as $i => $j) {
                if (preg_match('/xscjcx/', $j))                                                      //ѧ���α�
                {
                    // echo "<br>";
                    $url = $url5 . $j;
                }
            }
            // echo $url;
            $content = get_content($url, $cookie);
            // echo $content;
            $re = "/<td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td>/";
            preg_match_all($re, $content, $match);
            // print_r($match); 
            // print_r(JSON($match));
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
            $grades = array_T($match);
            // print_r($grades);
            return $grades;
            // print_r(JSON($grades));
        }
        private function take_kaoshi($url, $content, $cookie, $date, $xueqi)
        {
            $url5 = $url;
            preg_match_all('/<a href="(.*?)"/m', $content, $match);
            foreach ($match[1] as $i => $j) {
                if (preg_match('/xskscx/', $j))                                                      //ѧ���α�
                {
                    // echo "<br>";
                    $url = $url5 . $j;
                }
            }
            // print_r($url);
            $html = get_content($url, $this -> cookie);
            preg_match_all('/<input id="__VIEWSTATE" .*? value="(.*?)" >/m', $html, $vs);
            // print_r($vs);
            // $state=$vs[1][0];  //$state���һ��post��__VIEWSTATE
            // print_r($state);
            $post=array(
                '__EVENTTARGET'=>'',
                '__EVENTARGUMENT'=>'',
                '__VIEWSTATE'=>$state,
                'xnd'=>$date,//����Ϊ2015-2016��Ϊ2015-2016��ȳɼ�
                'xqd'=>$xueqi,//����Ϊ1��Ϊ��һѧ�ڳɼ�
                'btn_xq'=>'%D1%A7%C6%DA%B3%C9%BC%A8'
             );
            // echo $html;
        }
        private function take_lesson($url, $content, $cookie)
        {
            $url5 = $url;
            preg_match_all('/<a href="(.*?)"/m', $content, $match);
            foreach ($match[1] as $i => $j) {
                if (preg_match('/xskbcx/', $j))                                                      //ѧ���α�
                {
                    // echo "<br>";
                    $url = $url5 . $j;
                }
            }
            $content = get_content($url, $cookie);
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
    
    if(isset($_POST['school'])&&isset($_POST['username'])&&isset($_POST['password']))
    {
        $y = new User($_POST['school'], $_POST['username'], $_POST['password']);
        // $x = new User('�㽭ũ�ִ�ѧ', '201620020214', 'xlj123**');
        // $z = new User('����ʦ����ѧ', '2016210201104', 'Syt19970811');
        // var_dump($x -> get_lesson());
        // var_dump($x -> get_schools());
        // print_r($x -> get_lesson());
    }
?>
