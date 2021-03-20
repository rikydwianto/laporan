<?php 
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
if( !isset($_SESSION['id'])){
	// pindah("auth.php");
}
else
{
	$wil = aman($con,$_GET['wil']);
	$cabang = cabang($con,$wil);
	echo json_encode($cabang);

}
 ?>