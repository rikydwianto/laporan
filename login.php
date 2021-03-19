<div class="row">
	<div class="col-lg-12">
		<h2 class="page-header">SILAHKAN LOGIN</h2>
	</div>
</div>
<form method="post" >
	 <table class='table'>
	  <tr>
		<td>NIK</td>
		<td><input type="text" name="username" class="form-control"  placeholder="contoh 3729/2017"></td>
	  </tr>
	  <tr>
		<td>Password</td>
		<td><input type="password" class="form-control"  required name="password"></td>
	  </tr>
	  <tr>
		<td></td>
		<td><input type="submit" class="btn btn-primary"  name="login" value="Log In"></td>
	  </tr>
	</table> 
</form>
<?php
if(isset($_POST['login'])){
$user=aman($con,$_POST['username']);
$pass=aman($con,md5($_POST['password']))	;
$q=mysqli_query($con,"select * from karyawan where nik_karyawan='$user'  ");
if(mysqli_num_rows($q)){
	$cek=mysqli_fetch_array($q);
	if($cek['status_karyawan']=='aktif')
	{
		if($cek['password'] == $pass){
		$_SESSION['id']=$cek['id_karyawan'];
		$_SESSION['nama_karyawan']=$cek['nama_karyawan'];
		$_SESSION['id_cabang']=$cek['id_cabang'];
		$_SESSION['cabang']=$cek['id_cabang'];
		$_SESSION['su']=$cek['super_user'];
		pesan("BERHASIL LOGIN",'success');
		pindah("$url");
		
		}
		else
		pesan("NIK DITEMUKAN, Password SALAH!!",'danger');
	}
	else
		pesan("STATUS ANDA DINONAKTIKAN, SILAHKAN HUBUNGI ATASAN ANDA",'danger');

	
}

else pesan("USER/NIK TIDAK DITEMUKAN",'danger');
}
?>