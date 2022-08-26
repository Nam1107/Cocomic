<?php
session_start();
include ("../path.php") ;
include(ROOT_PATH . "/database/db.php");
include (ROOT_PATH . "/helpers/validateUser.php");

$errors = array();

$table = '[User]';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['create-user'])){
        $errors = validateUser($_POST);
        if(count($errors) === 0){
            if(!isset($_POST['IsAdmin'])){
                $_POST['IsAdmin'] = 0;
            }
            $_POST['Avatar'] = 'http://noidangsong.vn/files/uploads/fb1735058496563345/1526444239-tt_avatar_small.jpg';
            $_POST['Password'] = password_hash($_POST['Password'],PASSWORD_DEFAULT);
            unset($_POST['create-user'],$_POST['admin'],$_POST['re_Pass']);
            $user_id = create('[User]',$_POST);
            $data['rep'] = 1;
            $data['message'] = 'Create Success';
            dd($data);
            exit();
        }
        else{
            $data['rep'] = 0;
            $data['message'] = $errors;
            dd($data);
            exit();
        }
        
    }
    if(isset($_POST['update-user'])){
        if(!empty($_POST['Password'])){
            
            $errors = validateUpdateUser($_POST);
            if(count($errors) === 0){
                $_POST['Password'] = password_hash($_POST['Password'],PASSWORD_DEFAULT);
                $user['UserID'] = $_POST['UserID'];
                unset($_POST['update-user'],$_POST['admin'],$_POST['re_Pass'],$_POST['UserID']);
                update('[User]',$user,$_POST);
                $data['rep'] = 1;
                $data['message'] = 'Update Success';
                dd($data);
                exit();
            }
            else{
                $data['rep'] = 0;
                $data['message'] = $errors;
                dd($data);
                exit();
            }
            
        }else {
            $user['UserID'] = $_POST['UserID'];
            unset($_POST['update-user'],$_POST['re_Pass'],$_POST['Password'],$_POST['UserID']);
            $data['User'] = $user;
            update('[User]',$user,$_POST);
            $data['rep'] = 1;
            $data['message'] = 'Update Success';
            dd($data);
            exit();
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['delete-user'])){
        $id['UserID'] = $sent_vars['user-id'];
        delete($table,$id);
        exit();
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['user-id'])){
        $user_id = $_GET['user-id'];
        $user = selectOne($table,['UserID'=>$user_id]);
        $data['User'] = $user;
        dd($data);
    }
    
    if(isset($_GET['list-user'])){
        if($_GET['list-user']<=0){
            $page = 0;
        }else{
            $page = $_GET['list-user']-1;
        }
        $orderBy = 'ASC';
        if(isset($_GET['order-by'])){
            $orderBy = $_GET['order-by'];
        }
        $perPage = $_GET['per-page'];
        $search = $_GET['search'];

        $total = count(custom("SELECT * FROM [User] WHERE [UserName] LIKE '%$search%'"));
        $check = ceil($total/$perPage);
        if($page >= $check&&$check>0){
            $page = $check-1;
        }
        $users = selectAll('[User]',[],"WHERE [UserName] LIKE '%$search%' ORDER BY CreateDate $orderBy OFFSET  $perPage*$page ROWS FETCH NEXT $perPage ROWS ONLY");
        $NumOfUser = custom('SELECT COUNT(*)  AS NumOfUser FROM [User]; ');
        $data['User'] = $users;
        $data['NumOfUser']=$NumOfUser[0]['NumOfUser'];
        $data['NumOfPage'] = ceil($check);
        $data['Page'] = $page;
        dd($data);
        exit();
    }
}
