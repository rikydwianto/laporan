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
// echo var_dump($_GET);
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
            font-size: 10px;
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
        table tbody tr td{
            vertical-align: middle;
            height: 1cm;
        }
        .isi_tengah{
            text-align: center;
        }
        .kecil{
            font-size: 9px;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Daftar Anggota Keluar</title>
</head>
<body>
    <div class='kertas'>
        <div class='tengah'>
            <h1>DAFTAR ANGGOTA KELUAR</h1>
            <h3>CABANG <?=strtoupper($d['nama_cabang'])?></h3>
            <h3>Periode : <?=format_hari_tanggal($tgl_awal)?> s/d <?=format_hari_tanggal($tgl_akhir)?></h3>

        </div>
        <table>
            <thead>
               <th>NO</th>
               <th>ID</th>
               <th>ID DETAIL</th>
               <th>CTR</th>
                <th>KLP</th>
               <th>ANGGOTA</th>
               <th>TGL KELUAR</th>
               <th>ALASAN</th>
               <th>STAFF</th>
            </thead>
           <tbody>
               <?php 
               $q= mysqli_query($con,"
               SELECT * from temp_anggota_keluar a right join daftar_nasabah_mantan  mantan on a.id_nasabah=mantan.id_nasabah 
               join karyawan k on a.id_karyawan=k.id_karyawan
               where mantan.id_cabang='$id_cabang' and a.tgl_keluar between '$tgl_awal' and '$tgl_akhir' 
               group by a.id_nasabah order by a.tgl_keluar,k.nama_karyawan asc");
               echo mysqli_error($con);
               while($r =mysqli_fetch_array($q)){
                   ?>
                <tr>
                    <td><?=$no++?></td>
                    <td style='text-align: center;'><?=$r['id_nasabah']?></td>
                    <td ><span style='padding-left:10px;padding-right:10px;padding-top:10px'><?=$r['id_detail_nasabah']?></span></td>
                    <td style='text-align: center;'><?=$r['no_center']?></td>
                    <td  style='text-align: center;'><?=sprintf("%03d",explode("/",$r['id_detail_nasabah'])[2])?></td>
                    <td><span style='padding-left:10px;padding-right:10px;padding-top:10px'><?=$r['nama_nasabah']?></span></td>
                    <td style='text-align: center;'><?=$r['tgl_keluar']?></td>
                    <td ><span style='padding-left:10px;padding-right:10px;padding-top:10px'><?=$r['alasan']?></span></td>
                    <td><span style='padding-left:10px;padding-right:10px;padding-top:10px'><?=$r['nama_karyawan']?></span></td>
                    
                </tr>
                   <?php
               }
               ?>
           </tbody>
           <tfoot>
               <tr>
                   <th colspan="6">TOTAL ANGGOTA MASUK</th>
                   <th colspan="1"><?=$no-1?></th>
               </tr>
           </tfoot>
            
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
