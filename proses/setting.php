<div class="container-fluid">

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Edit Profile</h1>
			<br>
			<h3 style='font-style:italic'>Anda Tidak dapat mengakses Halaman lain jika masih menggunakan password Default (123456) <br> Silahkan ganti password anda! </h3>
		</div>
	</div>
	<?php 
	if(isset($_POST['simpan'])){
		// $passlama = md5($_POST['passlama']);
		$passbaru = md5($_POST['passbaru']);
		if($passbaru)
		{
			$query=mysqli_query($con,"UPDATE karyawan SET password= '$passbaru' WHERE id_karyawan = '$id_karyawan'; ");
			if($query)
			{
				pesan("PASSWORD Berhasil Dirubah",'success');
				pindah("$url");
			}
			else{
				pesan("PASSWORD Gagal diubah",'danger');
			}
		}
		else
		{
			pesan("TIDAK DISIMPAN!! PASSWORD lama yang diinput tidak sama dengan password lama",'danger');
			
		}
	}
	?>

	<form method='post'>
		<table class='table'>
			<tr>
				<td>NAMA</td>
				<td><?php echo strtoupper($d['nama_karyawan'])?></td>
			</tr>
			<!-- <tr>
				<td>PASSWORD LAMA</td>
				<td><input type='password' required name='passlama' class='form-control'></td>
			</tr> -->
			<tr>
				<td>PASSWORD BARU</td>
				<td><input type='password' required name='passbaru' class='form-control'></td>
			</tr>
			<tr>
				<td></td>
				<td><input type='submit' value='SIMPAN' name='simpan' class='btn btn-danger'></td>
			</tr>
		</table>
	</form>
	<?php
	if($su=='y'){
		?>
		<form action="" method="post">
			<select name="nama_cabang" id="">

				<?php 
			$q = mysqli_query($con,"SELECT * FROM cabang order by nama_cabang asc");
			while($r=mysqli_fetch_array($q)){
				if($id_cabang == $r['id_cabang'])
				$sel = 'selected';
				else $sel ='';
				?>
				<option value="<?=$r['id_cabang']?>" <?=$sel?>><?=strtoupper($r['nama_cabang'])?></option>
				<?php
			}
			?>
			</select>
			<input type="submit" name='submit_cabang' value='GANTI CABANG' />
		</form>
		<?php
		if(isset($_POST['submit_cabang']) && $_POST['submit_cabang']=='GANTI CABANG'){
			$_SESSION['cabang']=$_POST['nama_cabang'];
			$_SESSION['id_cabang']=$_POST['nama_cabang'];
			pindah($url);
		}
	}
	?>

</div>
