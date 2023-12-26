<?php
require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
use App\Connect;
use App\order\greenTea;
use App\order\lastTea;
use App\order\limonTea;
use App\order\redTea;
session_start();
?>

<?php
if (!isset($_GET["step"])) {
    $_GET["step"] = 1;
    header("Location:payment.php?step=" . $_GET["step"]);
}
$currentPage = $_GET["step"];

function createMenu(): string{
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
            /*background: url("/resources/paymentBack.jpg") no-repeat center center fixed;*/
            background-size: cover;
            font-size: 100%;
            font-family: "Droid Sans Mono", "Cambria Math";
            font-weight: bolder;
            pointer-events: none;
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
            top: 45%;
            left: 25%;
            width: 40%;
            pointer-events: none;
        }

        .preference{
            padding-top: 4%;
            padding-bottom: 2%;
            margin-bottom: 2%;
            text-align: left;
            vertical-align: middle;
            display:flex;
            margin: 5px;
            min-width: 50%;
        }

        label{
            display: contents;
        }

    </style>
    <script src=https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js></script>
    <script>
        function getData(){
            var obj = {}
            for (var i = 0; i < localStorage.length; i++) {
                var key = localStorage.key(i);
                obj[key] = localStorage.getItem(key);
            }
            var js = JSON.stringify(obj);
            return js;
        }
    </script>
</head>
<body>
<div id="payment-main">
    <div class="main-menu-payment">
        <ul id="order_step">
            <li class="step_done">Корзина</li>
            <li class="step_done">Вход</li>
            <li class="step_done">Адрес</li>
            <li class="step_done">Доставка</li>
            <li class="step_done">Оплата</li>
            <?=createMenu();?>
        </ul>
    </div>
    <?php
    if (isset($_POST['obj'])) {
        $data = json_decode($_POST['obj'], true);
        foreach ($data as $k => $v){
            echo  $k . " === " . $v . PHP_EOL;
        }
    } else {
        echo "<script type='text/javascript'>";
        echo "let js = getData();";
        echo "document.write('<form method=\'post\'>');";
        echo "document.write(js);";
        echo "document.write('</form>');";
        echo "</script>";
    }

    if($currentPage < 5){
        if($currentPage == 1 && !empty($_SESSION['user_id'])){
            echo "<a id='dalee' href='payment.php?step=" . ($currentPage + 2) . " '>Далее</a>";
        }else{
            echo "<a id='dalee' href='payment.php?step=" . ($currentPage + 1) . " '>Далее</a>";
        }
    }
    $thereIsData = false;
    $localStorage = null;
    if($currentPage != 1){
        if(isset($_POST)){


        }
        if(empty($_SESSION['user_id'])){  //Не забыть тут поменять
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
            /////////////////////////////////////////////////////
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
            /////////////////////////////////////////////////////
            echo<<<END
                    <div class="wrapTableDiv">
                        <table id="table">
                            <tr>
                                 <th style="padding-left: 4vw">Товар</th><th>Количество</th><th style="padding-right: 10vw">Сумма</th>
                            </tr>                           
                            <form id="my-form" method="post" class="button-menu" action="#">
                    END;

            //Написать функцию рисования СВГ рамок. Передать массив вероятно и точно количество в массиве
            $val = 0;
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
                }

                $a = $t->getName();
                $count = $t->getCount();
                if($count < 1) continue;
                $b = $t->getSum();
                $id = $row["cart_id"];
                $c = intval($b * $dollarsToRoubles);
                $sum += $b;
                $dataForJs = array("$amount", "$sum");
                echo<<<END
                    <tr>                   
                        <td style="padding-left: 3vw;"><div class="intoTD">$a</div></td>
                        <td  style="padding-bottom: 1vh;"> 
                        <div class="intoTD">                    
                        <button class="button" type="submit" id=$val value="add {$row["cart_id"]} {$row["product"]} {$row["count"]} $val" onclick='ins(this)'> 
                        <img id="imgADD" src="/resources/addingPayment.png"> 
                        </button>   
                        $count&nbsp;
                END;
                if((int)$row["count"] > 0){
                    echo "<button class='button' id='$val' type='submit' value='delete {$row["cart_id"]} {$row["product"]} {$row["count"]} $val' onclick='ins(this)'>";
                }else{
                    echo "<button class='button' id='$val' type='submit' value='delete {$row["cart_id"]} {$row["product"]} {$row["count"]} $val' disabled onclick='ins(this)'>";
                }
                $val += 1;
                if($val > 0){
                    $thereIsData = true;
                }
                echo<<<END
                                <img id="imgADD" src="/resources/deletingPayment.png">                       
                            </button>  
                        </div>  
                        </td>
                        
                        <td style="padding-right: 8vw"><div class="intoTD">$c рублей</div></td>                                         
                    </tr>
                          
                END;
            }
            echo<<<END
                        </form>                
                        </table>  
                    </div>
                    <script>     
                    function create(val) {
                        let screenHeight = window.innerHeight
                        //px / viewport total height * 100
                        const elements = document.querySelectorAll('td');
                        len = elements.length;
                        for (let i = 0; i < len; i++) {
                            elements[i].style.setProperty('height', 80 / val + '%')
                        }
                        //Если один элемент - выравниваем в таблице высоту
                        if (len == 3) {
                            ent = document.getElementsByClassName('intoTD')
                            for (let i = 0; i < ent.length; i++) {
                                ent[i].style.position = 'relative';
                                ent[i].style.top = '-3vh';
                            }
                        } else {
                            ent = document.getElementsByClassName('intoTD')
                            for (let i = 0; i < ent.length; i++) {
                                ent[i].style.position = 'relative';
                                ent[i].style.top = '4vh';
                            }
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
                        create("<?php echo $val; ?>");                       
                        window.addEventListener('resize', function(event) {
                            create("<?php echo $val; ?>");
                        }, true); 
                       </script>   
                END;
        }else{
            echo "<script>  
                        let screenHeight = window.innerHeight                                                                                        
                        let myTableDiv = document.getElementById('payment-main');                   
                        countOfProducts = localStorage.length;     
                        let emptyMessage = document.createElement('DIV');          
                        if(countOfProducts == 0){  
                            let circle = document.getElementById('dalee');
                            circle.style.display = 'none';                                                                                                                                                        
                            emptyMessage.innerText = 'У вас пустая корзина!';
                            emptyMessage.style.position = 'fixed';                   
                            emptyMessage.style.top = '40vh';
                            emptyMessage.style.left = '5vw';
                            emptyMessage.style.minHeight = '48vh';  
                            emptyMessage.style.textAlign = 'center';
                            emptyMessage.style.paddingTop = '10%';
                            emptyMessage.style.paddingBottom = '0%';
                            emptyMessage.style.height = '48vh';                                            
                            emptyMessage.style.width = '90vw';
                            emptyMessage.style.fontSize = '5.5vh';                                                                          
                            emptyMessage.style.backgroundColor = 'rgba(207, 213, 190, 1)';
                            emptyMessage.style.boxShadow = '2px 2px 8px 12px rgba(207, 213, 190, 1)';
                            emptyMessage.style.borderRadius = '3%';   
                            myTableDiv.appendChild(emptyMessage); 
                        } else{                                                                                                                                    
                            let tbl = document.createElement('TABLE');    
                            myTableDiv.appendChild(tbl);  
                            tbl.style.position = 'relative';                   
                            tbl.style.top = '40vh';
                            tbl.style.left = '5vw';
                            tbl.style.minHeight = '47vh';                   
                            tbl.style.height = '47vh';                                            
                            tbl.style.width = '90vw';
                            tbl.style.fontSize = '5.5vh';                                                                          
                            tbl.style.backgroundColor = 'rgba(207, 213, 190, 1)';
                            tbl.style.boxShadow = '2px 2px 8px 12px rgba(207, 213, 190, 1)';
                            tbl.style.borderRadius = '3%';                
                                          
                            let tr1 = document.createElement('TR');                      
                            tbl.appendChild(tr1);                       
                            let th1 = document.createElement('TH');                               
                            th1.innerText = 'Товар';   
                            th1.style.position = 'relative';
                            th1.width = '35%'; 
                            th1.style.height = '19%';     
                            th1.style.textAlign = 'center';
                            th1.style.border = 'none';   
                            th1.style.paddingLeft = '5vw';                     
                            tr1.appendChild(th1);                    
                            let th2 = document.createElement('TH');
                            th2.innerText = 'Количество';
                            th2.style.position = 'relative';
                            th2.width = '35%';                               
                            th2.style.textAlign = 'center';
                            th2.style.border = 'none';                
                            tr1.appendChild(th2);                   
                            let th3 = document.createElement('TH');
                            th3.innerText = 'Сумма';
                            th3.style.position = 'relative';
                            th3.width = '30%';                                                 
                            th3.style.textAlign = 'center';
                            th3.style.border = 'none';   
                            th3.style.paddingRight = '5vw';              
                            tr1.appendChild(th3);   
                                                   
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
                            rectBottom.setAttributeNS(null, 'y', (bootomVal.height / screenHeight * 100) - 8 + 'vh'); // Полезно. Из PX to VH
                            rectBottom.setAttributeNS(null, 'x', '10vw')
                            svg.appendChild(rectBottom)          
                            rectLeft = document.createElementNS(ns, 'rect')
                            rectLeft.setAttributeNS(null, 'width', '0.3vw')
                            rectLeft.setAttributeNS(null, 'height', (bootomVal.height / screenHeight * 100 - 10) + 'vh');
                            rectLeft.setAttributeNS(null, 'fill', 'yeloww')
                            rectLeft.setAttributeNS(null, 'y', (rectTop.getBoundingClientRect().y / screenHeight * 100) + 2.2 + 'vh')
                            rectLeft.setAttributeNS(null, 'x', '40vw')
                            svg.appendChild(rectLeft)           
                            rectRight = document.createElementNS(ns, 'rect')
                            rectRight.setAttributeNS(null, 'width', '0.3vw')
                            rectRight.setAttributeNS(null, 'height', (bootomVal.height / screenHeight * 100 - 10) + 'vh');
                            rectRight.setAttributeNS(null, 'fill', 'black')
                            rectRight.setAttributeNS(null, 'y', (rectTop.getBoundingClientRect().y / screenHeight * 100) + 2.2 + 'vh')
                            rectRight.setAttributeNS(null, 'x', '66vw')
                            svg.appendChild(rectRight)
                            svg.style.zIndex = '5';
                            svg.style.position = 'relative';
                                           
                            myTableDiv.appendChild(svg);    
                            
                            const arr = new Map([
                              ['green', 1.5],
                              ['red'  , 2.5],
                              ['limon', 2.1],
                              ['last' , 3.5]
                            ]);
                            // Будем делить это на количество итераций в цикле и высота это будет для TD в каждом ряду.
                            heightForTD = rectBottom.getBoundingClientRect().y - rectTop.getBoundingClientRect().y  - 2;
                            for(var i=0, len=localStorage.length; i<len; i++) {                              
                                let tr2 = document.createElement('TR');        
                                tbl.appendChild(tr2);  
                                let key = localStorage.key(i);                              
                                let value = localStorage[key];                                                                                   
                                let td = document.createElement('TD');
                                td.style.paddingLeft = '5vw'; 
                                let div = document.createElement('DIV');
                                div.className = 'intoTD'
                                td.appendChild(div);
                                
                                td.width = '75vw';                 
                                switch (key){
                                      case 'green':
                                          div.innerText = 'Зелёный чай';                                       
                                          break;
                                      case 'red':
                                          div.innerText = 'Красный чай';                                        
                                          break;
                                      case 'limon':
                                          div.innerText = 'Лимонный чай';                                         
                                          break;
                                      case 'last':
                                          div.innerText = 'Последний чай';                                         
                                          break;
                                }  
                                let priceFor50 = Number(arr.get(key));
                                tr2.appendChild(td);                                
                                let td2 = document.createElement('TD');
                                
                                let div2 = document.createElement('DIV');
                                div2.className = 'intoTD'                                                                                            
                                td2.width = '75vw';                               
                                
                                
                                                             
                                
                                let butt = document.createElement('BUTTON');
                                div2.appendChild(butt);
                                butt.className = 'button';
                                butt.id = 'value';
                                butt.type = 'submit';
                                butt.value = '5';
                                butt.style.width = '111px';
                                butt.innerHTML = '<img id=imgADD src=/resources/addingPayment.png/>';
                                
                                let buttDel = document.createElement('BUTTON');
                                div2.appendChild(buttDel);
                                buttDel.className = 'button';
                                buttDel.id = 'value';
                                buttDel.type = 'submit';
                                buttDel.value = '5';
                                buttDel.style.width = '111px';
                                buttDel.innerHTML = '<img id=imgADD src=/resources/deletingPayment.png/>';
                                
                                                                
                                div2.appendChild(butt); 
                                div2.appendChild(buttDel); 
                                td2.appendChild(div2); 
                                tr2.appendChild(td2);  
                                
                                
                                                             
                             
                                temp = (String(80/len)) + '%';
                                td2.style.height = temp; //px                                                                            
                                div2.innerText = String(value);                                                                                                                                                                                             
                                let td3 = document.createElement('TD');
                                let div3 = document.createElement('DIV');
                                div3.className = 'intoTD'
                                td3.appendChild(div3);  
                                                              
                                td3.width = '75vw'; 
                                td3.style.height = temp; //px  
                                td3.style.paddingRight = '5vw';    
                                div3.innerText = String(Number(value * priceFor50));                             
                                tr2.appendChild(td3);  
                                
                                // Если товар один, надо немного сдвинуть визуально
                                if (countOfProducts == 1){
                                    td.style.paddingBottom = '5vh';
                                    td2.style.paddingBottom = '5vh';
                                    td3.style.paddingBottom = '5vh';
                                }               
                            } 
                            
                            const elements = document.querySelectorAll('td');
                            len = elements.length;
                            for (let i = 0; i < len; i++) {
                                elements[i].style.setProperty('height', 80 + '%')  //val а не 5
                            }
                            //Если один элемент - выравниваем в таблице высоту
                            if (len == 3) {
                                ent = document.getElementsByClassName('intoTD')
                                for (let i = 0; i < ent.length; i++) {
                                    ent[i].style.position = 'relative';
                                    ent[i].style.top = '-3vh';
                                }
                            } else {
                                ent = document.getElementsByClassName('intoTD')
                                for (let i = 0; i < ent.length; i++) {
                                    ent[i].style.position = 'relative';
                                    ent[i].style.top = '-10%';
                                }
                            }
                                                                                 
                            window.addEventListener(`resize`, event => {
                                tbl.style.position = 'fixed';
                                tbl.style.top = '40vh';
                                tbl.style.left = '5vw';               
                                tbl.style.height = '47vh';
                                tbl.style.width = '90vw';
                                tbl.style.fontSize = '5.5vh';          
                                bootomVal = tbl.getBoundingClientRect();   
                                rectBottom.setAttributeNS(null, 'y', String(bootomVal.bottom - 15));                    
                                rectLeft.setAttributeNS(null, 'height', String(rectBottom.getBoundingClientRect().y - rectTop.getBoundingClientRect().y));
                                rectRight.setAttributeNS(null, 'height', String(rectBottom.getBoundingClientRect().y - rectTop.getBoundingClientRect().y));
                            }, false);
                }                                                                                                                                                    
            </script>";
        }
    }elseif ($currentPage == 2){
        echo<<<END
                    <div id="log-reg-entery">
                        <form action="/app/account/login.php" method="post">
                        <div class="preference">
                          <label for="log">Логин:<input style="pointer-events: auto; font-size: 3vh" type="text" name="log" id="log" required/></label>                        
                        </div>
                        <div class="preference">
                          <label for="log2">Пароль:<input style="pointer-events: auto; font-size: 3vh" type="text" name="password" id="password" required/></label>                        
                        </div>  
                        <input type="hidden" id="fromPayment" name="fromPayment" value="fromPayment">                      
                        <input style="pointer-events: auto" type="submit" value="Войти" />    
                        </form>          
                    </div>
                END;

        if(!empty($_POST)){
            var_dump($_POST['log']);
            var_dump($_POST['password']);
            echo PHP_EOL;
            var_dump($_POST);
        }else{
            echo<<<END
                        <style>
                            #dalee: 'display: none';
                        </style>
                    END;
        }

    }
    // ДОДЕЛАТЬ ЕСТЬ ЛИ КРУЖОК ЕСТЬ ЛИ ТОВАР КОГДА ЗАРЕГАНЫ. И ЕСЛИ ЗАРЕГАНЫ И НЕТ ТОВАРА СДЕЛАТЬ ПУСТУЮ ТАБЛИЧКУ
    //-----------------------------------------------------------

    ?>
</div>
<script src=https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js></script>
<script>
    function ins(t){
        const newString = t.value.split(" ");
        if (newString[0] == 'add'){
            newString[3] = String(Number(newString[3]) + 50);
        } else if((newString[0] == 'delete')){
            newString[3] = String(Number(newString[3]) - 50);
        }
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

    //Поместим все данные в объект
    /*
    alert("234324")
    var obj = {}
    for (var i = 0; i < localStorage.length; i++) {
        var key = localStorage.key(i);
        obj[key] = localStorage.getItem(key);
    }
    fetch('payment.php', {
        method: 'POST',
        headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        body: JSON.stringify({param: obj})
        //body: ('param=' + JSON.stringify(obj))
    })
        .then(function(response) {
            return response.text();
        })
        .then(function(text) {
            console.log('Request successful', obj);
        })
        .catch(function(error) {
            console.log('Request failed', error)
        });
    */
</script>
</body>
