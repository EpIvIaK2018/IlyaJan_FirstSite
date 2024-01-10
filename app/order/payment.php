<?php
require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
use App\Connect;
require "typeOfProducts/greenTea.php";
require "typeOfProducts/redTea.php";
require "typeOfProducts/limonTea.php";
require "typeOfProducts/lastTea.php";
session_start();
?>
<?php
if (!isset($_GET["step"])) {
    $_GET["step"] = 1;
    header("Location:payment.php?step=" . $_GET["step"]);
}

$currentPage = $_GET["step"];
$costOfDelivery = 0;

// Создание меню. Для HEREDOC переменная используется, чтоб вставить в HEREDOC
$createMenu = function(): string{
    $strBuild = "";
    $coord = array(8.5, 27, 45, 63, 81);
    $strBuild .= "            
        <rect x='{$coord[0]}vw' y='35%' height='2.5%' width='80%' fill='rgb(126, 130, 117)' />              
    ";
    for($i = 1; $i <= 5; $i++){
        $class = 'simpleCirle';
        if($i == $_GET['step']){
            $class = "currentCirle";
        }
        $strBuild .= "              
            <circle class='$class' r='1.2vw' cx='{$coord[$i-1]}vw' cy='36%'/>              
        ";
    }
    return "<svg width='100%' height='10vw'>" . $strBuild . "/<svg>";
};

function getJson_decode(): mixed
{
    if (isset($_POST['data'])) {
        $mainData = json_decode($_POST['data'], true);
    } else {
        echo "<script type='text/javascript'>";
        echo "document.write('<form method=\'post\' id=\'f\'>');";
        echo "document.write('<input type=\'hidden\' name=\'data\' value = \'' + js + '\'</p>');";
        echo "document.write('</form>');";
        echo "document.getElementById('f').submit()";
        echo "</script>";
    }
    return $mainData ?? null;
}

function getActuallyData(): array{
    $finalData = array();
    if(empty($_SESSION['user_id'])) {
        $data = getJson_decode();
        foreach ($data as $k=>$v){
            $finalData[] = array("product"=>$k, "count"=>$v);
        }
    }else{
        $stmt = Connect::getLink()->prepare('SELECT `product`, `count`, `cart_id` FROM cart_items WHERE id_Customer = ?');
        $stmt->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $row){
            $finalData[] = array("cart_id"=>$row['cart_id'], "product"=>$row['product'], "count"=>$row['count']);
        }
    }
    return $finalData;
}

function getRoubles(int $b): int{
    $file = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp?date_req=".date("d/m/Y"));
    $xml = $file->xpath("//Valute[@ID='R01235']");
    $dollarsToRoubles = str_replace(',', '.', strval($xml[0]->Value));
    $dollarsToRoubles = (floatval(number_format(floatval($dollarsToRoubles), 2))); // получим курс доллара;
    return $b * $dollarsToRoubles;
}

function createOrEditOfAddress($dataAddress = null, $edit = false): void{
?>
<div id="delivery">
    <form action="addAdress.php" method="post">
        <label for="name">Имя:</label>
        <input type="text" name="name" id="name"  <?php if ($edit) echo "value={$dataAddress[0]['name']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required/><br>
        <label for="lastname">Фамилия:</label>
        <input type="text" name="lastname" id="lastname" <?php if ($edit) echo "value={$dataAddress[0]['lastname']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required/><br>
        <label for="country">Страна:</label>
        <select type="text" name="country" id="country" <?php if ($edit) echo "value={$dataAddress[0]['country']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required>
            <option value="Armenia">Armenia</option>
            <option value="Azerbaijan">Azerbaijan</option>
            <option value="Belarus">Belarus</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="China">China</option>
            <option value="Estonia">Estonia</option>
            <option value="Finland">Finland</option>
            <option value="Georgia">Georgia</option>
            <option value="Kazakhstan">Kazakhstan</option>
            <option value="Korea">Korea</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Latvia">Latvia</option>
            <option value="Moldova">Moldova, Republic of</option>
            <option value="Poland">Poland</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russian Federation</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Ukraine">Ukraine</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Serbia">Serbia</option>
        </select>
        <br>
        <label for="city">Город:</label>
        <input type="text" name="city" id="city" <?php if ($edit) echo "value={$dataAddress[0]['city']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required/><br>

        <label for="street">Улица:</label>
        <input type="text" name="street" id="street" <?php if ($edit) echo "value={$dataAddress[0]['street']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required/><br>

        <label for="house">Дом и корпус:</label>
        <input type="text" name="house" id="house" <?php if ($edit) echo "value={$dataAddress[0]['house']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required/><br>

        <label for="apartment">Квартира:</label>
        <input type="text" name="apartment" id="apartment" <?php if ($edit) echo "value={$dataAddress[0]['apartment']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required/><br>

        <label for="entrance">Подъезд:</label>
        <input type="text" name="entrance" id="entrance" <?php if ($edit) echo "value={$dataAddress[0]['entrance']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required/><br>

        <label for="floor">Этаж:</label>
        <input type="text" name="floor" id="floor" <?php if ($edit) echo "value={$dataAddress[0]['floor']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required/><br>

        <label for="tel">Телефон:</label>
        <input type="text" name="tel" id="tel" <?php if ($edit) echo "value={$dataAddress[0]['tel']}"?> style="margin-bottom: 1vh; font-size: 2vh;" required/><br>
        <?php
        if($edit){
            echo '<input type="submit" value="Редактировать" style="background-color: green; height: 4vh">';
            echo "<input type='hidden' id='edit' name='edit' value='edit'>";
        }else{
            echo '<input type="submit" value="Сохранить" style="background-color: green; height: 4vh">';
        }
        echo<<<END
                </form>
            </div>
        END;
}
function showAdress($dataAddress): void{
    echo "<div class='wrapTableDiv'>";
    echo "<div id='address' style='border: 2px solid'><th</th>";
    echo "<p style='border-bottom: 1px solid black'>" . $dataAddress['name'] . "   " . $dataAddress['lastname'] . "</p>";
    echo "<p style='border-bottom: 1px solid black'>" . $dataAddress['city'] . ", ул. " . $dataAddress['street']
        . ", д." . $dataAddress['house'] . "</p>";
    echo "<p style='border-bottom: 1px solid black'>" . "кв." . $dataAddress['apartment'] . ", под. " . $dataAddress['entrance']
        . ", этаж " . $dataAddress['floor'] . "</p>";
    echo "<p style='border-bottom: 1px solid black'>" . "тел. " . $dataAddress['tel'] . "</p>";
}

function getSum(): int{
    $sum = 0;
    $mainData = getActuallyData();
    foreach($mainData as $k=>$row){
        if($row["product"] == "green") {
            $t = new greenTea($row['count']);
        } elseif ($row["product"] == "red"){
            $t = new redTea($row['count']);
        } elseif ($row["product"] == "limon"){
            $t = new limonTea($row['count']);
        } elseif ($row["product"] == "last"){
            $t = new lastTea($row['count']);
        }

        $a = $t->getName();
        $count = $t->getCount();
        if($count < 1) continue;
        $b = $t->getSum();
        //$id = $row["cart_id"];
        $c = getRoubles($b);
        $sum += $c;
    }
    return $sum;
}
function viewProducts(): void{
    $mainData = getActuallyData();
    if(count($mainData) > 0){
        echo<<<END
                    <div class="wrapTableDiv">
                    <table id="table">
                        <tr>
                             <th style="padding-left: 4vw">Товар</th><th>Количество</th><th style="padding-right: 10vw">Сумма</th>
                        </tr>                           
                        <form id="my-form" method="post" class="button-menu" action="#">
                END;
        $countOfProducts = 0;
        foreach($mainData as $k=>$row){
            if($row["product"] == "green") {
                $t = new greenTea($row['count']);
            } elseif ($row["product"] == "red"){
                $t = new redTea($row['count']);
            } elseif ($row["product"] == "limon"){
                $t = new limonTea($row['count']);
            } elseif ($row["product"] == "last"){
                $t = new lastTea($row['count']);
            }

            $a = $t->getName();
            $count = $t->getCount();
            if($count < 1) continue;
            $b = $t->getSum();
            //$id = $row["cart_id"];
            $c = getRoubles($b);
            $sum += $c;
            $countOfProducts++;
            echo<<<END
            <tr>                   
                <td style="padding-left: 3vw;"><div class="intoTD">$a</div></td>
                <td> 
                <div class="intoTD">    
        END;
            if(empty($_SESSION['user_id'])){
                echo "<button class='button' type='submit' id='val' value='add,{$row["product"]},{$row["count"]}' onclick='ins(this)'>";
            }else{
                echo "<button class='button' type='submit' id='val' value='add,{$row["cart_id"]},{$row["product"]},{$row["count"]}' onclick='ins(this, true)'>";
            }
            echo<<<END
            <img id="imgADD" src="/resources/addingPayment.png"> 
            </button>   
            <span id="value">$count&nbsp</span>
        END;
            if(empty($_SESSION['user_id'])){
                if((int)$row["count"] > 0){
                    echo "<button class='button' id='val' type='submit' value='delete,{$row["product"]},{$row["count"]}' onclick='ins(this)'>";
                }else{
                    echo "<button class='button' id='val' type='submit' value='delete,{$row["product"]},{$row["count"]}' disabled onclick='ins(this)'>";
                }
            }else{
                if((int)$row["count"] > 0){
                    echo "<button class='button' id='val' type='submit' value='delete,{$row["cart_id"]},{$row["product"]},{$row["count"]}' onclick='ins(this, true)'>";
                }else{
                    echo "<button class='button' id='val' type='submit' value='delete,{$row["cart_id"]},{$row["product"]},{$row["count"]}' disabled onclick='ins(this, true)'>";
                }
            }
            echo<<<END
                        <img id="imgADD" src="/resources/deletingPayment.png">                       
                    </button>  
                </div>  
                </td>
                
                <td style="padding-right: 8vw"><div class="intoTD">$c рублей</div></td>                                         
            </tr>              
        END;
        } // Конец цикла формирующего таблицу
        echo<<<END
                </form>                
                </table>                
            </div>
            <div class="summa">
                    <span style="position: relative; left: 15vw;">Общая сумма:</span> <span style="position: relative; left: 25vw;">$sum рублей</span>
            </div>
            <script>     
            function create(val) {          
                let screenHeight = window.innerHeight
                //px / viewport total height * 100
                const elements = document.querySelectorAll('td');
                len = elements.length;
                for (let i = 0; i < len; i++) {
                    elements[i].style.setProperty('height', String(85/Number(val)) + '%')                 
                    elements[i].style.position = 'relative';  
                }
                //Если один элемент - выравниваем в таблице высоту
                if (len == 3) {
                    ent = document.getElementsByClassName('intoTD')
                    for (let i = 0; i < ent.length; i++) {
                        ent[i].style.position = 'relative';
                        ent[i].style.top = '-2vh';
                    }
                } else {
                    ent = document.getElementsByClassName('intoTD')
                    for (let i = 0; i < ent.length; i++) {
                        ent[i].style.position = 'relative';                                                            
                        ent[i].style.top = '-1.5vh';
                    }
                }
                const THs = document.querySelectorAll('th');
                len = THs.length;
                for (let i = 0; i < len; i++) {
                    THs[i].style.setProperty('height', 15 + '%');
                    THs[i].style.maxHeight = '15%';
                    THs[i].style.textAlign = 'center';      
                    THs[i].style.position = 'relative';      
                    THs[i].style.paddingBottom = '2px';                                                        
                }                            
                let mainDiv = document.getElementById('payment-main');
                let tbl = document.getElementById('table');
                var ns = 'http://www.w3.org/2000/svg'
                var svg = document.createElementNS(ns, 'svg')
                svg.setAttributeNS(null, 'width', '100%')
                svg.setAttributeNS(null, 'height', '100%')          
                rectTop = document.createElementNS(ns, 'rect')
                rectTop.setAttributeNS(null, 'width', '80vw')
                rectTop.setAttributeNS(null, 'height', '0.5vh')
                rectTop.setAttributeNS(null, 'fill', 'black')
                rectTop.setAttributeNS(null, 'y', '2vh')
                rectTop.setAttributeNS(null, 'x', '10vw')
                svg.appendChild(rectTop)            
                let bootomVal = tbl.getBoundingClientRect();
                rectBottom = document.createElementNS(ns, 'rect')
                rectBottom.setAttributeNS(null, 'width', '80vw')
                rectBottom.setAttributeNS(null, 'height', '0.5vh')
                rectBottom.setAttributeNS(null, 'fill', 'black')
                rectBottom.setAttributeNS(null, 'y', (bootomVal.height / screenHeight * 100) - 6 + 'vh'); // Полезно. Из PX to VH
                rectBottom.setAttributeNS(null, 'x', '10vw')
                svg.appendChild(rectBottom)          
                rectLeft = document.createElementNS(ns, 'rect')
                rectLeft.setAttributeNS(null, 'width', '0.3vw')
                rectLeft.setAttributeNS(null, 'height', (bootomVal.height / screenHeight * 100 - 8) + 'vh');
                rectLeft.setAttributeNS(null, 'fill', 'yeloww')
                rectLeft.setAttributeNS(null, 'y', (rectTop.getBoundingClientRect().y / screenHeight * 100) + 2.2 + 'vh')
                rectLeft.setAttributeNS(null, 'x', '40vw')
                svg.appendChild(rectLeft)           
                rectRight = document.createElementNS(ns, 'rect')
                rectRight.setAttributeNS(null, 'width', '0.3vw')
                rectRight.setAttributeNS(null, 'height', (bootomVal.height / screenHeight * 100 - 8) + 'vh');
                rectRight.setAttributeNS(null, 'fill', 'black')
                rectRight.setAttributeNS(null, 'y', (rectTop.getBoundingClientRect().y / screenHeight * 100) + 2.2 + 'vh')
                rectRight.setAttributeNS(null, 'x', '66vw')
                svg.appendChild(rectRight)
                svg.style.zIndex = '5';
                svg.style.position = 'relative';
                mainDiv.appendChild(svg);
            }               
            //  С НИЖНЕЙ ЛИНИЕЙ СВГ ПОРАБОТАТЬ ЗАВТРА. КОГДА Ф5                                                                      
                create("$countOfProducts");                       
                window.addEventListener('resize', function(event) {
                    create("$countOfProducts");
                }, true); 
               </script>   
        END;
    }else{
        echo <<<END
                    <div id="table">
                        <p>Товаров нет в корзине. Вы можете вернуться <a href="/IlyaJan/public/index.php"><u>Назад</u></a> для заполнения</p>
                    </div>
                END;
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>Главная</title>
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

        #lastPage{
            position: absolute;
            height: 100%;
            width: 100%;
            background-size: cover;
            font-size: 100%;
            font-family: "Droid Sans Mono", "Cambria Math";
            font-weight: bolder;
            pointer-events: none;
        }

        #lastPageInfo{

            padding-left: 2vw;
            position: relative;
            left: 5vw;
            font-size: 4.5vh;
            top: 50vh;
            height: auto;
            width: 90vw;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            border-radius: 3%;
            border-spacing: 3px 3px;
            z-index: 0;
            border-collapse: separate; /**/
            float:none;
            pointer-events: auto;
        }

        #lastPageInfo>.temp :hover{
            background-color: #727a73;
            height: 200px;
            border: 2px solid black;
            cursor: pointer;
        }

        #table{
            padding-left: 2vw;
            position: relative;
            left: 5vw;
            font-size: 4.5vh;
            top: 40vh;
            min-height: 44vh;
            height: 44vh;
            width: 90vw;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            border-radius: 3%;
            border-spacing: 3px 3px;
            z-index: 0;
            border-collapse: separate; /**/
            float:none;
            pointer-events: auto;
        }

        #address{
            position: relative;
            left: 20vw;
            font-size: 4vh;
            top: 55vh;
            min-height: 40vh;
            height: 38vh;
            width: 58vw;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            border-radius: 3%;
            z-index: 0;
            border-collapse: collapse; /**/
            float:none;
            pointer-events: auto;
        }
        #address p{
            margin-bottom: 3vh;
            margin-top: 2vh;
            padding-left: 1vw;
        }

        .main-menu-payment{
            position: absolute; /*ТУТ изменили с абсолют*/
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            top: 15%;
            left: 5%;
            height: 14%;
            min-height: 14%;
            min-width: 90%;
            border-radius: 0% 5% 5% 5%;
            overflow: visible;
            display: block;
        }

        .button-menu{
            text-rendering: optimizeLegibility;
            font-family: "Droid Sans Mono", "Cambria Math";
            font-weight: bolder;
            border-spacing: 13px 3px;
            font-size: 5.5vh;
            margin: 0;
            padding: 0;
            z-index: 1;
            position: relative;
        }

        #order_step{
            position: relative;
            top: 1%;
            left: -1%;
            height: 100%;
            width: 100%;
            font-size: 3vw;
            display: inline-block;
        }

        .step_done{
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            line-height: 7vh;
            font-family: "Roboto', sans-serif;
            color: #111111;
            list-style: none;
            box-sizing: border-box;
            width: 20%;
            height: 20%;
            float: left;
            text-align: center;
        }


        .currentCirle{
            fill: rgb(101, 136, 234);*/
        }
        .simpleCirle{
            fill: black;
        }

        #dalee{
            position: absolute;
            top: 76%;
            right: 0.5%;
            width: 15vw;
            height: 24vh;
            line-height: 25vh;
            border-radius: 50%;
            font-size: 4vw;
            color: black;
            text-align: center;
            background: rgba(101, 136, 234, 1);
            z-index: 125;
            pointer-events: auto;
        }

        #nazad{
            position: absolute;
            top: 76%;
            left: 0.5%;
            width: 15vw;
            height: 24vh;
            line-height: 25vh;
            border-radius: 50%;
            font-size: 4vw;
            color: black;
            text-align: center;
            background: rgb(173, 158, 158);
            z-index: 115;
            pointer-events: auto;
        }

        tr{
            float:none;
            text-align: center;
            position: relative;
            width: 75vw;
            height: 5vh;
            border = none;
        }

        td:first-child {
            text-align: center;
        }

        th{
            position: relative;
            text-align: center;
            width: 35%;
            height: 12%;
            border: none;
        }

        #down_main{
            background-color: #240bff;
            z-index: 5;
        }

        .button{
            position: relative;
            pointer-events: auto;
            border-radius: 50%;
            border: 0px solid black;
            background-color: rgba(0,0,0,0);
        }

        #imgADD{
            position: relative;
            width: 3vw;
            bottom: -1vh;
        }

        #log-reg-entery{
            position: relative;
            font-size: 4.5vh;
            font-weight: bolder;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            border-radius: 15%;
            height: 40%;
            top: 25%;
            left: 32%;
            width: 40%;
            pointer-events: none;
        }

        .preference{
            margin-top: 3vh;
            text-align: left;
            vertical-align: middle;
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

        #submitStep2{
            pointer-events: auto;
            width: 79%;
            height: 6vh;
            background-color: white;
            font-size: large;
            margin-top: 3vh;
            margin-left: 1vw;
        }

        #submitStep2:hover{
            background-color: #dddddd;
        }

        .summa{
            padding-left: 2vw;
            position: absolute;
            left: 5vw;
            font-size: 6vh;
            vertical-align: bottom;
            font-family: "Calibri";
            top: 84vh;
            height: 8vh;
            width: 90vw;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            border-radius: 2px 12vh / 8vh;
            z-index: 0;
            display: block;
        }

        #delivery{
            position: absolute;
            top: 48%;
            left: 25%;
            padding-left: 2vw;
            font-size: 2.5vh;
            height: auto;
            width: 40%;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            pointer-events: auto;
        }

        #checkWay{
            position: absolute;
            top: 40%;
            left: 25%;
            padding-left: 2vw;
            font-size: 2.5vh;
            height: auto;
            width: 40%;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            pointer-events: auto;
        }

        .distance{
            border: 1px solid black;
            background-color: #727a73;
            display: none;
            font-size: 3vh;
            position: relative;
            bottom: 4%;
        }
        .distance input[type="number"]{
            font-weight: bolder;
            font-family: "Britannic Bold";
            font-size: larger;
        }
    </style>
    <script src=https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js></script>
    <script>
        var js;
        var obj = {}
        for (var i = 0; i < localStorage.length; i++) {
            var key = localStorage.key(i);
            obj[key] = localStorage.getItem(key);
        }
        js = JSON.stringify(obj);
    </script>
</head>
<body>
    <div id="payment-main">
        <?php if($currentPage != 2){
            echo<<<END
                <div class="main-menu-payment">
                    <ul id="order_step">
                    <li class="step_done">Корзина</li>
                    <li class="step_done">Вход</li>
                    <li class="step_done">Адрес</li>
                    <li class="step_done">Доставка</li>
                    <li class="step_done">Оплата</li>
                    {$createMenu()}
                    </ul>             
            END;
        }?>
    </div>
        <?php
        /**
         * @return mixed
         */
        /*
         * Вывод кнопки Вперёд
         * */
        if($currentPage < 5){
            if($currentPage == 1 && !empty($_SESSION['user_id'])){
                echo "<a id='dalee' href='payment.php?step=" . ($currentPage + 2) . " '>Далее</a>";
            }
            elseif($currentPage == 3){
                echo "<a id='dalee' style='display:none' href='payment.php?step=" . ($currentPage + 1) . " '>Далее</a>";
            }else{
                echo "<a id='dalee' href='payment.php?step=" . ($currentPage + 1) . " '>Далее</a>";
            }
        }
        /*
         * Вывод кнопки Назад
         * */
        if($currentPage == 3 && !empty($_SESSION['user_id'])){
            echo "<a id='nazad' href='payment.php?step=" . ($currentPage - 2) . " '>Назад</a>";
        }else{
            if($currentPage > 1){
                echo "<a id='nazad' href='payment.php?step=" . ($currentPage - 1) . " '>Назад</a>";
            }
        }
        // Первый раздел страницы
        /**
         * @param $dataAddress
         * @return void
         */
        if($currentPage == 1){
            if(!empty($_SESSION['user_id'])){  //Не забыть тут поменять
                $stmt = Connect::getLink()->prepare("SELECT `amount` FROM users WHERE id_Customer = ?");
                $stmt->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt->execute();
                $amount = $stmt->fetch()[0];
            }
            echo PHP_EOL;
            $sum = 0;
            /////////////////////////////////////////////////////
            /// Создаем таблицу если есть данные
            viewProducts();



        }elseif ($currentPage == 2){
            if(isset($_SESSION['user_id'])){
                header("Location:payment.php?step=" . 3);
            }else{
                echo<<<END
                <div id="log-reg-entery">
                    <form action="../account/login.php" method="post" style="display: grid; padding-bottom: 5vh;">
                    <div class="preference">
                      <label for="log">Логин:<input style="pointer-events:auto;font-size: 3vh;width: 23.1vw; border-bottom: 2px solid black;" type="text" name="log" id="log" required/></label>                        
                    </div>
                    <div class="preference">
                      <label for="log2">Пароль:<input style="pointer-events:auto;font-size:3vh;width: 21.8vw; border-bottom: 2px solid black;" type="text" name="password" id="password" required/></label>                        
                    </div>  
                    <input type="hidden" id="fromPayment" name="fromPayment" value="fromPayment">                      
                    <input id="submitStep2" type="submit" value="Отправить" />    
                    </form>                  
                    <a style="pointer-events: auto; padding-left: 1vw;" href="../account/register.php">Зарегистрироваться</a>        
                </div>
            END;
            }
            if(!empty($_POST)){

            }else{
                echo<<<END
                    <style>
                        #dalee: 'display: none';
                    </style>
                END;
            }
        }elseif ($currentPage == 3){
            $stmt = Connect::getLink()->prepare("SELECT * FROM address WHERE id_address = {$_SESSION['user_id']}");
            $stmt->execute();
            $dataAddress = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!isset($_SESSION['user_id'])){
                header("Location:payment.php?step=" . 2);
            }
            echo<<<CHECK
                <script>localStorage.setItem('page', '3')</script>
                <div id="checkWay">
                    <form>
                        <label>
                            <input type="checkbox" id="samo">
                            Самовывоз
                        </label>
                       <label>
                            <input type="checkbox" id="dost">
                            Доставка
                       </label>
                    </form>
                </div>
            CHECK;

            if(isset($_GET['edit'])){
                echo "<h1>РЕДАКТИРОВАТЬ</h1>";
                createOrEditOfAddress($dataAddress, true);
            }else {
                if (count($dataAddress) === 0) {
                    createOrEditOfAddress();
                    echo "<script>let isAddress = false</script>";
                } else if (!$_GET['edit'] && count($dataAddress[0]) > 0) {
                    echo "<script>let isAddress = true</script>";
                    showAdress($dataAddress[0]);
                    echo "<form method='get' action='payment.php'>
                    <input type='hidden' name='step' value='3'>
                    <input type='hidden' name='edit' value='true'>
                    <input style='pointer-events: auto; width: 15vw; height: 6vh' type='submit' value='Редактировать'>
                    </form>";
                    echo "</div></div>";
                }
            }
        }elseif ($currentPage == 4){
            echo "<script>localStorage.setItem('page', '4')</script>";
            if(isset($_GET['delivery'])){
                $stmt = Connect::getLink()->prepare("SELECT * FROM address WHERE id_address = {$_SESSION['user_id']}");
                $stmt->execute();
                $dataAddress = $stmt->fetchAll(PDO::FETCH_ASSOC);
                showAdress($dataAddress[0]);
                if($dataAddress[0]['city'] === 'Москва'){
                    echo "<label>
                            <input type='checkbox' id='saMKAD'>
                            За пределами МКАД
                     </label>";
                    echo "<div class='distance'><label>Расстояние (км)<input required id='km'; style='height: 3vh; display: inline-grid' type='number' max='20'></label></div>";
                    echo "</div></div>";
                }else{
                    echo "<p>В Ваш город доступна доставка транспортной компанией</p>";
                }
            }else if(isset($_GET['sam'])){
                echo "<div id='delivery'>";
                echo "<p>Вы можете забрать товар ";
                echo date('d.m.y', mktime(0, 0, 0, date("m")  , date("d") + 3 , date("Y")));
                echo "</p><p>По адрессу: г. Москва, метро Таганская. Грабли</p></div>";
            }
        }elseif($currentPage == 5){
            $finalSum = 0;
            $finalSum += getSum();
            var_export($finalSum);
            if($finalSum < 1){
                echo<<<FINAL
                    <div id="lastPage">
                        <div id="lastPageInfo">
                            Корзина пуста, для оформления заказа Вы будете перенаправлены
                        </div>
                    </div>   
                    <script>
                        setTimeout(() => window.location.href='payment.php?step=1', 1500);
                    </script>
                FINAL;
            }else{
                if(isset($_GET['delivery'])){
                    $sumOfDelivery = 800;
                    if(isset($_GET['distance'])){
                        $sumOfDelivery += ($_GET['distance'] * 30);
                    }
                    $finalSum += $sumOfDelivery;
                }
                echo<<<FINAL
                <form id="mailajob" method="post" action="finalPayment.php">
                    <input type="hidden" name="data" value="$finalSum">
                </form>         
                <div id="lastPage">
                    <div id="lastPageInfo">
                        Общая стоимость вашего заказа:  $finalSum ₽
                        Нажмите на кнопку для завершения заказа.
                    <p class='temp' style="font-family: 'Roboto', sans-serif;
                    box-sizing: border-box;
                    font-size: 2vh;                                
                    display: flex;
                    background: #423C67;
                    border-radius: 5px;
                    width: 30%;
                    height: 8.5vh;
                    ">                       
                    <a onclick="document.getElementById('mailajob').submit();" id="getAmount" title="Оплата" rel="nofollow" style="position: relative; height: 100%; width: 100%; color: #dddddd">
                    <img src="/resources/pamentIcon.jpg" alt="Оплата наличными" style="position: relative; top: 3vh; float: left; height: auto">
                    <br>Оплатить наличными при получении<br style="clear: both;">
                    </a>                                         
                    </p>
                    </div>
                </div>                
            FINAL;
            }
        }
        // ДОДЕЛАТЬ ЕСТЬ ЛИ КРУЖОК ЕСТЬ ЛИ ТОВАР КОГДА ЗАРЕГАНЫ. И ЕСЛИ ЗАРЕГАНЫ И НЕТ ТОВАРА СДЕЛАТЬ ПУСТУЮ ТАБЛИЧКУ
        //-----------------------------------------------------------
    ?>
    </div>
    <script src=https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js></script>
    <script>
        page = localStorage.getItem('page');
        if(page == '3'){
            const checkboxSam =  document.querySelector('#samo');
            const checkboxDost = document.querySelector('#dost');
            checkboxSam.addEventListener('change', () => {
                if (checkboxSam.checked){
                    document.getElementById('dalee').style.display = 'inline';
                    document.getElementById('dalee').href= 'payment.php?step=4&sam=1';
                    checkboxDost.setAttribute('disabled', 'true');
                }else{
                    document.getElementById('dalee').style.display = 'none';
                    document.getElementById('dalee').href= 'payment.php?step=4';
                    checkboxDost.removeAttribute("disabled");
                    checkboxDost.setAttribute("editable", 'true');
                }
            });

            checkboxDost.addEventListener('change', () => {
                if (checkboxDost.checked) {
                    if(isAddress === true) {
                        document.getElementById('dalee').style.display = 'inline';
                    }
                    document.getElementById('dalee').href= 'payment.php?step=4&delivery=1';
                    checkboxSam.setAttribute('disabled', 'true');
                    document.getElementById('delivery').style.display = 'inline';
                }else{
                    document.getElementById('dalee').style.display = 'none';
                    document.getElementById('dalee').href= 'payment.php?step=4';
                    checkboxSam.removeAttribute('disabled');
                    document.getElementById('delivery').style.display = 'none';
                }
            });
        }else if(page == '4'){
            const saMKAD = document.querySelector('#saMKAD');
            const counter = document.getElementById('km');
            saMKAD.addEventListener('change', () => {
                if(saMKAD.checked){
                    document.querySelector('.distance').style.display = 'inline-grid';
                }else{
                    document.querySelector('.distance').style.display = 'none';
                }
            });

            counter.addEventListener('input', () => {
                if(Number(counter.value) < 21){
                    document.getElementById('dalee').href= 'payment.php?step=5&delivery=1&distance=' + counter.value;
                }else {
                    alert("Не более 20 км");
                    document.getElementById('dalee').href = "payment.php?step=5";
                    counter.value = 0;
                }
            });
        }else if(page == '5'){

        }
        document.getElementById('val').addEventListener('click', function (e) {//e - событие элемента по умолчанию
            e.preventDefault();
        });

        function ins(arr, isFromBD = false){
            let temp;
            const newString = arr.value.split(",");
            var str = "";
            for (i = 0; i < newString.length; i++){
                str += (newString[i] + " ");
            }
            if (newString[0] == 'add'){
                if(!isFromBD){
                    temp = localStorage.getItem(newString[1]);
                    localStorage[newString[1]] = String(Number(temp) + 50);
                    document.getElementById('value').innerText = String(Number(temp) + 50);
                }else{
                    newString[3] = String(Number(newString[3]) + 50);
                }
            } else if(newString[0] == 'delete'){
                if(!isFromBD){
                    temp = localStorage.getItem(newString[1]);
                    if(temp > 0){
                        localStorage[newString[1]] = String(Number(temp) - 50);
                    }
                    document.getElementById('value').innerText = String(Number(temp) - 50);
                }else{
                    newString[3] = String(Number(newString[3]) - 50);
                }
            }
            if(isFromBD){
                const data = newString;
                $.ajax({
                    method: 'POST',
                    url: 'fastChangeCountInBD.php',
                    data: { 'data': data},
                    success: function(msg){
                        $('.answer').html(msg);
                    }
                })
            }
        }
    </script>
</body>
