<?php
include ("../path.php") ;
include(ROOT_PATH . "/database/db.php");

$errors = array();
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
$datetime->setTimezone($timezone);
$time = $datetime->format('Y-m-d H:i:s');

$table = '[Chapter_Page]';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['chapter-id'])){
        $orderBy = 'ASC';
        if(isset($_GET['order-by'])){
            $orderBy = $_GET['order-by'];
        }
        $chapter = selectOne('[Chapter]',['ChapterID'=>$_GET['chapter-id']]);
        if(empty($chapter)) {
            header('location: '. BASE_URL . '/client/comics/index.php' );
        }
        else {
            $pages = selectAll('[Chapter_Page]',['ChapterID'=>$_GET['chapter-id']]," order by Page $orderBy");
        }
        dd($pages);
        exit();
    }
    if(isset($_GET['page-id'])){
        $page = selectOne('[Chapter_Page]',['PageID'=>$_GET['page-id']]);
        dd($page);
        exit();
    }
    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add-page'])){
        $index = selectOne('[Chapter]',['ChapterID'=>$_POST['add-page']]);
        $_POST['CreateDate'] = $time;
        $_POST['LastUpdate'] = $time;
        $pages = $_POST['obj'];
        unset($_POST['add-page'],$_POST['obj']);
        foreach($pages as $key => $page ):
            $page['ChapterID'] = $index['ChapterID'];
            $page['ComicID'] = $index['ComicID'];
            create('Chapter_Page',$page);
        endforeach;
        LastUpdate($index['ChapterID'],$index['ComicID']);
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['update-page'])){
        dd($sent_vars);
        $date['LastUpdate'] = $time;
        $page['PageID'] = $sent_vars['update-page'];
        $index = selectOne('[Chapter_Page]',['PageID'=>$sent_vars['update-page']]);
        LastUpdate($index['ChapterID'],$index['ComicID']);
        unset($sent_vars['update-page']);
        dd($sent_vars);
        update('[Chapter_Page]',$page,$sent_vars);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"),$sent_vars);
    if(isset($sent_vars['delete-id'])){
        $index = selectOne('[Chapter_Page]',['PageID'=>$sent_vars['delete-id']]);
        LastUpdate($index['ChapterID'],$index['ComicID']);
        $page['PageID']=$sent_vars['delete-id'];
        unset($sent_vars['delete-id']);
        delete('[Chapter_Page]',$page);
        $pages = selectAll('[Chapter_Page]',['ChapterID'=>$sent_vars['ChapterID']],' order by Page ASC');
        exit();
    }
}



function LastUpdate($chapterID,$comicID){
    global $time;
    $chapter['ChapterID'] = $chapterID;
    $comic['ComicID'] = $comicID;
    $date['LastUpdate'] = $time;
    update('[Chapter]',$chapter,$date);
    update('[Comic]',$comic,$date);
}

?>