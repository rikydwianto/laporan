
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
<div class="col-md-12">
	<div class="panel-body post-body table-responsive " >
		<h3 class="page-header">
			REKAP LAPORAN MINGGUAN
		</h3>
		<form method='get' action='<?php echo $url.$menu ?>rekap_laporan'>
		<input type="hidden" name='menu' value='rekap_laporan_minggu'/>
				<input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d",(strtotime ( '-4 day' , strtotime ( date("Y-m-d")) ) )) )?>" class=""/>
				<input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
				<input type='submit' class="btn btn-info" name='cari' value='FILTER'/>
		</form>
	<a href="<?=$url?>/export/laporan_minggu.php?&tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>&cari=FILTER" class='btn btn-success'>
			<i class="fa fa-file-excel-o"></i> Export To Excel
		</a>
	<table class='table'>
		<tr>					
			
			<th colspan=10 style="text-align:center">LAPORAN <?php echo format_hari_tanggal($tglawal);?> - <?php echo format_hari_tanggal($tglakhir);?></th>
		</tr>
		<tr>				
			<th >NO</th>
			<th >NAMA</th>
			<td >CTR</td>
			<td >DTD</td>
			<td >AGT</td>
			<td >CLIENT</td>
			<td >Bayar</td>
			<td >Tdk Bayar</td>
			<td >Persen</td>
			<td >KETERANGAN</td>
		</tr>
		<?php 
		
		$cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
		$hitung_agt = 0; 
		$hitung_member = 0; 
		$hitung_dtd = 0; 
		$hitung_bayar = 0; 
		$hitung_tdk_bayar= 0; 
		$hitung_center= 0; 
		while($tampil=mysqli_fetch_array($cek_ka)){
			$cek_l1 = mysqli_query($con,"select * from laporan where id_karyawan='$tampil[id_karyawan]' and tgl_laporan >= '$tglawal' and tgl_laporan <= '$tglakhir'");
			$cek_l=mysqli_query($con,"SELECT sum(detail_laporan.total_agt)as anggota,sum(detail_laporan.member)as member, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, count(if(doortodoor='y',1,NULL) ) as hitung_dtd, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.id_karyawan='$tampil[id_karyawan]'  and laporan.tgl_laporan >= '$tglawal' and laporan.tgl_laporan <= '$tglakhir'");
			if(mysqli_num_rows($cek_l)){
				$tampil_lapor=mysqli_fetch_array($cek_l);
				if($tampil_lapor['bayar']!=NULL){
					$hitung_dtd = $hitung_dtd + $tampil_lapor['hitung_dtd']; 
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
					<td><?php echo $tampil_lapor['hitung_dtd'] ?></td>
					<td><?php echo $tampil_lapor['member'] ?></td>
					<td><?php echo $tampil_lapor['anggota'] ?></td>
					<td><?php echo $tampil_lapor['bayar'] ?></td>
					<td><?php echo $tampil_lapor['tidak_bayar'] ?></td>
					<td><?php echo round(($tampil_lapor['bayar']/$tampil_lapor['anggota'] *100  ))?>%</td>					
					<td>
						<?php
						while($ket = mysqli_fetch_array($cek_l1)){

							echo "<pre>".$ket['keterangan_laporan']."</pre><br/>";
							
						}
							?>
					</td>					
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
							<td colspan=10><i>belum membuat laporan</td>
							
						</tr>
						<?php
						}
				}
			}
			else {
			?>
				<tr>
					<td colspan=7>Belum bikin laporan </td>
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
			<th ><?php echo $hitung_dtd ?></th>
			<th ><?php echo $hitung_member ?></th>
			<th ><?php echo $hitung_agt ?></th>
			<th ><?php echo $hitung_bayar ?></th>
			<th ><?php echo $hitung_tdk_bayar ?></th>
			<th colspan=9><?php echo $persen  = round(($hitung_bayar/$hitung_agt)*100,2) ?>%</th>
		</tr>
	</table>
	
	<a href="<?=$url.$menu?>rekap_laporan_minggu&grafik&bayar=<?=$hitung_bayar?>&member=<?=$hitung_member?>&client=<?=$hitung_agt?>&persen=<?=$persen?>&tgl=<?=$tglawal?>&tgl1=<?=$tglakhir?>&dtd=<?=$hitung_dtd?>"
	 class="btn btn-danger"
	 onclick="return window.confirm('Apakah Sudah benar???')"
	 >Simpan Ke Grafik</a>
		<br>** PASTIKAN LAPORAN TELAH APPROVE SEMUA DAN TELAH selesaikan <br>
		*** TIDAK DAPAT DIEDIT

		<h2>PENURUNAN  PAR</h2>
	<table class="table">
		<tr>
			<th>NO</th>
			<th>NAMA</th>
			<th>TGL</th>
			<th>KETERANGAN</th>
		</tr>
		<?php
		$no1=1;
		$cek_ket = mysqli_query($con,"SELECT * from laporan l join karyawan k on k.id_karyawan=l.id_karyawan where k.id_cabang='$id_cabang' and tgl_laporan >= '$tglawal' and tgl_laporan <= '$tglakhir' and keterangan_lain is not null");
		echo mysqli_error($con);
		while($r = mysqli_fetch_array($cek_ket)){
			?>
		<tr>
			<td><?=$no1++?></td>
			<td><?=$r['nama_karyawan']?></td>
			<td><?=$r['tgl_laporan']?></td>
			<td><pre><?=$r['keterangan_lain']?></pre></td>
		</tr>
		<?php
		}
		?>

	</table>
	</div>
</div>
			

<?php 

//approve laporan
if(isset($_GET['grafik'])){
	$bayar = $_GET['bayar'];
	$tgl = $_GET['tgl'];
	$tgl1 = $_GET['tgl1'];
	$tgl2 = $tgl ." / ". $tgl1;
	$member = $_GET['member'];
	$client = $_GET['client'];
	$persen = $_GET['persen'];
	$dtd = $_GET['dtd'];
	$q = mysqli_query($con,"INSERT INTO `grafik` (`id_grafik`, `tgl_grafik`, `member`, `client`, `bayar`, `persen`, `id_cabang`,`dtd`) VALUES (NULL, '$tgl2', '$member', '$client', '$bayar', '$persen', '$cabang','$dtd');
	");
	if($q){
		pesan("Berhasil di simpan");
		pindah("$url$menu"."rekap_laporan_minggu&tglawal=$tgl&tglakhir=$tgl1&cari=FILTER");
	}
}
?>


