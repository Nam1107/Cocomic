<?php

function validateUser($user){
    
    $errors = array();
    if(empty($user['UserName'])){
        array_push($errors,'UserName is required');
    }
    if(empty($user['Email'])){
        array_push($errors,'Email is required');
    }
    if(strlen($user['Password'])<6){
        array_push($errors,'Password must have more than 6 characters');
    }
    if(empty($user['Password'])){
        array_push($errors,'Password is required');
    }
    if(empty($user['re_Pass'])){
        array_push($errors,'Password Confirm is required');
    }elseif($user['re_Pass'] !== $user['Password']){
        array_push($errors,'Password do not match');
    }
    $existingUser = selectOne('[User]',['Email' => $user['Email']]);
    if(isset($existingUser)){
        array_push($errors,'Email is already registered ');
    }

    return $errors;
}

function validateUpdateUser($user){
    
    $errors = array();
    if(strlen($user['Password'])<6){
        array_push($errors,'Password must have more than 6 characters');
    }
    if(empty($user['re_Pass'])){
        array_push($errors,'Password Confirm is required');
    }elseif($user['re_Pass'] !== $user['Password']){
        array_push($errors,'Password do not match');
    }

    return $errors;
}
function validateLogin($user){
    
    $errors = array();

    if(empty($user['Email'])){
        array_push($errors,'Email is required');
    }
    if(empty($user['Password'])){
        array_push($errors,'Password is required');
    }


    return $errors;
}

function authenToken() {
	if (isset($_SESSION['user'])) {
		return $_SESSION['user'];
	}

	
	if (empty($_COOKIE['token'])) {
		return null;
	}else{
        $token = $_COOKIE['token'];
    }

	$result   = custom("select [User].* from [User], Login_Token where [User].UserID = [Login_Token].UserID and [Login_Token].token = '$token'");

	if ($result != null && count($result) > 0) {
		$_SESSION['user'] = $result[0];

		return $result[0];
	}

	return null;
}
