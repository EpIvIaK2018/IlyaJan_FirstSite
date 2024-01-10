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
    <script>
        if (typeof localStorage === 'undefined') {
            alert("localStorage не работает!");
        }
    </script>
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
    <script>
        let temp = '<?php echo $log?>';
        var el = document.getElementById('line_blocRight');
        if(temp.length > 15){
            el.style.fontSize = '1.35vw';
            el.style.top = '25%';
            el.style.color = 'red';
            el.style.fontWeight = 'bolder';
        }else if(temp.length > 10){
            el.style.fontSize = '1.55vw';
            el.style.fontWeight = 'bolder';
            el.style.top = '28%';
            el.style.color = 'green';
        }else{
            el.style.fontSize = '1.8vw';
            el.style.color = 'blue';
            el.style.top = '20%';
        }
    </script>
<?php
//&#709;
?>