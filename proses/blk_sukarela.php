<a href="<?=$url.$menu?>blk_input" class="btn btn-success"> Kembali</a>
<?php

if(isset($_GET['download'])){
    $download = $_GET['download'];
    $back_dir    ="/export/excel/par/";
    $file =$back_dir.$download;
    pindah($url.$file);
    pindah($url.$menu."blk_input");
    
}
$file = $_FILES['file']['tmp_name'];
$path = $file;
$reader = PHPExcel_IOFactory::createReaderForFile($path);
$objek = $reader->load($path);
$ws = $objek->getActiveSheet();
$last_row = $ws->getHighestDataRow();
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
$sheet->setTitle('DATA STAFF');


$sheet->setCellValue('B1', 'DATA PAR ');
$sheet->setCellValue('A2', 'NO');
$sheet->setCellValue('b2', 'ID/CENTER');
$sheet->setCellValue('c2', 'NASABAH');
$sheet->setCellValue('d2', 'PEMB');
$sheet->setCellValue('e2', 'KE');
$sheet->setCellValue('f2', 'RILL');
$sheet->setCellValue('g2', 'AMOUNT');
$sheet->setCellValue('h2', 'O.S');
$sheet->setCellValue('i2', 'CICILAN');
$sheet->setCellValue('j2', 'CICILAN * PAR');
$sheet->setCellValue('k2', 'SUKARELA');
$sheet->setCellValue('l2', 'PAR');
$sheet->setCellValue('m2', 'SISA');
$sheet->setCellValue('n2', 'STAFF');
$spreadsheet->createSheet();
// Add some data
$shee = $spreadsheet->setActiveSheetIndex(1);
$sheet->setCellValue('A1', 'DATA PAR');
?>
<h2>BLK </h2>
<table id='data_blk' class='table-bordered'>
    <thead>
        <tr>
            <!-- <th>NO</th> -->
            <th>CTR</th>
            <th>ID</th>
            <th>NAMA</th>
            <th> </th>
            <th>KE</th>
            <th>RILL</th>
            <th>AMOUNT</th>
            <th>O.S</th>
            <th>CICILAN</th>
            <th>WAJIB</th>
            <th>SUKARELA</th>
            <th>PENSIUN</th>
            <th>PAR</th>
            <th>1 angsuran</th>
            <th>tanpa Margin</th>
            <th>Warna</th>

            <th>#</th>
        </tr>
    </thead>
    <tbody>
<?php
for($row = 7;$row<=$last_row;$row++){
    $kode_pemb =  $ws->getCell("C" . $row)->getValue();
    if($kode_pemb==null){
        
    }
    else{
        $agt = (substr($kode_pemb,0,3));
        // echo $agt;
        $ket1="";
        $kode_pemb = ganti_karakter($kode_pemb);
        if($kode_pemb=='PU' || $kode_pemb=='PMB' || $kode_pemb=='PSA' || $kode_pemb=='PRR' || $kode_pemb=='PPD'  ){
            $id_nasabah =  ganti_karakter($ws->getCell("A" . $row)->getValue());
           if($id_nasabah!=null){
            $nasabah =  ganti_karakter($ws->getCell("B".$row)->getValue());
            $pensiun =  (int)ganti_karakter(str_replace(",","",$ws->getCell("S".$row)->getValue()));
            $sukarela = (int)ganti_karakter(str_replace(",","",$ws->getCell("P".$row)->getValue()));
            $wajib = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$row)->getValue()));
            
           }
           else {
              
                
                    $baris_baru = $row-1;
                    $nasabah =  ganti_karakter($ws->getCell("B".$baris_baru)->getValue());
                    // $pensiun =  (int)ganti_karakter(str_replace(",","",$ws->getCell("S".$baris_baru)->getValue()));
                    $id_nasabah =  ganti_karakter($ws->getCell("A" . $baris_baru)->getValue());
                    // $sukarela = (int)ganti_karakter(str_replace(",","",$ws->getCell("P".$baris_baru)->getValue()));
                    // $wajib = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$baris_baru)->getValue()));
                if($nasabah==null || $nasabah==" "){
                    $baris_baru = $row - 1;
                    $id_nasabah =  ganti_karakter($ws->getCell("A" . $baris_baru)->getValue());
                }
                    
                
           }
         
           $ID = sprintf("%0d",$id_nasabah);
            
            $pokok =    (int)ganti_karakter(str_replace(",","",$ws->getCell("I".$row)->getValue()));
            $margin =   (int)ganti_karakter(str_replace(",","",$ws->getCell("J".$row)->getValue()));
            $amount =   (int)ganti_karakter(str_replace(",","",$ws->getCell("F".$row)->getValue()));
            $os =       (int)ganti_karakter(str_replace(",","",$ws->getCell("G".$row)->getValue()));
            $ke =       (int)ganti_karakter(str_replace(",","",$ws->getCell("D".$row)->getValue()));
            $rill =     (int)ganti_karakter(str_replace(",","",$ws->getCell("E".$row)->getValue()));

            $wajib_minggu=0;
            if($kode_pemb=='PU' || $kode_pemb=='PMB'){
                $wajib_minggu = $amount /1000;
                if(is_float($wajib_minggu )){
                    $pecah=explode(".",$wajib_minggu);
                    $awal = $pecah[0];
                    $wajib_minggu = ($awal + 1) * 1000;

                }
                else{
                    $wajib_minggu = $wajib_minggu * 1000 ;
                }
            }
           

            // $tgl = $ws->getCell("I".$row)->getValue();
            // $tgl =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl));
            $q = mysqli_query($con,"SELECT a.`status_center`,b.`nama_karyawan`,a.`no_center`
            , YEAR(CURDATE()) - YEAR(c.`tgl_bergabung`) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(c.`tgl_bergabung`, '%m%d')) AS lama
            FROM center a 
            JOIN karyawan b ON b.id_karyawan=a.id_karyawan
            JOIN daftar_nasabah c ON c.no_center=a.`no_center` 
            WHERE c.`id_nasabah`='$ID' AND a.`id_cabang`='$id_cabang'");
            $nama = mysqli_fetch_array($q);
            $warna="";
            $cicilan = $pokok + $margin + $wajib_minggu;
            $selisih = $ke - $rill;
            $ket='';
            $satu_angsuran=0;
            $warna_baris ="";
            $pensiun_tiga=0;
            $tanpa_margin=0;
            if($selisih == 0)
            {
                // echo 'double 1';
            }
            elseif($selisih>1){
                //PAR DARI 1 sampe 100
                // && $selisih <100
                if($selisih>1){
                    
                    $ket =  $selisih - 1 ." tunggakan";
                    if($nama['status_center']=='hijau' ){
                        $warna_baris="#79ff54";
                        $warna = "hijau";
                    }
                    elseif( $nama['status_center']=='kuning')
                    {
                        $warna_baris="yellow";
                        $warna = "kuning";
                        
                    }
                    
                    if($nama['lama']>=3){
                        if($pensiun < $pensiun_tiga + 10000){
                            $pensiun_tiga  = 0;
                        }
                        else{
                            $pensiun_tiga  = ($amount * 1/100) * 1000;
                        }
                        
                        $satu_angsuran = (($sukarela - 2000) + ($pensiun - $pensiun_tiga) ) -$cicilan;
                    }
                    else{
                        $satu_angsuran = ($sukarela - 2000) -$cicilan;
                    }
                    $tanpa_margin = $os - (($wajib-2000) + ($pensiun-2000) + ($sukarela-2000));
                    // $satu_angsuran = ($sukarela - 2000) -$cicilan;
                }
                else{
                    $ket = 'par '.($selisih - 1);
                    $tanpa_margin = $os - (($wajib-2000) + ($pensiun-2000) + ($sukarela-2000));

                }
            }
            elseif($selisih<0){
                // $ket = "double ".$selisih;
            }
            ?>
        
           
            <?php   

            if($ket){
                $cicil = $cicilan * $selisih;
                $kurangi_sukarela =  ($sukarela - 2000) - $cicil;
                if($kurangi_sukarela>0)
                {

                    $sheet->setCellValue('A'.$baris, $baris);
                    $sheet->setCellValue('b'.$baris, $id_nasabah.' / '. $nama['no_center']);
                    $sheet->setCellValue('c'.$baris, $nasabah);
                    $sheet->setCellValue('d'.$baris, $kode_pemb);
                    $sheet->setCellValue('e'.$baris, $ke);
                    $sheet->setCellValue('f'.$baris, $rill);
                    $sheet->setCellValue('g'.$baris, $amount);
                    $sheet->setCellValue('h'.$baris, $os);
                    $sheet->setCellValue('i'.$baris, $cicilan);
                    $sheet->setCellValue('j'.$baris, $cicil);
                    $sheet->setCellValue('k'.$baris, $sukarela);
                    $sheet->setCellValue('l'.$baris, ($selisih - 1));
                    $sheet->setCellValue('m'.$baris, $kurangi_sukarela);
                    // $sheet->setCellValue('l'.$baris, $kurangi_sukarela);
                    $sheet->setCellValue('n'.$baris, $nama['nama_karyawan']);
                    $baris++;
            }
            }
            

        }
    }
}
?>
</tbody>

</table>
<?php 
// $spreadsheet->getActiveSheet()->setTitle('Data Par');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);



$writer = new Xlsx($spreadsheet);
$filename='PAR - '.date("Y-m-d").' - '. time() ;
$writer->save('export/excel/par/'.$filename.'.xlsx');
pindah($url."export/excel/par/$filename.xlsx");
