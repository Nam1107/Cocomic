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
    if(isset($_GET['list-comment'])){
        unset($_GET['list-comment']);
        $list = selectAll('[Comments]',$_GET," order by ComicID ASC");
        for($i = 0;$i< count($list);$i++){
            $user = selectOne('[User]',['UserID'=>$list[$i]['UserID']]);
            $comic = selectOne('[Comic]',['ComicID'=>$list[$i]['ComicID']]);
            $list[$i]['ComicName'] = $comic['ComicName'];
            $list[$i]['UserName'] = $user['UserName'];
            $list[$i]['Avatar'] = $user['Avatar'];
        }
        $data['Comments'] = $list;
        dd($data);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add-comment'])){
        unset($_POST['add-comment']);
        $_POST['CommentDate'] = $time;
        create('[Comments]',$_POST);
        $comicID = $_POST['ComicID'];
        $index = count(custom("SELECT * FROM [Comments] WHERE [ComicID] = '$comicID'"));
        update('[Comic]',['ComicID'=>$comicID],['CommentNo'=>$index]);
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['delete-comment'])){
        unset($sent_vars['delete-comment']);
        $comment = selectOne('[Comments]',['ID'=>$sent_vars['ID']]);
        delete('[Comments]',['ID'=>$sent_vars['ID']]);
        $comicID = $comment['ComicID'];
        $index = count(custom("SELECT * FROM [Comments] WHERE [ComicID] = '$comicID'"));
        update('[Comic]',['ComicID'=>$comicID],['CommentNo'=>$index]);
        exit();
    }
}
?>