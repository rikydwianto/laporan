<?php
 header('Content-Type: application/json; charset=utf8');
 //panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

 //query tabel produk
 $cab = $_GET['cab'];
 $id_cabang=$cab;
 $tgl_max= mysqli_query($con,"select tgl_input from deliquency where id_cabang='$id_cabang' order by tgl_input desc ");
 $tgl_max = mysqli_fetch_array($tgl_max)['tgl_input'];
 $sql="SELECT COUNT(SUBSTRING_INDEX(loan, '-', 1)) AS total, SUBSTRING_INDEX(loan, '-', 1) as kode FROM deliquency WHERE tgl_input = '$tgl_max' AND id_cabang = '$id_cabang'
GROUP BY SUBSTRING_INDEX(loan, '-', 1)";
// echo $sql;
 $query=mysqli_query($con,$sql);
 //data array
 $array=array();
 echo mysqli_error($con);
 while($data=mysqli_fetch_assoc($query)) $array[]=$data; 
 
//mengubah data array menjadi json
 echo json_encode($array);
?>
