<?php
use App\Connect;
require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
session_start();

function sendMail(string $mail, string $message): void{
    $message = wordwrap($message, 70, "\r\n");
    echo $mail;
    mail($mail, 'Заказ подтверждён', $message);
}

if(!empty($_SESSION['user_id'])){
    $stmt = Connect::getLink()->prepare("SELECT * FROM users WHERE id_Customer = {$_SESSION['user_id']}");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $amount = strval($data['amount']);
    $mail = $data['email'];
    $ost = $amount - strval($_POST['data']);
    $currentId = "00000" . Connect::getLink()->prepare("SELECT id FROM orders where customer_id = {$_SESSION['user_id']}")->execute();
    $currentId = intval($currentId) + 1;
    echo "Остаток средств после завершения покупки: " . $ost;

    date_default_timezone_set('Europe/Moscow');
    $currentTime = date('Y-m-d H:i:s');
    if($ost >= 0){
        try{
            $stmt = Connect::getLink()->prepare("SELECT `product`, `count` FROM cart_items WHERE id_Customer = {$_SESSION['user_id']}");
            $stmt->execute();
            $str= json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            if(!empty($str)){
                $stmt = Connect::getLink()->prepare("INSERT INTO orders (`customer_id`, `goods`, `data`, `numberOfOrder`, `statusOfOrder`)
                VALUES (?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt->bindValue(2, $str);
                $stmt->bindValue(3, $currentTime);
                $stmt->bindValue(4, $currentId);
                $stmt->bindValue(5, "Выполняется");
                $stmt->execute();
                Connect::getLink()->prepare("DELETE FROM cart_items WHERE id_Customer = {$_SESSION['user_id']}")->execute();
            }else{
                echo<<<END
                    <script>
                        alert("Ваша корзина пуста!");
                        setTimeout(() => window.location.href='payment.php?step=1', 1000);
                    </script>
                END;
            }
        }catch(PDOException $e){
            echo $sql . "<br>" . $e->getMessage();
        }

        $message = $data['publicName'] . " Заказ оформлен, доставка будет в течение 3 дней";
        sendMail($mail, $message);
    }
}
