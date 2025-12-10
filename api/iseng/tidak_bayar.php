<?php 
require_once "../../config/seting.php";
require_once "../../config/koneksi.php";
require_once("../../proses/fungsi.php");
require_once("../../model/model.php");
 header('Access-Control-Allow-Origin: *'); 
$tgl = $_GET['tgl'];
$nik = $_GET['nik'];
$center = $_GET['no_center'];
$id = urldecode($_GET['id']);
$id_detail = $id;
$nama=$_GET['nama'];
$angsuran=$_GET['angsuran'];
$ket=$_GET['ket'];
$loan=$_GET['loan'];
$balance=$_GET['balance'];
$sukarela=$_GET['sukarela'];
$cabang = $_GET['cabang'];
$cab  = mysqli_query($con,"select * from cabang where kode_cabang='$cabang'");
$cab = mysqli_fetch_array($cab);
$id_cabang = $cab['id_cabang'];
echo $id_cabang;
$ceke = mysqli_query($con,"select * from tidak_bayar where id_detail_nasabah='$id' and tanggal='$tgl'");
echo mysqli_error($con);
if(mysqli_num_rows($ceke)){
    $tr = mysqli_fetch_array($ceke);
    mysqli_query($con,"delete from tidak_bayar where id_detail_nasabah='$id' and tanggal='$tgl'");
    mysqli_query($con,"INSERT INTO `tidak_bayar` (`id`, `id_detail_nasabah`, `no_center`, `nik`, `nama`, `angsuran`, `sukarela`, `keterangan`, `balance`, `tanggal`, `kode_cabang`,`id_cabang`,`loanno`)
     VALUES (NULL, '$id_detail', '$center', '$nik', '$nama', '$angsuran', '$sukarela', '$ket', '$balance', '$tgl', '$cabang','$id_cabang','$loan');
 
    ");
}
else{

    mysqli_query($con,"INSERT INTO `tidak_bayar` (`id`, `id_detail_nasabah`, `no_center`, `nik`, `nama`, `angsuran`, `sukarela`, `keterangan`, `balance`, `tanggal`, `kode_cabang`,`id_cabang`,`loanno`)
    VALUES (NULL, '$id_detail', '$center', '$nik', '$nama', '$angsuran', '$sukarela', '$ket', '$balance', '$tgl', '$cabang','$id_cabang','$loan');

   ");
}
?>
