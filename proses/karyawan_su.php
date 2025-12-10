<div class='content table-responsive'>
	<h3 class='page-header'>SELURUH DATA KARYAWAN</h3>
	<a href='<?=$url.$menu?>karyawan_su&tambah' class='btn btn-info '><i class='fa fa-plus'></i> Tambah Staff</a> <br/>
	<?php 
	if(isset($_SESSION['pesan'])){
		?>
		<div class="alert alert-success" role="alert">
		  <?php echo $_SESSION['pesan'] ?>
		</div>
			<?php
			unset($_SESSION['pesan']);
	}
	?>
	<div>
		<?php 
		if(isset($_GET['tambah']) ){
		?>
		
		<div class='col-md-6'>
		<form method='post'>
			<h3 class='page-header divider'>Tambah Karyawan</h3>
			<?php 
			if(isset($_POST['tmb_karyawan']))
			{
				$nik=$_POST['nik'];
				$nama=$_POST['nama'];
				$jabat=$_POST['jabatan'];
				$pass=$_POST['password1'];
				$cabang=$_POST['cabang'];
				$cek_nik=mysqli_query($con,"select * from karyawan where nik_karyawan='$nik'");
				$cek_nik1=mysqli_fetch_assoc($cek_nik);
				if($cek_nik1['nik_karyawan']==$nik){
					$_SESSION['pesan']="NIK $nik Sudah ada tidak input yang sama 2 kali";
				}
				else{
					$tambah = "INSERT INTO karyawan (id_karyawan, nik_karyawan, nama_karyawan, id_jabatan, status_karyawan, password, id_cabang) VALUES (NULL, '$nik', '$nama', '$jabat', 'aktif', MD5('$pass'), '$cabang'); ";
					$tambah = mysqli_query($con,$tambah);
					if($tambah){
						$_SESSION['pesan']="BERHASIL DISIMPAN";
						pindah($url.$menu."karyawan_su");
					}
					else
					{
						$_SESSION['pesan']="GAGAL DISIMPAN";
					}
				}
				
				
			}
			
			
			
			
			
			
			
			
			?>
				<div class="mb-3">
				  <label for="nik" required class="form-label">NIK (0001/2004)</label>
				  <input type="text" required name='nik' class="form-control" id="nik" placeholder="0001/2004">
				</div>
				<div class="mb-3">
				  <label for="nama" class="form-label">NAMA STAFF</label>
				  <input type="text" required name='nama' class="form-control" id="nik" placeholder="Tuliskan Nama">
				</div>
				<div class="mb-3">
				  <label for="jabatan" class="form-label">JABATAN</label>
				  <select name='jabatan' required class="form-control" aria-label="Default select example "id='jabatan'>
					<option value=''> -- Silahkan Pilih jabatan --</option>
					<?php 
					$jab = mysqli_query($con,"select * from jabatan ");
					while($jab1=mysqli_fetch_assoc($jab)){
						echo "<option value='$jab1[id_jabatan]'>$jab1[nama_jabatan]</option>";
						
					}
					?>
				  </select>
				</div>
				<div class="mb-3">
				  <label for="wilayah" class="form-label">Wilayah/Cabang</label>
				  <select name='cabang' required class="form-control" aria-label="Default select example "id='wilayah'>
					<option value=''> -- Silahkan Pilih Cabang --</option>
					<?php 
					$jab = mysqli_query($con,"select * from wilayah ");
					while($wil=mysqli_fetch_assoc($jab)){
						echo "<option value='' disabled><label>$wil[wilayah]</label></option>";
						$cab = mysqli_query($con,"select * from cabang where id_wilayah=$wil[id_wilayah] ");
						while($jab1=mysqli_fetch_assoc($cab)){
							echo "<option value='$jab1[id_cabang]' > --- ".  strtoupper($jab1['nama_cabang'])."</option>";
						}
					}
					?>
				  </select>
				</div>
				<div class="mb-3">
				  <label for="password1" required class="form-label">Masukan Password</label>
				  <input type="text" value='123456' name='password1' class="form-control" id="password" placeholder="Ketikan Password ....">
				</div>
				<div class="mb-3">
				  <br/>
				  <input type=submit name='tmb_karyawan' class='btn btn-info' value='TAMBAH' /> <a href='<?=$url.$menu?>karyawan_su' class='btn btn-danger '><i class='fa fa-times'></i> BATAL</a>
				</div>
				
				 <br/>
		</form>
		<br/>
		<br/>
		</div>
		
		
		<?php		
		}
		?>
	</div>
	<div class='content'>
	
		<?php 
		if(isset($_POST['ubah']))
		{
			$idk=$_GET['idkaryawan'];
			$nik=$_POST['nik'];
			$nama=$_POST['nama'];
			$status=$_POST['status'];
			$idj=$_POST['jabatan'];
			$passbaru=$_POST['passbaru'];
			$cab=$_POST['cabang'];
			if($passbaru!="")
				$gantipass=",  password=md5('$passbaru')";
			else $gantipass="";
			$query=mysqli_query($con,"UPDATE karyawan SET nik_karyawan = '$nik',nama_karyawan = '$nama', status_karyawan='$status', id_jabatan='$idj', id_cabang='$cab' $gantipass WHERE id_karyawan = $idk;");
			if($query){
				pesan("BERHASIL DIUBAH",'success');
			}
			else{
				pesan("GAGAL DI UBAH",'danger');
			}
		}
		
		
		
		
		if(isset($_GET['edit']))
		{
			$idk=$_GET['idkaryawan'];
			$data = detail_karyawan($con,$idk);
		?>
			<h4>EDIT <?=$data['nama_karyawan'] ?></h4>
			<form method=post>
				<table class='table table-bordered'>
					<tr>
						<th>NIK</th>
						<th><input type='text' value='<?=$data['nik_karyawan']?>' name='nik' class='form-control'/></th>
					</tr>
					<tr>
						<th>NAMA</th>
						<th><input type='text' value='<?=$data['nama_karyawan']?>' name='nama' class='form-control'/></th>
					</tr>
					<tr>
						<th>JABATAN</th>
						<th>
							  <select name='jabatan' required class="form-control" aria-label="Default select example "id='jabatan'>
								<option value=''> -- Silahkan Pilih jabatan --</option>
								<?php 
								$jab = mysqli_query($con,"select * from jabatan ");
								while($jab1=mysqli_fetch_assoc($jab)){
									if($jab1['id_jabatan']==$data['id_jabatan'])
										$chek='selected';
									else $chek="";
									echo "<option value='$jab1[id_jabatan]' $chek >$jab1[nama_jabatan]</option>";
									
								}
								?>
							  </select>
						</th>
					</tr>
					<tr>
						<td>Cabang</td>
						<td>
							<select name='cabang' required class="form-control" aria-label="Default select example "id='wilayah'>
								<option value=''> -- Silahkan Pilih Cabang --</option>
								<?php 
								$jab = mysqli_query($con,"select * from wilayah ");
								while($wil=mysqli_fetch_assoc($jab)){
									echo "<option value='' disabled><b>$wil[wilayah]</b></option>";
									$cab = mysqli_query($con,"select * from cabang where id_wilayah=$wil[id_wilayah] ");

									while($jab1=mysqli_fetch_assoc($cab)){
										if($data['id_cabang']==$jab1['id_cabang'])
											$pilih = "selected";
										else $pilih ='';
										echo "<option value='$jab1[id_cabang]' $pilih > --- ".  strtoupper($jab1['nama_cabang'])."</option>";
									}
								}
								?>
							  </select>

						</td>
					</tr>
					<tr>
						<th>STATUS KARYAWAN</th>
						<th>
						<?php 
						if($data['status_karyawan']=='aktif') 
							$ch='checked';
						else
							$ch1='checked';
						?>
							<input type="radio" name='status' value='aktif' id='status' <?=$ch?>/> <label for="status" class="form-label">AKTIF</label>
							<input type="radio" name='status' value='tidakaktif' id='status1' <?=$ch1?>/> <label for="status1" class="form-label">TIDAK AKTIF</label>
						</th>
					</tr>
					
					<tr>
						<th>UBAH PASSWORD <br/>
							<small>Kosongkan jika tidak ingin mengubahnya</small>
						</th>
						<th><input type='text' value='' name='passbaru' class='form-control'/></th>
					</tr>
					<tr>
						<th></th>
						<th><input type='submit' value='UBAH' name='ubah' class='btn btn-danger'/></th>
					</tr>
				</table>
			</form>
		<?php		
		}
		?>
		
	</div>
	<br/>
	
	<table class='table table-bordered ' id='data_karyawan'>	
		<thead>
			<tr>
				<th>NO</th>
				<th>NIK</th>
				<th>NAMA</th>
				<th>JABATAN</th>
				<th>CABANG</th>
				<th>status</th>
				<th>#</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$cek=mysqli_query($con,"select * from karyawan join cabang on karyawan.id_cabang=cabang.id_cabang order by nama_karyawan asc");
		while($tampil=mysqli_fetch_assoc($cek)){
			$detail = detail_karyawan($con,$tampil['id_karyawan']);
			?>
			<tr>
				<td><?=$no++?></td>
				<td><?=$detail['nik_karyawan']?></td>
				<td><?=$detail['nama_karyawan']?></td>
				<td><?=$detail['nama_jabatan']?></td>
				<td><?=$detail['nama_cabang']?></td>
				<td><?=$detail['status_karyawan']?></td>
				<td>
					<a href='<?=$url.$menu?>karyawan_su&idkaryawan=<?=$detail['id_karyawan'] ?>&delkaryawan' class='btn btn-danger' title="Tooltip on top" onclick="return window.confirm('Yakin ingin menghapus nya? ')"><i class='fa fa-times'></i></a>
					<a href='<?=$url.$menu?>karyawan_su&idkaryawan=<?=$detail['id_karyawan'] ?>&edit'  class='btn btn-info'><i class='fa fa-edit'></i></a>

				</td>
			</tr>
			<?php
			
		}
		?>
		</tbody>
	</table>
</div>

<?php 
if(isset($_GET['delkaryawan']) && isset($_GET['idkaryawan'])){
	$idk=$_GET['idkaryawan'];
	$q=mysqli_query($con,"DELETE FROM `karyawan` WHERE `id_karyawan` = '$idk'");	
	if($q){
		alert("Berhasil Dihapus");
		pindah("$url$menu"."karyawan_su");
	}
	
}

?>