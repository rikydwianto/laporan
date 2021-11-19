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
$path = "../RAHASIA/blk.xlsx";
$reader = PHPExcel_IOFactory::createReaderForFile($path);
$objek = $reader->load($path);
$ws = $objek->getActiveSheet();
$last_row = $ws->getHighestDataRow();

for($row = 7;$row<=$last_row;$row++){
    $kode_pemb =  $ws->getCell("C" . $row)->getValue();
    if($kode_pemb==null){
        
    }
    else{
        $agt = (substr($kode_pemb,0,3));
        // echo $agt;
        if($kode_pemb==' PU ' || $kode_pemb==' PMB ' || $kode_pemb==' PSA ' || $kode_pemb==' PRR ' || $kode_pemb==' PPD '  ){
            $id_nasabah =  $ws->getCell("A" . $row)->getValue();
            
            $nasabah =  ($ws->getCell("B".$row)->getValue());
            // $tgl = $ws->getCell("I".$row)->getValue();
            // $tgl =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl));
            $pokok = (int)ganti_karakter($ws->getCell("I".$row)->getValue());
            $margin = (int)ganti_karakter($ws->getCell("J".$row)->getValue());
            $pensiun = (int)ganti_karakter($ws->getCell("S".$row)->getValue());
            $sukarela = (int)ganti_karakter($ws->getCell("p".$row)->getValue());
            
        }
    }
}
