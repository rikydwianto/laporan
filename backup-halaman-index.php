<?php 
$query= mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_karyawan='$id_karyawan' ");
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

<div class="col-md-4 " style="">
  <div class="panel panel-default ">
	
	
	<div class="panel-body ">
		<h3 class='page-header'>INFORMASI <hr/></h3>
		<div class="card" style=";">
		  <ul class="list-group list-group-flush">
			<li class="list-group-item"><?php echo strtoupper($karyawan['nama_karyawan']) ?> </li>
			<li class="list-group-item">Jabatan : (<?php echo $karyawan['singkatan_jabatan'] ?>) <?php echo $karyawan['nama_jabatan'] ?></li>
			<li class="list-group-item">Cabang: (<?php echo $karyawan['kode_cabang'] ?>) <?php echo strtoupper($karyawan['nama_cabang']) ?></li>
		  </ul>
		</div>
		
		<?php 
		if($jabatan!='SL')
		{
			?>
			<h3 class='page-header'>STATUS CENTER<hr/></h3>
			<div class="card divider" style=";">
			  <ul class="list-group list-group-flush">
				<li class="list-group-item"><b>Total Center Doa :  <span style='background: blue' class="badge rounded-pill bg-primary"><?=$status['doa']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center Tidak Doa :  <span style='background: black' class="badge rounded-pill bg-danger"><?=$status['tidak_doa']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center Hijau :  <span style='background: green' class="badge rounded-pill bg-success"><?=$status['tidak_doa']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center Merah :  <span style='background: red' class="badge rounded-pill bg-danger"><?=$status['merah']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center Kuning :  <span style='background: #eea236;text-decoration-color: black;' class="badge rounded-pill bg-danger"><?=$status['kuning']?> </span></b> </li>
				<li class="list-group-item"><b>Total Center hitam :  <span style='background: black;' class="badge rounded-pill bg-danger "><?=$status['hitam']?> </span></b> </li>
				<li class="list-group-item"><b>Total Semua Center :  <span class="badge rounded-pill bg-danger"><?=$hitung->hitung_center($con,$id_cabang)?> </span></b> </li>
			  </ul>

			</div>
			<?php
		}
		?>
		
	</div>



  
  
	</div>
	
</div>

<div class="col-md-8" >
  <div class="panel panel-default  " style="">
	<?php 

	if($jabatan=='SL')
	{
	?>
		<h2 class="page-header">
			<?php echo format_hari_tanggal(date("Y-m-d"))."<hr/>";?>
		</h2>
		<div class='table-responsive'>
			<table class='table'>
			<tr>
				<th>No. CTR</th>
				<th>Status</th>
				<th>Doa</th>
				<th>Anggota</th>
				<th>Bayar</th>
				<th>Tidak Bayar</th>

			</tr>
			<?php 
			
			if(!mysqli_num_rows($cek_laporan1)){
				echo"
				<tr>
					<td style='text-align:center' colspan=6>
					<i>
					Anda belum membuat laporan hari ini <br/>
					silahkan 
					<a href='$url$menu"."tmb_laporan"."'>disini </a>
					
					untuk menambahkan laporan hari ini!
				<i>
					</td>
				</tr>
				";
				
			}
			else
			{
				$cq=mysqli_query($con,"select * from detail_laporan where id_laporan='$cek_laporan[id_laporan]'");
				if(!mysqli_num_rows($cq))
				{
					echo"<tr>
						<td colspan=7><i>data kosong!</i></td>
					</tr>";
				}
				else{
					$no1=1;
					$hitung_agt=0;
					$hitung_bayar=0;
					$hitung_tdk_bayar=0;
					while($ambil=mysqli_fetch_array($cq)){
						?>
						<tr>
							<td><?php echo $no1++.". ". $ambil['no_center']?> (<?php echo round((($ambil['total_bayar']/$ambil['total_agt'])*100),2)?>%)</td>
							<td><?php echo $ambil['status']?></td>
							<td><?php echo ($ambil['doa']=="t" ? "T" : "Y")?></td>
							<td><?php echo $ambil['total_agt']?></td>
							<td><?php echo $ambil['total_bayar']?></td>
							<td><?php echo $ambil['total_tidak_bayar']?>
							
							</td>
						</tr>
						
						<?php
						$hitung_agt = $hitung_agt + $ambil['total_agt'];
						$hitung_bayar = $hitung_bayar + $ambil['total_bayar'];
						$hitung_tdk_bayar = $hitung_tdk_bayar + $ambil['total_tidak_bayar'];
					}
				}
			}
			?>
			<tr>
				<th colspan=3>Total</th>
				<th colspan=1><?php echo $hitung_agt ?></th>
				<th ><?php echo $hitung_bayar ?></th>
				<th>
					<?php echo  $hitung_tdk_bayar ?>
				</th>
			</tr>
			<tr>
				<th colspan=5 style="text-align:center">
					Prosentase pembayaran <?php echo round(($hitung_bayar/$hitung_agt)*100,2)?>% <br/>
					<?php 
					if($cek_laporan['status_laporan']=='pending'){
						echo"<i>laporan belum di konfirmasi, silahkan selesaikan laporan</i>";
						?>
						<a href="<?php echo ("$url$menu"."tmb_laporan&id_laporan=".$cek_laporan['id_laporan']);?>" class=" ">tambah</a>
						<?php
					}
					?>
				</th>
				<th>
					
				</th>
			</tr>
			</table>
		</div>
	<?php
		
	}
	else if($jabatan=='BM' || $jabatan=='ASM' || $jabatan=='MIS' ){
	?>
		<h2 class="page-header">
			<?php echo format_hari_tanggal(date("Y-m-d"))."<hr/>";?>
		</h2>
		<div class='table-responsive'>
		<table class='table'>
			<tr>					
				<th rowspan=2>NO</th>
				<th rowspan=2>NAMA</th>
				<th colspan=7 style="text-align:center">LAPORAN</th>
			</tr>
			<tr>					

				<td >CTR</td>
				<td >AGT</td>
				<td >Bayar</td>
				<td >Tdk Bayar</td>
				<td >%</td>
				<td >Keterangan</td>
				<td >#</td>
			</tr>
			<?php 

			$cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
			$hitung_agt = 0; 
			$hitung_bayar = 0; 
			$hitung_tdk_bayar= 0; 
			$hitung_center= 0; 
			while($tampil=mysqli_fetch_array($cek_ka)){
				$cek_l=mysqli_query($con,"SELECT sum(detail_laporan.total_agt)as anggota, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan=curdate() and laporan.id_karyawan='$tampil[id_karyawan]'");
				if(mysqli_num_rows($cek_l)){
					$tampil_lapor=mysqli_fetch_array($cek_l);
					if($tampil_lapor['bayar']!=NULL){
						$hitung_agt = $hitung_agt + $tampil_lapor['anggota']; 
						$hitung_bayar = $hitung_bayar + $tampil_lapor['bayar']; 
						$hitung_tdk_bayar= $hitung_tdk_bayar + $tampil_lapor['tidak_bayar']; 
						$hitung_center= $hitung_center + $tampil_lapor['hitung_center']; 
				?>
					<tr>
						<td><?php echo $no++ ?>.</td>

						<td><?php echo $tampil['nama_karyawan'] ?></td>
						<td><?php echo $tampil_lapor['hitung_center'] ?></td>
						<td><?php echo $tampil_lapor['anggota'] ?></td>
						<td><?php echo $tampil_lapor['bayar'] ?></td>
						<td><?php echo $tampil_lapor['tidak_bayar'] ?></td>
						<td><?php echo round(($tampil_lapor['bayar']/$tampil_lapor['anggota'] *100  ))?>%</td>

						<td><?php echo $tampil_lapor['keterangan_laporan'] ?></td>
						<td><small><i><?php echo $tampil_lapor['status_laporan'] ?></i></small></td>
						
					</tr>
						<?php
					}
					else{
						?>
						<tr>
							<td><?php echo $no++ ?>.</td>

							<td><?php echo $tampil['nama_karyawan'] ?></td>
							<td colspan=8><i>belum membuat laporan</td>
							
						</tr>
						<?php
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
			?>
			<tr>
				<th colspan=2>Total</th>
				<th ><?php echo $hitung_center ?></th>
				<th ><?php echo $hitung_agt ?></th>
				<th ><?php echo $hitung_bayar ?></th>
				<th ><?php echo $hitung_tdk_bayar ?></th>
				<th colspan=6><?php echo round(($hitung_bayar/$hitung_agt)*100) ?>%</th>
			</tr>
		</table>
		</div>
	<?php
		
	}
	?>
	
</div>

  
</div>