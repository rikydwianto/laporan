<?php


session_start();
if(file_exists("config/seting.php")){
    $con = mysqli_connect($host, $username, $password, $db_name);
}
else{
    
    include("config/backset");
    $con = mysqli_connect($host, $username, $password, $db_name);
}
?> 