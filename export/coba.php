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
$path = "../RAHASIA/anggota.xlsx";
$reader = PHPExcel_IOFactory::createReaderForFile($path);
$objek = $reader->load($path);
$ws = $objek->getActiveSheet();
$last_row = $ws->getHighestDataRow();

for($row = 2;$row<=$last_row;$row++){
    $no =  $ws->getCell("B" . $row)->getValue();
    if($no==null){
        
    }
    else{
        $agt = ganti_karakter(substr($no,0,5));
        if($agt=='AGT'){
            $staff =  ganti_karakter($ws->getCell("W".$row)->getValue());
            $gabung = ganti_karakter($ws->getCell("L".$row)->getValue());
            mysqli_query($con,"INSERT INTO `temp_anggota` (`staff`, `tgl_bergabung`, `status_input`, `id_cabang`) VALUES ('$staff', '$gabung', 'belum', '$id_cabang'); ");
        }
    }
}
