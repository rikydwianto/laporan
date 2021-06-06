<?php

$con = mysqli_connect($host, $username, $password, $db_name);
session_start();
if($con){
    echo "Koneksi Berhasil";
}
else{
    echo "koneksi gagal";
    echo $usernama.'- '.$password;
}
?> 