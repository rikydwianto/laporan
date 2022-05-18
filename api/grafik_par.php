<?php
 header('Content-Type: application/json; charset=utf8');
 //panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

 //query tabel produk
 $cab = aman($con,$_GET['cab']);
 $id_cabang=$cab;
 $sql="SELECT  tgl_input,count(*) as hitung, sum(sisa_saldo) as total_par FROM deliquency where id_cabang='$id_cabang' group by tgl_input order by tgl_input desc limit 0, 10";
// echo $sql;
 $query=mysqli_query($con,$sql);
 //data array
 $array=array();
 echo mysqli_error($con);
 while($data=mysqli_fetch_assoc($query)) $array[]=$data; 
 
//mengubah data array menjadi json
 echo json_encode($array);
?>
