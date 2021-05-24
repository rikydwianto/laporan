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
$nama_jabatan=$d['singkatan_jabatan'];
 if(isset($_GET['tgl']))
	{
		$qtgl=$_GET['tgl'];
	}
	else{
		$qtgl=date("Y-m-d");
	}
	$hari = hari_biasa($qtgl);
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=laporan harian $hari .xls");
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
	th, td {
	  padding: 5px;
	  text-align: left;
	}
	</style>
</head>
<body>
	
</body>
</html>
	

