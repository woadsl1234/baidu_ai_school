<?php 
    session_start();
    require_once('function.php');
    header('Access-Control-Allow-Origin:*');

    if($_SERVER['REQUEST_METHOD']==="POST")
    {
        $data = file_get_contents("php://input");
        if(isset($data)){
            $data = json_decode($data,true);
            // print_r($data);
            $username = $data['username'];
            $password = $data['password'];
            $bool = judge_exist($username);
            if($bool){
                $result = get_password($username,$password);
                if($result === true)
                {
                    $info = getinfo($username);
                    $info['new_mail'] =  get_new_mail($username, $info['last_login_time']);
                    echo back_json('0',"登录成功",$info);
                    $_SESSION['username'] = $username;
                    $_SESSION['userid'] = $info['id'];
                }
                if($result === false)
                {
                    echo back_json('1', "密码错误");
                }
            }
            else{
                $ip = getip();
                $result = register($username, $password, $ip);
                if($result === true)
                {
                    echo back_json('0',"注册成功", getinfo($username));
                    $_SESSION['username'] = $username;
                    $_SESSION['userid'] = getinfo($username)['id'];
                }
                else{
                    echo back_json(mysqli_error($conn),"失败,不知道为啥联系后端");
                }
            }
        }
    }
