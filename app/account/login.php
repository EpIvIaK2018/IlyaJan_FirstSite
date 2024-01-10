<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/account/checkAuthorisation.php";
require $_SERVER['DOCUMENT_ROOT'] . "/app/Connect.php";

if(!empty($_SESSION['user_id'])){
    echo "Вы уже вошли" . PHP_EOL;
    echo "<p><a href='/IlyaJan/app/account/logout.php'>Выйти</a></p>";
} else {
    if (!empty($_POST['log']) && !empty($_POST['password'])) {
        $checkingLog = $_POST['log'];
        $connect = App\Connect::getInstance();
        $checkingPass = password_hash($connect::getLink()->quote($_POST['password']), PASSWORD_DEFAULT);
        $stmt = $connect::getLink()->prepare('SELECT * FROM users WHERE login = ?');
        $stmt->bindParam(1, $checkingLog);
        $stmt->execute();
        $output = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($output) {
            $hashed_password = $output[0]["password"];
            // Проверяем, не нужно ли использовать более новый алгоритм
            // или другую алгоритмическую стоимость
            // Например, если вы поменяете опции хеширования
            if (password_needs_rehash($checkingPass, PASSWORD_DEFAULT)) {
                $newHash = password_hash($connect::getLink()->quote($_POST['password']), PASSWORD_DEFAULT);
                $stmt = $connect::getLink()->prepare('UPDATE `users` SET `password` = :password WHERE `login` = :username');
                $stmt->execute([
                    'login' => $_POST['log'],
                    'password' => $newHash,
                ]);
            }

            if (password_verify($_POST['password'], $hashed_password)) {
                $_SESSION['user_id'] = $output[0]['id_Customer'];
                $_SESSION['login'] = $output[0]['login'];
                $user = '';
                echo "<script src=https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js></script>
                <script>
                 const cartItems = ['green', 'red' , 'limon' , 'last'];
                 let builder = new Map();
                 for(i = 0; i < cartItems.length; i++){
                     if(localStorage.getItem(cartItems[i]) != null){                      
                        builder.set(cartItems[i],localStorage.getItem(cartItems[i]));                      
                     }
                 }                             
                 jsonString = JSON.stringify(Object.fromEntries(builder));                             
                 $.ajax({
                    method: 'POST',                  
                    url: 'localToBD.php',
                    data: { 'data': jsonString},                                          
                })
                .done(function(){
                    localStorage.clear(); //12 ноября. Убрал аргумент msg               
                })
                </script>";
                if(!empty($_POST['fromPayment'])){
                    header('Refresh: 1; URL=/IlyaJan/app/order/payment.php?step=3');
                }else{
                    header('Refresh: 1; URL=/IlyaJan/public/index.php');
                }
            }else{
                echo "<script>alert('неверный пароль')</script>";
                header('Refresh: 0.1; URL=/IlyaJan/app/account/login.php');
            }
        } else {
            echo "<h1>" . 'Пользователь с такими данными не зарегистрирован' . "</h1>";
            header('Refresh: 0.1; URL=/IlyaJan/app/account/login.php');
        }
        die;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="/app/style.css">
        <style>
            body{
                background-color: darkgrey;
            }

            .validateBtn{
                pointer-events: auto;
                width: 85.2%;
                height: 6vh;
                background-color: white;
                font-size: large;
                margin-top: 3vh;
                margin-left: 1vw;
            }
            .validateBtn:hover{
                background-color: white;
            }

            .submit{
                pointer-events: auto;
                width: 85.5%;
                height: 6vh;
                background-color: white;
                font-size: large;
                margin-top: 3vh;
                margin-left: 1vw;
                left: 0%;

            }

            #loginForm{
                position: relative;;
                display: inline-grid;
                text-align: left;
                color: white;
                width: 80%;
                left: 0%;
                font-size: 18pt;
                height: auto;
            }

            #entery{
                position: relative;
                width: 40vw;
                left: 30vw;
                top: 10vh;
                background-color: #727a73;
                text-align: center;
                height: auto;
                line-height: 3vh;
                padding-top: 3vh;
                padding-bottom: 5vh;
                font-size: xx-large;
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    </head>
    <body>
    <div id="entery">
        <h1>Login</h1>
        <form method="post" id="loginForm" action="http://localhost:63342/IlyaJan/app/account/login.php">
            <div style="margin-top: 3vh;
            text-align: left;
            vertical-align: middle;
            display:flex;
            min-width: 50%;
            padding-left: 1vw;">
                <label style="font-family: Calibri; font-size: 25px">Логин:<input style="pointer-events:auto;font-size: 3vh;width: 23.1vw; border-bottom: 2px solid black;" type="text" name="log" required></label>
            </div>
            <div style="margin-top: 3vh;
            text-align: left;
            vertical-align: middle;
            display:flex;
            min-width: 50%;
            padding-left: 1vw;">
                <label style="font-family: Calibri; font-size: 25px">Пароль:<input style="pointer-events:auto;font-size:3vh;width: 22.4vw; border-bottom: 2px solid black;" type="text" name="password" required></label>
            </div>
            <input type="submit" name="submit" class="validateBtn">
        </form>

        <form id="loginForm" action="http://localhost:63342/IlyaJan/app/account/register.php" method="POST">
            <input class="submit" type="submit" value="Зарегистрироваться"/>
        </form>
    </div>
    </body>
    </html>
<?php
}
    ?>



