<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/account/checkAuthorisation.php";
$log = "";
if(!empty($_SESSION['login'])){
    $log = $_SESSION['login'];
} else {
    $log = "Login";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" type="text/css" href="/app/style.css">
    <script>
        if (typeof localStorage === 'undefined') {
            alert("localStorage не работает!");
        }
    </script>

    <style>
        .global{
            background: url(../resources/Back.png);
            background-size: cover;
            z-index: 1;
            position: fixed;
            min-height: 100%;
            min-width: 100%;
            height: 100%;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="global">
   <?php //<img loading="lazy" width="100%" alt=" Helias Oils" src="/resources/Back.jpg" data-src="/resources/Back.png" data-sizes="auto" data-srcset="//cdn.shopify.com/s/files/1/2955/9332/files/Brand-Hero-4_1800x.jpg?v=1632406147">?>
    <div class="header">
        <div class="header1">
            <div class="header1into_left">
            <div style="width:100%; height:0.5vh; clear:both;"></div> <!-- выравниваем без обтекания -->
                <div id="line_blocLeft"><p href="lorem.html"><a href="<?php echo 'http://localhost:63342/IlyaJan/public/index.php'?>"><img src="/resources/fbIcon.png" alt="" width="16vw"></a></p></div>
                <div id="line_blocLeft"><p href="lorem.html"><a href="lorem.html"><img src="/resources/found.png" alt="" width="16vw"></a></p></div>
                <div id="line_blocLeft"><p href="lorem.html"><a href="lorem.html"><img src="/resources/mail.png" alt="" width="16vw"></a></p></div>
            </div>
            <div style="width:100%; height:1px; clear:both;"></div> <!-- выравниваем без обтекания -->
                <div class="header1into_right">
                    <div id="line_blocRight"><a href="/app/account/login.php"><?= $log ?></a></div>
                </div>
        </div>

    </div>
<?php
//&#709;
?>