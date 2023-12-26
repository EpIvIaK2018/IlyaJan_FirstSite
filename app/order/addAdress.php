<?php
use App\Connect;
ini_set('display_errors', 1);
ini_set('error_reporting', -1);
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/app/Connect.php";
$temp = array();
$temp[] = $_POST;
$id = $_SESSION['user_id'];
$name = $_POST['name'];
$lastName = $_POST['lastname'];
$country = $_POST['country'];
$city = $_POST['city'];
$street = $_POST['street'];
$house = $_POST['house'];
$apartment = $_POST['apartment'];
$entrance = $_POST['entrance'];
$floor = $_POST['floor'];
$tel = $_POST['tel'];

var_dump($_POST['edit']);
if(isset($_POST['edit'])){
    $query = "UPDATE address SET `id_address` = ?, `name` = ?, `lastname` = ?, `country` = ?, `city` = ?, `street` = ?, `house` = ?, `apartment` = ?, `entrance` = ?, `floor` = ?, `tel` = ? WHERE `id_address` = {$id}";
} else{
    $query = "INSERT INTO address (`id_address`, `name`, `lastname`, `country`, `city`, `street`, `house`, `apartment`, `entrance`, `floor`, `tel`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
}
try{
    $connect = App\Connect::getInstance();
    $stmt = $connect::getLink()->prepare($query);
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->bindValue(2, $name);
    $stmt->bindValue(3, $lastName);
    $stmt->bindValue(4, $country);
    $stmt->bindValue(5, $city);
    $stmt->bindValue(6, $street);
    $stmt->bindValue(7, $house);
    $stmt->bindValue(8, $apartment, PDO::PARAM_INT);
    $stmt->bindValue(9, $entrance, PDO::PARAM_INT);
    $stmt->bindValue(10, $floor, PDO::PARAM_INT);
    $stmt->bindValue(11, $tel);
    $stmt->execute();
}catch (PDOException $err){
    print "Error!: " . $err->getMessage() . "<br/>";
    die();
}

