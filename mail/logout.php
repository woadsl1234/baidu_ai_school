<?php   
    require_once('function.php');
    header('Access-Control-Allow-Origin:*');
    if($_SERVER['REQUEST_METHOD']=="POST")
    {  
        $data = file_get_contents("php://input");
        // var_dump($data);
        $data = json_decode($data,true);
        $user = $data['user'];
        // var_dump($data['action']);
        if(!isset($data['action'])){
            die(back_json('1','没有action参数'));
        }
        else if($data['action']==='logout'){
            echo last_login($user);
            // session_destroy();
            echo back_json('0','登出');
        }
    }