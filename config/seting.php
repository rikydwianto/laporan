<?php
$host = 'localhost';
$username = 'root';
$password = 'root';
$db_name = 'komida1'; // nama databasenya
$server = $_SERVER['HTTP_HOST'];
$index =  $_SERVER['REQUEST_URI	'];
$port = ":8080/";
//$url = "http://localhost:8080/laporan/";

$url = "http://".$server."/".$index;
$menu ="index.php?menu=";
 date_default_timezone_set("Asia/Bangkok");
 error_reporting(0);
 $no=1;
 $url = "http://localhost:8080/laporan/";
?>	
