<?php
use App\Connect;
ini_set('display_errors', 1);
ini_set('error_reporting', -1);
require "../Connect.php";
if(!empty($_POST)){
    $log = $_POST['loginforReg'];
    $pass = password_hash($_POST['passwordforReg'], PASSWORD_DEFAULT );
    $ava = "../../resources/person.png";
    $email = $_POST['emailforReg'];
    $ip = $_POST['ip'];
    $publicName = strtoupper($log);

    $sql = "SELECT * FROM `users` WHERE `login` = '$log'";
    $stmt = Connect::getInstance()->getLink()->prepare($sql);
    $stmt->execute();
    $checkLog = $stmt->fetch();

    $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
    $stmt = Connect::getInstance()->getLink()->prepare($sql);
    $stmt->execute();
    $checkMail = $stmt->fetch();

    if($checkLog){
        echo "<script>alert('Такой пользователь уже есть уже есть')</script>";
        header('Refresh: 1; URL=register.php');
    }else if($checkMail){
        echo "<script>alert('Почта уже занята')</script>";
        header('Refresh: 1; URL=register.php');
    }else{
        echo "<script>alert('Вы зарегистрированы. Теперь вы можете войти')</script>";
        $sql = "INSERT INTO users (login, password, avatar, email, publicName, ip, amount) 
    VALUES ('$log', '$pass', '$ava', '$email', '$publicName', '$ip', 10000)";
        $stmt = Connect::getInstance()->getLink()->prepare($sql);
        $stmt->execute();
        header('Refresh: 2; URL=../account/login.php');
    }
}