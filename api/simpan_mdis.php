<?php
//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

if( isset($_GET['id'])){
    $id = aman($con,$_GET['id']);
    $edit  = mysqli_query($con, "update perbaikan set status_input='sudah' where id_perbaikan='$id'");

}