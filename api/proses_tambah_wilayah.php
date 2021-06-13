<?php
//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$cabang = $_SESSION['cabang'];
$id_cabang = $_SESSION['cabang'];
$su = $_SESSION['su'];

if (isset($_GET['kecamatan_desa'])) {
    $idkec = $_GET['kec'];
    $total_desa =  count($_SESSION['nama_desa'][$idkec]);
    $keca = $_SESSION['nama_kec'][$idkec];
    for ($i = 0; $i <= $total_desa; $i++) {
        $desa =  $_SESSION['nama_desa'][$idkec][$i];
        // while ($desa2 = mysqli_fetch_array($qdesa2)) 
        
        $wilaya  = mysqli_query($con, "SELECT * FROM daftar_wilayah_cabang WHERE desa='$desa' and kecamatan='$keca' and id_cabang='$id_cabang' limit 0,1");
        $wilaya = mysqli_fetch_array($wilaya);
        if ($wilaya['desa'] != '') {
            $desa_T[] = $wilaya['desa'];
        } else {
            if (!empty($desa)) {
                $q = mysqli_query($con, "INSERT INTO `daftar_wilayah_cabang` (`kecamatan`, `desa`, `id_cabang`) VALUES ('$keca', '$desa', '$cabang');");
            }
        }
    }
    if (count($desa_T) > 0) $keterangan = " Kecuali " . implode(" , ", $desa_T,) . "Sudah Pernah di input";
    else $keterangan = "";
    if ($q) {
        echo ("Semua Desa Di Kecamatan : $keca  Berhasil Ditambahkan $keterangan");
    } else {
        echo ("GAGAL : Sudah pernah diinput $keterangan");
    }
}
unset($_SESSION['nama_desa'][$idkec]);
unset($_SESSION['nama_kec'][$idkec]);
