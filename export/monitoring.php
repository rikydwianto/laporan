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



$filename = "monitoring-".date("Y-m-d").'-'.rand(0,100);
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
$cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
while($karyawan = mysqli_fetch_array($cek_ka)){
    $sheet->setCellValue('A'.$baris, $no1);
    $sheet->setCellValue('B'.$baris, $karyawan['nik_karyawan']);
    $sheet->setCellValue('C'.$baris, $karyawan['nama_karyawan']);
    
    $q = mysqli_query($con,"select count(id_detail_nasabah) as total from pinjaman where monitoring='belum' and id_karyawan='$karyawan[id_karyawan]' and id_cabang='$id_cabang'");
    $total = mysqli_fetch_array($q);
    $total = $total['total'];
    $total_monitoring =$total + $total_monitoring;

    $sheet->setCellValue('D'.$baris, $total);
    $baris++;$no1++;
}
$baris_akhir = $baris +1;
$sheet->setCellValue('D'.$baris_akhir,$total_monitoring);


$spreadsheet->createSheet();
// Add some data
$sheet2 = $spreadsheet->setActiveSheetIndex(1);
$sheet2->setCellValue('A1', 'DATA MONITORING');
// Rename worksheet
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
    $sheet2->setCellValue('r'.$row, $pinj['tgl_pencairan']);
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