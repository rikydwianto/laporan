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
	
</div>
