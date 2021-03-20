<div class="row">
	<h3 class="page-header">Input Anggota Masuk/Keluar</h3>
	<form method="post">
		<?php 
		if(isset($_POST['tambah_pemb']))
		{
			$idk=$_POST['idk'];
			$ppd=$_POST['ppd'];
			$psa=$_POST['psa'];
			$prr=$_POST['prr'];
			$arta=$_POST['arta'];
			$tgl=$_POST['tgl'];
$pmb=$_POST['pmb'];


			for ($x = 0; $x < count($idk); $x++) {
				
				$cek_dulu = mysqli_query($con,"select * from anggota where id_karyawan='$idk[$x]' and tgl_anggota='$tgl' and id_cabang='$id_cabang'");
				if(mysqli_num_rows($cek_dulu)>0)
				{
					$data = mysqli_fetch_array($cek_dulu);
					$query = mysqli_query($con,"UPDATE `anggota` SET 
						`psa` = '$psa[$x]',
						`prr` = '$prr[$x]',
						`ppd` = '$ppd[$x]',
						`arta` = '$arta[$x]'
						 WHERE `id_karyawan` ='$idk[$x]' and tgl_anggota='$tgl'; ");
				}
				else
				{
					$query1 = mysqli_query($con,"INSERT INTO anggota (id_anggota, id_karyawan, tgl_anggota, psa, prr, ppd,arta,pmb, id_cabang) VALUES (NULL, '$idk[$x]', '$tgl', '$psa[$x]', '$prr[$x]', '$ppd[$x]', '$arta[$x]','$pmb[x]', '$id_cabang');");
				}
					
			}
				

				
			if($query || $query1)
			{
				pesan("Berhasil ditambahkan",'success');
				// pindah("$url");
			}
			else
			{
				pesan("Gagal",'danger');
			}
		}
		?>
		<div class="col-lg-5">
		PILIH TANGGAL <input type=date name="tgl" class=" form-control" value="<?php echo date('Y-m-d') ?>" ></input>
			
		<br>
		</div>
		isi 0 jika tidak ada 
		<table class="table">
			<input type='hidden' name='menu' class="form-control" value='anggota'></input>
			<tr>
				<th>NO</th>
				<th>Staff</th>
				<th>PSA</th>
				<th>PPD</th>
				<th>PRR</th>
				<th>ARTA</th>
			</tr>
			<?php 
			$qk=mysqli_query($con,"select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
			while($cek_ka=mysqli_fetch_array($qk))
			{
				?>
				<tr>
					<td><?=$no++?></td>
					<td><?=$cek_ka['nama_karyawan']?></td>
<td>
						
						<input  type='number' class="form-control" style="width: 100px" name='pmb[]'  value='0'></input>
					</td>
					<td>
						<input type='hidden' name='idk[]' class="form-control" value='<?=$cek_ka['id_karyawan']?>'></input>
						<input  type='number' class="form-control" style="width: 100px" name='psa[]'  value='0'></input>
					</td>
					<td>
						<input  type='number' class="form-control" style="width: 100px" name='ppd[]'  value='0'></input>

					</td>
					<td>
						<input  type='number' class="form-control" style="width: 100px" name='prr[]'  value='0'></input>

					</td>
					<td>
						<input  type='number' class="form-control" style="width: 100px" name='arta[]'  value='0'></input>

					</td>
				</tr>
				<?php
				
			}
			?>
			<tr>
				<td colspan="5"></td>
				<td >
					
					<input type='submit' name='tambah_pemb' class="btn btn-info " value='SIMPAN'></input>
				</td>
			</tr>
		</table>
	</form>
</div>
