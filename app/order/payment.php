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
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" type="text/css" href="/app/style.css">
    <style>
        #payment-main{
            position: relative;
            height: 100%;
            width: 100%;
            background: url("/resources/paymentBack.jpg") no-repeat center center fixed;
            background-size: cover;
            font-size: 100%;
            font-family: "Droid Sans Mono", "Cambria Math";
            font-weight: bolder;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .main-menu-payment{
            position: fixed;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            top: 15%;
            left: 5%;
            height: 14%;
            min-height: 14%;
            min-width: 90%;
            border-radius: 0% 5% 5% 5%;
            overflow: visible;
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
            z-index: 3;
        }

        table{
            position: fixed;
            left: 5vw;
            font-size: 5.5vh;
            top: 40vh;
            min-height: 47vh;
            height: 47vh;
            width: 90vw;
            background-color: rgba(207, 213, 190, 1);
            box-shadow: 2px 2px 8px 12px rgba(207, 213, 190, 1);
            border-radius: 3%;
            border-spacing: 3px 3px;
            border-collapse: separate;
        }

        tr{
            text-align: center;
            position: relative;
            width: 75vw;
            height: 3vh;
            border = none;
        }

        th{
            position: relative;
            text-align: center;
            width: 35%;
            height: 20%;
            border: none;
        }
    </style>

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
        if($currentPage < 5){
            if($currentPage == 1 && !empty($_SESSION['user_id'])){
                echo "<a id='dalee' href='payment.php?step=" . ($currentPage + 2) . " '>Далее</a>";
            }else{
                echo "<a id='dalee' href='payment.php?step=" . ($currentPage + 1) . " '>Далее</a>";
            }
        }

        $thereIsData = false;
            if($currentPage == 1){
                if(!empty($_SESSION['user_id'])){
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
                    echo<<<END
                        <table id="table">
                            <tr>
                                 <th>Товар</th><th>Количество</th><th>Сумма</th>
                            </tr>
                    END;

                    //Написать функцию рисования СВГ рамок. Передать массив вероятно и точно количество в массиве
                    $val = 0;
                    foreach($data as $row){
                        $val += 1;
                        if($val > 0){
                            $thereIsData = true;
                        }
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
                        $count = $t->getCount();
                        if($count < 1) continue;
                        $b = $t->getSum();
                        $id = $row["cart_id"];
                        $c = intval($b * $dollarsToRoubles);
                        $sum += $b;
                        $dataForJs = array("$amount", "$sum");
                        echo<<<END
                    <form id="my-form" method="post" class="button-menu" action="#">                
                    <tr>                   
                       <td>$a</td>
                       <td>$count</td>
                       <td>$c рублей</td>                                         
                    </tr>
                END;
                    }
                echo<<<END
                    <script>                     
                        const elements = document.querySelectorAll('td');
                        len = elements.length;
                        for (let i = 0; i < len; i++) {
                            elements[i].style.setProperty('height', 80/$val + '%')                   
                        }   
                       
                        let myTableDiv = document.getElementById('payment-main'); 
                        let tbl = document.getElementById('table');
                        myTableDiv.appendChild(tbl);  
                        var ns = 'http://www.w3.org/2000/svg'
                        var svg = document.createElementNS(ns, 'svg')
                        svg.setAttributeNS(null, 'width', '100%')
                        svg.setAttributeNS(null, 'height', '100%')
                        myTableDiv.appendChild(svg)
                        rectTop = document.createElementNS(ns, 'rect')
                        rectTop.setAttributeNS(null, 'width', '83vw')
                        rectTop.setAttributeNS(null, 'height', '0.5vh')
                        rectTop.setAttributeNS(null, 'fill', 'black')
                        rectTop.setAttributeNS(null, 'y', '49vh')
                        rectTop.setAttributeNS(null, 'x', '8vw')
                        svg.appendChild(rectTop)
                        svg.style.zIndex = 2;
                        
                        let bootomVal = tbl.getBoundingClientRect();                           
                        rectBottom = document.createElementNS(ns, 'rect')
                        rectBottom.setAttributeNS(null, 'width', '83vw')
                        rectBottom.setAttributeNS(null, 'height', '0.5vh')
                        rectBottom.setAttributeNS(null, 'fill', 'black')
                        rectBottom.setAttributeNS(null, 'y', bootomVal.bottom.toString() - 15);
                        rectBottom.setAttributeNS(null, 'x', '8vw')
                        svg.appendChild(rectBottom)
                                                                                              
                        rectLeft = document.createElementNS(ns, 'rect')
                        rectLeft.setAttributeNS(null, 'width', '0.3vw')
                        rectLeft.setAttributeNS(null, 'height', String(rectBottom.getBoundingClientRect().y - rectTop.getBoundingClientRect().y));
                        rectLeft.setAttributeNS(null, 'fill', 'black')
                        
                        rectLeft.setAttributeNS(null, 'y', '49vh')
                        rectLeft.setAttributeNS(null, 'x', '38vw')
                        svg.appendChild(rectLeft)
                                                                    
                        rectRight = document.createElementNS(ns, 'rect')
                        rectRight.setAttributeNS(null, 'width', '0.3vw')
                        rectRight.setAttributeNS(null, 'height', String(rectBottom.getBoundingClientRect().y - rectTop.getBoundingClientRect().y));
                        rectRight.setAttributeNS(null, 'fill', 'black')
                        rectRight.setAttributeNS(null, 'y', '49vh')
                        rectRight.setAttributeNS(null, 'x', '68vw')
                        svg.appendChild(rectRight)                   
                        myTableDiv.appendChild(svg);
                                             
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
                       </script>   
                END;
                echo<<<END
                            </form>
                       </table>
                   </div>                                  
                END;
                }else{
                    echo "<script>                                                                                          
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
                        tbl.style.position = 'fixed';                   
                        tbl.style.top = '40vh';
                        tbl.style.left = '5vw';
                        tbl.style.minHeight = '47vh';                   
                        tbl.style.height = '47vh';                                            
                        tbl.style.width = '90vw';
                        tbl.style.fontSize = '5.5vh';                                                                          
                        tbl.style.backgroundColor = 'rgba(207, 213, 190, 1)';
                        tbl.style.boxShadow = '2px 2px 8px 12px rgba(207, 213, 190, 1)';
                        tbl.style.borderRadius = '3%';                    
                        let tableBody = document.createElement('TBODY');
                        tbl.appendChild(tableBody);                                            
                        let tr1 = document.createElement('TR');                      
                        tableBody.appendChild(tr1);  
                        
                        let th1 = document.createElement('TH');                               
                        th1.innerText = 'Товар';   
                        th1.style.position = 'relative';
                        th1.width = '35%'; 
                        th1.style.height = '20%';     
                        th1.style.textAlign = 'center';
                        th1.style.border = 'none';                        
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
                        tr1.appendChild(th3);   
                                               
                        var ns = 'http://www.w3.org/2000/svg'
                        var svg = document.createElementNS(ns, 'svg')
                        svg.setAttributeNS(null, 'width', '100%')
                        svg.setAttributeNS(null, 'height', '100%')
                        myTableDiv.appendChild(svg)
                        rectTop = document.createElementNS(ns, 'rect')
                        rectTop.setAttributeNS(null, 'width', '83vw')
                        rectTop.setAttributeNS(null, 'height', '0.5vh')
                        rectTop.setAttributeNS(null, 'fill', 'black')
                        rectTop.setAttributeNS(null, 'y', '49vh')
                        rectTop.setAttributeNS(null, 'x', '8vw')
                        svg.appendChild(rectTop)
                        svg.style.zIndex = 2;
                        
                        let bootomVal = tbl.getBoundingClientRect();                           
                        rectBottom = document.createElementNS(ns, 'rect')
                        rectBottom.setAttributeNS(null, 'width', '83vw')
                        rectBottom.setAttributeNS(null, 'height', '0.5vh')
                        rectBottom.setAttributeNS(null, 'fill', 'black')
                        rectBottom.setAttributeNS(null, 'y', bootomVal.bottom.toString() - 15);
                        rectBottom.setAttributeNS(null, 'x', '8vw')
                        svg.appendChild(rectBottom)
                                                                                              
                        rectLeft = document.createElementNS(ns, 'rect')
                        rectLeft.setAttributeNS(null, 'width', '0.3vw')
                        rectLeft.setAttributeNS(null, 'height', String(rectBottom.getBoundingClientRect().y - rectTop.getBoundingClientRect().y));
                        rectLeft.setAttributeNS(null, 'fill', 'black')
                        
                        rectLeft.setAttributeNS(null, 'y', '49vh')
                        rectLeft.setAttributeNS(null, 'x', '38vw')
                        svg.appendChild(rectLeft)
                                                                    
                        rectRight = document.createElementNS(ns, 'rect')
                        rectRight.setAttributeNS(null, 'width', '0.3vw')
                        rectRight.setAttributeNS(null, 'height', String(rectBottom.getBoundingClientRect().y - rectTop.getBoundingClientRect().y));
                        rectRight.setAttributeNS(null, 'fill', 'black')
                        rectRight.setAttributeNS(null, 'y', '49vh')
                        rectRight.setAttributeNS(null, 'x', '68vw')
                        svg.appendChild(rectRight)                   
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
                            tableBody.appendChild(tr2);  
                            let key = localStorage.key(i);                              
                            let value = localStorage[key];                                                                                   
                            let td = document.createElement('TD');
                            td.width = '75vw';                 
                            switch (key){
                                  case 'green':
                                      td.innerText = 'Зелёный чай';                                       
                                      break;
                                  case 'red':
                                      td.innerText = 'Красный чай';                                        
                                      break;
                                  case 'limon':
                                      td.innerText = 'Лимонный чай';                                         
                                      break;
                                  case 'last':
                                      td.innerText = 'Последний чай';                                         
                                      break;
                            }  
                            let priceFor50 = Number(arr.get(key));
                            tr2.appendChild(td);                                
                            let td2 = document.createElement('TD');
                            td2.width = '75vw'; 
                            
                            temp = (String(80/len)) + '%';
                            td2.style.height = temp; //px                                                                            
                            td2.innerText = String(value);                             
                            tr2.appendChild(td2);
                                                                                                                                     
                            let td3 = document.createElement('TD');
                            td3.width = '75vw'; 
                            td3.style.height = temp; //px     
                            td3.innerText = String(Number(value * priceFor50));                             
                            tr2.appendChild(td3);  
                            
                            // Если товар один, надо немного сдвинуть визуально
                            if (countOfProducts == 1){
                                td.style.paddingBottom = '5vh';
                                td2.style.paddingBottom = '5vh';
                                td3.style.paddingBottom = '5vh';
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

            }
            // ДОДЕЛАТЬ ЕСТЬ ЛИ КРУЖОК ЕСТЬ ЛИ ТОВАР КОГДА ЗАРЕГАНЫ. И ЕСЛИ ЗАРЕГАНЫ И НЕТ ТОВАРА СДЕЛАТЬ ПУСТУЮ ТАБЛИЧКУ
            //-----------------------------------------------------------


        ?>
    </div>

</body>

