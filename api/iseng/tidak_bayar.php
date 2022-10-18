<?php 
require_once "../../config/seting.php";
require_once "../../config/koneksi.php";
require_once("../../proses/fungsi.php");
require_once("../../model/model.php");
 header('Access-Control-Allow-Origin: *'); 
echo var_dump($_GET);
$tgl = $_GET['tgl'];
$nik = $_GET['nik'];
$center = $_GET['no_center'];
$id = $_GET['id'];
$id_detail = $id;
$nama=$_GET['nama'];
$angsuran=$_GET['angsuran'];
$ket=$_GET['ket'];
$balance=$_GET['balance'];
$sukarela=$_GET['sukarela'];
$cabang = $_GET['cabang'];
$cab  = mysqli_query($con,"select * from cabang where kode_cabang='$cabang'");
$cab = mysqli_fetch_array($cab);
$id_cabang = $cab['id_cabang'];

$ceke = mysqli_query($con,"select * from tidak_bayar where id_detail_anggota='$id' and tanggal='$tgl' and nik='$nik' and kode_cabang='$cabang'");
echo mysqli_error($con);
if(mysqli_num_rows($ceke)){
    $tr = mysqli_fetch_array($ceke);
    mysqli_query($con,"delete from tidak_bayar where id='$tr[id]'");
    mysqli_query($con,"INSERT INTO `tidak_bayar` (`id`, `id_detail_nasabah`, `no_center`, `nik`, `nama`, `angsuran`, `sukarela`, `keterangan`, `balance`, `tanggal`, `kode_cabang`)
     VALUES (NULL, '$id_detail', '$center', '$nik', '$nama', '$angsuran', '$sukarela', '$ket', '$balance', '$tgl', '$cabang');
 
    ");
}
else{

    mysqli_query($con,"INSERT INTO `tidak_bayar` (`id`, `id_detail_nasabah`, `no_center`, `nik`, `nama`, `angsuran`, `sukarela`, `keterangan`, `balance`, `tanggal`, `kode_cabang`)
    VALUES (NULL, '$id_detail', '$center', '$nik', '$nama', '$angsuran', '$sukarela', '$ket', '$balance', '$tgl', '$cabang');

   ");
}
?>
