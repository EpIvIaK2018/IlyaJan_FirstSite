<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/account/checkAuthorisation.php";
require $_SERVER['DOCUMENT_ROOT'] . "/app/Connect.php";

if(!empty($_SESSION['user_id'])){
    echo "Вы уже вошли" . PHP_EOL;
    echo "<p><a href='/app/account/logout.php'>Выйти</a></p>";
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
                    localStorage.clear();            
                })
                </script>";
                if(isset($_POST['fromPayment'])){
                    header('Refresh: 1; URL=/app/order/payment.php?step=3');
                }else{
                    header('Refresh: 1; URL=/public/index.php');
                }
            }else{
                echo "<script>alert('неверный пароль')</script>";
                header('Refresh: 0.1; URL=/app/account/login.php');
            }
        } else {
            echo "<h1>" . 'Пользователь с такими данными не зарегистрирован' . "</h1>";
            header('Refresh: 0.1; URL=/app/account/login.php');
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
            #payment-main{
                position: absolute;
                height: 100%;
                width: 100%;
                background: url("/resources/paymentBack.jpg") no-repeat center center fixed;
                background-size: cover;
                font-size: 100%;
                font-family: "Droid Sans Mono", "Cambria Math";
                font-weight: bolder;
                pointer-events: none;
            }

            #address p{
                margin-bottom: 3vh;
                margin-top: 2vh;
                padding-left: 1vw;
            }

            #log-reg-entery{
                position: relative;
                font-size: 4.5vh;
                font-weight: bolder;
                background-color: rgba(207, 213, 190, 1);
                box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
                border-radius: 10%;
                height: 35vh;
                top: 25%;
                left: 32%;
                width: 40%;
                pointer-events: none;
            }

            .preference{
                margin-top: 3vh;
                text-align: left;
                vertical-align: center;
                display:flex;
                min-width: 50%;
                padding-left: 1vw;
            }

            label{
                display: contents;
            }

            a:hover{
                background-color: #EDEDED;
            }

            #password{
                position: relative;
                top: -12%;
                text-align: center;
            }
            #log{
                position: relative;
                top: -12%;
                text-align: center;
            }

            #submitStep{
                background: transparent;
                pointer-events: auto;
                text-rendering: optimizeLegibility;
                font-family: "Droid Sans Mono", "Cambria Math";
                font-size: 4.5vh;
                font-weight: bolder;
                padding: 0;
                color: black;
                text-decoration: none;
                pointer-events: auto;
                margin-bottom: 3vh;

            }

            #submitStep:hover{
                background-color: #ecebeb;
                cursor: pointer;
            }

            #submitStep:active{
                background-color: #111111;
                color: #dddddd;
                cursor: pointer;
            }

        </style>
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="payment-main">
            <div id="log-reg-entery">
                <form action="../account/login.php" method="post" style="display: grid; padding-bottom: 5vh;" id="form-1">
                    <div class="preference">
                        <label for="log">Логин:<input style="pointer-events:auto; margin-left: 8.2vw; max-width: 18vw; width: 18vw; font-size: 3vh;width: 23.1vw; border: 0; border-bottom: 2px solid black; background: transparent" type="text" name="log" id="log" required/></label>
                    </div>
                    <div class="preference">
                        <label for="log2">Пароль:<input style="pointer-events:auto; margin-left: 7vw; font-size:3vh; max-width: 18vw; width: 18vw; border: 0; display:flow-root; border-bottom: 2px solid black; background: transparent" type="text" name="password" id="password" required/></label>
                    </div>
                    <?php
                    if(isset($_GET['fromPayment'])){
                        echo "<input type='hidden' id='fromPayment' name='fromPayment' value='fromPayment'>";
                    }
                    ?>
                    <div class="preference">
                        <a onclick="document.getElementById('form-1').submit()" id="submitStep">Отправить</a>
                    </div>
                    <a style="pointer-events: auto; padding-left: 1vw;" id="submitStep" href="../account/register.php">Зарегистрироваться</a>
                </form>
            </div>
        </div>
        <script>
            document.getElementById('submitStep2').addEventListener('click', () => {
                if(Number(document.getElementById('log').value.length) < 1 && Number(document.getElementById('password').value.length) < 1){
                    alert(document.getElementById('password').value.length);
                }else{
                    document.getElementById('form-1').submit()
                }
            })
        </script>
    </body>
    </html>
<?php
}
    ?>



