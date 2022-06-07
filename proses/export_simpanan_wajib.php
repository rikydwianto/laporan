<a href="<?=$url.$menu?>blk_input" class="btn btn-success"> Kembali</a>
<?php
ini_set('max_execution_time',0);
if(isset($_GET['download'])){
    $download = $_GET['download'];
    $back_dir    ="/export/excel/par/";
    $file =$back_dir.$download;
    pindah($url.$file);
    pindah($url.$menu."blk_input");
    
}
// // // // $file = $_FILES['file']['tmp_name'];
// // // // $path = $file;
// // // $reader = PHPExcel_IOFactory::createReaderForFile($path);
// // $objek = $reader->load($path);
// $ws = $objek->getActiveSheet();
// $last_row = $ws->getHighestDataRow();
require './vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$baris = 3;
$no1=1;

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getColumnDimension('H')->setAutoSize(true);
$sheet->getColumnDimension('I')->setAutoSize(true);
$sheet->getColumnDimension('J')->setAutoSize(true);
$sheet->getColumnDimension('K')->setAutoSize(true);
$sheet->getColumnDimension('L')->setAutoSize(true);
$sheet->getColumnDimension('M')->setAutoSize(true);
$sheet->getColumnDimension('N')->setAutoSize(true);
$sheet->getColumnDimension('O')->setAutoSize(true);
$sheet->getColumnDimension('P')->setAutoSize(true);
$sheet->getColumnDimension('Q')->setAutoSize(true);
$sheet->setTitle('DATA PAR');


// $sheet->setCellValue('B1', 'DATA PAR ');
$sheet->setCellValue('A2', 'NO');
$sheet->setCellValue('B2', 'NASABAH');
$sheet->setCellValue('C2', 'ID');
$sheet->setCellValue('D2', 'CENTER');
$sheet->setCellValue('E2', 'LOAN');
$sheet->setCellValue('F2', 'PEMB');
$sheet->setCellValue('G2', 'DISBURSE DATE');
$sheet->setCellValue('H2', 'KE');
$sheet->setCellValue('I2', 'RILL');
$sheet->setCellValue('J2', 'AMOUNT');
$sheet->setCellValue('K2', 'O.S');
$sheet->setCellValue('L2', 'CICILAN');
$sheet->setCellValue('M2', 'WAJIB');
$sheet->setCellValue('N2', 'SUKARELA');
$sheet->setCellValue('O2', 'PENSIUN');
$sheet->setCellValue('P2', 'HARI RAYA');
$sheet->setCellValue('Q2', 'TOTAL SIMPANAN');
$sheet->setCellValue('R2', 'PAR');
$sheet->setCellValue('S2', '1 Angsuran');
$sheet->setCellValue('T2', 'Tanpa Margin');
$sheet->setCellValue('U2', 'STAFF');
$sheet->setCellValue('V2', 'HARI');
$spreadsheet->createSheet();



?>
<h2>BLK </h2>
<?php

$cek_tgl = mysqli_query($con,"SELECT max(tgl_input) AS tgl FROM deliquency WHERE id_cabang='$id_cabang' ORDER BY tgl_input DESC");
$tgl_delin = mysqli_fetch_array($cek_tgl);
$tgl_delin = $tgl_delin['tgl'];
$no1 = 1;
$cek_delin1 = mysqli_query($con,"SELECT  deliquency.* FROM deliquency  WHERE tgl_input='$tgl_delin' AND id_cabang='$id_cabang'  ");
$cek_delin = mysqli_num_rows($cek_delin1);
// error_reporting(0);
  $nor=1;
while($r = mysqli_fetch_array($cek_delin1)){
   
               

                $sheet->setCellValue('A'.$baris, $nor++);
                $sheet->setCellValue('b'.$baris, $nasabah);
                $sheet->setCellValue('c'.$baris, $id_nasabah);
                $sheet->setCellValue('D'.$baris, $nama['no_center']);
                $sheet->setCellValue('e'.$baris, $loan);
                $sheet->setCellValue('f'.$baris, $kode_pemb);
                $sheet->setCellValue('g'.$baris, $tgl_dis);
                $sheet->setCellValue('h'.$baris, $ke);
                $sheet->setCellValue('i'.$baris, $rill);
                $sheet->setCellValue('j'.$baris, $amount);

                //SHEET 2


                $baris++;
            
            

        }
        $kode_cabang  = $_SESSION['kode_cabang'];
        $text ="$nik $kode_cabang sedang mengunduh alasan PAR";
    
        $url_tele = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&reply_message_id=214&force_reply=true";
        file_get_contents($url_tele);
?>
</tbody>

</table>
<?php 
// $spreadsheet->getActiveSheet()->setTitle('Data Par');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);



$writer = new Xlsx($spreadsheet);
$filename=$_SESSION['kode_cabang'].'-Alasan PAR new - '.date("Y-m-d").' - '. time() ;
$writer->save('export/excel/par/'.$filename.'.xlsx');
pindah($url."blk.php?download=".$filename.".xlsx");
