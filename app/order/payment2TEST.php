<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" type="text/css" href="/app/style.css">
    <style>
        a{
            font-size: 50px;
            color: darkgreen;
        }

        fieldset{
            position: absolute;
            width: 80%;
            left: 5%;
        }

        .payment-main{
            position: relative;
            height: 100%;
            width: 100%;
            background: url("/resources/paymentBack.jpg");
            font-size: 150%;
            font-family: "Droid Sans Mono", "Cambria Math";
            font-weight: bolder;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: flex-start;
        }


        .form_payment{
            font-size: 30px;
            font-family: "Arial Rounded MT Bold";
            font-weight: bolder;
            padding-bottom: 10px;
            padding-left: 30px;
            position: absolute;
            left: 1%;
            top: 30%;
            background-color: rgba(50,50,50, 0.3);
            color: black;
            width: 30%;
            height: 50%;
        }

        table {
            left: 40%;
            text-align: left;
            position: absolute;
            top: 28%;
            border-collapse: collapse;
            background-color: #f6f6f6;
        }/* Spacing */
        td, th {
            border: 1px solid #999;
            padding: 4%;
        }
        th {
            background: #5149c2;
            color: white;
            border-radius: 0;
            position: sticky;
            top: 0;
            padding: 1%;
        }

        tfoot > tr  {
            background: black;
            color: white;
        }
        tbody > tr:hover {
            background-color: #ffc107;
        }

        #checkboxes {
            position: relative;
            padding: 15px 30px;
            max-width: 80%;
            width: 100%;
            border: 3px solid black;
            border-radius: 5px;
            background: rgb(43,94,143);
            background: linear-gradient(90deg, rgba(43,94,143,1) 0%, rgba(75,75,156,0.8407738095238095) 27%, rgba(64,96,171,1) 59%, rgba(24,168,223,1) 82%, rgba(0,212,255,1) 100%);
        }

        #sam, #dostavka {
            display: inline-flex;
            position: relative;
            width: 70px;
            height: 30px;
            border-radius: 30px;
            cursor: pointer;
            top: -10px;
        }


        .myCheckbox1 .checkbox {
            cursor: pointer;
        }

        #dostavka, #sam{
            margin-left: 20px;
        }

        input[type=text] {
            width: 80%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            background-color: rgba(252, 252, 252, 0.7);
            border: 2px solid #010044;
            border-radius: 4px;
            font-family: monospace;
            text-decoration: cornflowerblue;
            font-size: 25px;
            font-weight: bolder;
            font-style: oblique;
        }

        .button-menu{
            display: flex;
            align-items:center;
            margin-right: 10px;
            margin-left: 10px;
        }

        .add-delete{
            display: flex;
            font-size: 30px;
            margin-left: -25px;
            margin-right: 15px;
        }

        #dostavka:checked .distance{
            display: block;
        }

        #distance{
            display: none;
        }

    </style>
</head>
<body>
<p id="demo"></p>
<?php
require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
use App\Connect;
use App\order\greenTea;
use App\order\lastTea;
use App\order\limonTea;
use App\order\redTea;
session_start();
if(empty($_SESSION['login'])){
    echo " 
            <h2>Небходимо войти для оформления заказа</h2>
            <div><a href='/app/account/login.php'>Войти</a></div>
        ";
}else{
$stmt = Connect::getLink()->prepare("SELECT `amount` FROM users WHERE id_Customer = ?");
$stmt->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$amount = $stmt->fetch()[0];

$stmt = Connect::getLink()->prepare('SELECT `product`, `count`, `cart_id` FROM cart_items WHERE id_Customer = ?');
$stmt->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$builder = "";
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$file = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp?date_req=".date("d/m/Y"));
$xml = $file->xpath("//Valute[@ID='R01235']");
$dollarsToRoubles = str_replace(',', '.', strval($xml[0]->Value));
$dollarsToRoubles = (floatval(number_format(floatval($dollarsToRoubles), 2))); // получим курс доллара

$sum = 0;
foreach($data as $row){
    $t = null;
    if($row["product"] == "green") {
        $t = new greenTea($row['count']);
    } elseif ($row["product"] == "red"){
        $t = new redTea($row['count']);
    } elseif ($row["product"] == "limon"){
        $t = new limonTea($row['count']);
    } elseif ($row["product"] == "last"){
        $t = new lastTea($row['count']);
    } else{
        continue;
    }

    $b = $t->getSum();
    $sum += $b * $dollarsToRoubles;
}

$dataForJs = array("$amount", "$sum");
?>
<div class="payment-main">
    <form method="post" class="form_payment">
        <label for="adress">Адресс <input type="text" name="adress"></label><br>
        <fieldset>
            <legend>Выберите способ оплаты</legend>
            <div id="checkboxes">
                <ul>
                    <li>
                        <h2>Самовывоз</h2>
                        <label><img src="/resources/sam.png" width="15%" /><input type="checkbox" id="sam"/></label>
                    </li>
                    <li>
                        <h2>Доставка</h2>
                        <label><img src="/resources/dostavka.png"  width="15%" /><input type="checkbox" id="dostavka"/></label>
                        <label for="distance">Расстояние от МКАД<input id="distance" type="text" name="distance"></label>
                    </li>
                </ul>
            </div>
            <input style="background-color: black; font-size: 2vw; color: #5149c2" type="submit" name="Подтвердить" value="Отправить" onclick='confirm(<?=json_encode($dataForJs);?>)'>
            <br>
        </fieldset>
    </form>
    <div class="welcome-to-order">
        <h2>Уважаемый <?php echo "{$_SESSION['login']}," .  " на Вашем счету:  $amount рублей"?></h2>
    </div>
    <div style="
            background-color: rgb(73,19,73);
            color: white;
            position: absolute;
            top: 1%;
            right: 2%;
            width: 10%;
            display: inline-flex;
            ">
        <img src="../../resources/dollar_icon.png">
        <h1 style="position: absolute; left: 35%; top: 10%;"><?=$dollarsToRoubles;?></h1>
    </div>

    <table border=1 width=800 cellpadding=30>
        <caption>Заказ</caption>
        <tr>
            <th>Имя</th><th>Количество</th><th>Сумма</th>
        </tr>
        <div class="popup">
            <form id="my-form" method="post" class="button-menu" action="#">
                <?php
                $sum = 0;
                $it = 0;
                foreach($data as $row){
                    $t = null;
                    if($row["product"] == "green") {
                        $t = new greenTea($row['count']);
                    } elseif ($row["product"] == "red"){
                        $t = new redTea($row['count']);
                    } elseif ($row["product"] == "limon"){
                        $t = new limonTea($row['count']);
                    } elseif ($row["product"] == "last"){
                        $t = new lastTea($row['count']);
                    } else{
                        continue;
                    }

                    $a = $t->getName();
                    $b = $t->getSum();
                    $id = $row["cart_id"];
                    $c = intval($b * $dollarsToRoubles);
                    $sum += $b;
                    echo <<<END
                        <tr>
                            <div class="into-head"><td><p>$a</p></td><td>
                            <div class="add-delete">
                                                    
                            <button type="submit" id="$it" value="add {$row["cart_id"]} {$row["product"]} {$row["count"]} $it" onclick='ins(this)'>                                                  
                                                                        
                            <span class="button-libel">Добавить 
                            </span>
                            </button>                                        
                            <p id="p_$it" value='{$row[`count`]}' style="margin-top: 5px">{$row['count']}</p>
                    END;
                    if((int)$row["count"] > 0){
                        echo "<button id='$it' type='submit' value='delete {$row["cart_id"]} {$row["product"]} {$row["count"]} $it' onclick='ins(this)'>";
                    }else{
                        echo "<button id='$it' type='submit' value='delete {$row["cart_id"]} {$row["product"]} {$row["count"]} $it' disabled onclick='ins(this)'>";
                    }
                    echo <<<END
                            </span>
                            <span class="button-libel">Убавить 
                            </span>                         
                            </button>                                                                                                      
                            </div>
                            </td><td></p>$b доллара, $c рублей<p></td></div>
                        </tr>
                    END;
                    $it++;
                }
                echo "</form></div>";
                $c = intval($sum * $dollarsToRoubles);
                echo "<div class='into-head'><tr><td colspan='2'>Сумма</td><td>$sum долларов, $c рублей</td></tr></div>";
                ?>
    </table>
</html>
</div>
<?php
}
?>
<script src=https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js></script>
<script>
    const checkboxSam = document.getElementById('sam');
    const checkboxDostavka = document.getElementById('dostavka');
    const mainCheckboxWindow = document.getElementById("checkboxes");
    const MKADdistance = document.getElementById("distance");

    // в расстояние от мкада запретить буквы
    MKADdistance.addEventListener("keyup", function(){
        this.value = this.value.replace(/[^\d]/g, "");
    });

    checkboxSam.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
            checkboxDostavka.checked = false;
            MKADdistance.style.display = "none";
            checkboxSam.disabled = true;
            checkboxDostavka.disabled = false;
        }
    })

    checkboxDostavka.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
            checkboxSam.checked = false;
            checkboxSam.disabled = false;

            checkboxDostavka.disabled = true;

            MKADdistance.style.display = "inline-flex";
        } else {

        }
    })

    function ins(t){
        var newString = t.value.split(" ");
        if (newString[0] == 'add'){
            newString[3] = String(Number(newString[3]) + 50);
        } else if((newString[0] == 'delete')){
            newString[3] = String(Number(newString[3]) - 50);
        }
        //dataes = JSON.stringify(newString);
        var data_to_send = newString;

        $.ajax({
            method: 'POST',
            url: 'fastChangeCountInBD.php',
            data: { 'data': data_to_send},
            success: function(msg){
                $('.answer').html(msg);
            }
        })
    }

    function confirm(data){
        var count = data[0];
        var sum = data[1];
        var distance = document.getElementById('distance').value;
        if (distance.length == 0) {
            distance = 0;
        }
        var sum1 = Number(sum) + (Number(distance) * 30);
        alert(sum1);
        if (count - sum < 0){
            alert("Недостаточно денег");
        }
    }
</script>
</body>