<?php

function userOnly(){
    if(empty($_SESSION['user']['UserID'])){
        $_SESSION['message'] = 'You need to login first';
        $_SESSION['errors'] = 1;
        return 0;
    }else {
        return 1;
    }
}

function adminOnly(){
    if(empty($_SESSION['UserID']||$_SESSION['IsAdmin']==0)){
        $_SESSION['message'] = 'You are not authorized';
        $_SESSION['errors'] = 1;
        return 0;
    }else {
        return 1;
    }
}
