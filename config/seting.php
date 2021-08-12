<?php
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'komida'; // nama databasenya
$server = $_SERVER['HTTP_HOST'];
$index =  $_SERVER['REQUEST_URI'];
$port = ":8080/";
//$url = "http://localhost:8080/laporan/";

$url = "http://".$server."/".$index;
$menu ="index.php?menu=";
 date_default_timezone_set("Asia/Bangkok");
// error_reporting(0);
 $no=1;
 $url = "http://localhost/laporan/";
//  $url = "http://192.168.227.109/laporan/";
 
?>	
