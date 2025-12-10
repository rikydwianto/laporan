<table border=1>
    <tr>
        <td>CABANG</td>
        <td> CENTER</td>
        <td>KEL</td>
        <td>ID NASABAH</td>
        <td>NASABAH</td>
        <td>KESALAHAN</td>
      
    </tr>


<?php 
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_karyawan = $_SESSION['id'];
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan= $_SESSION['jabatan'];
$cabang= $_SESSION['cabang'];
$id_cabang= $_SESSION['cabang'];
$su= $_SESSION['su']; 
$d = detail_karyawan($con, $id_karyawan);
$kode_cbg = $_SESSION['kode_cabang']; 
// echo $kode_cbg;exit;
require("../vendor/PHPExcel/Classes/PHPExcel.php");
$path = "../RAHASIA/perbaikan.xlsx";
$reader = PHPExcel_IOFactory::createReaderForFile($path);
$objek = $reader->load($path);
$ws = $objek->getActiveSheet();
$last_row = $ws->getHighestDataRow();
$no_input=0;
mysqli_query($con,"delete from perbaikan where id_cabang='$id_cabang'");
for($row = 0;$row<=$last_row;$row++){
    $kode_cabang = sprintf("%03d", ganti_karakter($ws->getCell("D".$row)->getValue()));
    if($kode_cabang==$kode_cbg){
        $kode_cabang = sprintf("%03d", ganti_karakter($ws->getCell("D".$row)->getValue()));
        $nasabah =  htmlspecialchars(ganti_karakter($ws->getCell("J".$row)->getValue()),ENT_QUOTES);
        $kelompok =  ganti_karakter($ws->getCell("H".$row)->getValue());
        $center =  sprintf("%03d", ganti_karakter($ws->getCell("G".$row)->getValue()));
        $kesalahan =  ($ws->getCell("K".$row)->getValue());
        $id_nasabah =  $ws->getCell("I" . $row)->getValue();
        $qcari = mysqli_query($con,"select id_karyawan from daftar_nasabah where id_cabang='$id_cabang' and id_detail_nasabah='$id_nasabah'");
        $cari_perbaikan = mysqli_num_rows($qcari);
        if($cari_perbaikan){
            $ID_K = mysqli_fetch_array($qcari);
            mysqli_query($con,"INSERT INTO `perbaikan` ( `no_center`, `GroupID`, `id_detail_nasabah`, `nama_nasabah`, `kesalahan`, `status`,`status_input`, `id_cabang`,`id_karyawan`) 
            VALUES ( '$center', '$kelompok', '$id_nasabah', '$nasabah', '$kesalahan',   'belum', NULL,'$id_cabang','$ID_K[id_karyawan]'); 
        ");
        }
        else{
            // echo"tidak ada";
        }
        
        ?>
        <tr>
            <td><?=$no_input++;?>.<?=$kode_cabang?></td>
            <td> <?=$center?></td>
            <td><?=$kelompok?></td>
            <td><?=$id_nasabah?></td>
            <td><?=$nasabah?></td>
            <td><?=$kesalahan?></td>
            <td><?php 
            if($cari_perbaikan){
                echo"ada di daftar";
            }
            else{
                echo"tidak ada";
            }
            ?></td>
        
        </tr>
        <?php


    }    
        
    
        
           
            
        
    
}
?>
</table>