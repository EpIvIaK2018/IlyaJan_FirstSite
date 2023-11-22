<?php
require_once ("../vendor/autoload.php");
require 'header.php';
?>
<div class="main">
    <div class="header2">
        <div class="frame">
            <a href="https://google.com"><h1>TEETÄ TILAUKSESTA</h1></a>
            <div class="image-desc">
                <p><a href="http://localhost:63342/IlyaJan/app/order/order.php?variant=green&title=Tee%20jasmiinin%20kanssa">Tee jasmiinin kanssa</a><br /></p>
                <p><a href="http://localhost:63342/IlyaJan/app/order/order.php?variant=red&title=Tee%20appelsiininkuorella">Tee appelsiininkuorella</a><br /></p>
                <p><a href="http://localhost:63342/IlyaJan/app/order/order.php?variant=limon&title=Tee%20appelsiininkuorella">Tee ruusun terälehdillä</a><br /></p>
                <p><a href="http://localhost:63342/IlyaJan/app/order/order.php?variant=last&title=Tee%20mausteinen">Tee mausteinen</a><br /></p>
            </div>
        </div>


        <div class="pirista">
            <li><a href="google.com"><span class="textIntoHead">piristää</span></a></li>
            <li><a href="google.com"><span class="textIntoHead">eväste</span></a></li>
            <li><a href="google.com"><span class="textIntoHead">toinen</span></a></li>
        </div>


        <div style="position: relative; right: 2vw; bottom: -0.2vh">
            <a href="../app/order/payment.php?step=1">
                <img src="../resources/symka.png" alt="HTML tutorial" style="width:4vw;height: 4vw;">
            </a>
            <?php
            if(isset($_SESSION['cart'])){
                if($_SESSION['cart'] > 0) {
                    echo "<div id='mark'></div>";
                }
            }
            ?>
        </div>
    </div>

    <?php
    if (isset($_SESSION['user_id'])){
        //echo "<h1>УРААААААААА авторизация</h1>";
    }
    ?>
    <?php
    //echo "<a style='text-decoration: none; color: white;' href='/IlyaJan/app/admin/viewOfUsers.php'>Ссылка</a>";
    ?>
    <div class='position-left-main'>
        <div class='subtitle'>
            <p>
                Valitsemalla teetä teet oikean<br />
                valinnan. Meillä on vain<br />
                parhaat lajikkeet.<br />
            </p>
        </div>
    </div>
<?php
require_once 'footer.html';
?>