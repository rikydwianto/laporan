<?php
 //panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$center = sprintf("%03d",$_GET['center']);
$id_cabang = $_GET['cab'];
 //query tabel produk
 $sql="SELECT no_center,hari,jam_center,status_center,anggota_center,member_center,karyawan.nama_karyawan,
 center.latitude,center.longitude,cabang.nama_cabang
  FROM center 
  join karyawan on karyawan.id_karyawan=center.id_karyawan 
  join cabang on cabang.id_cabang = karyawan.id_cabang
  where  center.no_center = '$center' and center.id_cabang='$id_cabang'
  ";
 // echo $sql;
 $query=mysqli_query($con,$sql);
if(mysqli_num_rows($query)){    
    $data = mysqli_fetch_array($query);
    ?>
    <?=$data['nama_karyawan']?> <br/>
    <?=$data['hari']?>(<?=$data['anggota_center']?>)/
    <?=$data['jam_center']?>
    
    <?php
}
else{
    echo "Center Tidak ditemukan";
}
//data array
 
//mengubah data array menjadi json
?>
