<?php
    require_once('function.php');
    header('Access-Control-Allow-Origin:*');
    // if(!isset($_SESSION['username']))
    // {
    //     die(back_json('1','你还没登录呢亲'));
    // }
    if($_SERVER['REQUEST_METHOD']==="POST")
    {
        // var_dump($_SESSION['username']);
        $data = file_get_contents("php://input");
        // var_dump($data);
        $data = json_decode($data,true);
        // var_dump($data['action']);
        if(!isset($data['action'])){
            die(back_json('1','没有action参数'));
        }
        $name = $data['name'];
        $user = $data['user'];
        if(isset($data['beizhu'])){
            $beizhu = $data['beizhu'];
        }
        else{
            $beizhu = '';
        }
        if($data['action']==='pull'){
            echo get_all_friends($user);
        }
        else if($data['action']==='delete'){
            echo delete_friend($user,$name);
        }
        else if($data['action']==='add')
        {
            echo add_friends($user,$name, $beizhu);
        }
        else if($data['action']==='change')
        {
            echo update_beizhu($user,$name,$beizhu);
        }
        else{
            echo back_json("1","貌似没有这个参数");
        }
    }
