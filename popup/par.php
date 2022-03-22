<!DOCTYPE html>
<html lang="en">
<?php require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");

require_once("../model/model.php");
require("../vendor/PHPExcel/Classes/PHPExcel.php");
$id_karyawan = $_SESSION['id'];
$tgl = aman($con,$_GET['tgl']);
$sepat = 'titik';

$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan = $_SESSION['jabatan'];
$cabang = $_SESSION['cabang'];
$id_cabang = $_SESSION['cabang'];
$su = $_SESSION['su'];
$d = detail_karyawan($con, $id_karyawan);
$nama_jabatan = $d['singkatan_jabatan'];
$_SESSION['kode_cabang']=$d['kode_cabang'];?>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;

        }
       
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Daftar Pinjaman</title>
</head>
<body>
    <div class='kertas'>
    <h1> ANGGOTA PAR <?=$tgl?><br>
    Cabang <?=$d['nama_cabang']?> <br>
    <?php
     $q_tambah="";
     $q_tambah1="";
    if(isset($_GET['minggu'])){
        $minggu = $_GET['minggu'];
      echo "MINGGU : " . $minggu .'<br/>';
        $q_tambah = "and d.minggu='$minggu'";
    }
    
    if(isset($_GET['bulan'])){
        $bulan = $_GET['bulan'];
      echo "PRIODE : " . $bulan ;
        $q_tambah1 = "and d.tgl_disburse like '$bulan-%'";
    }
    
    ?>
</h1>
    <table class='table'>
        <tr>
            <td>NO</td>
            <td>LOAN</td>
            <td>CENTER</td>
            <td>ID AGT</td>
            <td>ANGGOTA</td>
            <td>DISBURSE</td>
            <td>TGL DISBURSE</td>
            <td>BALANCE</td>
            <td>ARREAS</td>
            <td>WEEK PAS</td>
            <td>STATUS</td>
            <td>STAFF</td>
        </tr>
    
    <?php
   
    $no=1;
    $total_bermasalah=0;
    $query = mysqli_query($con,"
    SELECT d.*,k.nama_karyawan FROM deliquency d 
	JOIN center c ON c.`no_center`=d.`no_center` 
	JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` where d.tgl_input='$tgl' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' $q_tambah  $q_tambah1 order by k.nama_karyawan asc");
    while($data = mysqli_fetch_array($query)){
        $total_bermasalah+=$data['sisa_saldo'];
        $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
        if($par){
            $baris['baris']= "#c9c7c1";
            $baris['text']= "red";
            $baris['ket']='RE/DTD';
        }
        else{
            $baris['baris'] = "#ffff";
            $baris['text'] = "#black";
            $baris['ket']='';

        } 
        ?>
        <tr style="background-color:<?=$baris['baris']?>;color:<?=$baris['text']?>">
            <td><?=$no++?></td>
            <td><?=$data['loan']?></td>
            <td><?=$data['no_center']?></td>
            <td><?=$data['id_detail_nasabah']?></td>
            <td><?=$data['nasabah']?></td>
            <td><?=angka($data['amount'],$sepat)?></td>
            <td><?=$data['tgl_disburse']?></td>
            <td><?=angka($data['sisa_saldo'],$sepat)?></td>
            <td><?=angka($data['tunggakan'],$sepat)?></td>
            <td><?=$data['minggu']?></td>
            <td><?=$baris['ket']?></td>
            <td><?=$data['nama_karyawan']?></td>
        </tr>
        <?php
    }?>
    <tr>
        <th colspan="7">TOTAL OUTSTANDING BERMASALAH</th>
        <th><?=angka($total_bermasalah,$sepat)?></th>
    </tr>
    </table>
    </div>
</body>
<style>
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
</style>
</html>
