<?php 
require_once "../../config/seting.php";
require_once "../../config/koneksi.php";
require_once("../../proses/fungsi.php");
require_once("../../model/model.php");
 header('Access-Control-Allow-Origin: *'); 
define('AKHIR',"</br>");
$kode_cabang =  $_POST['cabang'];
$tgl  = $_POST['tgl'];
$jenis  = $_POST['ket'];
$data = urldecode($_POST['data']);
$waktu = date("Y-m-d H:i:s");
$index = $_POST['nomor'];

$q = mysqli_query($con,"select * from sync_center where nomor='$index' and jenis='$jenis' and kode_cabang='$kode_cabang' and tgl='$tgl'");
if(mysqli_num_rows($q)){
    $text = "update sync_center set data_json='$data',waktu='$waktu' where nomor='$index' and jenis='$jenis' and  kode_cabang='$kode_cabang' and tgl='$tgl' ";
$query = mysqli_query($con,$text);
}
else{
    $text = "INSERT into sync_center(kode_cabang,tgl,data_json,jenis,waktu,nomor) VALUES
                ('$kode_cabang','$tgl','$data','$jenis','$waktu','$index')";
    $query = mysqli_query($con,$text);
    echo mysqli_error($con);
  
}
echo strlen($data);
?>