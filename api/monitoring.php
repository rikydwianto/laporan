<?php
//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

if (isset($_GET['mtr']) && isset($_GET['mtr'])) {

    $id  = aman($con,$_GET['id']);
    $mtr  = $_GET['mtr'];
    $detail  = $_GET['detail'];
    $edit  = mysqli_query($con, "update pinjaman set monitoring='$mtr' where id_pinjaman='$id'");
    $keluan  = mysqli_query($con, "update banding_monitoring set status='selesai' where id_detail_pinjaman='$detail'");
    if ($edit) {
        echo "berhasil";
    } else {
        echo "gagal";
    }
}
else if(isset($_GET['refresh'])){
    
}