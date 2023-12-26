<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" type="text/css" href="/app/style.css">
    <script src=https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js></script>
    <script>
        var js;
        var obj = {}
        for (var i = 0; i < localStorage.length; i++) {
            var key = localStorage.key(i);
            obj[key] = localStorage.getItem(key);
        }
        js = JSON.stringify(obj);
    </script>
</head>

<?php
$mainData = getJson_decode();

























