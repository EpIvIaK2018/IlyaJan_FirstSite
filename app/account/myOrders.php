<?php
use App\Connect;
require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
session_start();
if(isset($_SESSION['user_id'])){
    $myId = $_SESSION['user_id'];
    $statement = Connect::getLink()->prepare("SELECT * FROM orders where customer_id = ?");
    $statement->bindParam(1, $myId, PDO::PARAM_INT);
    $statement->execute();
    $data = $statement->fetchAll();
    echo <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
            <title>Мои заказы</title>
            <link rel="stylesheet" type="text/css" href="/app/style.css">
            </head>
            <style>
                body, html{                  
                    position: absolute;              
                }
                
                .main{                                  
                    width: 100%;
                    height: auto;
                    min-height: 100%;
                    background-color: #374f39;
                }
                
                .header{
                    position: relative;
                    width: 20vw;
                    height: auto;
                    max-height: 100%;                    
                    display: grid;
                    left: 1%;
                    top: 15%;  
                    min-height: 10%;                                
                }
                
                .currentId{
                    position: relative;
                    margin-bottom: 2vh;
                    background-color: #576937;
                    font-size: 3vh;
                    font-family: "Britannic Bold";
                    height: available;
                    width: 35vw;                     
                    display: block;      
                    height: auto;
                    padding: 1vw;
                   
                }
                
                .wholeOrder{
                    margin-top: 5vh;
                    position: relative;
                    padding: 3vh;
                    background: linear-gradient(180deg, #a146c9 0%, #9825c9 15%, #7f09b4 35%, #500072 76%);   
                    left: 33vw;
                    border: 2px black solid;
                    border-radius: 5%;
                    font-size: 2vh;
                    margin-bottom: 3vh;
                }
                
                .info{      
                    position: relative;                           
                    width: 15vw;
                    height: 100%;                               
                    border-radius: 5px;                                        
                    align-items: center;  
                    left: 0%;  
                    line-height: 6vh;                                                             
                }
                               
                .container {
                    position: absolute;   
                    width: 10vw;
                    height: 12vh;                                                 
                    align-items: center;  
                    right: 10%;    
                    top: 20%;                                                          
                }
                
                img {
                    position: relative;
                    max-width: none;                 
                    min-width: auto;
                    min-height: auto;                 
                    height: 100%;   
                    width: 100%;             
                    position: absolute;                 
                    top: 50%;
                    left: 50%;                  
                    transform: translate(-50%, -50%);
                }
                
                .return:hover{                  
                    top: 40%;
                    background-color: black;
                }
            </style>
            <body>         
            <div class="main">
            <div class="header">
            <div class="return" style="float: left; font-size: 3vh; position: relative; left: 5vh; top: 5vh; background-color: white; height: 7vh; width: 7vw;"><a href="/public/index.php">На Главную</a></div>
        HTML;
    if(!empty($data)){
        foreach ($data as $order){
            $d = json_decode($order['goods'], true, 512, 1);
            echo "<div class='wholeOrder'>";
            echo "<p>Статус заказа: {$order['statusOfOrder']}</p>";
            foreach ($d as $directlyGood){
                $urlToimg = "/resources/";
                switch ($directlyGood['product']){
                    case "green": $urlToimg .= "orderGreen.jpeg"; break;
                    case "red":   $urlToimg .= "orderRed.jpg"; break;
                    case "limon": $urlToimg .= "orange.jpg"; break;
                    case "last":  $urlToimg .= "last.jpeg"; break;
                }
                echo<<<INTO
                    <div class='currentId'><div class="info">{$order['data']}<br>Товар: 
                    {$directlyGood['product']}<br>Количество:{$directlyGood['count']}</div>
                    <div class='container'>
                        <img src=$urlToimg alt=$urlToimg>
                    </div>
                    </div>
                INTO;

            }
            echo "</div>";
        }
        echo<<<HTML
            </div>
            </div>
            </body>
            <html>
        HTML;
    }
}else{
    echo  'Вы не авторизованы. Перемещаемся назад';
    header('Refresh: 1; URL=/public/index.php');
}

