<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/app/Connect.php";
use App\Connect;
if(isset($_POST['userId'])){
    $userId = intval($_POST['userId']);
    $query = "DELETE FROM cart_items WHERE id_Customer = ?";

    try{
        $connect = Connect::getInstance();
        $stmt = $connect::getLink()->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();
    }catch (PDOException $err){
        echo $err->getMessage();
    }
}
