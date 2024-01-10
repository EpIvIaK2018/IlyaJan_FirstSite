<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/app/Connect.php";
if (isset($_POST['data'])){
    file_put_contents("SOS.txt", print_r($_POST['data'], true));
    $connect = App\Connect::getInstance();
    $id = $_POST['data'][1];
    $value = $_POST['data'][3];
    $stmt = $connect::getLink()->prepare('UPDATE `cart_items` SET `count` = :count WHERE `cart_id` = :id');
    $stmt->bindParam('count', $value, PDO::PARAM_INT);
    $stmt->bindParam('id', $id);
    $stmt->execute();
}
