<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
if( $curl = curl_init() ) {
    curl_setopt($curl,CURLOPT_URL,'http://myrusakov.ru');
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_NOBODY,false);
    curl_setopt($curl,CURLOPT_HEADER,true);
    $out = curl_exec($curl);
    echo $out;
    curl_close($curl);
}
?>
</body>
</html>


