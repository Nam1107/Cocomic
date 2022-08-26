<?php
$host = 'DESKTOP-RV5216I\SQLEXPRESS';
$userAccount = 'sa';
$passAccount = '123456';
$database = 'Webtoon';



try {  
    $conn = new PDO( "sqlsrv:server=$host;Database = $database", $userAccount, $passAccount);  
    // $conn = new PDO( "sqlsrv:server=117.103.207.22;Database = WebtoonDB", "tts2022", 'tTs20@@_v$c');  
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );   
 }  
 catch( PDOException $e ) {  
    die( "Error connecting to SQL Server" );   
 }  





 

 