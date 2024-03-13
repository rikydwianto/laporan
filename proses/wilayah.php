<div class='content table-responsive'>
	<h2 class='page-header'>SELURUH WILAYAH KORDINASI</h2>
	<hr />
	<!-- Button to Open the Modal -->
	<a href='<?= $url . $menu ?>cabang&tambah' class="btn btn-success">
		<i class="fa fa-plus"></i> Tambah Cabang
	</a>
	<a href='<?= $url . $menu ?>cabang&tambah_wilayah' class="btn btn-info">
		<i class="fa fa-plus"></i> Tambah Wilayah
	</a>
	<br>
	<?php
	if (isset($_GET['del'])) {
		$iddet = $_GET['idwil'];
		$del = mysqli_query($con, "delete from wilayah where id_wilayah='$iddet'");
		if ($del) {
			pesan("Wilayah Berhasil dihapus", 'success');
		}
	}



	if (isset($_GET['edit'])) {
		$idwil = $_GET['idwil'];
		$wilayah = $_GET['wilayah'];
	?>
		<div class="col-md-6">
			<form method="post">
				<h3>EDIT Wilayah</h3>
				<table class='table'>

					<tr>
						<td>Nama Wilayah</td>
						<td>
							<input type="hidden" value="<?= $idwil ?>" name='idwil' class="form-control"></input>
							<input name='nama_wilayah' value="<?= $wilayah ?>" class="form-control"></input>
						</td>
					</tr>

					<tr>
						<td>REGIONAL</td>
						<td>
							<!-- 	<select name='wilayah' required class="form-control" aria-label="Default select example "id='wilayah'>
								<option value=''> -- Silahkan Pilih Cabang --</option>
								<?php
								$jab = mysqli_query($con, "select * from wilayah ");
								while ($wil = mysqli_fetch_assoc($jab)) {
									echo "<option value='$wil[id_wilayah]' ><b>$wil[wilayah]</b></option>";
								}
								?>
							  </select> -->

						</td>

					</tr>

					<tr>
						<td> </td>
						<td>
							<input type='submit' name='edit_wilayah' class="btn btn-success" value='EDIT WILAYAH'></input>
						</td>
					</tr>

				</table>

			</form>

		</div>
	<?php
	}





	//TAMBAH Wilayah
	if (isset($_POST['edit_wilayah'])) {

		$wil = $_POST['idwil'];
		$nama = $_POST['nama_wilayah'];
		$wilayah = $_POST['wilayah'];
		$qtambah = mysqli_query($con, "
	UPDATE `wilayah` SET `wilayah` = '$nama' WHERE `wilayah`.`id_wilayah` = $wil; 
	  		");
		if ($qtambah) {
			pesan("WILAYAH Berhasil DiRUBAH");
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
				<th>REGIONAL</th>

				<th>#</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no_cabang = 0;
			$wil = mysqli_query($con, "select * from wilayah");
			while ($wilayah = mysqli_fetch_assoc($wil)) {
			?>
				<tr>

					<th><?= $no++ ?></th>
					<th colspan="1"><?= $wilayah['wilayah'] ?></th>

					<td></td>
					<td>
						<a href="<?= $url . $menu ?>wilayah&del&idwil=<?= $wilayah['id_wilayah'] ?>" onclick="return window.confirm('Menghapus Wilayah dapat mempengaruhi SEMUA')"> <i class='fa fa-times'></i> Hapus</a>


						<a href="<?= $url . $menu ?>wilayah&edit&idwil=<?= $wilayah['id_wilayah'] ?>&wilayah=<?= $wilayah['wilayah'] ?>"> <i class='fa fa-edit'></i> EDIT</a>

					</td>
				</tr>
			<?php
			}


			?>
		</tbody>
	</table>
</div>