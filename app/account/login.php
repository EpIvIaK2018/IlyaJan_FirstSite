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
                header('Refresh: 0; URL=/IlyaJan/public/index.php');
            }else{
                echo "<script>alert('неверный пароль')</script>";
                header('Refresh: 0.5; URL=/IlyaJan/app/account/login.php');
            }
        } else {
            echo "<h1>" . 'Пользователь с такими данными не зарегистрирован' . "</h1>";
            header('Refresh: 0.5; URL=/IlyaJan/app/account/login.php');
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
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    </head>
    <body>
    <div class="reg">
        <h1>Login</h1>
        <form action="http://localhost:63342/IlyaJan/app/account/login.php" method="post" id="loginform">
            <div>
                <label style="font-family: Calibri; font-size: 25px">Логин:<input type="text" name="log" required></label>
            </div>
            <div>
                <label style="font-family: Calibri; font-size: 25px">Пароль:<input type="text" name="password" required></label>
            </div>
            <input type="submit" name="submit" class="validateBtn" style="background: #897322;
        color: #ffffff;
        border: 1px solid #897322;
        padding: 0 20px;
        text-align: center;
        cursor: pointer;
        min-height: 44px;
        height: 40px;
        line-height: 1.2;
        vertical-align: top;
        font-family: Futura, sans-serif;
        font-weight: normal;
        font-style: normal;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 30px;

        display: -webkit-inline-flex;
        display: -ms-inline-flexbox;
        display: inline-flex;
        -webkit-align-items: center;
        -moz-align-items: center;
        -ms-align-items: center;
        align-items: center;
        -webkit-justify-content: center;
        -moz-justify-content: center;
        -ms-justify-content: center;
        justify-content: center;
        -ms-flex-pack: center;
        transition: all 0.2s linear;
        -webkit-appearance: none;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-smoothing: antialiased;
        border-radius: 100px;">
        </form>

        <form action="http://localhost:63342/IlyaJan/app/account/register.php" method="POST">
            <input type="submit" value="Зарегистрироваться" style="background: #897322;
        color: #ffffff;
        border: 1px solid #897322;
        padding: 0 20px;
        text-align: center;
        cursor: pointer;
        min-height: 44px;
        height: 40px;
        line-height: 1.2;
        vertical-align: top;
        font-family: Futura, sans-serif;
        font-weight: normal;
        font-style: normal;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 30px;

        display: -webkit-inline-flex;
        display: -ms-inline-flexbox;
        display: inline-flex;
        -webkit-align-items: center;
        -moz-align-items: center;
        -ms-align-items: center;
        align-items: center;
        -webkit-justify-content: center;
        -moz-justify-content: center;
        -ms-justify-content: center;
        justify-content: center;
        -ms-flex-pack: center;
        transition: all 0.2s linear;
        -webkit-appearance: none;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-smoothing: antialiased;
        border-radius: 100px;"/>
        </form>
    </div>
    </body>
    </html>
<?php
}
    ?>



