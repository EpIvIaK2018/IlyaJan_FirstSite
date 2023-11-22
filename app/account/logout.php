<?php
session_start();
ob_start();
$_SESSION = array();
unset($_SESSION);
session_unset();
session_destroy();
exit("<html><head><title>Загрузка..</title><meta http-equiv='refresh' content='0; URL=/IlyaJan/public/index.php'></head></html>");
