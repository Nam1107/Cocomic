<?php
include('path.php');
session_start();
// $_SESSION['UserID'] = 'Nam';
unset($_SESSION['UserID']);
unset($_SESSION['Username']) ;
unset($_SESSION['Email']);
unset($_SESSION['IsAdministrator']);
unset($_SESSION['message']);
unset($_SESSION['type']);

session_destroy();

header('location: '. BASE_URL . '/sign-in.php');