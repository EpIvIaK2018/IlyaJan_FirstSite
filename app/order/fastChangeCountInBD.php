<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/app/Connect.php";
ini_set('display_errors', 1);
ini_set('error_reporting', 1);

if (isset($_POST['data'])){
    $list =  $_POST['data'];
    $connect = App\Connect::getInstance();
    $id = $_POST['data'][1];
    $value = $_POST['data'][3];
    $stmt = $connect::getLink()->prepare('UPDATE `cart_items` SET `count` = :count WHERE `cart_id` = :id');
    $stmt->bindParam('count', $value, PDO::PARAM_INT);
    $stmt->bindParam('id', $id);
    $stmt->execute();
}
