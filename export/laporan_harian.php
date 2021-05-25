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
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan harian $hari .xls");
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
	<title></title>
</head>
<body>
	<table class='' border="1">
		<tr>					
			<th colspan=8 style="text-align:center">LAPORAN <?php echo format_hari_tanggal($qtgl);?></th>
		</tr>
		<tr>					

			<td >No. </td>
			<td >Nama</td>
			<td >CTR</td>
			<td >AGT</td>
			<td >Client</td>
			<td >Bayar</td>
			<td >Tdk Bayar</td>
			<td >%</td>
			<td >Keterangan</td>
		</tr>
		<?php 
		
		$cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' order by karyawan.nama_karyawan asc");
		$hitung_agt = 0; 
		$hitung_bayar = 0; 
		$hitung_tdk_bayar= 0; 
		$hitung_center= 0; 
		while($tampil=mysqli_fetch_array($cek_ka)){
			$cek_l1 = mysqli_query($con,"select * from laporan where id_karyawan='$tampil[id_karyawan]' and tgl_laporan='$qtgl'");
			$cek_l=mysqli_query($con,"SELECT sum(detail_laporan.total_agt)as anggota,sum(detail_laporan.member)as member, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan='$qtgl' and laporan.id_karyawan='$tampil[id_karyawan]'");
			if(mysqli_num_rows($cek_l)){
				$tampil_lapor=mysqli_fetch_array($cek_l);
				if($tampil_lapor['bayar']!=NULL){
					$hitung_member = $hitung_member + $tampil_lapor['member']; 
					$hitung_agt = $hitung_agt + $tampil_lapor['anggota']; 
					$hitung_bayar = $hitung_bayar + $tampil_lapor['bayar']; 
					$hitung_tdk_bayar= $hitung_tdk_bayar+ $tampil_lapor['tidak_bayar']; 
					$hitung_center= $hitung_center + $tampil_lapor['hitung_center']; 
			?>
				<tr>
					<td><?php echo $no++ ?>.</td>

					<td><?php echo $tampil['nama_karyawan'] ?></td>
					<td><?php echo $tampil_lapor['hitung_center'] ?></td>
					<td><?php echo $tampil_lapor['member'] ?></td>
					<td><?php echo $tampil_lapor['anggota'] ?></td>
					<td><?php echo $tampil_lapor['bayar'] ?></td>
					<td><?php echo $tampil_lapor['tidak_bayar'] ?></td>
					<td><?php echo round(($tampil_lapor['bayar']/$tampil_lapor['anggota'] *100  ))?>%</td>

					<td><?php echo $tampil_lapor['keterangan_laporan'] ?></td>
					
				</tr>
					<?php
				}
				else{
					if(mysqli_num_rows($cek_l1))
						{
							$tampil_lapor1 = mysqli_fetch_array($cek_l1);
							?>
							<tr>
								
								<td><?php echo $no++ ?>.</td>

								<td><?php echo $tampil['nama_karyawan'] ?></td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0%</td>

								<td><?php echo $tampil_lapor1['keterangan_laporan'] ?></td>
							</tr>
							<?php
						}
						else
						{

						?>
						<tr>
							<td><?php echo $no++ ?>.</td>

							<td><?php echo $tampil['nama_karyawan'] ?></td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td><i>belum laporan</i></td>
							
						</tr>
						<?php
						}
					}
			}
			else {
			?>
				<tr>
					<td colspan=1>0 </td>
					<td colspan=1>0 </td>
					<td colspan=1>0 </td>
					<td colspan=1>0 </td>
					<td colspan=1>0 </td>
					<td colspan=1>0 </td>
					<td colspan=1>0 </td>
					<td colspan=1>0 </td>
					<td colspan=1><i>tidak buat laporan</i></td>
				</tr>
			<?php
			}
		?>
		
		<?php
			
		}
		?>
		<tr>
			<th colspan=2 class='text-center'>Total</th>
			<th ><?php echo $hitung_center ?></th>
			<th ><?php echo $hitung_member ?></th>
			<th ><?php echo $hitung_agt ?></th>
			<th ><?php echo $hitung_bayar ?></th>
			<th ><?php echo $hitung_tdk_bayar ?></th>
			<th colspan=1><?php echo round(($hitung_bayar/$hitung_agt)*100) ?>%</th>
			<th></th>
		</tr>
	</table>
</body>
</html>
	

