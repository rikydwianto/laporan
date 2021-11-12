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
	
</head>
<body>
	<table border="1">
		<tr>
			<td>ID ANGGOTA</td>
			<td>NAMA</td>
			<td>STAFF</td>
			<td>status</td>
		</tr>
		<?php 
		 $q = mysqli_query($con, "SELECT * from perbaikan 
         JOIN karyawan on perbaikan.id_karyawan=karyawan.id_karyawan
         JOIN center on perbaikan.no_center=center.no_center where perbaikan.status='sudah' and karyawan.id_cabang='$id_cabang' and status_input ='sudah' ");
		while($ctr = mysqli_fetch_array($q)){
			?>
		<tr>
			<td><?=$ctr['id_detail_nasabah']?></td>
			<td><?=strtoupper($ctr['nama_nasabah'])?></td>
			<td><?=($ctr['nama_karyawan'])?></td>
			<td><?=($ctr['status_input'])?></td>
		</tr>
			<?php
		}
		?>
	</table>
	
</body>
</html>
	

