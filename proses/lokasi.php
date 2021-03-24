<?php
if(!isset($_GET['lat']) && !isset($_GET['lat']))
{
	pindah("$url");
}
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$kategori = $_GET['pilih'];

if(isset($_POST['simpan_lokasi'])){
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$kategori = $_POST['kategori'];
	$nama = $_POST['judul'];
	$center = $_POST['center'];
	$idcenter = $_POST['idcenter'];
	$keterangan = $_POST['keterangan'];
	$alamat = $_POST['alamat'];
	if($kategori=='center'){
		//update

		// UPDATE `komida1`.`center` SET `latitude` = 'as' WHERE `center`.`id_center` = 1;
		mysqli_query($con,"UPDATE `center` SET `latitude` = '$lat',`longitude` = '$lng' WHERE `center`.`id_center` = '$idcenter';");
	}
	else{
		mysqli_query($con,"
	INSERT INTO `lokasi` (`id_lokasi`, `nama_lokasi`, `kategori`, `center`, `keterangan`, `alamat`, `latitude`, `longitude`, `link_google`, `id_karyawan`) VALUES (NULL, '$nama', '$kategori', '$center', '$keterangan', '$alamat', '$lat', '$lng', '".link_maps($lat,$lng)."',$id_karyawan);
			");
	}
	pindah("$url/lokasi.php");
}
?>

<dir class="col-md-7 table-responsive">
	<form method="post">
		<table class='table'>
			<tr>
				<td>Latitude</td>
				<td>
					<input type="text" value="<?=$lat?>" readonly="" class="form-control" name="lat">
				</td>

			</tr>
			<tr>
				<td>Longitude</td>
				<td>
					<input type="text" value="<?=$lng?>" name="lng" readonly="" class="form-control" >
				</td>

			</tr>

			<tr>
				<td>KATEGORI</td>
				<td>
					<input type="text" value="<?=$kategori?>" readonly="" name="kategori" class="form-control" >
				</td>

			</tr>
			<?php if($kategori=='center') :?>
			<tr>
				<td>NO CENTER <br><small>Harus Diisi</small></td>
				<td>
					<select name='idcenter' class='form-control'>
						<?php 
						$cek = mysqli_query($con,"select * from center where id_cabang='$id_cabang' and id_karyawan='$id_karyawan'");

						while($cent = mysqli_fetch_array($cek))
						{
							?>
				<option value="<?=$cent['id_center']?>" ><?=$cent['no_center']?> --- <?=$cent['hari']?> </option>

							<?php
						}
						?>
					</select>
					<!-- <input type="text" value="" required="" class="form-control" name='center'> -->
				</td>

			</tr>
		<?php else :?>
			<tr>
				<td>Judul</td>
				<td>
					<input type="text" value="" name="judul" class="form-control" >
				</td>

			</tr>

			<tr>
				<td>Keterangan</td>
				<td>
					<textarea name='keterangan' class="form-control"></textarea>
				</td>

			</tr>
			<tr>
				<td>Alamat <br><small>Boleh dikosongkan</small></td>
				<td>
					<textarea name='alamat' class="form-control"></textarea>
				</td>

			</tr>
		<?php endif;?>
			<tr>
				<td></td>
				<td>
					<input class="btn btn-primary" type="submit" name="simpan_lokasi" value='Tambah lokasi' ></input>
				</td>

			</tr>

		</table>

	</form>
</dir>
<!--  -->