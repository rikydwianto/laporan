<?php 
$query= mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang,wilayah where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang
	and cabang.id_wilayah=wilayah.id_wilayah
 and karyawan.id_karyawan='$id_karyawan' ");
$karyawan = mysqli_fetch_array($query);

if(!$_SESSION['jabatan']){
	$_SESSION['jabatan']=$karyawan['singkatan_jabatan'];
	$_SESSION['cabang']=$karyawan['id_cabang'];
	pindah($url);

}
	$cek_laporan1 = mysqli_query($con,"select * from laporan where tgl_laporan=curdate() and id_karyawan='$id_karyawan' ");

	$cek_laporan = mysqli_fetch_array($cek_laporan1);

?>
<?php 
	if ($jabatan!=="SL")
		include("view/statistik.php");
?>

<div class="col-md-4 ">
  <div class="panel panel-default ">
	

	
	<div class="panel-body ">
		<h3 class='page-header'>INFORMASI ! <hr/></h3>
		<div class="card">
		  <ul class="list-group list-group-flush">
			<li class="list-group-item"><?php echo strtoupper($karyawan['nama_karyawan']) ?> </li>
			<li class="list-group-item">Jabatan : (<?php echo $karyawan['singkatan_jabatan'] ?>) <?php echo $karyawan['nama_jabatan'] ?></li>
			<li class="list-group-item">Cabang: (<?php echo $karyawan['kode_cabang'] ?>) <?php echo strtoupper($karyawan['nama_cabang']) ?></li>
			<li class="list-group-item"> Wilayah <?php echo strtoupper($karyawan['wilayah']) ?></li>
		  </ul>
		</div>
		
		<?php
		if($jabatan=='SL'){
			$qpin = mysqli_query($con,"SELECT id_karyawan,
			SUM(CASE WHEN (DATEDIFF(CURDATE(), tgl_cair)) >0 AND (DATEDIFF(CURDATE(), tgl_cair)) <=14 THEN 1 ELSE 0 END) AS normal,
			SUM(CASE WHEN (DATEDIFF(CURDATE(), tgl_cair)) >14  THEN 1 ELSE 0 END) AS kurang_normal,
			COUNT(*) as total
				 FROM pinjaman WHERE monitoring='belum' and id_karyawan='$id_karyawan' GROUP BY id_karyawan ");
			$mon = mysqli_fetch_array($qpin);
			$mon1 = $mon['total'];
			?>
				<h3 class='page-header'>MONITORING ! <hr/></h3>
				<div class="card">
					<h4> Monitoring 0 - 14 hari : <?=$mon['normal']?> </h4>
					<h4>  lebih 14 hari : <?=$mon['kurang_normal']?> </h4>
					<h3>Total Monitoring  : <?=$mon1?> </h3>
				</div>
			<?php
		}

		


		//STATISTIK
		if($jabatan!='SL')
		{
			?>
			<h3 class='page-header'>STATUS CENTER<hr/></h3>
			<div class="card divider" >
			  <ul class="list-group list-group-flush">
				<li class="list-group-item"><b>MEMBER :  <span style='float:right;background: blue' class="badge rounded-pill bg-primary"><?=$hitung->hitung_client($con,$id_cabang);?> </span></b> </li>
				<li class="list-group-item"><b>Total Center Doa  <span style='float:right;background: blue' class="badge rounded-pill bg-primary"><?=$status['doa']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center Tidak Doa  <span style='float:right;background: black' class="badge rounded-pill bg-danger"><?=$status['tidak_doa']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center Hijau  <span style='float:right;background: green' class="badge rounded-pill bg-success"><?=$status['tidak_doa']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center Merah  <span style='float:right;background: red' class="badge rounded-pill bg-danger"><?=$status['merah']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center Kuning  <span style='float:right;background: #eea236;text-decoration-color: black;' class="badge rounded-pill bg-danger"><?=$status['kuning']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center hitam  <span style='float:right;background: black;' class="badge rounded-pill bg-danger "><?=$status['hitam']?> </span></b> </li>
				<li class="list-group-item"><b>DOORTODOOR  <span style='float:right;background: black;' class="badge rounded-pill bg-danger "><?=$status['iya']?> </span></b> </li>
				<li class="list-group-item"><b>SETENGAH DOORTODOOR  <span style='float:right;background: #eea236;' class="badge rounded-pill bg-warning "><?=$status['ragu']?> </span></b> </li>
				<li class="list-group-item"><b>KUMPULAN  <span style='float:right;background: green;' class="badge rounded-pill bg-yellow "><?=$status['tidak']?> </span></b> </li>
				<li class="list-group-item"><b>Total Semua Center  <span style='float:right' class="badge rounded-pill bg-danger"><?=$hitung->hitung_center($con,$id_cabang)?> </span></b> </li>
			  </ul>

			</div>
			<?php
			$qpin = mysqli_query($con,"SELECT id_karyawan,
			SUM(CASE WHEN (DATEDIFF(CURDATE(), tgl_cair)) >0 AND (DATEDIFF(CURDATE(), tgl_cair)) <=14 THEN 1 ELSE 0 END) AS normal,
			SUM(CASE WHEN (DATEDIFF(CURDATE(), tgl_cair)) >14  THEN 1 ELSE 0 END) AS kurang_normal,
			COUNT(*) as total
				 FROM pinjaman WHERE monitoring='belum' and id_cabang='$id_cabang' GROUP BY id_cabang ");
			$mon = mysqli_fetch_array($qpin);
			$mon1 = $mon['total'];
			
			?>
				<div class="card">
					<h4> KELUHAN MONITORING : <?=$hitung_banding?> </h4>
					<h4> Monitoring 0 - 14 hari : <?=$mon['normal']?> </h4>
					<h4>  lebih 14 hari : <?=$mon['kurang_normal']?> </h4>
					<h3>Total Monitoring  : <?=$mon1?> </h3>
				</div>
			<?php
		}
		?>
		
	</div>



  
  
	</div>
	
</div>

<div class="col-md-8" >
  <div class="panel panel-default  " >
	<?php 

	if($jabatan=='SL')
	{
		//ADA DI VIEW/MENU.PHP
		if($cekJam['belum']>0){
			include "proses/konfirmasi_center.php";
		}
		else{
			include "index-sl.php";
		}
		
	}
	else if($jabatan=='BM' || $jabatan=='ASM' || $jabatan=='MIS' ){
	?>
		<h2 class="page-header">
			<?php echo format_hari_tanggal(date("Y-m-d"))."";?>
		</h2>
		<a href="<?=$url?>/export/laporan_harian.php?tgl=<?=date("Y-m-d")?>" class='btn btn-success'>
			<i class="fa fa-file-excel-o"></i> Export To Excel
		</a>
		<div class='table-responsive'>
		<br>
		<table class='table'>
			<tr>					
				<th rowspan=2>NO</th>
				<th rowspan=2>NAMA</th>
				<th colspan=8 style="text-align:center">LAPORAN</th>
			</tr>
			<tr>					

				<td >CTR</td>
				<td >AGT</td>
				<td >Client</td>
				<td >Bayar</td>
				<td >Tdk Bayar</td>
				<td >%</td>
				<td >Change</td>
				<td >Keterangan</td>
				<td >#</td>
			</tr>
			<?php 

			$cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
			$hitung_member = 0; 
			$hitung_agt = 0; 
			$hitung_bayar = 0; 
			$hitung_tdk_bayar= 0; 
			$hitung_center= 0; 
			$hitung_chg = 0;
			while($tampil=mysqli_fetch_array($cek_ka)){
				$cek_l1 = mysqli_query($con,"select * from laporan where id_karyawan='$tampil[id_karyawan]' and tgl_laporan=curdate()");

				$cek_l=mysqli_query($con,"SELECT sum(detail_laporan.total_agt)as anggota, sum(detail_laporan.member)as member, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan=curdate() and laporan.id_karyawan='$tampil[id_karyawan]'");

				// echo "SELECT sum(detail_laporan.total_agt)as anggota, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan=curdate() and laporan.id_karyawan='$tampil[id_karyawan]'";
				if(mysqli_num_rows($cek_l) ){
					$tampil_lapor=mysqli_fetch_array($cek_l);
					if($tampil_lapor['anggota']!=NULL){
						$hitung_member = $hitung_member + $tampil_lapor['member']; 
						$hitung_agt = $hitung_agt + $tampil_lapor['anggota']; 
						$hitung_bayar = $hitung_bayar + $tampil_lapor['bayar']; 
						$hitung_tdk_bayar= $hitung_tdk_bayar + $tampil_lapor['tidak_bayar']; 
						$hitung_center= $hitung_center + $tampil_lapor['hitung_center']; 

						$tgl1 = date("Y-m-d");// pendefinisian tanggal awal
						$tgl2 = date('Y-m-d', strtotime('-7 days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari

						
						$qchg = mysqli_query($con,"SELECT SUM(detail_laporan.`total_bayar`) AS bayar,
						SUM(detail_laporan.`total_tidak_bayar`) AS tidak_bayar,
						(SUM(detail_laporan.`total_bayar`)/SUM(detail_laporan.`total_agt`) *100) AS persen
						 FROM laporan JOIN detail_laporan ON laporan.`id_laporan`=detail_laporan.`id_laporan` 
						
						WHERE laporan.`tgl_laporan`='$tgl2' AND laporan.id_karyawan='$tampil[id_karyawan]'
						 GROUP BY laporan.`id_karyawan`
						 
						");
						$persen = round(($tampil_lapor['bayar']/$tampil_lapor['anggota'] *100  ),2);
						$chg = mysqli_fetch_array($qchg);
						$chg = round($chg['persen'],2);
						$rubah = $persen - $chg ;
						$hitung_chg = $rubah + $hitung_chg;
						if($rubah>0){
							$warna_chg = "#52eb34";
						}
						else{
							$warna_chg = "#e4544d";
							
						}
				?>
					<tr>
						<td><?php echo $no++ ?>.</td>

						<td><?php echo $tampil['nama_karyawan'] ?></td>
						<td><?php echo $tampil_lapor['hitung_center'] ?></td>
						<td><?php echo $tampil_lapor['member'] ?></td>
						<td><?php echo $tampil_lapor['anggota'] ?></td>
						<td><?php echo $tampil_lapor['bayar'] ?></td>
						<td><?php echo $tampil_lapor['tidak_bayar'] ?></td>
						<td><?php echo $persen ?>%</td>
						<td style="color:<?=$warna_chg?>">
							<?php echo ($rubah==null?"0":$rubah."%") ?>

						</td>

						<td><?php echo $tampil_lapor['keterangan_laporan'] ?></td>
						<td><small><i><?php echo $tampil_lapor['status_laporan'] ?></i></small></td>
						
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
								<td><small><i><?php echo $tampil_lapor1['status_laporan'] ?></i></small></td>
							</tr>
							<?php
						}
						else
						{

						?>
						<tr>
							<td><?php echo $no++ ?>.</td>

							<td><?php echo $tampil['nama_karyawan'] ?></td>
							<td colspan=9><i>belum membuat laporan</td>
							
						</tr>
						<?php
						}
					}
				}
				else {

				?>
					<tr>
						<td colspan=5>Belum bikin laporan </td>
					</tr>
				<?php
				}
			?>
			
			<?php
				
			}
			if($hitung_chg>0){
				$warna_chg = "#52eb34";
			}
			else{
				$warna_chg = "#e4544d";
				
			}
			?>
			<tr>
				<th colspan=2>Total</th>
				<th ><?php echo $hitung_center ?></th>
				<th ><?php echo $hitung_member ?></th>
				<th ><?php echo $hitung_agt ?></th>
				<th ><?php echo $hitung_bayar ?></th>
				<th ><?php echo $hitung_tdk_bayar ?></th>
				<th ><?php echo $persen = round(($hitung_bayar/$hitung_agt)*100,2) ?>%</th>
				<th style="color:<?=$warna_chg?>" ><?php echo $hitung_chg ?></th>
			</tr>
		</table>
		</div>
	<?php
		
	}if($jabatan=='ADM') {
		?>
		<h2 class='page-header' style='text-align:center'>SISA MONITORING <hr/></h2>
		<table class="table table-bordered">
            <tr>
                <td>
                    NO 
                </td>
                <td>NIK</td>
                <td>STAFF</td>
                <td>SISA MONITORING</td>
                <td></td>
            </tr>
            <?php
            $total_monitoring = 0;
            $cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
            while($karyawan = mysqli_fetch_array($cek_ka)){
                ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$karyawan['nik_karyawan']?></td>
                <td><?=$karyawan['nama_karyawan']?></td>
                <td>
                    <?php 
                    $q = mysqli_query($con,"select count(id_detail_nasabah) as total from pinjaman where monitoring='belum' and id_karyawan='$karyawan[id_karyawan]' and id_cabang='$id_cabang'");
                    $total = mysqli_fetch_array($q);
                    $total = $total['total'];
                    $total_monitoring =$total + $total_monitoring;
                    echo $total ;
                    ?>
                    
                </td>
                <td><a href="<?= $url . $menu ?>monitoring&id=<?=$karyawan['id_karyawan']?>" > Detail</a> </td>
            </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="3"></td>
                <td><?=$total_monitoring?></td>
            </tr>
        </table>
		<?php

	}

	?>




	
</div>

  
</div>

<div class="col-md-12 " >
<h1>Grafik Minggu</h1>
<h3>Baca Grafik dari kiri ke kanan</h3>
<hr> <small>hanya 12 minggu terbaru </small>
  <div class="panel panel-default ">
  <canvas id="myChart" style="width:100%;max-width:100%;min-height:40px;"></canvas>


  </div>
</div>
