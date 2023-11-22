<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/app/Connect.php";
ini_set('display_errors', 1);
ini_set('error_reporting', 1);

if (empty($_POST['data'])) {

} else {
    $current = $_POST['data'];
    $userId = $_SESSION['user_id'];
    $connect = App\Connect::getInstance();
    $list =  json_decode($current, true);

    file_put_contents(__DIR__ . '/log.txt', print_r($list, true) . " то, что пришло из JS." . PHP_EOL, FILE_APPEND);
    // Он проходится не по всем 4 сортам. А лишь по тому (тем) сорту(ам) который в localStorage фигурирует
    foreach ($list as $key => $val){
        $stmt = $connect::getLink()->prepare("SELECT * FROM cart_items WHERE (`id_Customer` = :id_Customer AND `product` = :product)");
        $stmt->bindValue(':id_Customer', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':product', $key);
        $stmt->execute();
        $result = $stmt->fetchAll();
        file_put_contents(__DIR__ . '/log.txt', print_r($result, true) . PHP_EOL, FILE_APPEND);
        if($result){
            foreach ($result as $currentValue) {
                if($currentValue[3] > 0){
                    //Ясно Из-за того что я не count извлекаю, а ВСЁ
                    $newVal = $currentValue[3] + $val;
                    //Проверить. Косяки при добавлении. Странные цифры
                    $stmt = $connect::getLink()->prepare("UPDATE cart_items SET count = '$newVal' WHERE (`id_Customer` = :id_Customer AND `product` = :product)");
                    $stmt->bindValue(':id_Customer', $userId, PDO::PARAM_INT);
                    $stmt->bindValue(':product', $key);
                    $stmt->execute();
                }
            }
        }else{
            file_put_contents(__DIR__ . '/log.txt', print_r(array($userId, $key, $val), true) . PHP_EOL, FILE_APPEND);
            $stmt = $connect::getLink()->prepare("INSERT INTO cart_items (id_Customer, product, count) VALUES ('$userId', '$key', '$val')");
            $stmt->execute();
        }
    }
}
