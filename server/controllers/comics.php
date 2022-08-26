<?php
session_start();
include ("../path.php") ;
include(ROOT_PATH . "/database/db.php");

$errors = array();
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
$datetime->setTimezone($timezone);
$time = $datetime->format('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add-comic'])){
        $_POST['IsPublic'] = isset($_POST['IsPublic']) ? 1:0; 
        unset($_POST['add-comic']);
        $_POST['CreateDate'] = $time;
        $_POST['LastUpdate'] = $time;
        $_POST['LikeNo'] = 0;
        $_POST['ViewNo'] = 0;
        $_POST['FollowNo'] = 0;
        $_POST['CommentNo'] = 0;
        $Comic_ID = create('[Comic]',$_POST);
        if(!$Comic_ID){
            $data['errors'] = 1;
            $data['messenger'] = 'Errors';
        }else {
            $data['errors'] = 0;
            $data['messenger'] = 'Success';
            $data['LastID'] = $Comic_ID;
        }
        dd($data);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['list-comic'])){
        if($_GET['curPage']<=0){
            $page = 0;
        }else{
            $page = $_GET['curPage']-1;
        }
        $sortBy = ' ASC ';
        $orderBy = ' ComicName ';
        if(isset($_GET['order-by'])){
            $orderBy = $_GET['order-by'];
        }
        if(isset($_GET['sort-by'])){
            $sortBy = $_GET['sort-by'];
        }
        if($_GET['list-comic']==$keyadmin){
            $public = '';
        }else if($_GET['list-comic']==$keyuser){
            $public = 'IsPublic = 1 AND';
        }
        if(isset($_GET['tag'])){
            $tag = $_GET['tag'];
        }else {
            $tag = '';
        }
        $perPage = $_GET['per-page'];
        $search = $_GET['search'];

        $total = count(selectAll('[Comic]',[],"WHERE ".$public." [Tags] LIKE '%$tag%' AND [AnotherName] LIKE '%$search%'"));
        $check = ceil($total/$perPage);
        if($page >= $check&&$check>0){
            $page = $check-1;
        }

        $comics = selectAll('[Comic]',[],"WHERE ".$public." [Tags] LIKE '%$tag%' AND [AnotherName] LIKE '%$search%' ORDER BY $orderBy $sortBy OFFSET  $perPage*$page ROWS FETCH NEXT $perPage ROWS ONLY");
        $_SESSION['message'] = 'Get list comic';
        $_SESSION['type'] = 'success';
        $NumOfComic = custom('SELECT COUNT(*)  AS NumOfComic FROM [Comic]; ');
        $data['comics']=$comics;
        $data['NumOfComic']=$NumOfComic[0]['NumOfComic'];
        $data['NumOfPage'] = ceil($check);
        $data['Page'] = $page;
        
        dd($data);
        exit();
        
    }
    
    if(isset($_GET['list-rank'])){
        $rank =$_GET['list-rank'];
        $comic = custom("SELECT TOP (10) * FROM [Comic] Where IsPublic = 1 Order by $rank DESC");
        if(!$comic){
            $data['errors'] = 1;
            $data['messenger'] = 'Not found';
        }
        else {
            $data['errors'] = 0;
            $data['messenger'] = 'Success';
            $data['Comic'] = $comic;
        }
        dd($data);
        exit();
    }

    if(isset($_GET['get-comic'])){
        $getcomic['ComicID'] = $_GET['comic-id'];
        $tags = custom("SELECT * FROM [Tags] Order by TagsName ASC");
        
        if($_GET['get-comic']==$keyuser){
            $getcomic['IsPublic'] = 1;

        }
        $comic = selectOne('[Comic]',$getcomic);
        if(!$comic){
            $data['errors'] = 1;
            $data['messenger'] = 'Not found';
        }
        else {
            $data['errors'] = 0;
            $data['messenger'] = 'Success';
            $data['Comic'] = $comic;
            $data['Tags'] = $tags;
        }
        dd($data);
        exit();
    }
    if(isset($_GET['list-tags'])){
        $tags = custom("SELECT * FROM [Tags] Order by TagsName ASC");
        $data['Tags'] = $tags;
        dd($data);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['delete-id'])){
        $id['ComicID'] = $sent_vars['delete-id'];
        unset($sent_vars['delete-id']);
        delete('[Comic]',$id);
        delete('[Chapter]',$id);
        delete('[Chapter_Page]',$id);
        delete('[Follow_Comic]',$id);
        delete('[Like_Comic]',$id);
        $_SESSION['message'] = 'Delete comic';
        $_SESSION['type'] = 'success';
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['setpublic'])){
        $comic = selectOne('[Comic]',['ComicID'=>$sent_vars['setpublic']]);
        $comic['IsPublic'] = !$comic['IsPublic'];
        $where=['ComicID'=>$comic['ComicID']];
        unset($comic['ComicID']);        
        update('[Comic]',$where,$comic);
        $_SESSION['message'] = 'Update ';
        $_SESSION['type'] = 'success';
        exit();
    }
    if(isset($sent_vars['update-comic'])){
        $sent_vars['IsPublic'] = ($sent_vars['IsPublic'] == 'true')? 1:0; 
        $where=['ComicID'=>$sent_vars['update-comic']];
        unset($sent_vars['update-comic']);
        update('[Comic]',$where,$sent_vars);
        exit();
    }
    if(isset($sent_vars['up-view'])){
        $comicid = $sent_vars['ComicID'];
        custom("UPDATE Comic SET ViewNo += 1 WHERE ComicID = $comicid");
    }
}

