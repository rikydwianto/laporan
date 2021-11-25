<div class="row">
	<h3 class="page-header">Input Anggota Masuk/Keluar</h3>
	<a href="<?= $url . $menu ?>anggota&tambah" class="btn btn-info">ANGGOTA MASUK </a>
	<a href="<?= $url . $menu ?>anggota&anggota_keluar" class="btn btn-danger">ANGGOTA KELUAR </a>
	<a href="<?= $url . $menu ?>anggota&sinkron" class="btn btn-success">SINKRON </a>
	<hr />
	<?php
	if (isset($_GET['tambah'])) {

	?>
		<form method="post" enctype="multipart/form-data">
			<div class="col-md-4">
				<label for="formFile" class="form-label">SILAHKAN PILIH FILE : ANGGOA MASUK/ DETAIL NASABAH</label>
				<input class="form-control" type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
				<input type="submit" value="Proses" class='btn btn-danger' name='preview'>
			</div>
		</form>
		<br />
		<table class='table'>
			<tr>
				<th>NO</th>
				<th>STAFF</th>
				<th>TANGGAL</th>
			</tr>

			<?php
			if (isset($_POST['simpan'])) {
				
				pindah($url . $menu . "anggota&sinkron");
				
			}
			if (isset($_POST['preview'])) {
				$nama_file = $_FILES['file']['tmp_name'];
				$_SESSION['nama_file'] = $nama_file;
				$path = $_SESSION['nama_file'];
				$reader = PHPExcel_IOFactory::createReaderForFile($path);
				$objek = $reader->load($path);
				$ws = $objek->getActiveSheet();
				$last_row = $ws->getHighestDataRow();
				for ($row = 2; $row <= $last_row; $row++) {
					$no_id =  $ws->getCell("B" . $row)->getValue();
					if ($no_id == null) {
					} else {
						$agt = ganti_karakter(substr($no_id, 0, 5));
						if ($agt == 'AGT') {
							$staff =  ganti_karakter($ws->getCell("W" . $row)->getValue());
							$id_detail =  ganti_karakter1($ws->getCell("B" . $row)->getValue());
							$tgl = ($ws->getCell("L" . $row)->getValue());
							$tgl = $tgl;
							$tgl = explode("/", $tgl);
							$cek_ang = mysqli_num_rows(mysqli_query($con,"select id_detail_nasabah from temp_anggota where id_detail_nasabah='$id_detail'"));
							if($cek_ang){

							}
							else{
								$new_tgl = ganti_karakter($tgl[2]) . "-" . ganti_karakter($tgl[1]) . "-" . ganti_karakter($tgl[0]);
								mysqli_query($con, "INSERT INTO `temp_anggota` (`staff`,id_detail_nasabah, `tgl_bergabung`, `status_input`, `id_cabang`) VALUES ('$staff','$id_detail', '$new_tgl', 'belum', '$id_cabang'); ");

							}

				
						}
					}
				}
				?>
				<tr>
					<td></td>
					<td></td>

					<td>
						<form method="post">

							<input type="submit" name='simpan' class='btn btn-info' value='SIMPAN' onclick="return confirm('Apakah Yakin?')" />
						</form>
					</td>
				</tr>
			<?php
			pindah($url.$menu."anggota&sinkron");
			}

			?>
		</table>
	<?php

	}else if(isset($_GET['anggota_keluar'])){

			?>
				<form method="post" enctype="multipart/form-data">
					<div class="col-md-4">
						<label for="formFile" class="form-label">ANGGOTA KELUAR : SILAHKAN PILIH FILE</label>
						<input class="form-control" type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
						<input type="submit" value="Proses" class='btn btn-danger' name='preview'>
					</div>
				</form>
				<br />
					<?php
					if (isset($_POST['konfirmasi_keluar'])) {
						$tgl_file=date("Y-m-d");
						$ck = mysqli_query($con,"select *, count(id_nasabah) as total from temp_anggota_keluar b join karyawan a on a.id_karyawan=b.id_karyawan where b.status='belum' and b.id_cabang='$id_cabang'  group by b.id_karyawan,b.tgl_keluar order by b.tgl_keluar");
						while($keluarkan = mysqli_fetch_array($ck)){
							mysqli_query($con,"INSERT INTO `anggota` (`id_karyawan`, `tgl_anggota`, `anggota_masuk`, `anggota_keluar`, `net_anggota`,id_cabang) VALUES ('$keluarkan[id_karyawan]', '$keluarkan[tgl_keluar]', '0', '$keluarkan[total]', '-$keluarkan[total]','$id_cabang'); ");
						}
						$upd = mysqli_query($con,"select * from temp_anggota_keluar where status='belum' and id_cabang='$id_cabang'");
						while($update = mysqli_fetch_array($upd)){
							mysqli_query($con,"UPDATE `temp_anggota_keluar` SET `status` = 'sudah' WHERE `id` = '$update[id]'; ");
							mysqli_query($con,"INSERT INTO `daftar_nasabah_mantan` 
							SELECT * FROM daftar_nasabah where id_nasabah='$update[id_nasabah]'
						");
						mysqli_query($con,"delete from daftar_nasabah where id_nasabah='$update[id_nasabah]'");
						}
						pesan("Berhasil ditambahkan!",'success');
						
					}

					if (isset($_POST['preview'])) {
						$nama_file = $_FILES['file']['tmp_name'];
						$path = $nama_file;
						$reader = PHPExcel_IOFactory::createReaderForFile($path);
						$objek = $reader->load($path);
						$ws = $objek->getActiveSheet();
						$last_row = $ws->getHighestDataRow();

						for($row = 2;$row<=$last_row;$row++){
							$no_id =  $ws->getCell("D" . $row)->getValue();
							if($no_id==null){
								
							}
							else{
								$agt = (substr($no_id,0,3));
								// echo $agt;
								if($agt=='AGT'){
									$id_nasabah =  $ws->getCell("D" . $row)->getValue();
									$ID = explode("-",$id_nasabah)[1];
									
									$nasabah =  ($ws->getCell("E".$row)->getValue());
									$alasan = ($ws->getCell("J".$row)->getValue());
									$staff = ($ws->getCell("A".$row)->getValue());
									$tgl =$ws->getCell("I".$row)->getValue();
									$tgl =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl));
									$ID = sprintf("%0d",$ID);
									$center = $ws->getCell("C".$row)->getValue();
									$center = str_replace(" ","",explode("/",$center)[0]);
									$cari_keluar  = mysqli_query($con,"select * from temp_anggota_keluar where id_nasabah='$ID' ");
									if(mysqli_num_rows($cari_keluar)){

									}
									else{
										$cari_staff  = mysqli_query($con,"select * from center join karyawan on karyawan.id_karyawan=center.id_karyawan where no_center='$center'");
										$cari_staff  = mysqli_fetch_array($cari_staff);
										
										mysqli_query($con,"INSERT INTO `temp_anggota_keluar` (`id_nasabah`, `tgl_keluar`, `id_karyawan`, `id_cabang`, `status`,`alasan`) 
										VALUES ('$ID', '$tgl', '$cari_staff[id_karyawan]', '$id_cabang', 'belum','$alasan'); ");
										?>
										
										<?php
									}
								}
							}
						}


						
					// pindah($url.$menu."anggota&sinkron");
					}
		?>
		<form method="post">
		<table class='table'>
				<tr>
					<th>NO</th>
					<th>STAFF</th>
					<th>TGL KELUAR</th>
					<th>TOTAL AK</th>
				</tr>
			<?php
			$total_ak = 0;
			$cek_keluar = mysqli_query($con,"select *, count(id_nasabah) as total from temp_anggota_keluar b join karyawan a on a.id_karyawan=b.id_karyawan where b.status='belum' and b.id_cabang='$id_cabang' group by b.id_karyawan,b.tgl_keluar order by b.tgl_keluar");
			while($keluar = mysqli_fetch_array($cek_keluar)){
				?>
				<tr>
					<td><?=$no++?></td>
					<td><?=$keluar['nama_karyawan']?></td>
					<td><?=$keluar['tgl_keluar']?></td>
					<td><?=$keluar['total']?></td>
				</tr>
				<?php
				$total_ak = $keluar['total'] + $total_ak;
			}
			?>
			<tr>
				<td></td>
				<td></td>
				<td><input type="submit" value="KONFIRMASI" name='konfirmasi_keluar' class='btn btn-danger'></td>
				<td><?=$total_ak?></td>
			</tr>
		</form>

		</table>
		<?php			
	} 
	else if (isset($_GET['sinkron'])) {
		if (isset($_POST['konfirmasi'])) {
			$staf = $_POST['nama_mdis'];
			$id_k = $_POST['karyawan'];
			for ($i = 0; $i < count($staf); $i++) {
				if (!empty($id_k[$i])) {
					$text = " UPDATE `temp_anggota` SET `staff` = null , id_karyawan='$id_k[$i]' WHERE `staff` = '$staf[$i]' and id_cabang='$id_cabang'; ";
					mysqli_query($con, $text);
				}
			}
			
			$total_semua = 0;
			$cari_tgl = mysqli_query($con, "SELECT tgl_bergabung FROM temp_anggota where id_cabang='$id_cabang' GROUP BY tgl_bergabung");
			while ($tgll = mysqli_fetch_array($cari_tgl)) {
				$tgl = $tgll['tgl_bergabung'];
				echo $tgl." Proses <br/>";
				$total_semua = $total_semua;
				$qcariStaff = mysqli_query($con, "select *,count(id) as total_anggota from temp_anggota where tgl_bergabung='$tgl' and id_cabang='$id_cabang' group by id_karyawan");
				while ($cariStaff = mysqli_fetch_array($qcariStaff)) {
					$total_semua = $total_semua + $cariStaff['total_anggota'];
					// echo $cariStaff['id_karyawan'];
					mysqli_query($con, "INSERT INTO `anggota` (`id_karyawan`, `tgl_anggota`, `anggota_masuk`, `anggota_keluar`, `net_anggota`) VALUES ('$cariStaff[id_karyawan]', '$tgl', '$cariStaff[total_anggota]', '0', '$cariStaff[total_anggota]'); ");
				}
			}
			// mysqli_query($con,"delete from temp_anggota where id_cabang='$id_cabang' ");
			echo "Proses selesai tunggu prosess slanjutnya!";
			pindah($url.$menu."anggota&tambah");
		}

	?>
		<form action="" method="post">

			<table class='table'>
				<tr>
					<th>NO</th>
					<th>STAFF MDIS</th>
					<th>ANGGOTA MASUK</th>
					<th>STAFF</th>
				</tr>
				<?php
				$no = 1;
				$total_am=0;
				$kary = mysqli_query($con, "SELECT *,count(tgl_bergabung) as am FROM temp_anggota where id_cabang='$id_cabang' and status_input='belum' and staff is not null GROUP BY staff order by staff asc ");
				while ($nama = mysqli_fetch_array($kary)) {
					$total_am = $total_am + $nama['am'];
				?>
					<tr>
						<td><?= $no++ ?></td>
						<td>
							<?= $nama['staff'] ?>
							<input type="hidden" name="nama_mdis[]" value="<?= $nama['staff'] ?>">
						</td>
						<td><?=$nama['am']?></td>
						<td>
							<select name="karyawan[]" id="" required class='form-control'>
								<option value="">Pilih Staff</option>
								<?php $data_karyawan  = (karyawan($con, $id_cabang)['data']);
								for ($i = 0; $i < count($data_karyawan); $i++) {
									$nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
									if (strtolower($nama_karyawan) == strtolower($nama['staff'])) {
										echo "<option selected value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
									} else {
										echo "<option value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
									}
								}
								?>
							</select>
						</td>
					</tr>
				<?php
				}
				?>
				<tr>
					<td></td>
					<td></td>
					<td><?=$total_am?></td>
					<td>
						<input type="submit" value="SINKRON" name="konfirmasi" class='btn btn-info'>
					</td>
				</tr>
			</table>
		</form>

	<?php
	}
	elseif(isset($_GET['edit_anggota'])){
		include("./proses/edit_anggota.php");
	} 
	else {
	?>
		<form method="post">
			<?php
			if (isset($_POST['tambah_anggota'])) {
				$idk = $_POST['idk'];
				$masuk = $_POST['masuk'];
				$keluar = $_POST['keluar'];
				$tgl = $_POST['tgl'];

				for ($x = 0; $x < count($idk); $x++) {
					$nett = $masuk[$x] - $keluar[$x];
					IF($masuk[$x]==0 && $keluar[$x]==0){

					}else{

						$query = mysqli_query($con, "INSERT INTO anggota (id_anggota, id_karyawan, tgl_anggota, anggota_masuk, anggota_keluar, net_anggota, id_cabang) VALUES (NULL, '$idk[$x]', '$tgl', '$masuk[$x]', '$keluar[$x]', '$nett', '$id_cabang');");
					}
				}
				if ($query) {
					pesan("Berhasil ditambahkan", 'success');
					pindah("$url");
				} else {
					pesan("Gagal", 'danger');
				}
			}
			?>
			<div class="col-lg-5">
				PILIH TANGGAL <input type=date name="tgl" class=" form-control" value="<?php echo date('Y-m-d') ?>"></input>

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
				$qk = mysqli_query($con, "select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
				while ($cek_ka = mysqli_fetch_array($qk)) {
				?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $cek_ka['nama_karyawan'] ?></td>
						<td>
							<input type='number' class="form-control" style="width: 100px" name='masuk[]' id='masuk<?= $cek_ka['id_karyawan'] ?>' value='0'></input>
							<input type='hidden' name='idk[]' class="form-control" value='<?= $cek_ka['id_karyawan'] ?>'></input>

						</td>
						<td>
							<input type='number' class="form-control" style="width: 100px" name='keluar[]' id='keluar<?= $cek_ka['id_karyawan'] ?>' onkeyup="ganti_net('<?= $cek_ka['id_karyawan'] ?>')" value='0'></input>

						</td>
						<td>
							<input type='number' class="form-control" readonly style="width: 100px" name='nett[]' id='nett<?= $cek_ka['id_karyawan'] ?>' value='0'></input>

						</td>
					</tr>
				<?php

				}
				?>
				<tr>
					<td colspan="4"></td>
					<td>

						<input type='submit' name='tambah_anggota' class="btn btn-info " value='SIMPAN'></input>
					</td>
				</tr>
			</table>
		</form>
	<?php
	}
	?>
</div>