<div class='content table-responsive'>
	<h2 class='page-header'>SELURUH CABANG</h2>
	<hr/>
	  <!-- Button to Open the Modal -->
  <a href='<?=$url.$menu?>cabang&tambah' class="btn btn-success" >
    <i class="fa fa-plus"></i> Tambah Cabang
  </a>
  <a href='<?=$url.$menu?>cabang&tambah_wilayah' class="btn btn-info" >
    <i class="fa fa-plus"></i> Tambah Wilayah
  </a>
<br>
  <?php 
  if(isset($_GET['del']))
  {
  	$iddet= $_GET['idcab'];
  	$del = mysqli_query($con,"delete from cabang where id_cabang='$iddet'");
  	if($del){
  		pesan("Cabang Berhasil dihapus",'success');
  	}
  }
 if(isset($_GET['tambah']))
  {
  	?>
  	<div class="col-md-6">
	  	<form method="post">
	  		<h3>Tambah Cabang</h3>
	  		<table class='table'>

	  			<tr>
	  				<td>Kode Cabang</td>
	  				<td>
	  					<input name='kode_cabang' class="form-control"></input>
	  				</td>
	  			</tr>		
	  			<tr>
	  				<td>Nama Cabang</td>
	  				<td>
	  					<input name='nama_cabang' class="form-control"></input>
	  				</td>
	  			</tr>		

	  			<tr>
	  				<td>Wilayah</td>
	  				<td>
	  					<select name='wilayah' required class="form-control" aria-label="Default select example "id='wilayah'>
								<option value=''> -- Silahkan Pilih Cabang --</option>
								<?php 
								$jab = mysqli_query($con,"select * from wilayah ");
								while($wil=mysqli_fetch_assoc($jab)){
									echo "<option value='$wil[id_wilayah]' ><b>$wil[wilayah]</b></option>";
								}
								?>
							  </select>

	  				</td>

	  			</tr>

	  			<tr>
	  				<td> </td>
	  				<td>
	  					<input type='submit' name='tambah_cabang' class="btn btn-success" value='TAMBAH CABANG'></input>
	  				</td>
	  			</tr>			

	  		</table>
	  		
	  	</form>
  		
  	</div>
  	<?php
  }



 if(isset($_GET['edit']))
  {
  	$idcab = $_GET['idcab'];
  	$kode = $_GET['kode_cabang'];
  	$nama = $_GET['nama_cabang'];
  	$wilayah = $_GET['wilayah'];
  	?>
  	<div class="col-md-6">
	  	<form method="post">
	  		<h3>EDIT Cabang</h3>
	  		<table class='table'>

	  			<tr>
	  				<td>Kode Cabang</td>
	  				<td>
	  					<input type="hidden" name='idcab' value="<?=$idcab?>" class="form-control"></input>
	  					<input name='kode_cabang' value="<?=$kode?>" class="form-control"></input>
	  				</td>
	  			</tr>		
	  			<tr>
	  				<td>Nama Cabang</td>
	  				<td>
	  					<input name='nama_cabang' value="<?=$nama?>" class="form-control"></input>
	  				</td>
	  			</tr>		

	  			<tr>
	  				<td>Wilayah</td>
	  				<td>
	  					<select name='wilayah' required class="form-control" aria-label="Default select example "id='wilayah'>
								<option value=''> -- Silahkan Pilih Wilayah --</option>
								<?php 
								$jab = mysqli_query($con,"select * from wilayah ");
								while($wil=mysqli_fetch_assoc($jab)){
									if($wilayah==$wil['id_wilayah'])
										echo "<option value='$wil[id_wilayah]' selected><b>$wil[wilayah]</b></option>";
									else
										echo "<option value='$wil[id_wilayah]' ><b>$wil[wilayah]</b></option>";
								}
								?>
							  </select>

	  				</td>

	  			</tr>

	  			<tr>
	  				<td> </td>
	  				<td>
	  					<input type='submit' name='edit_cabang' class="btn btn-success" value='SIMPAN CABANG'></input>
	  				</td>
	  			</tr>			

	  		</table>
	  		
	  	</form>
  		
  	</div>
  	<?php
  }


if(isset($_GET['tambah_wilayah']))
  {
  	?>
  	<div class="col-md-6">
	  	<form method="post">
	  		<h3>Tambah Wilayah</h3>
	  		<table class='table'>

	  			<tr>
	  				<td>Nama Wilayah</td>
	  				<td>
	  					<input name='nama_wilayah' class="form-control"></input>
	  				</td>
	  			</tr>		

	  			<tr>
	  				<td>REGIONAL</td>
	  				<td>
	  				<!-- 	<select name='wilayah' required class="form-control" aria-label="Default select example "id='wilayah'>
								<option value=''> -- Silahkan Pilih Cabang --</option>
								<?php 
								$jab = mysqli_query($con,"select * from wilayah ");
								while($wil=mysqli_fetch_assoc($jab)){
									echo "<option value='$wil[id_wilayah]' ><b>$wil[wilayah]</b></option>";
								}
								?>
							  </select> -->

	  				</td>

	  			</tr>

	  			<tr>
	  				<td> </td>
	  				<td>
	  					<input type='submit' name='tambah_wilayah' class="btn btn-success" value='TAMBAH WILAYAH'></input>
	  				</td>
	  			</tr>			

	  		</table>
	  		
	  	</form>
  		
  	</div>
  	<?php
  }






  //TAMBAH CABANG
    if(isset($_POST['tambah_cabang']))
	  {
	  	$kode = $_POST['kode_cabang'];
	  	$nama = $_POST['nama_cabang'];
	  	$wilayah = $_POST['wilayah'];
	  	$qtambah = mysqli_query($con,"
			INSERT INTO `cabang` (`id_cabang`, `kode_cabang`, `nama_cabang`, `id_wilayah`) VALUES (NULL, '$kode', '$nama', '$wilayah'); 
	  		");
	  	if($qtambah){
	  		pesan("Cabang Berhasil Ditambahkan");
	  	}
	  }
  //EDIT CABANG
    if(isset($_POST['edit_cabang']))
	  {
	  	$idcab = $_POST['idcab'];
	  	$kode = $_POST['kode_cabang'];
	  	$nama = $_POST['nama_cabang'];
	  	$wilayah = $_POST['wilayah'];
	  	$qtambah = mysqli_query($con,"
			UPDATE `cabang` SET `kode_cabang` = '$kode', `nama_cabang` = '$nama', `id_wilayah` = '$wilayah' WHERE `cabang`.`id_cabang` = $idcab; 
	  		");
	  	echo "UPDATE `cabang` SET `kode_cabang` = '$kode', `nama_cabang` = '$nama', `id_wilayah` = '$wilayah' WHERE `cabang`.`id_cabang` = $idcab; ";
	  	if($qtambah){
	  		pesan("Cabang Berhasil DISIMPAN","success");
	  		pindah("$url$menu"."cabang");
	  	}
	  }
  //TAMBAH Wilayah
    if(isset($_POST['tambah_wilayah']))
	  {

	  	$kode = $_POST['kode_cabang'];
	  	$nama = $_POST['nama_wilayah'];
	  	$wilayah = $_POST['wilayah'];
	  	$qtambah = mysqli_query($con,"
			INSERT INTO `wilayah` (`id_wilayah`, `wilayah`) VALUES (NULL, '$nama'); 
	  		");
	  	if($qtambah){
	  		pesan("WILAYAH Berhasil Ditambahkan");
	  	}
	  }

?>



  <br>
  <br>
  <br>
	<table id='data_karyawan'>
		<thead>
			<tr>
				<th>NO</th>
				<th>WILAYAH</th>
				<th>CABANG</th>
				<th>KODE</th>

				<th>#</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$no_cabang = 0;
		$wil = mysqli_query($con,"select * from wilayah");
		while($wilayah = mysqli_fetch_array($wil)){
			?>
			<tr>
				
			<th><?=$no++?></th>
				<th colspan="1"><?=$wilayah['wilayah']?></th>
				<th><hr></th>
				<th><hr></th>
				<th><hr></th>

			</tr>
			<?php 
			$q=mysqli_query($con,"select * from cabang  where id_wilayah ='$wilayah[id_wilayah]' order by nama_cabang asc");
			while($center=mysqli_fetch_assoc($q))
			{
				?>
				<tr>
					<td><?=$no++;?></td>
					<td><?=$wilayah['wilayah']?></td>
					<td><?=strtoupper($center['nama_cabang']);?></td>
					<td><?=$center['kode_cabang'];?></td>

					<td>
						<a href="<?=$url.$menu?>cabang&del&idcab=<?=$center['id_cabang']?>" onclick="return window.confirm('Menghapus cabang dapat mempengaruhi SEMUA')"> <i class='fa fa-times'></i> Hapus</a>
						<a href="<?=$url.$menu?>cabang&edit&idcab=<?=$center['id_cabang']?>&kode_cabang=<?=$center['kode_cabang']?>&nama_cabang=<?=$center['nama_cabang']?>&wilayah=<?=$center['id_wilayah']?>" > <i class='fa fa-edit'></i> EDIT</a>

					</td>
				</tr>
				<?php
			}
			?>
			<?php
		}


		?>
		</tbody>
	</table>
</div>
<!-- Button trigger modal -->


