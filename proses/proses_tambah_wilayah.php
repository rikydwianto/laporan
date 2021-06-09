<?php
 //panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$cabang= $_SESSION['cabang'];
$id_cabang= $_SESSION['cabang'];
$su= $_SESSION['su'];
$d = detail_karyawan($con,$id_karyawan);
if (isset($_GET['satu_desa'])) {
    $desa = wilayah($con, $_GET['desa']);
    $keca = wilayah($con, $_GET['kec']);
    $wilaya  = mysqli_query($con, "SELECT * FROM daftar_wilayah_cabang WHERE desa='$desa' and kecamatan='$keca' and id_cabang='$id_cabang' limit 0,1");
    $wilaya = mysqli_fetch_array($wilaya);
    if ($wilaya['desa'] != '') {
        $desa_T[] = $wilaya['desa'];
        alert(" Tidak bisa ditambahkan $desa Telah diinput di Database sebelumnya");
    } else {
        $q = mysqli_query($con, "INSERT INTO `daftar_wilayah_cabang` (`kecamatan`, `desa`, `id_cabang`) VALUES ('$keca', '$desa', '$cabang');");
        alert(" Desa $desa  Berhasil Ditambahkan $keterangan");
    }




    
}
if (isset($_GET['kecamatan_desa'])) {
    $idkec = $_GET['kec'];
    $qdesa2  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,8)='$idkec' AND CHAR_LENGTH(kode)=13 ORDER BY nama");
    $keca = wilayah($con, $idkec);
    while ($desa2 = mysqli_fetch_array($qdesa2)) {
        $desa = wilayah($con, $desa2['kode']);

        $wilaya  = mysqli_query($con, "SELECT * FROM daftar_wilayah_cabang WHERE desa='$desa' and kecamatan='$keca' and id_cabang='$id_cabang' limit 0,1");
        $wilaya = mysqli_fetch_array($wilaya);
        if ($wilaya['desa'] != '') {
            $desa_T[] = $wilaya['desa'];
        } else {
            $q = mysqli_query($con, "INSERT INTO `daftar_wilayah_cabang` (`kecamatan`, `desa`, `id_cabang`) VALUES ('$keca', '$desa', '$cabang');");
        }
    }
    if (count($desa_T) > 0) $keterangan = "Kecuali " . implode(", ", $desa_T,) . "Sudah Pernah di input";
    else $keterangan = "";

    alert("Semua Desa Di Kecamatan : $keca  Berhasil Ditambahkan $keterangan");
}


pindah("$url$menu"."daftar_wilayah");
?>