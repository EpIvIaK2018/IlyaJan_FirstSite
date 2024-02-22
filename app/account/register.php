<?php
function getIp(): string{
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
$url = $_SERVER['DOCUMENT_ROOT'] . "../public/index.php";
$ip  = getIp();
if(!empty($_SESSION['login'])){
    echo header("Location:". $url);
    echo "<h1>" . 'Вы уже зарегистрированы, Вы будете перенаправлены' . "</h1>";
}
echo <<<REG
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <link rel="stylesheet" type="text/css" href="/app/style.css">
        <style>
            body{
                background-color: #1c1a1a;
                background-size: auto;   
            }
            .global{             
                background: url(/resources/back2.png) no-repeat;
                z-index: 1;
                position: fixed;
                min-height: 100%;
                min-width: 100%;
                height: 100%;
                width: 100%;
            }
            
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
                height: 55vh;
                top: 25%;
                left: 26%;
                width: 50vw;
                pointer-events: none;
            }

            .preference{
                margin-top: 3vh;
                text-align: left;
                vertical-align: center;
                display:flex;
                width: 80%;
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
        <script>
            function validate(){
                let patternMail = /^[a-zA-Z0-9_.-]+@([a-z0-9-]+\.)+[a-z]{2,6}$/i;                                            
                let patternPass = /(?=.*[0-9])(?=.*[a-z])[0-9a-zA-Z!@#$%^&*]{6,}/g
                let login = document.forms['validForm']['loginforReg'].value;
                let email = document.forms["validForm"]["emailforReg"].value;
                let pass = document.forms["validForm"]["passwordforReg"].value;
                let passRepeat = document.forms["validForm"]["passwordSubmit"].value;
                if(!patternMail.test(email)){
                    alert("Некорректный Email");
                    return false;
                }
                if(pass.length < 6){
                    alert("Пароль не менее 6 символов");
                    return false;
                }
                if(!patternPass.test(pass)){
                    alert("Пароль с использованием цифр и латиницы");
                    return false;
                }
                if(pass != passRepeat){
                    alert("Неверное подтверждение пароля");
                    return false;
                }
                if(login.length < 3){
                    alert("Слишком короткий логин, не менее 3 символов")
                    return false;
                }
                return true;
            }
        </script>    
    </head>
    <body>   
    <div id="payment-main">
    <div id="log-reg-entery">
        <h1 style="color: black; margin-bottom: 2vh; margin-left: 2vw">Create Account</h1>
        <form method="post" action="confirm.php" name="validForm" class='formWithValidation' onsubmit="return validate()">
            <div class="preference">
                <label for="log">Логин:<input style="pointer-events:auto; margin-left: 8.2vw; max-width: 18vw; width: 18vw; font-size: 3vh; border: 0; border-bottom: 2px solid black; background: transparent" type="text" name="loginforReg" required/></label>              
            </div>
            <div class="preference">
                <label for="log">Пароль:<input style="pointer-events:auto; margin-left: 7.5vw; max-width: 18.2vw; width: 19vw; font-size: 3vh; border: 0; border-bottom: 2px solid black; background: transparent" type="password" name="passwordforReg" id="pass" required/></label>          
            </div>
    
            <div class="preference">
                <label for="log">Подтверждение пароля:<input style="pointer-events:auto; margin-left: 2vw; width: 26vw; font-size: 2vh; border: 0; border-bottom: 2px solid black; background: transparent" type="password" name="passwordSubmit" id="passRepeat" required/></label>
            </div>    
            <div class="preference">
                <label for="log">Почта:<input style="pointer-events:auto; margin-left: 8.2vw; max-width: 18vw; width: 18vw; font-size: 3vh;width: 23.1vw; border: 0; border-bottom: 2px solid black; background: transparent" type="text" name="emailforReg" id="email" required/></label>
            </div>
            <input type="hidden" name="ip" value={$ip}>
            <input type="submit" name="ok" class="validateBtn">
        </form>
    </div>
    </div>
    </body>
    </html>
REG;
?>
<?=require_once  $_SERVER['DOCUMENT_ROOT'] . '/public/footer.html';?>
