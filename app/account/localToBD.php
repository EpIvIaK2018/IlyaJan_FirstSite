<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/app/Connect.php";
ini_set('display_errors', 1);
ini_set('error_reporting', 1);
/*
 * Если у нас во временной корзине был товар, а затем ав авторизовались, то данный код
 * перенесёт из времянки товары в корзину пользователя
 * */
if (!empty($_POST['data']) && empty($_SESSION['user_id'])) {
    file_put_contents('log2.txt', print_r($_SESSION, true) . PHP_EOL);
}else{
    file_put_contents('log2.txt', print_r($_SESSION, true) . PHP_EOL);
    $current = $_POST['data'];
    $userId = $_SESSION['user_id'];
    $connect = App\Connect::getInstance();
    $list =  json_decode($current, true);

    foreach ($list as $key => $val){
        $stmt = $connect::getLink()->prepare("SELECT * FROM cart_items WHERE (`id_Customer` = :id_Customer AND `product` = :product)");
        $stmt->bindValue(':id_Customer', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':product', $key);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if($result){
            foreach ($result as $currentValue) {
                if($currentValue[3] > 0) {
                    $newVal = $currentValue[3] + $val;
                }else {
                    $newVal = $val;
                }
                    //Проверить. Косяки при добавлении. Странные цифры
                    $stmt = $connect::getLink()->prepare("UPDATE cart_items SET count = '$newVal' WHERE (`id_Customer` = :id_Customer AND `product` = :product)");
                    $stmt->bindValue(':id_Customer', $userId, PDO::PARAM_INT);
                    $stmt->bindValue(':product', $key);
                    $stmt->execute();
                }
        }else{
            file_put_contents('log2.txt', print_r($_SESSION, true) . PHP_EOL);
            $stmt = $connect::getLink()->prepare("INSERT INTO cart_items (id_Customer, product, count) VALUES ('$userId', '$key', '$val')");
            $stmt->execute();
        }
    }
}
