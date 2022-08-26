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
    if(isset($_GET['list-follow'])){
        $list = selectAll('[Follow_Comic]',['UserID'=>$_GET['user-id']]);

        for($i = 0;$i< count($list);$i++){
            $comic = selectOne('[Comic]',['ComicID'=>$list[$i]['ComicID']]);
            $list[$i]['ComicName'] = $comic['ComicName'];
            $list[$i]['ComicImage'] = $comic['ComicImage'];
        }
        $data['Follows'] = $list;
        dd($data);
        exit();
    }
    if(isset($_GET['get-follow'])){
        $userID = $_GET['UserID'];
        $comicID  = $_GET['ComicID'];
        $list = custom("SELECT *  FROM [Follow_Comic] where [UserID] = $userID and [ComicID] = $comicID");
        $data['Follows'] = $list;
        dd($data);
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add-follow'])){
        unset($_POST['add-follow']);
        $_POST['FollowDate'] = $time;
        create('[Follow_Comic]',$_POST);
        $comicID = $_POST['ComicID'];
        $index = count(custom("SELECT * FROM [Follow_Comic] WHERE [ComicID] = '$comicID'"));
        update('[Comic]',['ComicID'=>$comicID],['FollowNo'=>$index]);
        
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['delete-follow'])){
        unset($sent_vars['delete-follow']);
        $userID = $sent_vars['UserID'];
        $comicID  = $sent_vars['ComicID'];
        delete('[Follow_Comic]',$sent_vars);
        $index = count(custom("SELECT * FROM [Follow_Comic] WHERE [ComicID] = '$comicID'"));
        update('[Comic]',['ComicID'=>$comicID],['FollowNo'=>$index]);
        exit();
    }
}

?>