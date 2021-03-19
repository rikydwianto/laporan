
<?php
	 if(isset($_GET['tgl']))
	{
		$qtgl=$_GET['tgl'];
	}
	else{
		$qtgl=date("Y-m-d");
	}
?>
<div class="col-md-12" style=";">
	<div class="table-responsive " >
		<h3 class="page-header">
			EDIT LAPORAN
		</h3>
		<form method='get' action='<?php echo $url.$menu ?>rekap_laporan'>
		<input type=hidden name='menu' value='edit_laporan' />
		<input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
		<input type=submit name='cari' value='CARI' />
		</form>
	
	<table class='table'>
		<tr style="text-align:center">					
			<th colspan=5 class='text-center'> <?php echo format_hari_tanggal($qtgl);?></th>
		</tr>					
		<tr>					
			<th >NO</th>
			<th >NAMA</th>
			<td >Keterangan</td>
			<td >Status</td>
			<td >Un-Approve</td>
			<td >#</td>
		</tr>
		<?php 
		
		$cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' order by karyawan.nama_karyawan asc");
		$hitung_agt = 0; 
		$hitung_bayar = 0; 
		$hitung_tdk_bayar= 0; 
		$hitung_center= 0; 
		while($tampil=mysqli_fetch_array($cek_ka)){
			$cek_stat=mysqli_query($con,"select * from laporan where id_karyawan='$tampil[id_karyawan]' and tgl_laporan='$qtgl'");
			if(mysqli_num_rows($cek_stat))
			{
				$cek_status=mysqli_fetch_assoc($cek_stat);
				?>
			<tr>
				<td><?=$no++?></td>
				<td><?=$tampil['nama_karyawan']?></td>
				<td><?=$cek_status['keterangan_laporan']?></td>
				<td>
					<small><i><?=$cek_status['status_laporan']?></i></small>
				</td>
				<td>
					<small><i>
						<?php 
						if($cek_status['status_laporan']=='sukses')
						{
						?>
							<a href='<?php echo $url.$menu?>tmb_laporan&id_laporan=<?=$cek_status['id_laporan']?>&non_app&url=edit_laporan&tgl=<?=$qtgl?>' onclick="return window.confirm('Yakin akan Un-Approve laporan ini?')" class='btn btn-danger'>Un-Approve</a>

						<?php 	
						}
						else{
						?>
							<a href='<?php echo $url.$menu?>tmb_laporan&id_laporan=<?=$cek_status['id_laporan']?>&approve&url=edit_laporan&tgl=<?=$qtgl?>' onclick="return window.confirm('Yakin akan Un-Approve laporan ini?')" class='btn btn-success'>Approve</a>

						<?php 	
						}
						?>
						

					</i></small>
				</td>
				<td>
					<a href='<?php echo $url.$menu?>tmb_laporan&id_laporan=<?=$cek_status['id_laporan']?>' class='btn btn-info' ><i class='fa fa-edit'></i></a>
					<a href='<?php echo "$url$menu"."tmb_laporan&id_laporan=$cek_status[id_laporan]&dellaporan&url=edit_laporan"?>' class='btn btn-danger' onclick="return window.confirm('Yakin akan hapus laporan ini??')"><i class='fa fa-times'></i></a>
				</td>
				
			</tr>
				
				<?php
			}
			else{
				?>
			<tr>
				<td><?=$no++?></td>
				<td><?=$tampil['nama_karyawan']?></td>
				<td colspan=4>
					<small><i>belum membuat laporan...</small>
				</td>
			</tr>
				<?php
			}
		
			
		}
		?>
		<tr>
			<th colspan=5 class='text-center'> BATAS</th>
		</tr>
	</table>
	</div>
</div>
			