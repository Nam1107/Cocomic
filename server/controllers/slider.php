<?php
session_start();
include ("../path.php") ;
include(ROOT_PATH . "/database/db.php");

$errors = array();
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
$datetime->setTimezone($timezone);
$time = $datetime->format('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['slider'])){
        $list = selectAll('[Slider]');
        $data['slider'] = $list;
        dd($data);
        exit();
    }

    if(isset($_GET['banner'])){
        $list = selectOne('[Slider]',['id'=>$_GET['id']]);
        $data['slider'] = $list;
        dd($data);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add-banner'])){
        unset($_POST['add-banner']);
        $list = create('[Slider]',$_POST);
        exit();
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['delete-banner'])){
        if($sent_vars['delete-banner']==$keyadmin){
            unset($sent_vars['delete-banner']);
            delete('[Slider]',$sent_vars);
        }
        exit();
    }

}
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['update-banner'])){
        if($sent_vars['update-banner']==$keyadmin){
            $id = $sent_vars['id'];
            unset($sent_vars['update-banner'],$sent_vars['id']);
            update('[Slider]',['id'=>$id],$sent_vars);
        }
        exit();
    }

}

?>