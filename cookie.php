<?php 
session_start();
// $test['time'] = time();
// $test['cookie'] = $_COOKIE;
// $test['session'] = $_SESSION;
echo json_encode($_SESSION);
?>