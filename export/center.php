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
			<td>NO</td>
			<td>STAFF</td>
			<td>HARI</td>
			<td>id_karyawan</td>
			<td>JAM</td>
			<td>DESA</td>
		</tr>
		<?php 
		$q=mysqli_query($con,"SELECT * FROM center RIGHT JOIN karyawan ON center.id_karyawan=karyawan.id_karyawan WHERE center.id_cabang='$id_cabang' order by center.no_center asc");
		while($ctr = mysqli_fetch_array($q)){
			?>
		<tr>
			<td><?=$ctr['no_center']?></td>
			<td><?=$ctr['nama_karyawan']?></td>
			<td><?=strtoupper($ctr['hari'])?></td>
			<td><?=($ctr['id_karyawan'])?></td>
			<td><?=($ctr['jam_center'])?></td>
			<td><?=str_replace(" ","",$ctr['desa'])?></td>
		</tr>
			<?php
		}
		?>
	</table>
	
</body>
</html>
	

