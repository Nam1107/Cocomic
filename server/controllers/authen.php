<?php
session_start();
include ("../path.php") ;
include(ROOT_PATH . "/database/db.php");
include (ROOT_PATH . "/helpers/validateUser.php");

define('MD5_PRIVATE_KEY', '2342kuhskdfsd23(&kusdhfjsgJYGJGsfdf384');

$errors = array();

$table = '[User]';

function md5Security($pwd) {
	return md5(md5($pwd).MD5_PRIVATE_KEY);
}

if(isset($_POST['authenLogin'])){
    $user = authenToken();
    $data['user'] = null;
    if($user != null ){
        if($user['IsAdmin']==0){
            $data['status'] = 'user';
        }else {
            $data['status'] = 'admin';
        }
        $data['user'] = $user;
        
    }else{
        $data['status'] = 'guest';
    }
    dd($data);
}
if(isset($_POST['logout'])){
    
    session_destroy();
    if (isset($_COOKIE['token'])) {
        $token['token'] = $_COOKIE['token'];
        
    }else{
        die();
    }
    delete('[Login_Token]',$token);
    setcookie('token', '', time()-7*24*60*60, '/');
    
    die();
}
if(isset($_POST['signup'])){
    $errors = validateUser($_POST);

    if(count($errors) === 0){
        unset($_POST['signup'],$_POST['re_Pass']);
        $_POST['IsAdministrator'] = '0';
        $_POST['Password'] = password_hash($_POST['Password'],PASSWORD_DEFAULT);
        $user_id = create('[User]',$_POST);
        $user = selectOne('[User]',['Email'=>$_POST['Email']]);
        
        // loginUser($user);
        $_SESSION['user'] = $user;
    }else {
        $username = $_POST['Username'];
        $email = $_POST['Email'];
        $pass = $_POST['Password'];
        $re_pass = $_POST['re_Pass'];
    }
    
}


if(isset($_POST['signin'])){
    $errors = validateLogin($_POST);

    if(count($errors) === 0){
        $user = selectOne('[User]',['Email'=>$_POST['Email']]);
        if(!$user){
            array_push($errors,'Wrong Email');
        }
        elseif(password_verify($_POST['Password'],$user['Password'])){
             $login_token['Token'] = md5Security($user['Email'].time().$user['UserID']);
             $login_token['UserID'] = $user['UserID'];
             setcookie('token', $login_token['Token'], time()+7*24*60*60, '/');
             create('[Login_Token]',$login_token);
             $_SESSION['user'] = $user;
        }else {
            array_push($errors,'Wrong Password');
        }
    }

    $data['errors'] = $errors;
    if(count($errors) === 0){
        if($user['IsAdmin']==0){
            $data['status'] = 'user';
        }else {
            $data['status'] = 'admin';
        }
        
    }else{
        $data['status'] = 'guest';
    }
    dd($data);
}