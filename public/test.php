<?php
use App\User;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include $_SERVER['DOCUMENT_ROOT'] . "/app/Connect.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/user/User.php";
$stmt = \App\Connect::getLink()->query('SELECT * FROM users');
$listOfPerson = array();
while ($row = $stmt->fetch())
{
    $listOfPerson[] = new User($row['login'], $row['password'], $row['email'], $row['ip']);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Тест</title>
    <script src="http://code.jquery.com/jquery-latest.js"></script>

    <style>
        .intoMain{
            margin-bottom: 10px;
            font-family: "Britannic Bold";
            border: solid 2px;
            display: block;
            color: beige;
        }
        table{
            display: block;
            min-width: 100%;
        }

        body{
            background-color: darkgray;
            background-image: linear-gradient(
                    to bottom,
                    rgba(255, 255, 0, 0.2),
                    rgba(0, 0, 255, 0.2)
            ), url("../resources/space.jpg");
            style="background-image:url('../resources/person.png')"
        }
        .product{
            left: 10%;
            position: absolute;
            height: 500px;
            min-height: 500px;
            width: 1500px;
        }
        .product span, a{
            color: rgba(219, 104, 255, 0);
        }

        .product span, a{
            color: rgba(219, 104, 255, 0);
            transition: color 1s;
        }


        .product{
            background-color: rgba(219, 104, 255, 0);
            transition: background-color 0.3s;
        }

        .into {
            background-color: rgba(219, 104, 255, 0);
            transition: background-color 0.3s;
        }

        .into span{
            font-size: 50px;
        }

        .into{
            background-color: rgba(102, 204, 255, 0);
            color: rgba(102, 204, 255, 0);
            min-height: 30px;
            font-size: 10px;
            vertical-align: center;
            text-align: center;
            margin-bottom: 25px;
            z-index: 1;
            overflow-wrap: normal;
        }

        .product:hover > div {
            height: 500px;
            cursor: pointer;
            border: solid 2px;
            background-color: rgba(102, 204, 255, 0.5);
        }

        th{
            color: rgba(102, 204, 255, 0);
        }

        .th {
            background-color: rgba(219, 104, 255, 1);
            transition: background-color 0.3s;
        }


        .product:hover div{
            color: rgba(5, 0, 61, 1);
        }
        .product:hover span{
            color: rgba(5, 0, 61, 1);
        }

        .product:hover .into {
            color: greenyellow;
            overflow: hidden;
            background-color: rgba(219, 104, 255, 0);
        }
    </style>
    <script type="text/javascript">
    </script>
</head>
<body>
    <div class="product">
            Контент
            <div class="into">
                <p><a href="index.php"><span>Главная</span></a></p>
            </div>
            <div class="into">
                <p><a href="../app/admin/viewOfUsers.php"><span>Список юзеров</span></a></p>
            </div>

            <table>
                <th>Логин</th>
                <th>Пароль</th>
                <th>Чёт</th>

            <?php
                $builder = "";
                foreach ($listOfPerson as $user){
                    $builder .= "<tr><td><span>" . $user->getName() . "<span></td><td><span>" . $user->getPassword() . "</span></td><td><span>" . $user->getEmail() . "</span></td><td><span>" . $user->getIp() . "</span></td></tr>";
                    echo $builder;
                }
            ?>
                <div class='into'>
                <?php
                $val = mt_rand(0, 5200);
                $it = 0;
                while(true){
                    $str = print_r(get_class_methods("App\\User"), true);
                    $current = mt_rand(0, 5200);
                    //$builder = "<tbody class='intoMain'><tr><td>$it</td><td>$current</td><td>$str</td></tr></tbody>";
                    $builder = "<tbody class='intoMain'><tr><td>$it</td><td>$current</td><td>$str</td></tr></tbody>";
                    echo $builder;
                    if($current == $val) {
                        break;
                    }
                    $it++;
                }
                ?>
                </div>
            </table>
    </div>
</body>
</html>