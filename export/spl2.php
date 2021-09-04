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
$d = detail_karyawan($con,$id_karyawan);
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;



$filename = "spl-".date("Y-m-d").'-'.rand(0,100);
$_SESSION['nama_file']=$filename;
$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('DATA STAFF');

$sheet->setCellValue('A1', 'DATA MONITORING PER STAFF');
$sheet->setCellValue('A2', 'NO');
$sheet->setCellValue('b2', 'NIK STAFF');
$sheet->setCellValue('c2', 'NAMA STAFF');
$sheet->setCellValue('d2', 'MONITORING');

$total_monitoring = 0;
$baris = 3;
$no1=1;
    $sheet->setCellValue('A'.$baris, $no1);
    $sheet->setCellValue('B'.$baris, "NAMA")->setWidth('23');
    $sheet->setCellValue('C'.$baris, "s");
    


$writer = new Xlsx($spreadsheet);
$writer->save('excel/'.$filename.'.xlsx');

// unlink("excel/".$filename.".xlsx");
// sleep(2);
?>


<script>
    let url = "<?=$url?>"; 
    let nama = "<?=$_SESSION['nama_file']?>.xlsx"; 
    // window.href.location= url + "export/" + nama;

   window.close();
</script>