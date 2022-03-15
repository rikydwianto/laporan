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
$sheet->setCellValue('E2', 'PEMB');
$sheet->setCellValue('F2', 'KE');
$sheet->setCellValue('G2', 'RILL');
$sheet->setCellValue('H2', 'AMOUNT');
$sheet->setCellValue('I2', 'O.S');
$sheet->setCellValue('J2', 'CICILAN');
$sheet->setCellValue('K2', 'WAJIB');
$sheet->setCellValue('L2', 'SUKARELA');
$sheet->setCellValue('M2', 'PENSIUN');
$sheet->setCellValue('N2', 'HARI RAYA');
$sheet->setCellValue('O2', 'TOTAL SIMPANAN');
$sheet->setCellValue('P2', 'STAFF');
$sheet->setCellValue('Q2', 'HARI');
$spreadsheet->createSheet();



$spreadsheet->createSheet();
// Add some data
$sheet2 = $spreadsheet->setActiveSheetIndex(1);
$sheet2->setTitle('PAR KETUTUP');
$sheet2->setCellValue('A1', 'DATA PAR KETUTUP DARI SUKARELA DAN PENSIUN');
$sheet2->getColumnDimension('B')->setAutoSize(true);
$sheet2->getColumnDimension('C')->setAutoSize(true);
$sheet2->getColumnDimension('D')->setAutoSize(true);
$sheet2->getColumnDimension('E')->setAutoSize(true);
$sheet2->getColumnDimension('F')->setAutoSize(true);
$sheet2->getColumnDimension('G')->setAutoSize(true);
$sheet2->getColumnDimension('H')->setAutoSize(true);
$sheet2->getColumnDimension('I')->setAutoSize(true);
$sheet2->getColumnDimension('J')->setAutoSize(true);
$sheet2->getColumnDimension('K')->setAutoSize(true);
$sheet2->getColumnDimension('L')->setAutoSize(true);
$sheet2->getColumnDimension('M')->setAutoSize(true);
$sheet2->getColumnDimension('N')->setAutoSize(true);
$sheet2->getColumnDimension('O')->setAutoSize(true);
$sheet2->getColumnDimension('P')->setAutoSize(true);
$sheet2->getColumnDimension('Q')->setAutoSize(true);
// $sheet2->setTitle('DATA STAFF');



$baris_ws2=3;
$no_baris_ws2=1;
?>
<h2>BLK </h2>
<table id='data_blk' class='table-bordered'>
    <thead>
        <tr>
            <th>NO</th>
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
ini_set('max_execution_time', 0);

$cek_delin1 = mysqli_query($con,"select * from daftar_nasabah where id_cabang='$id_cabang' order by no_center asc");
error_reporting(0);
  $nor=1;
while($r = mysqli_fetch_array($cek_delin1)){
    
    $kode = explode("-",$kode)[0];
    $simp = mysqli_query($con,"select * from detail_simpanan where id_nasabah='$r[id_nasabah]' and id_cabang='$id_cabang'");
    $simp = mysqli_fetch_array($simp);
    $json  = $simp['detail_simpanan'];
    
            $json = json_decode($json);
            //    if($id_nasabah!=null){
            $id_nasabah =  $r['id_nasabah'];
            $nasabah =  $r['nama_nasabah'];
            $pensiun =  $json->pensiun;
            $pensiun_asli = $pensiun;
            $sukarela = $json->sukarela;
            $wajib = $json->wajib;
            $hari_raya = $json->hari_raya;
            

         //{"wajib":96000,"sukarela":20000,"pensiun":40180,"hari_raya":82139,"rill":15,"ke":16,"pinjaman":4000,"sisa_saldo":2976800,"margin":26100,"pokok":73100,"kode":"PU"}
           $ID = sprintf("%06d",$id_nasabah);
           $IDs = sprintf("%0d",$id_nasabah);
            
            $pokok =    $json->pokok;
            $margin =   $json->margin;
            $amount =   $json->pinjaman;
            $os =       $json->sisa_saldo;
            $ke =       $json->ke;
            $rill =     $json->rill;
            $kode_pemb = $json->kode;

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
            $q = mysqli_query($con,"SELECT a.`status_center`,b.`nama_karyawan`,a.`no_center`,a.hari
            , YEAR(CURDATE()) - YEAR(c.`tgl_bergabung`) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(c.`tgl_bergabung`, '%m%d')) AS lama
            FROM center a 
            JOIN karyawan b ON b.id_karyawan=a.id_karyawan
            JOIN daftar_nasabah c ON c.no_center=a.`no_center` 
            WHERE c.`id_nasabah`='$IDs' AND a.`id_cabang`='$id_cabang' and b.`id_cabang`='$id_cabang' and c.`id_cabang`='$id_cabang'");
            $nama = mysqli_fetch_array($q);
            $warna="";
            $cicilan = $pokok + $margin + $wajib_minggu;
            $selisih = $r['minggu'];
            $ket='';
            $satu_angsuran=0;
            $warna_baris ="";
            $pensiun_tiga=0;
            $tanpa_margin=0;
            if($selisih == 0)
            {
                // echo 'double 1';
            }
            elseif($selisih>=1){
                //PAR DARI 1 sampe 100
                // && $selisih <100
                if($selisih>=1){
                    
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
                        $sukarela_pensiun = (($sukarela - 2000) + ($pensiun - $pensiun_tiga) );
                        $satu_angsuran = (($sukarela - 2000) + ($pensiun - $pensiun_tiga) ) -$cicilan;
                        
                    }
                    else{
                       // $pensiun=0;
                        $sukarela_pensiun = ($sukarela - 2000) ;
                        $satu_angsuran = ($sukarela - 2000) -$cicilan;
                    }
                    $tanpa_margin = $os - (($wajib-2000) + ($pensiun-2000) + ($sukarela-2000) + ($hari_raya-2000));
                    // $satu_angsuran = ($sukarela - 2000) -$cicilan;
                }
                else{
                    $ket = 'par '.($selisih - 1);
                    $tanpa_margin = $os - (($wajib-2000) + ($pensiun-2000) + ($sukarela-2000) + ($hari_raya-2000));

                }
            }
            elseif($selisih<0){
                // $ket = "double ".$selisih;
            }
           
           
          /*   <!-- <tr>
                    <th><?=$nor++?></th>
                    <th><?=$r['nasabah']?></th>
                    <th><?=$id_nasabah?></th>
                    <th>NAMA</th>
                    <th><?=$simp['pembiayaan']?> </th>
                    <th><?=$ke?></th>
                    <th><?=$rill?></th>
                    <th><?=$amount?></th>
                    <th><?=$os?></th>
                    <th><?=$cicilan?></th>
                    <th><?=$wajib?></th>
                    <th><?=$sukarela?></th>
                    <th><?=$pensiun?></th>
                    <th>PAR</th>
                    <th>1 angsuran</th>
                    <th>tanpa Margin</th>
                    <th>Warna</th>

                    <th>#</th>
                </tr> -->
         
            */
            $ket  = "ada";
         
              $selisih = $r['minggu'];
              $sheet->setCellValue('A2', 'NO');
                $sheet->setCellValue('B2', 'NASABAH');
                $sheet->setCellValue('C2', 'ID');
                $sheet->setCellValue('D2', 'CENTER');
                $sheet->setCellValue('E2', 'PEMB');
                $sheet->setCellValue('F2', 'KE');
                $sheet->setCellValue('G2', 'RILL');
                $sheet->setCellValue('H2', 'AMOUNT');
                $sheet->setCellValue('I2', 'O.S');
                $sheet->setCellValue('J2', 'CICILAN');
                $sheet->setCellValue('K2', 'WAJIB');
                $sheet->setCellValue('L2', 'SUKARELA');
                $sheet->setCellValue('M2', 'PENSIUN');
                $sheet->setCellValue('N2', 'HARI RAYA');
                $sheet->setCellValue('O2', 'TOTAL SIMPANAN');
                $sheet->setCellValue('P2', 'STAFF');
                $sheet->setCellValue('Q2', 'HARI');
               

                $total_simpanan   = $sukarela + $wajib + $pensiun_asli + $hari_raya;           
                $sheet->setCellValue('A'.$baris, $nor++);
                $sheet->setCellValue('b'.$baris, $nasabah);
                $sheet->setCellValue('c'.$baris, $id_nasabah);
                $sheet->setCellValue('D'.$baris, $nama['no_center']);
                $sheet->setCellValue('E'.$baris, $kode_pemb);
                $sheet->setCellValue('F'.$baris, $ke);
                $sheet->setCellValue('G'.$baris, $rill);
                $sheet->setCellValue('H'.$baris, $amount);
                $sheet->setCellValue('I'.$baris, $os);
                $sheet->setCellValue('J'.$baris, $cicilan);
                $sheet->setCellValue('K'.$baris, $wajib);
                $sheet->setCellValue('L'.$baris, $sukarela);
                $sheet->setCellValue('M'.$baris, $pensiun_asli);
                $sheet->setCellValue('N'.$baris, $hari_raya);
                $sheet->setCellValue('O'.$baris, $total_simpanan);
                $sheet->setCellValue('P'.$baris, $nama['nama_karyawan']);
                $sheet->setCellValue('Q'.$baris, $nama['hari']);

                //SHEET 2

               


                $baris++;
            
            

        }
        $kode_cabang  = $_SESSION['kode_cabang'];
        $text ="$nik $kode_cabang sedang mengunduh analisis PAR";
    
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
$filename=$_SESSION['kode_cabang'].'-REKAP SIMPANAN - '.date("Y-m-d").' - '. time() ;
$writer->save('export/excel/par/'.$filename.'.xlsx');
pindah($url."blk.php?download=".$filename.".xlsx");
