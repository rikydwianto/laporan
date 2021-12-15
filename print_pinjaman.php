<!DOCTYPE html>
<html lang="en">
<?php require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");

require_once("model/model.php");
require("vendor/PHPExcel/Classes/PHPExcel.php");
$id_karyawan = $_SESSION['id'];
$tgl_awal = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];
// require 'vendor/autoload.php';

// use Dompdf\Dompdf;

// // instantiate and use the dompdf class
// $dompdf = new Dompdf();
// $dompdf->loadHtml('hello world');

// // (Optional) Setup the paper size and orientation
// $dompdf->setPaper('F4', 'landscape');

// // Render the HTML as PDF
// $dompdf->render();

// // Output the generated PDF to Browser
// $dompdf->stream();
// $url="http://192.168.100.6/laporan/";
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
        table tr th{
            height: 0.7cm;
            padding:3px;
            font-weight: bold;
            font-size: 11px;
        }

        .kertas table{
            font-size: 8px;
            margin-left: 0cm;
            margin-right:0.2cm;
        }
        .kertas{
            /* background-color: red; */
            width: 100%;
            /* height: 21cm; */
        }
        .kertas .tengah{
            padding-top: 0.5cm;
            text-align: center;
            line-height: 0cm
        }
        table tr td{
            vertical-align: middle;

        }
        .isi_tengah{
            text-align: center;
        }
        .kecil{
            font-size: 7px;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Daftar Pinjaman</title>
</head>
<body>
    <div class='kertas'>
        <div class='tengah'>
            <h1>DAFTAR PINJAMAN</h1>
            <h3>CABANG <?=strtoupper($d['nama_cabang'])?></h3>
            <h3>Periode : <?=format_hari_tanggal($tgl_awal)?> s/d <?=format_hari_tanggal($tgl_akhir)?></h3>

        </div>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">NO</th>
                    <th colspan="3" >NASABAH</th>
                    <th rowspan="2" >PHONE</th>
                    <th rowspan="2" >CENTER</th>
                    <th rowspan="2" >GROUP</th>
                    <th rowspan="2" >PRODUK</th>
                    <th rowspan="2" >JUMLAH.<br/>PINJAMAN</th>
                    <th rowspan="2" >OUTSTANDING</th>
                    <th rowspan="2" >J.<br/>Waktu</th>
                    <th rowspan="2" >RATE (%)</th>
                    <th rowspan="2" >angsuran</th>
                    <th rowspan="2" >TUJUAN <br/>PINJAMAN</th>
                    <th rowspan="2" >KE</th>
                    <th rowspan="2">NAMA F.O.</th>
                    <th colspan="3">KETERANGAN</th>
                </tr>
                <tr>
                    <th >ID</th>
                    <th >ID.PINJAMAN</th>
                    <th >NAMA LENGKAP</th>
                    
                    <th style='width:5px'>PENCAIRAN </th>
                    <th style="width:8cm">KETERANGAN</th>
                    <th style="width:0">PARAF</th>
                </tr>
            </thead>
            <?php 
            $total_disburse = 0;
            $q = mysqli_query($con,"SELECT * from pinjaman p
            join karyawan k on k.id_karyawan=p.id_karyawan
             where p.id_cabang='$id_cabang' and p.tgl_cair between '$tgl_awal' and '$tgl_akhir'");
             echo mysqli_error($con);
             while($r=mysqli_fetch_array($q)){
                 $total_disburse += $r['jumlah_pinjaman'];
                 $group = explode("/",$r['id_detail_nasabah'])[2];
                 ?>
                 <tr >
                    <td style="height: 2.5px;" colspan="" ><?=$no++?>.</td>
                    <td class="kecil"><?=$r['id_detail_nasabah']?></td>
                    <td class="kecil"><?=$r['id_detail_pinjaman']?></td>
                    <td ><?=$r['nama_nasabah']?></td>
                    <td ><?=$r['no_hp']?></td>
                    <td class='kecil'><?=$r['center']?></td>
                    <td class='isi_tengah'><?=$group?></td>
                    <td class='kecil'><?=$r['produk']?></td>
                    <td ><?=angka($r['jumlah_pinjaman'])?></td>
                    <td ><?=angka($r['outstanding'])?></td>
                    <td class='isi_tengah'><?=$r['jk_waktu']?></td>
                    <td class='isi_tengah'  ><?=$r['margin']?></td>
                    <td ><?=angka($r['angsuran'])?></td>
                    <td ><?=$r['tujuan_pinjaman']?></td>
                    <td class='isi_tengah'><?=$r['pinjaman_ke']?></td>
                    <td class='kecil'><?=$r['nama_karyawan']?></td>
                    <td class='isi_tengah'><?=$r['tgl_cair']?></td>
                    <td ></td>
                    <td ></td>
                 </tr>
                 <?php
             }
            ?>

            
                <tr>
                    <th colspan="8">TOTAL</th>
                    <th colspan="1"><?=angka($total_disburse)?></th>
                    <th colspan="1"></th>
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
