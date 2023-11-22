<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

</body>
</html>

<?php
use App\Connect;
require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
//
require '../../public/header.php';

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array();
}
$prodId = $_GET['id'] ?? 0;
if(!empty($prodId) && $prodId == strval(50) || empty($prodId == strval(100) || empty($prodId) == strval(150))){
    $_SESSION['variant'] = $_GET['variant'];
    $_SESSION['title'] = $_GET['title'];
}

$temp = $_GET['variant'];
$count = $_GET['id'];
/** @var TYPE_NAME $_SESSION */

/*
 * $stmt = pdo()->prepare('UPDATE `users` SET `password` = :password WHERE `login` = :username');
                $stmt->execute([
                    'login' => $_POST['log'],
                    'password' => $newHash,
                ]);
 *
 * */

if(!empty($_SESSION['login'])){
    $var = $_SESSION['user_id'];
    $stmt = Connect::getLink()->prepare('SELECT * FROM cart_items WHERE product = :password and id_Customer = :id_Cust');
    $stmt->execute(['password' => $temp, 'id_Cust' => $var]);
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $oldVal = 0;
    if(isset($list[0])){
        if(intval($list[0]['count']) > 0) {
            $oldVal += intval($list[0]['count']);
            $stmt = Connect::getLink()->prepare('DELETE FROM cart_items WHERE product = :password');
            $stmt->execute(['password' => $temp,]);
        }
    }
    $stmt = Connect::getLink()->prepare('INSERT INTO cart_items(id_Customer, product, count) VALUES (:id, :product, :count)');
    $finalValue = $oldVal + $count;
    $stmt->bindParam('id', $var);
    $stmt->bindParam('product', $temp);
    $stmt->bindParam('count', $finalValue, PDO::PARAM_INT);
    $stmt->execute();

    // Важно таймер чтоб было время коду выше в локл всё сохранить
    header("Refresh: 1; URL=order.php?variant={$_SESSION['variant']}&title={$_SESSION['title']}");
}else{
    echo "<script>         
        if(localStorage.getItem('$temp')){               
            const data = Number(localStorage.getItem('$temp'));                                                                          
            newData = data + Number('$count');           
            localStorage.setItem('$temp', String(newData));                                                                          
        } else{          
            localStorage.setItem('$temp', '$count');          
        }
        
        /*
        var limit = 6 * 1000; // 1 минута
        var localStorageInitTime = localStorage.getItem('localStorageInitTime');
        localStorage.setItem('localStorageInitTime', +new Date());      
        if(+new Date() - localStorageInitTime > limit){                   
            localStorage.clear();
            localStorage.setItem('localStorageInitTime', +new Date());
        }     
         */
        </script>";

    // Важно таймер чтоб было время коду выше в локл всё сохранить
    header("Refresh: 1; URL=order.php");
}
?>

