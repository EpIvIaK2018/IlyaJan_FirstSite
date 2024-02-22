<?php
require 'header.php';
?>
<div class="main">
    <div class="header2">
        <div class="frame">
            <div class="listDown"><a href="https://google.com"><h1>TEETÄ TILAUKSESTA</h1></a></div>
            <div class="image-desc">
                <p><a href="/app/order/order.php?variant=green&title=Tee%20jasmiinin%20kanssa">Tee jasmiinin kanssa</a><br /></p>
                <p><a href="/app/order/order.php?variant=red&title=Tee%20appelsiininkuorella">Tee appelsiininkuorella</a><br /></p>
                <p><a href="/app/order/order.php?variant=limon&title=Tee%20appelsiininkuorella">Tee ruusun terälehdillä</a><br /></p>
                <p><a href="/app/order/order.php?variant=last&title=Tee%20mausteinen">Tee mausteinen</a><br /></p>
            </div>
        </div>

        <div class="pirista" style="top: 3.8vh;">
            <li><a href="google.com"><span class="textIntoHead">piristää</span></a></li>
            <li><a href="google.com"><span class="textIntoHead">eväste</span></a></li>
            <li><a href="google.com"><span class="textIntoHead">toinen</span></a></li>
        </div>


        <div style="position: relative; right: 2vw; bottom: -0.4vh">
            <form style="position: relative; top: 2vh;" id="myform" action="../app/order/payment.php" id="my_form" method="POST">
                <a style='text-decoration: none; color: white;' href='/app/order/payment.php?step=1'>
                    <img src="../resources/symka.png" alt="HTML tutorial" style="width:4vw;height: 4vw;">
                </a>
            </form>
        </div>
    </div>
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