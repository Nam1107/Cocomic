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
    if(isset($_GET['list-like'])){
        $list = selectAll('[Like_Comic]',['UserID'=>$_GET['user-id']]);
        for($i = 0;$i< count($list);$i++){
            $comic = selectOne('[Comic]',['ComicID'=>$list[$i]['ComicID']]);
            $list[$i]['ComicName'] = $comic['ComicName'];
            $list[$i]['ComicImage'] = $comic['ComicImage'];
        }
        $data['Likes'] = $list;
        dd($data);
        exit();
    }
    if(isset($_GET['get-like'])){
        $userID = $_GET['UserID'];
        $comicID  = $_GET['ComicID'];
        $list = custom("SELECT *  FROM [Like_Comic] where [UserID] = $userID and [ComicID] = $comicID");
        $data['Likes'] = $list;
        dd($data);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add-like'])){
        unset($_POST['add-like']);
        $_POST['LikeDate'] = $time;
        create('[Like_Comic]',$_POST);
        $comicID = $_POST['ComicID'];
        $index = count(custom("SELECT * FROM [Like_Comic] WHERE [ComicID] = '$comicID'"));
        update('[Comic]',['ComicID'=>$comicID],['LikeNo'=>$index]);
        
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['delete-like'])){
        unset($sent_vars['delete-like']);
        $userID = $sent_vars['UserID'];
        $comicID  = $sent_vars['ComicID'];
        delete('[Like_Comic]',$sent_vars);
        $index = count(custom("SELECT * FROM [Like_Comic] WHERE [ComicID] = '$comicID'"));
        update('[Comic]',['ComicID'=>$comicID],['LikeNo'=>$index]);
        exit();
    }
}
?>