<?php
 header('Content-Type: application/json; charset=utf8');
 //panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

 //query tabel produk
 $cab = $_GET['cab'];
 $sql="SELECT * from grafik where id_cabang='$cab' order by id_grafik desc limit 0,12 ";
 $query=mysqli_query($con,$sql);

//data array
 $array=array();
 while($data=mysqli_fetch_assoc($query)) $array[]=$data; 
 
//mengubah data array menjadi json
 echo json_encode($array);
?>
