<a href="<?= $url . $menu ?>blk_input" class="btn btn-success"> Kembali</a>
<?php
ini_set('max_execution_time', 0);
if (isset($_GET['download'])) {
    $download = $_GET['download'];
    $back_dir    = "/export/excel/par/";
    $file = $back_dir . $download;
    pindah($url . $file);
    pindah($url . $menu . "blk_input");
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
$no1 = 1;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$style = array(
    'font'  => array(
        'size'  => 8,
        'name'  => 'Arial'
    ),


);



$sheet->getStyle('A2:AA2500')->applyFromArray($style);

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
$sheet->getColumnDimension('R')->setAutoSize(true);
$sheet->getColumnDimension('S')->setAutoSize(true);
$sheet->getColumnDimension('T')->setAutoSize(true);
$sheet->getColumnDimension('U')->setAutoSize(true);
$sheet->getColumnDimension('V')->setAutoSize(true);
$sheet->getColumnDimension('W')->setAutoSize(true);
$sheet->getColumnDimension('X')->setAutoSize(true);
$sheet->getColumnDimension('Y')->setAutoSize(true);
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
// $sheet->setCellValue('W2', 'ID ANGGOTA');
$sheet->setCellValue('W2', 'PRIODE');
$sheet->setCellValue('X2', 'ALASAN PAR');
$sheet->setCellValue('Y2', 'TARGET PENYELESAIAN');
$spreadsheet->createSheet();



$spreadsheet->createSheet();
// Add some data
$sheet2 = $spreadsheet->setActiveSheetIndex(1);
$sheet2->setTitle('ANGSURAN DARI SUKARELA');
$sheet2->setCellValue('A1', 'DATA PAR UNTUK ANGSURAN DARI SUKARELA');
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
$sheet2->getColumnDimension('R')->setAutoSize(true);
$sheet2->getColumnDimension('S')->setAutoSize(true);
$sheet2->getColumnDimension('T')->setAutoSize(true);
$sheet2->getColumnDimension('U')->setAutoSize(true);
$sheet2->getColumnDimension('V')->setAutoSize(true);
$sheet2->getColumnDimension('W')->setAutoSize(true);
$sheet2->getColumnDimension('X')->setAutoSize(true);
$sheet2->getColumnDimension('Y')->setAutoSize(true);
// $sheet2->setTitle('DATA STAFF');


// $sheet2->setCellValue('B1', 'DATA PAR ');
$sheet2->setCellValue('A2', 'NO');
$sheet2->setCellValue('B2', 'CENTER');
$sheet2->setCellValue('C2', 'ID');
$sheet2->setCellValue('D2', 'NASABAH');
$sheet2->setCellValue('E2', 'PEMB');
$sheet2->setCellValue('F2', 'KE');
$sheet2->setCellValue('G2', 'RILL');
$sheet2->setCellValue('H2', 'AMOUNT');
$sheet2->setCellValue('I2', 'O.S');
$sheet2->setCellValue('J2', 'CICILAN');
$sheet2->setCellValue('K2', 'WAJIB');
$sheet2->setCellValue('L2', 'SUKARELA');
$sheet2->setCellValue('M2', 'DUE PASS');
$sheet2->setCellValue('N2', "MASUK \nANGSURAN ");
$sheet2->setCellValue('O2', "MASUK ANGSURAN X \n CICILAN");
$sheet2->setCellValue('P2', 'SISA SUKARELA ');
$sheet2->setCellValue('Q2', 'KETERANGAN');
$sheet2->setCellValue('R2', 'STAFF');
$sheet2->setCellValue('S2', 'HARI');
$sheet2->getStyle('A2:Z2')->getAlignment()->setWrapText(true);
// Add some data
$shee = $spreadsheet->setActiveSheetIndex(1);
$sheet->setCellValue('A1', 'DATA PAR');

$sheet3 = $spreadsheet->setActiveSheetIndex(2);
$sheet3->setTitle('ANGSURAN DARI WAJIB');
$sheet3->setCellValue('A1', 'DATA PAR UNTUK ANGSURAN DARI WAJIB');
$sheet3->getColumnDimension('B')->setAutoSize(true);
$sheet3->getColumnDimension('C')->setAutoSize(true);
$sheet3->getColumnDimension('D')->setAutoSize(true);
$sheet3->getColumnDimension('E')->setAutoSize(true);
$sheet3->getColumnDimension('F')->setAutoSize(true);
$sheet3->getColumnDimension('G')->setAutoSize(true);
$sheet3->getColumnDimension('H')->setAutoSize(true);
$sheet3->getColumnDimension('I')->setAutoSize(true);
$sheet3->getColumnDimension('J')->setAutoSize(true);
$sheet3->getColumnDimension('K')->setAutoSize(true);
$sheet3->getColumnDimension('L')->setAutoSize(true);
$sheet3->getColumnDimension('M')->setAutoSize(true);
$sheet3->getColumnDimension('N')->setAutoSize(true);
$sheet3->getColumnDimension('O')->setAutoSize(true);
$sheet3->getColumnDimension('P')->setAutoSize(true);
$sheet3->getColumnDimension('Q')->setAutoSize(true);
$sheet3->getColumnDimension('R')->setAutoSize(true);
$sheet3->getColumnDimension('S')->setAutoSize(true);
$sheet3->getColumnDimension('T')->setAutoSize(true);


$sheet3->setCellValue('A2', 'NO');
$sheet3->setCellValue('B2', 'CENTER');
$sheet3->setCellValue('C2', 'ID');
$sheet3->setCellValue('D2', 'NASABAH');
$sheet3->setCellValue('E2', 'PEMB');
$sheet3->setCellValue('F2', 'KE');
$sheet3->setCellValue('G2', 'RILL');
$sheet3->setCellValue('H2', 'AMOUNT');
$sheet3->setCellValue('I2', 'O.S');
$sheet3->setCellValue('J2', 'CICILAN');
$sheet3->setCellValue('K2', 'WAJIB');
$sheet3->setCellValue('L2', 'SUKARELA');
$sheet3->setCellValue('M2', 'DUE PASS');
$sheet3->setCellValue('N2', "MASUK \nANGSURAN ");
$sheet3->setCellValue('O2', "MASUK ANGSURAN X \n CICILAN");
$sheet3->setCellValue('P2', 'SISA SUKARELA ');
$sheet3->setCellValue('Q2', 'KETERANGAN');
$sheet3->setCellValue('R2', 'STAFF');
$sheet3->setCellValue('S2', 'HARI');
$sheet3->getStyle('A2:Z2')->getAlignment()->setWrapText(true);


$sheet2->getStyle('A2:AA2500')->applyFromArray($style);
$sheet3->getStyle('A2:AA2500')->applyFromArray($style);

$baris_ws2 = 3;
$no_baris_ws2 = 1;

ini_set('max_execution_time', '0'); // for infinite time of execution 
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

        $cek_tgl = mysqli_query($con, "SELECT max(tgl_input) AS tgl FROM deliquency WHERE id_cabang='$id_cabang' ORDER BY tgl_input DESC");
        $tgl_delin = mysqli_fetch_assoc($cek_tgl);
        $tgl_delin = $tgl_delin['tgl'];
        $no1 = 1;
        $cek_delin1 = mysqli_query($con, "SELECT  SUBSTRING_INDEX(id_detail_nasabah,'-',-1) as idn,deliquency.* FROM deliquency  WHERE tgl_input='$tgl_delin' 
            AND id_cabang='$id_cabang'  ");
        $cek_delin = mysqli_num_rows($cek_delin1);
        // error_reporting(0);
        $nor = 1;
        while ($r = mysqli_fetch_assoc($cek_delin1)) {
            $kode = $r['loan'];
            $kode = explode("-", $kode)[0];

            //         $json = json_decode($json);
            //    if($id_nasabah!=null){
            $idn =  $r['id_detail_nasabah'];
            $id_nasabah =  $r['idn'];
            $id_nasabah =  $idn;
            $nasabah =  $r['nasabah'];
            $loan =  $r['loan'];
            $tgl_dis    =  $r['tgl_disburse'];
            $pensiun =  $r['pensiun'];
            $pensiun_asli = $pensiun;
            $sukarela = $r['sukarela'];
            $wajib = $r['wajib'];
            $hari_raya = $r['hariraya'];
            $priode = $r['priode'];


            //{"wajib":96000,"sukarela":20000,"pensiun":40180,"hari_raya":82139,"rill":15,"ke":16,"pinjaman":4000,"sisa_saldo":2976800,"margin":26100,"pokok":73100,"kode":"PU"}
            $ID = sprintf("%06d", $id_nasabah);
            $IDs = sprintf("%0d", $id_nasabah);

            $pokok =    $r['cicilan'];
            $margin =   0; //$json->margin;
            $amount =   $r['amount'];
            $os =       $r['sisa_saldo'];
            $ke =       $r['minggu_ke'];
            $rill =     $r['minggu_rill'];
            $kode_pemb = explode("-", $loan)[0];

            $wajib_minggu = 0;
            if ($kode_pemb == 'PU' || $kode_pemb == 'PMB') {
                if ($amount <= 1000000) {
                    $wajib_minggu = 1000;
                } else {
                    $wajib_minggu = angka($amount);
                    if (is_float($wajib_minggu)) {
                        $pecah = explode(".", $wajib_minggu);
                        $awal = $pecah[0];
                        $wajib_minggu = ($awal + 1) * 1000;
                    } else {
                        $wajib_minggu = $wajib_minggu * 1000;
                    }
                }
            }


            // $tgl = $ws->getCell("I".$row)->getValue();
            // $tgl =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl));
            $q = mysqli_query($con, "SELECT a.`status_center`,b.`nama_karyawan`,a.`no_center`,a.hari
            , YEAR(CURDATE()) - YEAR(c.`tgl_bergabung`) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(c.`tgl_bergabung`, '%m%d')) AS lama
            FROM center a 
            JOIN karyawan b ON b.id_karyawan=a.id_karyawan
            JOIN daftar_nasabah c ON c.no_center=a.`no_center` 
            WHERE c.`id_nasabah`='$IDs' AND a.`id_cabang`='$id_cabang' and b.`id_cabang`='$id_cabang' and c.`id_cabang`='$id_cabang'");
            $nama = mysqli_fetch_assoc($q);
            $warna = "";
            $cicilan = $pokok + $margin; //+ $wajib_minggu;
            $selisih = $r['minggu'];
            $ket = '';
            $warna_baris = "";
            $pensiun_tiga = 0;
            $tanpa_margin = 0;
            //FILTER ANGGOTA TIGA TAHUN UNTUK PENGAMBILAN PENSIUN
            if ($nama['lama'] >= 3) {
                $pensiun_akhir = ($pensiun - 2000);
            } else $pensiun_akhir = 0;

            //END PENSIUN
            if ($selisih == 0) {
                // echo 'double 1';
            } elseif ($selisih >= 1) {
                if ($selisih >= 1) {


                    // $pensiun_tiga  = ($amount * 1/100) * 1000;
                    // $satu_angsuran = $cicilan - (($sukarela - 2000) + ($pensiun - 2000) );
                    // $tanpa_margin = $os - (($wajib-2000) + ($pensiun-2000) + ($sukarela-2000) + ($hari_raya-2000));
                    // $satu_angsuran = ($sukarela - 2000) -$cicilan;
                } else {
                    $ket = 'par ' . ($selisih - 1);
                    // $tanpa_margin = $os - (($wajib-2000) + ($pensiun-2000) + ($sukarela-2000) + ($hari_raya-2000));

                }
            } elseif ($selisih < 0) {
                // $ket = "double ".$selisih;
            }



            $ket  = "ada";

            $selisih = $r['minggu'];
            $cek_alasan = mysqli_query($con, "SELECT * from alasan_par where id_cabang='$id_cabang' and id_detail_nasabah='$id_nasabah' and id_loan='$loan'");
            if (mysqli_num_rows($cek_alasan)) {
                $reason = mysqli_fetch_assoc($cek_alasan);
                $ket = $reason['alasan'];
                $target = $reason['penyelesaian_par'];
                $a = $ket;
            } else {
                $ket = "";
                $a = "";
                $target = "";
            }
            $pensiun_tiga  = ($amount * 0.01);
            $satu_angsuran =  (($sukarela - 2000) + $pensiun_akhir + ($hari_raya == 0 ? 0 : ($hari_raya - 2000))) - $cicilan;
            $tanpa_margin = $os - (($wajib - 2000) + ($pensiun - 2000) + ($sukarela - 2000) + ($hari_raya - 2000));


            $total_simpanan   = $sukarela + $wajib + $pensiun_asli + $hari_raya;
            $sheet->setCellValue('A' . $baris, $nor++);
            $sheet->setCellValue('b' . $baris, $nasabah);
            $sheet->setCellValue('c' . $baris, $id_nasabah);
            $sheet->setCellValue('D' . $baris, $r['no_center']);
            $sheet->setCellValue('e' . $baris, $loan);
            $sheet->setCellValue('f' . $baris, $kode_pemb);
            $sheet->setCellValue('g' . $baris, $tgl_dis);
            $sheet->setCellValue('h' . $baris, $ke);
            $sheet->setCellValue('i' . $baris, $rill);
            $sheet->setCellValue('j' . $baris, $amount);
            $sheet->setCellValue('k' . $baris, $os);
            $sheet->setCellValue('l' . $baris, $cicilan);
            $sheet->setCellValue('m' . $baris, $wajib);
            $sheet->setCellValue('n' . $baris, $sukarela);
            $sheet->setCellValue('o' . $baris, $pensiun_asli);
            $sheet->setCellValue('p' . $baris, $hari_raya);
            $sheet->setCellValue('q' . $baris, $total_simpanan);
            $sheet->setCellValue('r' . $baris, ($selisih));
            $sheet->setCellValue('s' . $baris, ($satu_angsuran == 0 ? "" : ($satu_angsuran)));
            $sheet->setCellValue('t' . $baris, ($tanpa_margin == 0 ? "" : ($tanpa_margin)));
            $sheet->setCellValue('u' . $baris, explode(" - ", $r['staff'])[1]);
            $sheet->setCellValue('v' . $baris, $nama['hari']);
            // $sheet->setCellValue('w'.$baris, $idn);
            $sheet->setCellValue('W' . $baris, $priode);
            $sheet->setCellValue('X' . $baris, $ket);
            $sheet->setCellValue('Y' . $baris, $target);

            //SHEET 2

            // CEK ANGSURAN DARI SUKARELA
            // floor();
            $cek_angsuran =  floor(($sukarela - 2000) / ($cicilan));
            $angsuran_tunggakan = ($selisih + 1) * $cicilan;
            if ($cek_angsuran > 0) {
                if ($selisih > $cek_angsuran)
                    $keter = "";
                else $keter = "bisa tutup par";
                $sheet2->setCellValue('A' . $baris_ws2, $no_baris_ws2++);
                $sheet2->setCellValue('b' . $baris_ws2, $r['no_center']);
                $sheet2->setCellValue('c' . $baris_ws2, $id_nasabah);
                $sheet2->setCellValue('d' . $baris_ws2, $nasabah);
                $sheet2->setCellValue('e' . $baris_ws2, $kode_pemb);
                $sheet2->setCellValue('f' . $baris_ws2, $ke);
                $sheet2->setCellValue('g' . $baris_ws2, $rill);
                $sheet2->setCellValue('h' . $baris_ws2, $amount);
                $sheet2->setCellValue('i' . $baris_ws2, $os);
                $sheet2->setCellValue('j' . $baris_ws2, $cicilan);
                $sheet2->setCellValue('k' . $baris_ws2, $wajib);
                $sheet2->setCellValue('l' . $baris_ws2, $sukarela);
                $sheet2->setCellValue('m' . $baris_ws2, $selisih);
                $sheet2->setCellValue('n' . $baris_ws2, ($cek_angsuran));
                $sheet2->setCellValue('o' . $baris_ws2, $cek_angsuran * $cicilan);
                $sheet2->setCellValue('p' . $baris_ws2, ($sukarela) - ($cek_angsuran * $cicilan));
                $sheet2->setCellValue('q' . $baris_ws2, $keter);
                $sheet2->setCellValue('r' . $baris_ws2, explode(" - ", $r['staff'])[1]);
                $sheet2->setCellValue('s' . $baris_ws2, $r['hari']);
                $baris_ws2++;
            }


            $baris++;
        }
        $kode_cabang  = $_SESSION['kode_cabang'];
        $text = "$nik $kode_cabang sedang mengunduh analisis PAR";

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
$filename = $_SESSION['kode_cabang'] . '- DELIN SAVING ANALISIS new - ' . date("Y-m-d") . ' - ' . time();
$writer->save('export/excel/par/' . $filename . '.xlsx');
pindah($url . "blk.php?download=" . $filename . ".xlsx");
