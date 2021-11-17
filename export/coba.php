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
require("../vendor/PHPExcel/Classes/PHPExcel.php");
$path = "../RAHASIA/nasabah_keluar.xlsx";
$reader = PHPExcel_IOFactory::createReaderForFile($path);
$objek = $reader->load($path);
$ws = $objek->getActiveSheet();
$last_row = $ws->getHighestDataRow();

for($row = 4;$row<=$last_row;$row++){
    $no_id =  $ws->getCell("D" . $row)->getValue();
    if($no_id==null){
        
    }
    else{
        $agt = (substr($no_id,0,3));
        // echo $agt;
        if($agt=='AGT'){
            $id_nasabah =  $ws->getCell("D" . $row)->getValue();
            $ID = explode("-",$id_nasabah)[1];
            
            $nasabah =  ($ws->getCell("E".$row)->getValue());
            $alasan = ($ws->getCell("J".$row)->getValue());
            $staff = ($ws->getCell("A".$row)->getValue());
            $ID = sprintf("%0d",$ID);
            $center = $ws->getCell("C".$row)->getValue();
            $tgl = $ws->getCell("I".$row)->getValue();
            $tgl =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl));
            $center = str_replace(" ","",explode("/",$center)[0]);
            $cari_staff  = mysqli_query($con,"select * from center join karyawan on karyawan.id_karyawan=center.id_karyawan where no_center='$center'");
            $cari_staff = mysqli_fetch_array($cari_staff);
            // echo $no++.$cari_staff['nama_karyawan']."<br/>";
            // echo $no++.$id_nasabah.'  -  '.$nasabah." - ".$alasan."<br/>";
            // mysqli_query($con,"INSERT INTO `temp_anggota` (`staff`, `tgl_bergabung`, `status_input`, `id_cabang`) VALUES ('$staff', '$gabung', 'belum', '$id_cabang'); ");
        }
    }
}
