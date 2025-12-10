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
 if(isset($_GET['tglawal']) || isset($_GET['tglakhir']))
{
	$tglawal = $_GET['tglawal'];
	$tglakhir = $_GET['tglakhir'];
}
else{
	$tglawal = date("Y-m-d",strtotime ( '-4 day' , strtotime ( date("Y-m-d")))) ;
	$tglakhir = date("Y-m-d");
}
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan anggota dan pemb lainya .xls");

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	
<?php
 if(isset($_GET['tglawal']) || isset($_GET['tglakhir']))
{
	$tglawal = $_GET['tglawal'];
	$tglakhir = $_GET['tglakhir'];
}
else{
	$tglawal = date("Y-m-d",strtotime ( '-4 day' , strtotime ( date("Y-m-d")))) ;
	$tglakhir = date("Y-m-d");
}
	 
?>
<div class="row">
	
		<?php
		if(isset($_GET['cari']))
		{
			$tglawal = $_GET['tglawal'];
			$tglakhir = $_GET['tglakhir'];
			$data = new Hitung();
			 $data_ = $data->cari_anggota($con,$id_cabang,$tglawal,$tglakhir);
			 ?>
		 <table border=1>
		 	<tr>
		 		<th colspan=9 class='text-center'>
		 			<?php echo $tglawal.' s/d '. $tglakhir?>
		 		</th>
		 	</tr>
		 	<tr>
		 		<th>NO.</th>
		 		<th>Nama</th>
		 		<th>Masuk</th>
		 		<th>Keluar</th>
		 		<th>Nett</th>
		 		<th>PMB</th>
		 		<th>PSA</th>
		 		<th>PPD</th>
		 		<th>PRR</th>
		 		<th>ARTA</th>
		 	</tr>
		 	<?php 
		 	$masuk=0;
		 	$keluar=0;
		 	$nett = 0;
		 	$pmb = 0;
		 	$psa = 0;
		 	$ppd = 0;
		 	$prr = 0;
		 	$arta = 0;
		 	foreach ($data_ as $key => $val ) {
		 		
		 		?>

		 	<tr>
		 		<td><?=$no++ ?></td>
		 		<td><?=$val['nama_karyawan'] ?></td>
		 		<th><?=$masuk1 = $val['masuk'] ?></th>
		 		<th><?=$keluar1 =$val['keluar'] ?></th>
		 		<th><?=$nett1= $val['nett'] ?></th>
		 		<th><?=$pmb1= $val['pmb'] ?></th>
		 		<th><?=$psa1= $val['psa'] ?></th>
		 		<th><?=$ppd1= $val['ppd'] ?></th>
		 		<th><?=$prr1= $val['prr'] ?></th>
		 		<th><?=$arta1= $val['arta'] ?></th>
		 	</tr>
		 		<?php
		 		$masuk=$masuk1+$masuk;
		 		$keluar=$keluar1+$keluar;
		 		$nett=$nett1+$nett;
		 		$psa=$psa1+$psa;
		 		$pmb=$pmb1+$pmb;
		 		$ppd=$ppd1+$ppd;
		 		$prr=$prr1+$prr;
		 		$arta=$arta1+$arta;
		 	}
		 	?>
		 	<tr>
		 		<th colspan=2>Total</th>
		 		<th><?=$masuk?></th>
		 		<th><?=$keluar?></th>
		 		<th><?=$nett?></th>
		 		<th><?=$pmb?></th>
		 		<th><?=$psa?></th>
		 		<th><?=$ppd?></th>
		 		<th><?=$prr?></th>
		 		<th><?=$arta?></th>
		 	</tr>
		 </table>
			 <?php
		}
		?>
</div>

</body>
</html>
