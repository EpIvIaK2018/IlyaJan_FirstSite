<?php
session_start();
if(!isset($_SESSION['oldTime'])){
    $_SESSION['oldTime'] = time();
} else{
    $_SESSION['newTime'] = time();
    if($_SESSION['newTime'] - $_SESSION['oldTime'] > 900){
        unset($_SESSION['oldTime']);
        $_SESSION = array();
        echo "<script>localStorage.clear()</script>";
        session_unset();
    }//else{
        //$_SESSION['oldTime'] = $_SESSION['newTime'];
    //}
}