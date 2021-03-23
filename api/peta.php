<?php
 header('Content-Type: application/json; charset=utf8');
 //panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

 //query tabel produk
 $sql="SELECT lokasi.*,karyawan.nama_karyawan FROM lokasi join karyawan on lokasi.id_karyawan=karyawan.id_karyawan";
 $query=mysqli_query($con,$sql);

//data array
 $array=array();
 while($data=mysqli_fetch_assoc($query)) $array[]=$data; 
 
//mengubah data array menjadi json
 echo json_encode($array);
?>
