<?php
 header('Content-Type: application/json; charset=utf8');
 //panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_cabang = $_SESSION['cabang'];
 //query tabel produk
 $sql="SELECT no_center,hari,jam_center,status_center,anggota_center,member_center,karyawan.nama_karyawan,
 center.latitude,center.longitude,cabang.nama_cabang
  FROM center 
  JOIN karyawan ON karyawan.id_karyawan=center.id_karyawan 
  JOIN cabang ON cabang.id_cabang = center.id_cabang
  WHERE
  center.latitude !='' AND center.longitude !='' AND center.latitude !='null' AND center.longitude !='null' and center.id_cabang='$id_cabang'";
 // echo $sql;
 $query=mysqli_query($con,$sql);

//data array
 $array=array();
 while($data=mysqli_fetch_assoc($query)) $array[]=$data; 
 
//mengubah data array menjadi json
 echo json_encode($array);
?>
