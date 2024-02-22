<?php
use App\Connect;
require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
session_start();

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array();
}
$prodId = $_POST['id'] ?? 0;
if(!empty($prodId) && $prodId == strval(50) || empty($prodId == strval(100) || empty($prodId) == strval(150))){
    $_SESSION['variant'] = $_POST['variant'];
    $_SESSION['title'] = $_POST['title'];
}

$variant = $_POST['variant'];
$addValue = $_POST['id'];

if(!empty($_SESSION['user_id'])){
    $userId = $_SESSION['user_id'];
    $stmt = Connect::getLink()->prepare('SELECT `cart_id`, `count` FROM cart_items WHERE product = :variant AND id_Customer = :id_Cust');
    $stmt->execute(['variant' => $variant, 'id_Cust' => $userId]);
    $list = $stmt->fetch(PDO::FETCH_ASSOC);
    $oldVal = 0;
    if(isset($list['count'])){
        if(intval($list['count']) > 0) {
            $oldVal = intval($list['count']);
            $id     = intval($list['cart_id']);
            $finalValue = $oldVal + intval($addValue);
            $stmt = Connect::getLink()->prepare('UPDATE cart_items SET count = :count WHERE product = :variant AND cart_id = :id');
            $stmt->bindParam('count', $finalValue, PDO::PARAM_INT);
            $stmt->bindParam('variant', $variant);
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }else{
        $stmt = Connect::getLink()->prepare('INSERT INTO cart_items(id_Customer, product, count) VALUES (:id, :product, :count)');
        $finalValue = $oldVal + intval($addValue);
        $stmt->bindParam('id', $userId, PDO::PARAM_INT);
        $stmt->bindParam('product', $variant);
        $stmt->bindParam('count', $finalValue, PDO::PARAM_INT);
        $stmt->execute();
    }
    $response = array('status' => 'success');
    echo json_encode($response);
}
