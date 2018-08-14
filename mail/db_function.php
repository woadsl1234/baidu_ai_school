<?php
@$conn = new mysqli('127.0.0.1', 'root', 'adsl1234', 'mail');
    //0表示没有任何错误
    /* change character set to utf8 */
$conn->set_charset("utf8");
if (mysqli_connect_errno()) {
    echo '数据库连接错误' . mysqli_connect_error();
    exit();
}

function getinfo($username)
{
    global $conn;
    $_sql = "select * from users where username='" . $username . "'";
    $result = $conn->query($_sql);
    $result = $result->fetch_array(MYSQLI_ASSOC);
    return $result;
}

function judge_exist($username)
{
    global $conn;
    $_sql = "select * from users where username='" . $username . "'";
    $result = $conn->query($_sql);
    $result = $result->fetch_array(MYSQLI_ASSOC);
    // var_dump($result);
    if ($result === null)
        return False;
    else
        return True;
}

function get_password($username, $password)
{
    global $conn;
    $_sql = "select * from users where username='" . $username . "'";
    $result = $conn->query($_sql);
    $result = $result->fetch_array(MYSQLI_ASSOC);
    if ($result['password'] === $password) {
        return True;
    } else {
        return False;
    }
}
function register($username, $password, $ip)
{
    global $conn;
    $_sql = "insert into `users`(`username`,`password`,`ip`) values('" . $username . "','" . $password . "','" . $ip . "')";
        // echo $_sql;
    $result = $conn->query($_sql);
        // if( $conn ->errno) {
        //     echo' 数据库操作时发生错误，错误代码是： ' . $_mysqli ->error;
        //     }
    if ($result === True) {
        return True;
    } else {
        return False;
    }
}

function sql_query($_sql){
    global $conn;
    $result = $conn->query($_sql);
    return $result->fetch_array(MYSQLI_ASSOC);
}

function send_mail($sender, $reciever, $content)
{
    global $conn;
    // echo $reciever;
    // var_dump(sql_query("select * from users where username = 'ckj12'"));
    // var_dump(judge_exist($reciever));

    // getinfo($username);
    if (!judge_exist($reciever)) {
        return back_json('1', '用户不存在');
    } else {
        if (!isset($_SESSION['userid'])) {
            $id = '';
        } else {
            $id = $_SESSION['userid'];
        }
        $_sql = "insert into mail (`send`,`reciever`,`userid`,`content`,`updatetime`) values ('" . $sender . "','" . $reciever . "','" . $_SESSION['userid'] . "','" . $content . "','".get_date()."')";
        // var_dump($_sql);
        $res = $conn->query($_sql);
        if ($res === True) {
            return back_json('0','发送成功');
        } else {
            return back_json(mysqli_error($conn),'后端去看看');
        }
    }

}
function get_all_sql($_sql){
    global $conn;
    $result = $conn->query($_sql);
    $re = array();
    while($res = $result->fetch_array(MYSQLI_ASSOC)){
        $re[] = $res;
    }
    return $re;
}

function get_mail($username){
    global $conn;
    $_sql = "select * from mail where reciever='".$username."' order by updatetime desc";
    // echo $_sql;
    $res = get_all_sql($_sql);
    return back_json('0','success',$res);
}

function add_friends($user, $name, $beizhu=null)
{   
    global $conn;
    if (!judge_exist($name)){
        return back_json('1', '用户并未注册');
    }
    $_sql = "insert into menu (`user`, `name`, `beizhu`) values ('".$user."','".$name."','".$beizhu."')";
    $res = $conn->query($_sql);
    if ($res === True) {
        return back_json('0','添加成功');
    } else {
        return back_json(mysqli_error($conn),'后端去看看');
    }
}

function update_beizhu($user, $name, $beizhu)
{
    global $conn;
    $_sql = "update menu set beizhu = '".$beizhu."' where user = '".$user."' and name = '".$name."'";
    $res = $conn->query($_sql);
    if ($res === True) {
        return back_json('0','修改成功');
    } else {
        return back_json(mysqli_error($conn),'后端去看看');
    }
}

function delete_friend($user, $name){
    global $conn;
    $_sql = " delete from menu where user = '".$user."' and name = '".$name."'";
    $res = $conn->query($_sql);
    if ($res === True) {
        return back_json('0','删除成功');
    } else {
        return back_json(mysqli_error($conn),'后端去看看');
    }
}

function get_all_friends($user){
    $res = get_all_sql("select * from menu where user='".$user."' order by name");
    return back_json('0','success',$res);
}

function get_new_mail($user, $last_time){
    $_sql = "select * from mail where updatetime > '".$last_time."'";
    $len = count(get_all_sql($_sql));
    return $len;
}

function get_noread_number($user)
{
    
}

function last_login($user){
    global $conn;
    $_sql = "update users set last_login_time = '".get_date()."' where username = '".$user."'";
    $res = $conn->query($_sql);
    if ($res === True) {
        return ;
    } else {
        return back_json(mysqli_error($conn),'后端去看看');
    }
}

function read_mail($user){

}

function read_back_mail($user){

}

function chehui_mail($user){
    
}