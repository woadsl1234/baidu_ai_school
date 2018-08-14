<?php
header('content-type:text/json;charset=utf-8');
use const ParagonIE\ConstantTime\true;
    require_once('db_function.php');
    function getip(){
        if(getenv('HTTP_CLIENT_IP')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
          } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
          } elseif(getenv('REMOTE_ADDR')) {
            $onlineip = getenv('REMOTE_ADDR');
          } else {
            $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
          }
          return $onlineip;
    }
    
    function err_back(){
        return back_json(mysqli_error($conn),'后端去看看');
    }

    function back_json($err,$msg,$info=null){
        $json = array();
        $json['err'] = $err;
        $json['msg'] = $msg;
        // echo json_encode($json,True);
        // echo "<br>";
        if($info != null)
        {
            $json['info'] = $info;
        }
        return json_encode($json,JSON_UNESCAPED_UNICODE);
    }
    
    function get_date(){
        $str = date("Y-m-d H:i:s", time()+8*3600);
        return $str;
    }

    