<?php 
require_once "../../config/seting.php";
require_once "../../config/koneksi.php";
require_once("../../proses/fungsi.php");
require_once("../../model/model.php");
 header('Access-Control-Allow-Origin: *'); 
define('AKHIR',"</br>");
$nik = $_POST['nik'];
$kode_cabang =  $_POST['cabang'];
$tgl  = $_POST['tgl'];
$data = $_POST['data_center'];

$q = mysqli_query($con,"select * from temp_bayar_json where nik='$nik' and kode_cabang='$kode_cabang' and tgl='$tgl'");
if(mysqli_num_rows($q)){
    $text = "update temp_bayar_json set json='$data' where  nik='$nik' and kode_cabang='$kode_cabang' and tgl='$tgl' ";
$query = mysqli_query($con,$text);
}
else{
    $text = "INSERT into temp_bayar_json(kode_cabang,nik,tgl,json,status) VALUES
                ('$kode_cabang','$nik','$tgl','$data','belum')";
    $query = mysqli_query($con,$text);
    echo mysqli_error($con);
}

?>