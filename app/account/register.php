<?php
ini_set('display_errors', 1);
ini_set('error_reporting', -1);
require $_SERVER['DOCUMENT_ROOT'] . '/public/header.php';
$url = $_SERVER['DOCUMENT_ROOT'] . "../public/index.php";
if(!empty($_SESSION['login'])){
    echo header("Location:". $url);
    echo "<h1>" . 'Вы уже зарегистрированы, Вы будете перенаправлены' . "</h1>";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script>
        function validate() {
            var pattern = /^[a-zA-Z0-9_.-]+@([a-z0-9-]+\.)+[a-z]{2,6}$/i;
            var email = document.forms["validForm"]["emailforReg"].value;
            var pass = document.forms["validForm"]["passwordforReg"].value;
            var passRepeat = document.forms["validForm"]["passwordSubmit"].value;
            if(!pattern.test(email)){
                alert("Некорректный Email");
                return false;
            }
            if(pass != passRepeat) {
                alert("Неверное подтверждение пароля");
                return false;
            }
            return true;
        }
    </script>
    <style>
        .formWithValidation{
            position: relative;;
            display: inline-grid;
            text-align: left;
            color: white;
            width: 80%;
            left: 0%;
            font-size: 18pt;
            height: auto;
        }

        input[type=text]{
            font-size: 18pt;
        }
    </style>
</head>
<body>
<div class="reg">
    <h1 style="color: cornflowerblue; margin-bottom: 3vh">Create Account</h1>
    <form method="post" action="confirm.php" name="validForm" class='formWithValidation' onsubmit="return validate()">
        <div>
            <label style="font-family: Calibri; font-size: 25px">Логин:<input type="text" name="loginforReg" required></label>
        </div>
        <div>
            <label style="font-family: Calibri; font-size: 25px">Пароль:<input type="text" name="passwordforReg" id="pass" required></label>
        </div>

        <div>
            <label style="font-family: Calibri; font-size: 25px">Подтвердите пароль:<input type="text" name="passwordSubmit" id="passRepeat" required></label>
        </div>

        <div>
            <label style="font-family: Calibri; font-size: 25px">Почта:<input type="text" name="emailforReg" id="email" required></label>
        </div>
        <input type="hidden" name="ip" value=<?php echo get_ip() ?>>
        <input type="submit" name="ok" class="validateBtn" style="background: #897322;
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
</div>
</body>
</html>

<?php
function get_ip(): string
{
    $value = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $value = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $value = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $value = $_SERVER['REMOTE_ADDR'];
    }

    return $value;
}
?>

<?=require_once  $_SERVER['DOCUMENT_ROOT'] . '/public/footer.html';?>
