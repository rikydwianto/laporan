<div class="row">
	<h3 class="page-header">Input Anggota Masuk/Keluar</h3>
	<form method="post">
		<?php 
		if(isset($_POST['tambah_anggota']))
		{
			$idk=$_POST['idk'];
			$masuk=$_POST['masuk'];
			$keluar=$_POST['keluar'];
			$tgl=$_POST['tgl'];

			for ($x = 0; $x < count($idk); $x++) {
				$nett = $masuk[$x] - $keluar[$x];
					$query = mysqli_query($con,"INSERT INTO anggota (id_anggota, id_karyawan, tgl_anggota, anggota_masuk, anggota_keluar, net_anggota, id_cabang) VALUES (NULL, '$idk[$x]', '$tgl', '$masuk[$x]', '$keluar[$x]', '$nett', '$id_cabang');");
				
			}
			if($query)
			{
				pesan("Berhasil ditambahkan",'success');
				pindah("$url");
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
				<th>Anggota Masuk</th>
				<th>Anggota Keluar</th>
				<th>NETT</th>
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
						<input type='number' class="form-control" style="width: 100px" name='masuk[]' id='masuk<?=$cek_ka['id_karyawan']?>' value='0'></input>
						<input type='hidden' name='idk[]' class="form-control" value='<?=$cek_ka['id_karyawan']?>'></input>

					</td>
					<td>
						<input  type='number' class="form-control" style="width: 100px" name='keluar[]' id='keluar<?=$cek_ka['id_karyawan']?>' onkeyup="ganti_net('<?=$cek_ka['id_karyawan']?>')" value='0'></input>

					</td>
					<td>
						<input  type='number' class="form-control" readonly style="width: 100px" name='nett[]' id='nett<?=$cek_ka['id_karyawan']?>' value='0'></input>

					</td>
				</tr>
				<?php
				
			}
			?>
			<tr>
				<td colspan="4"></td>
				<td >
					
					<input type='submit' name='tambah_anggota' class="btn btn-info " value='SIMPAN'></input>
				</td>
			</tr>
		</table>
	</form>
</div>