<?php
    require_once('function.php');
    header('Access-Control-Allow-Origin:*');
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $data = file_get_contents("php://input");
        $data = json_decode($data,true);
        // print_r($data);
        if(isset($data['action']))
        {
            if($data['action']==='send'){
                $sender = $data['from'];
                $reciever = $data['to'];
                // var_dump(judge_exist($reciever));
                // print_r($reciever);
                $userid = $_SESSION['id'];
                $content = $data['content'];
                echo send_mail($sender, $reciever, $content);
            }
            else if($data['action']==='get')
            {
                // print_r($data);
                $name = $data['receiver'];
                echo get_mail($name);
            }
            else if($data['action']==='back')
            {

            }
            else if($data['action']==='read'){
                
            }
        }
        else{
            echo back_json('1','没有action参数');
        }
    }