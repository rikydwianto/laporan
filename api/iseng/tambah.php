<?php 
require_once "../../config/seting.php";
require_once "../../config/koneksi.php";
require_once("../../proses/fungsi.php");
require_once("../../model/model.php");
$tgl = $_GET['tgl'];
$nik = $_GET['nik'];
$center = $_GET['no_center'];
$bayar = $_GET['bayar'];
$loan = $_GET['loan'];
$id = $_GET['id'];
$cabang = $_GET['cabang'];
$ceke = mysqli_query($con,"select * from temp_bayar where id_detail_anggota='$id' and no_center='$center' and tgl_bayar='$tgl' and nik='$nik' and kode_cabang='$cabang' and jenis='$loan'");
echo "select * from temp_bayar where id_detail_nasabah='$id' and no_center='$center' and tgl_bayar='$tgl' and nik='$nik' and kode_cabang='$cabang' and jenis='$loan'";
echo mysqli_error($con);
if(mysqli_num_rows($ceke)){
    $tr = mysqli_fetch_array($ceke);
    mysqli_query($con,"delete from temp_bayar where id_bayar='$tr[id_bayar]'");
    mysqli_query($con,"INSERT INTO `temp_bayar` (`bayar`, `no_center`, `id_detail_anggota`, `tgl_bayar`, `nik`, `jenis`,`kode_cabang`) 
    VALUES ('$bayar', '$center', '$id', '$tgl', '$nik', '$loan','$cabang'); 
    ");
}
else{

    mysqli_query($con,"INSERT INTO `temp_bayar` (`bayar`, `no_center`, `id_detail_anggota`, `tgl_bayar`, `nik`, `jenis`,`kode_cabang`) 
    VALUES ('$bayar', '$center', '$id', '$tgl', '$nik', '$loan','$cabang'); 
    ");
}
?>
