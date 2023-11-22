<?php
namespace App;
require ("../../vendor/autoload.php");
ini_set('display_errors', 1);
ini_set('error_reporting', -1);
require '../../public/header.php';
$url = '';
if(isset($_GET['variant']) && isset($_GET['title'])){
    switch ($_GET['variant']){
        case 'green':
            $url = '/resources/orderGreen.jpeg';
            break;
        case 'red':
            $url = '/resources/orderRed.jpg';
            break;
        case 'limon':
            $url = '/resources/orange.jpg';
            break;
        case 'last':
            $url = '/resources/last.jpeg';
            break;
    }
} else{
    header("Location: ../../public/index.php");
}
if (isset($_COOKIE['value'])){
    /*
    $val = $_COOKIE['value'];
    productsSummary::addProduct(new greenTea($val));
    var_dump(productsSummary::getSumALl());
    unset($_COOKIE['value']);
    */
    //echo "<script>document.cookie = value=; path='', expires=-1;</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order</title>
    <style>
        .global{
            background: url(<?= $url?>);
            background-size: cover;
            z-index: 1;
        }
        <?php
            switch ($_GET['variant']){
                case 'green':
                    echo<<<END
                        .header1into_left{
                        background-color: rgba(255, 241, 240, 0.7);                    
                        box-shadow: 2px 2px 13px 15px rgba(255, 241, 240, 0.7);
                        }
                        .header1into_right{
                            background-color: rgba(255, 241, 240, 1);
                            box-shadow: 2px 2px 18px 22px rgba(255, 241, 240, 1);
                        }
                        .header2{
                            background-color: rgba(255, 241, 240, 1);
                            box-shadow: 2vb 1.3vb 2.5vb 5.5vb rgba(255, 241, 240, 1);
                        }
                        .subtitle{
                            display: inline-block;
                            position: fixed;
                            height: 6vh;
                            padding: 0 0 0 0;
                            margin: 0 0 0 0;
                            -webkit-font-smoothing: antialiased;
                            text-align: left;
                            color: black;
                            font-family: Futura, sans-serif;
                            width: 100%;
                            line-height: calc(4vh);
                            vertical-align: center;
                            background-color: rgba(255, 241, 240, 0.9);
                            box-shadow: 0vb 0vb 1.2vh 1.4vh rgba(255, 241, 240, 0.9);
                            bottom: 3vh;
                        }
                        .subtitle p{
                            position: relative;
                            height: auto;
                            left: 1vh;
                            top: -5vh;
                            font-size: 1.4vw;
                            letter-spacing: 0.3vw;
                            overflow: hidden;
                        }
                        .order{
                            background-color: rgba(115, 255, 2, 0.8);
                            box-shadow: 0vh 0vh 1.4vh 1.5vh rgba(115, 255, 2, 0.8);
                        }                       
                    END;
                    break;

                    case 'last':
                    echo<<<END
                        .header1into_left{
                        background-color: rgba(255, 241, 240, 0.7);
                        box-shadow: 2px 2px 13px 15px rgba(255, 241, 240, 0.7);
                        }
                        .header1into_right{
                            background-color: rgba(255, 241, 240, 1);
                            box-shadow: 2px 2px 18px 22px rgba(255, 241, 240, 1);
                        }
                        .header2{
                            background-color: rgba(254, 228, 205, 1);
                            box-shadow: 2vb 1.3vb 2.5vb 5.5vb rgba(254, 228, 205, 1);
                        }
                        .subtitle{
                            display: inline-block;
                            position: fixed;
                            height: 6vh;
                            padding: 0 0 0 0;
                            margin: 0 0 0 0;
                            -webkit-font-smoothing: antialiased;
                            text-align: left;
                            color: black;
                            font-family: Futura, sans-serif;
                            width: 100%;
                            line-height: calc(4vh);
                            vertical-align: center;
                            background-color: rgba(254, 228, 205, 0.9);
                            box-shadow: 0vb 0vb 1.2vh 1.4vh rgba(254, 228, 205, 0.9);
                            bottom: 3vh;
                        }
                        .subtitle p{
                            position: relative;
                            height: auto;
                            left: 1vh;
                            top: -5vh;
                            font-size: 1.4vw;
                            letter-spacing: 0.3vw;
                            overflow: hidden;
                        }
                        .order{
                            background-color: rgba(255, 241, 240, 0.8);
                            box-shadow: 0vh 0vh 1.4vh 1.5vh rgba(255, 241, 240, 0.8);
                        }                       
                    END;
                    break;

                    case 'limon':
                    echo<<<END
                        .header1into_left{
                        background-color: rgba(224, 194, 124, 0.7);                 
                        box-shadow: 2px 2px 13px 15px rgba(224, 194, 124, 0.7);
                        }
                        .header1into_right{
                            background-color: rgba(224, 194, 124, 1);
                            box-shadow: 2px 2px 18px 22px rgba(224, 194, 124, 1);
                        }
                        .header2{
                            background-color: rgba(224, 194, 124, 1);
                            box-shadow: 2vb 1.3vb 2.5vb 5.5vb rgba(224, 194, 124, 1);
                        }
                        .subtitle{
                            display: inline-block;
                            position: fixed;
                            height: 6vh;
                            padding: 0 0 0 0;
                            margin: 0 0 0 0;
                            -webkit-font-smoothing: antialiased;
                            text-align: left;
                            color: black;
                            font-family: Futura, sans-serif;
                            width: 100%;
                            line-height: calc(4vh);
                            vertical-align: center;
                            background-color: rgba(224, 200, 180, 0.9);
                            box-shadow: 0vb 0vb 1.2vh 1.4vh rgba(224, 200, 180, 0.9);
                            bottom: 3vh;
                        }
                        .subtitle p{
                            position: relative;
                            height: auto;
                            left: 1vh;
                            top: -5vh;
                            font-size: 1.4vw;
                            letter-spacing: 0.3vw;
                            overflow: hidden;
                        }
                        .order{
                            background-color: rgba(238, 225, 205, 0.8);
                            box-shadow: 0vh 0vh 1.4vh 1.5vh rgba(238, 225, 205, 0.8);
                        }                       
                    END;
                    break;

                    case 'red':
                    echo<<<END
                        .header1into_left{
                        background-color: rgba(255, 228, 205, 0.7);
                        box-shadow: 2px 2px 13px 15px rgba(255, 228, 205, 0.7);
                        }
                        .header1into_right{
                            background-color: rgba(255, 228, 205, 1);
                            box-shadow: 2px 2px 18px 22px rgba(255, 228, 205, 1);
                        }
                        .header2{
                            background-color: rgba(115, 255, 2, 1);
                            box-shadow: 2vb 1.3vb 2.5vb 5.5vb rgba(115, 255, 2, 1);
                        }
                        .subtitle{
                            display: inline-block;
                            position: fixed;
                            height: 6vh;
                            padding: 0 0 0 0;
                            margin: 0 0 0 0;
                            -webkit-font-smoothing: antialiased;
                            text-align: left;
                            color: black;
                            font-family: Futura, sans-serif;
                            width: 100%;
                            line-height: calc(4vh);
                            vertical-align: center;
                            background-color: rgba(115, 255, 2, 0.9);
                            box-shadow: 0vb 0vb 1.2vh 1.4vh rgba(115, 255, 2, 0.9);
                            bottom: 3vh;
                        }
                        .subtitle p{
                            position: relative;
                            height: auto;
                            left: 1vh;
                            top: -5vh;
                            font-size: 1.4vw;
                            letter-spacing: 0.3vw;
                            overflow: hidden;
                        }
                        .order{
                            background-color: rgba(255, 228, 205, 0.8);
                            box-shadow: 0vh 0vh 1.4vh 1.5vh rgba(255, 228, 205, 0.8);
                        }                       
                    END;
                    break;
            }
        ?>


    </style>
    <script>
    </script>
</head>
<body>
<div class="main">
    <div class="header2">
        <div class="frame">
            <h1><?=$_GET['title']?></h1>
        </div>
        <div class="pirista">
            <li><a href="google.com"><span class="textIntoHead">piristää</span></a></li>
            <li><a href="google.com"><span class="textIntoHead">eväste</span></a></li>
            <li><a href="google.com"><span class="textIntoHead">toinen</span></a></li>
        </div>


        <div style="position: absolute; right: 1vw; top: 2vh">
            <a href="payment.php">
                <img src="../../resources/symka.png" alt="HTML tutorial" style="width:3vw;height: 5vh;">
            </a>
            <?php
            if(isset($_SESSION['cart'])){
                if($_SESSION['cart'] > 0) echo "<div id='mark'></div>";
            }
            ?>
        </div>
    </div>
        <div class="order">
            <div><span>Pakkaus</span></div>
            <div><button><a href="http://localhost:63342/IlyaJan/app/order/toCart.php?id=50&variant=<?= $_GET['variant'] ?? ""?>&title=<?=$_GET['title'] ?? ""?>">50gr.</a></button></div>
            <div><button><a href="http://localhost:63342/IlyaJan/app/order/toCart.php?id=100&variant=<?= $_GET['variant'] ?? ""?>&title=<?=$_GET['title'] ?? ""?>">100gr.&nbsp;</a></button></div>
            <div><button><a href="http://localhost:63342/IlyaJan/app/order/toCart.php?id=150&variant=<?= $_GET['variant'] ?? ""?>&title=<?=$_GET['title'] ?? ""?>">150g.</a></button></div>
        </div>

        <div class="subtitle">
            <p>
                Ainutlaatuinen elävä orgaaninen tee, jolla on vahva käyminen aurinkoisten i säntien puhtaista<br />
                istutuksista. Uskomattoman maukasta ja terveellistä. Koottu ja valmistettu käsin tänä keväänä.
            </p>
        </div>
    <?php
    require_once '../../public/footer.html';
    ?>
</body>
</html>
