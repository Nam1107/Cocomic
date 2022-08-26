<?php
session_start();
include ("../path.php") ;
include(ROOT_PATH . "/database/db.php");

$errors = array();
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
$datetime->setTimezone($timezone);
$time = $datetime->format('Y-m-d H:i:s');


$table = '[Chapter]';
$chapters = [];
$comic1 = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['comic-id'])){
        $orderBy = 'ASC';
        if(isset($_GET['order-by'])){
            $orderBy = $_GET['order-by'];
        }
        $chapters = selectAll('Chapter',['ComicID'=>$_GET['comic-id']]," order by ChapterNum $orderBy");
        dd($chapters);
        exit();
    }
    if(isset($_GET['last-chapter'])){
        $chapters = selectAll('Chapter',['ComicID'=>$_GET['last-chapter']]," order by ChapterNum DESC");
        if($chapters==null){
            $data['ChapterNum']=0;
        }else{
            $data['ChapterNum']=$chapters[0]['ChapterNum'];
        }
        dd($data);
        exit();
    }
    if(isset($_GET['chapter-id'])){
        $chapter = selectOne('[Chapter]',['ChapterID'=>$_GET['chapter-id']]);
        $comic = selectOne('[Comic]',['ComicID'=>$chapter['ComicID']]);
        $data['Comic']=$comic;
        $data['Chapter']=$chapter;
        dd($data);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    dd($_POST);
    if(isset($_POST['add-chapter'])){
        $_POST['ComicID'] = $_POST['add-chapter'];
        $_POST['CreateDate'] = $time;
        $_POST['LastUpdate'] = $time;
        $pages = $_POST['obj'];
        unset($_POST['add-chapter'],$_POST['obj']);
        $chapter_id = create('[Chapter]',$_POST);
        update('[Comic]',['ComicID'=>$_POST['ComicID']],['LastUpdate'=>$time]);
        foreach($pages as $key => $page ):
            $page['ChapterID'] = $chapter_id;
            $page['ComicID'] = $_POST['ComicID'];
            create('Chapter_Page',$page);
        endforeach;
        $_SESSION['message'] = 'Chapter created successfully';
        $_SESSION['type'] = 'success';
        exit();
    }
    
}


if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['update-chapter'])){
        $chapter = ['ChapterID'=>$sent_vars['update-chapter']];
        $comic = ['ComicID'=>$sent_vars['ComicID']];
        unset($sent_vars['update-chapter']);
        $sent_vars['LastUpdate'] = $time;
        $date['LastUpdate'] = $time;
        update('[Chapter]',$chapter,$sent_vars);
        update('[Comic]',$comic,$date);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['delete-id'])){
        $id['ChapterID'] = $sent_vars['delete-id'];
        delete('[Chapter]',$id);
        delete('[Chapter_Page]',$id);
        $_SESSION['message'] = 'Delete chapter';
        $_SESSION['type'] = 'success';
        
        exit();
    }
}