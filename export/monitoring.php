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



$filename = "monitoring-".date("Y-m-d").'-'.time();
$_SESSION['nama_file']=$filename;
$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('K')->setAutoSize(true);
$sheet->setTitle('DATA STAFF');

$sheet->setCellValue('A1', 'DATA MONITORING PER STAFF');
$sheet->setCellValue('A2', 'NO');
$sheet->setCellValue('b2', 'NIK STAFF');
$sheet->setCellValue('c2', 'NAMA STAFF');
$sheet->setCellValue('d2', 'P.U');
$sheet->setCellValue('e2', 'PSA');
$sheet->setCellValue('f2', 'PPD');
$sheet->setCellValue('g2', 'ARTA');
$sheet->setCellValue('h2', 'PRR');
$sheet->setCellValue('i2', 'LAINNYA');
$sheet->setCellValue('j2', 'TOTAL');
$sheet->setCellValue('k2', 'NAMA STAFF');
$sheet->setCellValue('l2', 'JABATAN');
$sheet->setCellValue('m2', 'AGT');

$baris = 3;
$no1=1;
$cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
while($karyawan = mysqli_fetch_array($cek_ka)){
    $sheet->setCellValue('A'.$baris, $no1);
    $sheet->setCellValue('B'.$baris, $karyawan['nik_karyawan']);
    $sheet->setCellValue('C'.$baris, $karyawan['nama_karyawan']);

    $hitung_agt = mysqli_query($con, "select total_nasabah as member from total_nasabah where id_cabang='$id_cabang' and id_karyawan='$karyawan[id_karyawan]'");
    $hitung_agt = mysqli_fetch_array($hitung_agt);
    $hitung_agt = $hitung_agt['member'];
    

    
    
    $q = mysqli_query($con,"
    SELECT  id_karyawan,
    SUM(CASE WHEN produk = 'PINJAMAN UMUM' THEN 1 ELSE 0 END) AS pu,
    SUM(CASE WHEN produk = 'PINJAMAN MIKROBISNIS' THEN 1 ELSE 0 END) AS pmb,
    SUM(CASE WHEN produk = 'PINJAMAN SANITASI' THEN 1 ELSE 0 END) AS psa,
    SUM(CASE WHEN produk = 'PINJAMAN DT. PENDIDIKAN' THEN 1 ELSE 0 END) AS ppd,
    SUM(CASE WHEN produk = 'PINJAMAN ARTA' THEN 1 ELSE 0 END) AS arta,
    SUM(CASE WHEN produk = 'PINJAMAN RENOVASIRUMAH' THEN 1 ELSE 0 END) AS prr,
        SUM(CASE WHEN 
    produk != 'PINJAMAN UMUM' AND  
    produk != 'PINJAMAN SANITASI' AND
    produk != 'PINJAMAN MIKROBISNIS' AND
    produk != 'PINJAMAN DT. PENDIDIKAN' AND
    produk != 'PINJAMAN ARTA' AND produk != 'PINJAMAN RENOVASIRUMAH'
    
    THEN 1 ELSE 0 END) AS lain_lain,
    COUNT(*) AS total
    
FROM pinjaman where id_karyawan=$karyawan[id_karyawan] and monitoring='belum' GROUP BY id_karyawan ");
        $pemb = mysqli_fetch_array($q);
        $total = ($pemb['total'] == null ? 0 : $pemb['total']);
        $pu = ($pemb['pu'] == null ? 0:$pemb['pu']);
        $pmb = ($pemb['pmb'] == null ? 0:$pemb['pmb']);;
        $psa = ($pemb['psa'] == null ? 0:$pemb['psa']);;
        $ppd = ($pemb['ppd'] == null ? 0:$pemb['ppd']);;
        $arta = ($pemb['arta'] == null ? 0:$pemb['arta']);;
        $lain = ($pemb['lain_lain'] == null ? 0:$pemb['lain_lain']);
        $prr = ($pemb['prr'] == null ? 0:$pemb['prr']);
        $total_pu  = $total_pu   + $pu;
        $total_pmb = $total_pmb + $pmb;
        $total_psa = $total_psa + $psa;
        $total_ppd = $total_ppd + $ppd;
        $total_arta= $total_arta + $arta;
        $total_lain= $total_lain + $lain;
        $total_prr = $total_prr + $prr;

        $total_monitoring =$total + $total_monitoring;

        $persen = ($hitung_agt == null ? 0 : round(($total_monitoring /$hitung_agt),2));

        $sheet->setCellValue('d'.$baris,$pu);
        $sheet->setCellValue('e'.$baris, $psa);
        $sheet->setCellValue('f'.$baris, $ppd);
        $sheet->setCellValue('g'.$baris, $arta);
        $sheet->setCellValue('h'.$baris, $prr);
        $sheet->setCellValue('i'.$baris, $lain);
        $sheet->setCellValue('j'.$baris, $total);
        $sheet->setCellValue('K'.$baris, $karyawan['nama_karyawan']);
        $sheet->setCellValue('m'.$baris, $hitung_agt);
        $sheet->setCellValue('l'.$baris, 'FO');
        // $sheet->setCellValue('m'.$baris, $persen."%");
    $baris++;$no1++;
}
$baris_akhir = $baris +1;
        $sheet->setCellValue('c'.$baris_akhir,"TOTAL MONITORING");
        $sheet->setCellValue('d'.$baris_akhir,$total_pu);
        $sheet->setCellValue('e'.$baris_akhir,$total_psa);
        $sheet->setCellValue('f'.$baris_akhir,$total_ppd);
        $sheet->setCellValue('g'.$baris_akhir,$total_arta);
        $sheet->setCellValue('h'.$baris_akhir,$total_prr);
        $sheet->setCellValue('i'.$baris_akhir,$total_lain);
        $sheet->setCellValue('j'.$baris_akhir,$total_monitoring);

$spreadsheet->createSheet();
// Add some data
$sheet2 = $spreadsheet->setActiveSheetIndex(1);
$sheet2->setCellValue('A1', 'DATA MONITORING');
// Rename worksheet
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
$row=2;
$sheet2->setCellValue('A'.$row, "NO");
    $sheet2->setCellValue('B'.$row, 'id nasabah');
    $sheet2->setCellValue('c'.$row, 'id pinjaman');
    $sheet2->setCellValue('d'.$row, 'nama nasabah');
    $sheet2->setCellValue('e'.$row, 'no hp');
    $sheet2->setCellValue('f'.$row, 'center');
    $sheet2->setCellValue('g'.$row, 'kelompok');
    $sheet2->setCellValue('h'.$row, 'produk');
    $sheet2->setCellValue('i'.$row, 'jumlah pinjaman');
    $sheet2->setCellValue('j'.$row, 'outstanding');
    $sheet2->setCellValue('k'.$row, 'jangka waktu');
    $sheet2->setCellValue('l'.$row, 'margin');
    $sheet2->setCellValue('m'.$row, 'angsuran');
    $sheet2->setCellValue('n'.$row, 'tujuan pinjaman');
    $sheet2->setCellValue('o'.$row, 'pinjaman ke');
    $sheet2->setCellValue('p'.$row, 'nama karyawan');
    $sheet2->setCellValue('q'.$row, 'tgl pengajuan');
    $sheet2->setCellValue('r'.$row, 'tgl pencairan');
    $sheet2->setCellValue('s'.$row, 'tgl angsuran');

$row = 3;

$q = mysqli_query($con, "select * from pinjaman left join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan where pinjaman.id_cabang='$id_cabang' and monitoring ='belum' order by karyawan.nama_karyawan asc");
while ($pinj = mysqli_fetch_array($q)) {
 
    $sheet2->setCellValue('A'.$row, $no);
    $sheet2->setCellValue('B'.$row, $pinj['id_detail_nasabah']);
    $sheet2->setCellValue('c'.$row, $pinj['id_detail_pinjaman']);
    $sheet2->setCellValue('d'.$row, $pinj['nama_nasabah']);
    $sheet2->setCellValue('e'.$row, $pinj['no_hp']);
    $sheet2->setCellValue('f'.$row, $pinj['center']);
    $sheet2->setCellValue('g'.$row, $pinj['kelompok']);
    $sheet2->setCellValue('h'.$row, $pinj['produk']);
    $sheet2->setCellValue('i'.$row, $pinj['jumlah_pinjaman']);
    $sheet2->setCellValue('j'.$row, $pinj['outstanding']);
    $sheet2->setCellValue('k'.$row, $pinj['jk_waktu']);
    $sheet2->setCellValue('l'.$row, $pinj['margin']);
    $sheet2->setCellValue('m'.$row, $pinj['angsuran']);
    $sheet2->setCellValue('n'.$row, $pinj['tujuan_pinjaman']);
    $sheet2->setCellValue('o'.$row, $pinj['pinjaman_ke']);
    $sheet2->setCellValue('p'.$row, $pinj['nama_karyawan']);
    $sheet2->setCellValue('q'.$row, $pinj['tgl_pengajuan']);
    $sheet2->setCellValue('r'.$row, $pinj['tgl_cair']);
    $sheet2->setCellValue('s'.$row, $pinj['tgl_angsuran']);
    $row++;$no++;

    

}

// DATA MONITORING
$spreadsheet->getActiveSheet()->setTitle('Data Monitoring');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);


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