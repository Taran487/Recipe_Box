<?php
    session_start(); 

    $_SESSION = array();//unset all session variables 

    session_destroy();//it will destroy session file on server

    header("Location: login.php");//from here we redirect to our login page

    exit;
?>