<?php
session_start();
use App\User;
$url = $_SERVER['DOCUMENT_ROOT'] . "/app/public/index.php";
if($_SESSION['login'] !== 'IlyaJanAdmIn1988-1990'){
    header('HTTP/1.1 301 Moved Permanently');
    header("Refresh: 3; URL=$url");
}else{
    echo "<script>
    var code = prompt('Кодовое слово?', '');
    if(code != 'Холстинин'){
        window.location.href = '$url';
    }
    </script>";
}

require $_SERVER['DOCUMENT_ROOT'] . "/app/getConnect.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/user/User.php";
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$stmt = \App\Connect::getLink()->query('SELECT * FROM users');
$listOfPerson = array();
while ($row = $stmt->fetch())
{
    $listOfPerson[] = new User($row['login'], $row['password'], $row['email'], $row['ip']);
}

?>
<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border: none;
            margin-bottom: 20px;
            border-collapse: separate;
        }
        table thead th {
            font-weight: bold;
            text-align: left;
            border: none;
            padding: 10px 15px;
            background: #EDEDED;
            font-size: 14px;
            border-top: 1px solid #ddd;
        }
        table tr th:first-child, .table tr td:first-child {
            border-left: 1px solid #ddd;
        }
        table tr th:last-child, .table tr td:last-child {
            border-right: 1px solid #ddd;
        }
        table thead tr th:first-child {
            border-radius: 20px 0 0 0;
        }
        . thead tr th:last-child {
            border-radius: 0 20px 0 0;
        }
        table tbody td {
            text-align: left;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            vertical-align: top;
        }
        table tbody tr:nth-child(even) {
            background: #F8F8F8;
        }
        table tbody tr:last-child td{
            border-bottom: 1px solid #ddd;
        }
        table tbody tr:last-child td:first-child {
            border-radius: 0 0 0 20px;
        }
        table tbody tr:last-child td:last-child {
            border-radius: 0 0 20px 0;
        }
    </style>
</head>

<body>
    <?php if(!empty($listOfPerson)){
        $builder = "";
        foreach($listOfPerson as $user){
            $builder .= "<tr><td>" . $user->getName() . "</td><td>" . $user->getPassword() . "</td><td>" . $user->getEmail() . "</td><td>" . $user->getIp() . "</td></tr>";
            $builder .= PHP_EOL;
        }
        echo <<< END
        <table>
            <th>Имя</th>
            <th>Пароль</th>
            <th>Почта</th> 
            <th>IP адрес</th>          
            $builder
        </table>
        <a href="viewOfOrders.php">Список заказов</a>
        END;
    }?>
</body>
</html>

