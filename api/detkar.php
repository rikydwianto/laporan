<?php
// header('Content-Type: application/json; charset=utf-8');

//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

if (isset($_GET['id']) ) {

    $id  = aman($con,$_GET['id']);
    $d = mysqli_query($con,"select nama_karyawan,c.*,j.* from karyawan k join cabang c on c.id_cabang=k.id_cabang join jabatan j on j.id_jabatan=k.id_jabatan ");
    $d = mysqli_fetch_assoc($d);

    echo json_encode($d);
}
