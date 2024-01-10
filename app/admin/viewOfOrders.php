<?php
require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
use App\Connect;
$stmt = Connect::getLink()->prepare("SELECT * FROM orders");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo<<<VIEW
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    </head>
    <body>
        <div class="main">
            <table style="border: 3px solid black">
VIEW;
foreach ($data as $arr){
    //var_export($arr);
    //'goods' => '[{"product":"red","count":200},{"product":"limon","count":300},{"product":"last","count":300},{"product":"green","count":300}]'
    echo "<tr>";
    foreach ($arr as $k=>$v){
        if($k==="goods"){
            $v = json_decode($v, true);
            $builder = "";
            foreach ($v as $k=>$val) {
                $builder .= ($val['product'] . $val['count'] . "<br>");
            }
            echo "<td style='font-size: 15px; border: 1px solid black'>" . $builder . "</td>";
        }else{
            echo "<td style='border: 1px solid black''>" . $k . "</td>";
            echo "<td style='border: 1px solid black''>" . $v . "</td>";
        }
    }
    echo "<tr>";
    echo "_________________________";
}

echo<<<VIEW
                </table>
            </div>
        </body>
    </html>
VIEW;

