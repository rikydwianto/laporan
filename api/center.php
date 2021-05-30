<?php
 header('Content-Type: application/json; charset=utf8');
 //panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

 //query tabel produk
 $sql="SELECT no_center,hari,jam_center,status_center,anggota_center,member_center,karyawan.nama_karyawan,
 center.latitude,center.longitude,cabang.nama_cabang
  FROM center 
  join karyawan on karyawan.id_karyawan=center.id_karyawan 
  join cabang on cabang.id_cabang = karyawan.id_cabang
  where center.latitude !='' and center.longitude !='' ";
 // echo $sql;
 $query=mysqli_query($con,$sql);

//data array
 $array=array();
 while($data=mysqli_fetch_assoc($query)) $array[]=$data; 
 
//mengubah data array menjadi json
 echo json_encode($array);
?>
