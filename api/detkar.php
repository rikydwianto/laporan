<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

if (isset($_GET['id']) ) {

    $id  = aman($con,$_GET['id']);
    $d = mysqli_query($con,"SELECT nama_karyawan,c.*,j.*, t.total_nasabah from 
    karyawan k join cabang c on c.id_cabang=k.id_cabang join jabatan j on j.id_jabatan=k.id_jabatan
    join total_nasabah t on t.id_karyawan=k.id_karyawan
    where k.id_karyawan='$id' ");
    $d = mysqli_fetch_assoc($d);

    echo json_encode($d);
}
