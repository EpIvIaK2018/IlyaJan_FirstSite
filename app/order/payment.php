<?php
require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
use App\Connect;
require "typeOfProducts/greenTea.php";
require "typeOfProducts/redTea.php";
require "typeOfProducts/limonTea.php";
require "typeOfProducts/lastTea.php";
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
session_start();
?>
<?php
if (!isset($_GET["step"])) {
    $_GET["step"] = 1;
    header("Location:payment.php?step=" . $_GET["step"]);
}
$currentPage = $_GET["step"];
$costOfDelivery = 0;

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
function getJson_decode()
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
/*
 * Getting a list of products. If authorized - from the database. If not, from local storage.
 */
function getActuallyData(): array{
    $stmt = Connect::getLink()->prepare('DELETE FROM cart_items WHERE count = 0');
    $stmt->execute();

    $finalData = array();
    if(empty($_SESSION['user_id'])) {
        $data = getJson_decode();
        foreach ($data as $k=>$v){
            if($k === 'green' || $k === 'red' || $k === 'limon' || $k === 'last'){
                $finalData[] = array("product"=>$k, "count"=>$v);
            }
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

function getRoubles(float $countOfRoubles): int{
    $file = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp?date_req=".date("d/m/Y"));
    $xml = $file->xpath("//Valute[@ID='R01235']");
    $dollarsToRoubles = str_replace(',', '.', strval($xml[0]->Value));
    $dollarsToRoubles = (intval(number_format(floatval($dollarsToRoubles), 2))); // получим курс доллара;
    return intval($countOfRoubles) * $dollarsToRoubles;
}

function createOrEditOfAddress($dataAddress = null, $edit = false): void{
?>
<div id="delivery">
    <form action="addAdress.php" method="post">
        <div class="field">
        <label for="name">Имя:</label>
        <input type="text" name="name" id="name"  <?php if ($edit) echo "value={$dataAddress[0]['name']}"?> style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required/><br>
        </div>
        <div class="field">
        <label for="lastname">Фамилия:</label>
        <input type="text" name="lastname" id="lastname" <?php if ($edit) echo "value={$dataAddress[0]['lastname']}"?> style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required/><br>
        </div>
        <div class="field">
        <label for="country">Страна:</label>
        <select type="text" name="country" id="country" style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required>
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
        </div>
        <br>
        <div class="field">
        <label for="city">Город:</label>
        <input type="text" name="city" id="city" <?php if ($edit) echo "value={$dataAddress[0]['city']}"?> style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required/><br>
        </div>
        <div class="field">
        <label for="street">Улица:</label>
        <input type="text" name="street" id="street" <?php if ($edit) echo "value={$dataAddress[0]['street']}"?> style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required/><br>
        </div>
        <div class="field">
        <label for="house">Дом и корпус:</label>
        <input type="text" name="house" id="house" <?php if ($edit) echo "value={$dataAddress[0]['house']}"?> style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required/><br>
        </div>
        <div class="field">
        <label for="apartment">Квартира:</label>
        <input type="text" name="apartment" id="apartment" <?php if ($edit) echo "value={$dataAddress[0]['apartment']}"?> style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required/><br>
        </div>
        <div class="field">
        <label for="entrance">Подъезд:</label>
        <input type="text" name="entrance" id="entrance" <?php if ($edit) echo "value={$dataAddress[0]['entrance']}"?> style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required/><br>
        </div>
        <div class="field">
        <label for="floor">Этаж:</label>
        <input type="text" name="floor" id="floor" <?php if ($edit) echo "value={$dataAddress[0]['floor']}"?> style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required/><br>
        </div>
        <div class="field">
        <label for="tel">Телефон:</label>
        <input type="text" name="tel" id="tel" <?php if ($edit) echo "value={$dataAddress[0]['tel']}"?> style="text-align: center; margin-bottom: 1vh; font-size: 2vh; background: transparent; border: 0; border-bottom: 1px black solid" required/><br>
        </div>
        <?php
        if($edit){
            echo '<input type="submit" value="Редактировать" style="background-color: #F8F8F8; height: 4vh">';
            echo "<input type='hidden' id='edit' name='edit' value='edit'>";
        }else{
            echo '<input type="submit" value="Сохранить" style="background-color: green; height: 4vh">';
        }
        echo<<<END
                </form>
            </div>
        END;
}
function showAddress($dataAddress): void{
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
    $sum = 0;
    $thereIs = false;
    foreach ($mainData as $row){
        if(intval($row['count']) > 0){
            $thereIs = true;
        }
    }
    if($thereIs){
        echo<<<END
            <div class="wrapTableDiv">
                <table id="table">
                    <tr>
                         <th style="padding-left: 4vw">Товар</th><th>Количество</th><th style="padding-right: 10vw">Сумма</th>                          
                    </tr> 
                                                                             
                    <form id="my-form" method="post" class="button-menu" action="#">
        END;
        $countOfProducts = 0;
        foreach($mainData as $row){
            if($row["product"] == "green") {
                $t = new greenTea($row['count']);
            } elseif ($row["product"] == "red"){
                $t = new redTea($row['count']);
            } elseif ($row["product"] == "limon"){
                $t = new limonTea($row['count']);
            } elseif ($row["product"] == "last"){
                $t = new lastTea($row['count']);
            }else{
                break;
            }

            $a = $t->getName();
            $count = $t->getCount();
            if($count < 1) continue;
            $b = $t->getSum();
            $currentSum = getRoubles($b);
            $sum += $currentSum;
            $countOfProducts++;
            $id = $row["product"] . $row["count"];
            echo<<<END
                <tr>                   
                    <td style="padding-left: 3vw;"><div class="intoTD">$a</div></td>
                    <td> 
                    <div class="intoTD">    
            END;
            if(empty($_SESSION['user_id'])){
                echo "<button class='button' type='submit' id='val' value='add,{$row["product"]},{$row["count"]}' onclick='ins(this, `$id`)'>";
            }else{
                echo "<button class='button' type='submit' id='val' value='add,{$row["cart_id"]},{$row["product"]},{$row["count"]}' onclick='ins(this, `$id`, true)'>";
            }
            echo<<<END
                <img id="imgADD" src="/resources/addingPayment.png"> 
                </button>   
                <span id="$id">$count&nbsp</span>
            END;
            if(empty($_SESSION['user_id'])){
                if((int)$row["count"] >= -1){
                    echo "<button class='button' id='val' type='submit' value='delete,{$row["product"]},{$row["count"]}' onclick='ins(this, `$id`)'>";
                }else{
                    echo "<button class='button' id='val' type='submit' value='delete,{$row["product"]},{$row["count"]}' disabled onclick='ins(this, `$id`)'>";
                }
            }else{
                if((int)$row["count"] >= -1){
                    echo "<button class='button' id='val' type='submit' value='delete,{$row["cart_id"]},{$row["product"]},{$row["count"]}' onclick='ins(this, `$id`, true)'>";
                }else{
                    echo "<button class='button' id='val' type='submit' value='delete,{$row["cart_id"]},{$row["product"]},{$row["count"]}' disabled onclick='ins(this, `$id`, true)'>";
                }
            }
        echo<<<END
                        <img id="imgADD" src="/resources/deletingPayment.png">                       
                    </button>  
                </div>  
                </td>
                
                <td style="padding-right: 8vw"><div class="intoTD">$currentSum рублей</div></td>                                         
            </tr>              
        END;
        } // Конец цикла формирующего таблицу
        $currentId = "";
        if(isset($_SESSION['user_id'])){
            $currentId = $_SESSION['user_id'];
        }
        echo<<<END
                </form>                
                </table>                
            </div>
            <div class="summa">
            <span style="position: relative; left: 8vw;">Общая сумма:</span> <span style="position: relative; left: 15vw;">$sum рублей</span>       
            <button style="position: relative; top: 30%; float: left; height: 30px; width: 330px; pointer-events: auto" type="submit" onclick="deleteAll(`{$currentId}`)">
            Удалить всё
            </button>
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
                <p>Товаров нет в корзине. Вы можете вернуться <a href="/public/index.php"><u>Назад</u></a> для заполнения</p>
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
            background-color: white;
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

        #checkWay{
            position: absolute;
            top: 40%;
            left: 30vw;
            padding-left: 2vw;
            font-size: 2.5vh;
            height: auto;
            width: 40vw;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            pointer-events: auto;
        }

        #patchToHome{
            left: 2vw;
            position: relative;
            top: 1%;
            text-align: center;
            float: left;
            display: grid;
            width: 4vw;
            z-index: 1;
        }
        #patchToHome > a > img{
            width: 3vw;
        }

        #patchToHome :hover{
            background-color: transparent;
        }
        #patchToHome > a > img:hover{
            border: 3px white solid;
            background: darkviolet;
        }

        #address{
            position: relative;
            left: 5vw;
            font-size: 4vh;
            top: 55vh;
            min-height: 40vh;
            height: 38vh;
            width: 90.5vw;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            border-radius: 3%;
            z-index: 0;
            border-collapse: collapse; /**/
            float:none;
            pointer-events: auto;
            text-align: center;
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
            font-family: "Roboto', sans-serif";
            color: #111111;
            list-style: none;
            box-sizing: border-box;
            width: 20%;
            height: 20%;
            float: left;
            text-align: center;
        }


        .currentCirle{
            fill: rgb(101, 136, 234);
        }
        .simpleCirle{
            fill: black;
        }

        #dalee{
            position: absolute;
            bottom: 1vh;
            right: 0.5%;
            width: 14vw;
            height: 14vw;
            border-radius: 50%;
            font-size: 4vw;
            color: black;
            text-align: center;
            background: rgba(101, 136, 234, 1);
            z-index: 125;
            pointer-events: auto;
        }
        #dalee div{
            position: relative;
            top: 36%;
            font-size: 3.5vw;
        }

        #nazad{
            position: absolute;
            bottom: 1vh;
            left: 0.5%;
            width: 14vw;
            height: 14vw;
            border-radius: 50%;
            font-size: 4vw;
            color: black;
            text-align: center;
            background: rgb(173, 158, 158);
            z-index: 115;
            pointer-events: auto;

        }
        #nazad div{
            position: relative;
            top: 36%;
            font-size: 3.5vw;
        }

        #delivery{
            position: absolute;
            top: 48%;
            left: 30vw;
            padding-left: 2vw;
            font-size: 2.5vh;
            height: auto;
            width: 40vw;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            pointer-events: auto;
            float:left;
        }
        .field {clear:both; text-align:left; line-height: 3vh;}
        .field input {float:right; width: 14.3vw; margin-right: 12vw}
        .field select {float:right; width: 14.3vw; padding-top: 1vh; margin-right: 12vw}

        tr{
            float:none;
            text-align: center;
            position: relative;
            width: 75vw;
            height: 5vh;
            border: none;
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

        .button-29 {
            align-items: center;
            appearance: none;
            background-image: radial-gradient(100% 100% at 100% 0, #5adaff 0, #5468ff 100%);
            border: 0;
            border-radius: 6px;
            box-shadow: rgba(45, 35, 66, .4) 0 2px 4px,rgba(45, 35, 66, .3) 0 7px 13px -3px,rgba(58, 65, 111, .5) 0 -3px 0 inset;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            display: inline-flex;
            font-family: "JetBrains Mono",monospace;
            height: 48px;
            justify-content: center;
            line-height: 1;
            list-style: none;
            overflow: hidden;
            padding-left: 16px;
            padding-right: 16px;
            position: relative;
            text-align: left;
            text-decoration: none;
            transition: box-shadow .15s,transform .15s;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            white-space: nowrap;
            will-change: box-shadow,transform;
            font-size: 18px;
       }

        .button-29:focus {
            box-shadow: #3c4fe0 0 0 0 1.5px inset, rgba(45, 35, 66, .4) 0 2px 4px, rgba(45, 35, 66, .3) 0 7px 13px -3px, #3c4fe0 0 -3px 0 inset;
        }

        .button-29:hover {
            box-shadow: rgba(45, 35, 66, .4) 0 4px 8px, rgba(45, 35, 66, .3) 0 7px 13px -3px, #3c4fe0 0 -3px 0 inset;
            transform: translateY(-2px);
        }

        .button-29:active {
            box-shadow: #3c4fe0 0 3px 7px inset;
            transform: translateY(2px);
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
    <div id="patchToHome"><a href="/public/index.php"><img src="/resources/patchToHome.png" alt=""></a></div>
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
                echo "<a id='dalee' href='payment.php?step=" . ($currentPage + 2) . " '><div>Далее</div></a>";
            }
            elseif($currentPage == 2 || $currentPage == 3){
                echo "<a id='dalee' style='display:none' href='payment.php?step=" . ($currentPage + 1) . " '><div>Далее</div></a>";
            }else{
                echo "<a id='dalee' href='payment.php?step=" . ($currentPage + 1) . " '><div>Далее</div></a>";
            }
        }
        /*
         *   Вывод кнопки Назад
        * */
        if($currentPage == 3 && !empty($_SESSION['user_id'])){
            echo "<a id='nazad' href='payment.php?step=" . ($currentPage - 2) . " '><div>Назад</div></a>";
        }else{
            if($currentPage > 1){
                echo "<a id='nazad' href='payment.php?step=" . ($currentPage - 1) . " '><div>Назад</div></a>";
            }
        }
        /*
        *    Первый раздел страницы
        * */
        if($currentPage == 1){
            // Создаем таблицу если есть данные
            viewProducts();
        }elseif ($currentPage == 2){
            if(isset($_SESSION['user_id'])){
                header("Location:payment.php?step=" . 3);
            }else{
                    echo"<script>window.location.href = '/app/account/login.php?fromPayment=1'</script>";
            }
            echo<<<END
                <style>
                    #dalee: 'display: none';
                </style>
            END;
        }elseif ($currentPage == 3 && isset($_SESSION['user_id'])){
            $stmt = Connect::getLink()->prepare("SELECT * FROM address WHERE id_address = {$_SESSION['user_id']}");
            $stmt->execute();
            $dataAddress = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                createOrEditOfAddress($dataAddress, true);
            }else {
                if (count($dataAddress) === 0) {
                    createOrEditOfAddress($dataAddress);
                    echo "<script>let isAddress = false</script>";
                } else if (!isset($_GET['edit']) && count($dataAddress[0]) > 0) {
                    echo "<script>let isAddress = true</script>";
                    showAddress($dataAddress[0]);
                    echo "<form method='get' action='payment.php'>
                    <input type='hidden' name='step' value='3'>
                    <input type='hidden' name='edit' value='true'>
                    <input class='button-29'style='pointer-events: auto; width: 15vw; height: 6vh' type='submit' value='Редактировать'>
                    </form>";
                    echo "</div>";
                }
            }
        }elseif ($currentPage == 4){
            echo "<script>localStorage.setItem('page', '4')</script>";
            if(isset($_GET['delivery'])){
                echo "<script>localStorage.removeItem('sam')</script>";
                $stmt = Connect::getLink()->prepare("SELECT * FROM address WHERE id_address = {$_SESSION['user_id']}");
                $stmt->execute();
                $dataAddress = $stmt->fetchAll(PDO::FETCH_ASSOC);
                showAddress($dataAddress[0]);
                if($dataAddress[0]['city'] === 'Москва'){
                    echo "<label>
                            За пределами МКАД
                            <input type='checkbox' id='saMKAD'>                          
                     </label>";
                    echo "<div class='distance'><label>Расстояние (км)<input required id='km'; style='height: 3vh; display: inline-grid' type='number' max='20'></label></div>";
                    echo "</div></div>";
                }else{
                    echo "<p style='position: relative; top 50%; font-size: 3.2vh'>В Ваш город доступна доставка транспортной компанией</p>";
                    echo "<script>localStorage.setItem('transportCompany', '1')</script>";
                }
            }else if(isset($_GET['sam'])){
                echo "<div id='delivery'>";
                echo "<p>Вы можете забрать товар ";
                echo date('d.m.y', mktime(0, 0, 0, date("m")  , date("d") + 3 , date("Y")));
                echo "</p><p>По адрессу: г. Москва, метро Таганская. Грабли</p></div>";
            }else{
                echo "<div id='delivery'>";
                echo "<p>Вы не выбрали способ доставки, вернитесь <a href='payment.php/?step=3' style='color: cadetblue'>назад</a>, пожалуйста";
            }
        }elseif($currentPage == 5){
            $finalSum = 0;
            $finalSum += getSum();
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
                if(empty($_GET['sam']) && empty($_GET['delivery'])){
                    echo "<div id='delivery'>";
                    echo "<p>Вы не выбрали способ доставки, вернитесь <a href='payment.php/?step=3' style='color: cadetblue'>назад</a>, пожалуйста";
                    echo var_dump($_GET);
                }

                $typeOfOrder = "";
                if(isset($_GET['delivery'])){
                    $typeOfOrder = 'delivery';
                    $sumOfDelivery = 800;
                    if(isset($_GET['transportCompany'])){
                        $sumOfDelivery += 800;
                    }else{
                        if(isset($_GET['distance'])){
                            $sumOfDelivery += ($_GET['distance'] * 30);
                        }
                    }
                    $finalSum += $sumOfDelivery;
                }else{
                    $typeOfOrder = 'sam';
                }
                echo<<<FINAL
                    <form id="mailajob" method="post" action="finalPayment.php">
                        <input type="hidden" name="data" value="$finalSum">
                        <input type="hidden" name="dostavka" value="$typeOfOrder">                       
                    </form>         
                    <div id="lastPage">
                        <div id="lastPageInfo">
                            Общая стоимость вашего заказа:  $finalSum ₽
                            Нажмите на кнопку для завершения заказа.
                            
                        </div>                     
                    </div>    
                    <a onclick="document.getElementById('mailajob').submit(); return(false);" title="Оплата" id='dalee';><div style="top: 40%;font-size: 2.5vw">Завершить</div></a>                         
                FINAL;
            }
        }
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
                    localStorage.removeItem('transportCompany');
                    localStorage.setItem('sam', 'true');
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
                    document.getElementById('dalee').style.display = 'inline';
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
            if(localStorage.getItem('transportCompany') !== null){
                document.getElementById('dalee').href= 'payment.php?step=5&delivery=1&transportCompany=1';
            }else if(localStorage.getItem('sam') !== null){
                document.getElementById('dalee').href= 'payment.php?step=5&sam=1';
            }else {
                document.getElementById('dalee').href= 'payment.php?step=5&delivery=1';
            }
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
                    counter.value = 1;
                }
            });
        }else if(page == '5'){

        }
        document.getElementById('val').addEventListener('click', function (e) {//e - событие элемента по умолчанию
            e.preventDefault();
        });

        function ins(arr, id, isFromBD = false){
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
                    document.getElementById(id).innerText = String(Number(temp) + 50);
                }else{
                    newString[3] = String(Number(newString[3]) + 50);
                }
            } else if(newString[0] == 'delete'){
                if(!isFromBD){
                    temp = localStorage.getItem(newString[1]);
                    if(temp > 0){
                        localStorage[newString[1]] = String(Number(temp) - 50);
                    }
                    document.getElementById(id).innerText = String(Number(temp) - 50);
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

        function deleteAll(id){
            localStorage.clear();
            var params = new FormData();
            alert(id)
            if(id !== ''){
                alert(id)
                params.set('userId', id)
                fetch('fastDeleteAllFromBD.php',{
                    method: 'post',
                    body: params
                })
                    .then(function(response) {
                        if (response.ok) {
                            return response;
                        } else {
                            throw new Error('Ошибка при выполнении запроса');
                        }
                    })
                    .then(function(data) {
                        window.location.replace("/app/order/payment.php?step=1");
                    })
                    .catch(function(error) {
                        // Обработка ошибок
                        console.log(error);
                    });
            }else{
                window.location.replace("/app/order/payment.php?step=1");
            }
        }
    </script>
</body>






























