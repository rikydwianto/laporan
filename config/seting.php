<?php

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || '::1') {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $db_name = 'komida'; // nama databasenya
    $server = $_SERVER['HTTP_HOST'];
    $index =  $_SERVER['REQUEST_URI'];
    $port = ":8080/";
    //$url = "http://localhost:8080/laporan/";

    $url = "http://" . $server . "/" . $index;
    $menu = "index.php?menu=";
    date_default_timezone_set("Asia/Bangkok");
    error_reporting(0);
    $no = 1;
    $url = "http://localhost/laporan/";
} else {
    $host = 'localhost';
    $username = 'komw2213';
    $password = '8dzX23xCGDxi41';
    $db_name = 'komw2213_komida'; // nama databasenya
    $server = $_SERVER['HTTP_HOST'];
    $index =  $_SERVER['REQUEST_URI'];
    $port = ":8080/";

    $url = "http://" . $server . "/" . $index;
    $menu = "index.php?menu=";
    date_default_timezone_set("Asia/Bangkok");
    // error_reporting(0);
    $no = 1;
    $url = "https://laporan.komidapagaden.my.id/";
}
