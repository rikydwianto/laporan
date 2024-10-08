<div class="row">
	<h3 class="page-header">Input Anggota Masuk/Keluar</h3>
	<a href="<?= $url . $menu ?>anggota&tambah" class="btn btn-info">ANGGOTA MASUK </a>
	<a href="<?= $url . $menu ?>anggota&anggota_keluar" class="btn btn-danger">ANGGOTA KELUAR </a>
	<a href="<?= $url . $menu ?>anggota&sinkron&lanjutkan" class="btn btn-success">SINKRON </a>
	<a href="<?= $url . $menu ?>anggota&edit_anggota" class="btn btn-info">EDIT </a>
	<a href="<?= $url . $menu ?>anggota" class="btn btn-danger">TAMBAH ANGGOTA MANUAL </a>
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
				// echo $id_cabang;
				$nama_file = $_FILES['file']['tmp_name'];
				$_SESSION['nama_file'] = $nama_file;
				$path = $_SESSION['nama_file'];
				$reader = PHPExcel_IOFactory::createReaderForFile($path);
				$objek = $reader->load($path);
				$ws = $objek->getActiveSheet();
				$last_row = $ws->getHighestDataRow();

				$ket_excel =  $ws->getCell("A4")->getValue();
				$ket_excel1 =  $ws->getCell("A3")->getValue();
				if (($ket_excel == "DATABASE NASABAH " || $ket_excel == "DATABASE ANGGOTA " || $ket_excel == "DATABASE NASABAH") || ($ket_excel1 == "DATABASE NASABAH " || $ket_excel1 == "DATABASE ANGGOTA " || $ket_excel1 == "DATABASE NASABAH")) {
					for ($row = 2; $row <= $last_row; $row++) {
						$no_id =  $ws->getCell("B" . $row)->getValue();
						if ($no_id == null) {
						} else {
							$agt = ganti_karakter(substr($no_id, 0, 5));
							if ($agt == 'AGT') {
								$staff =  ganti_karakter($ws->getCell("W" . $row)->getValue());
								$nama_nasabah =  ganti_karakter($ws->getCell("C" . $row)->getValue());
								$nama_suami =  ganti_karakter($ws->getCell("F" . $row)->getValue());
								$jml_anak =  ganti_karakter($ws->getCell("J" . $row)->getValue());
								$umur =  ganti_karakter($ws->getCell("I" . $row)->getValue());
								$tempat_lahir =  ganti_karakter($ws->getCell("G" . $row)->getValue());
								$alamat_nasabah =  ganti_karakter($ws->getCell("K" . $row)->getValue());
								$tgl_lahir =  ganti_karakter1($ws->getCell("H" . $row)->getValue());
								$id_detail =  ganti_karakter1($ws->getCell("B" . $row)->getValue());
								$tgl =  ganti_karakter1($ws->getCell("L" . $row)->getValue());
								$tgl = explode("/", $tgl);
								// $tgl_lahir;
								$tgl1 = explode("/", $tgl_lahir);
								$baru_tgl = "$tgl1[2]-$tgl1[1]-$tgl1[0]";

								$cek_ang = mysqli_num_rows(mysqli_query($con, "select id_detail_nasabah from temp_anggota where id_detail_nasabah='$id_detail'"));
								if ($cek_ang) {
									mysqli_query($con, "UPDATE `temp_anggota` SET `nama_nasabah` = '$nama_nasabah' , nama_suami='$nama_suami',
									`tempat_lahir` = '$tempat_lahir' , `tgl_lahir` = '$baru_tgl' , `umur` = '$umur' , `jml_anak` = '$jml_anak' , `alamat_nasabah` = '$alamat_nasabah'
									  WHERE `id_detail_nasabah` = '$id_detail'; 
									");
								} else {
									$new_tgl = ganti_karakter1($tgl[2]) . "-" . ganti_karakter1($tgl[1]) . "-" . ganti_karakter1($tgl[0]);
									// echo $id_detail . '<br/>';
									mysqli_query($con, "INSERT INTO `temp_anggota` 
									(`staff`,`id_detail_nasabah`, `tgl_bergabung`, `status_input`, `id_cabang`,`nama_nasabah`,`nama_suami`,`tempat_lahir`,`tgl_lahir`,umur,jml_anak,alamat_nasabah) VALUES
									 ('$staff','$id_detail', '$new_tgl', 'belum', '$id_cabang','$nama_nasabah','$nama_suami','$tempat_lahir','$baru_tgl','$umur','$jml_anak','$alamat_nasabah'); ");
								}
							}
						}
					}
				} else {
					alert('DITOLAK BUKAN FILE DATABASE NASABAH');
				}
			?>
				<tr>
					<td></td>
					<td></td>

					<td>
						<form method="post">

							<input type="submit" name='simpan' class='btn btn-info' value='SIMPAN'
								onclick="return confirm('Apakah Yakin?')" />
						</form>
					</td>
				</tr>
			<?php
				pindah($url . $menu . "anggota&sebelum_sinkron");
			}

			?>
		</table>
	<?php

	} else if (isset($_GET['anggota_keluar'])) {

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
			$tgl_file = date("Y-m-d");
			$ck = mysqli_query($con, "select *, count(id_nasabah) as total from temp_anggota_keluar b join karyawan a on a.id_karyawan=b.id_karyawan where b.status='belum' and b.id_cabang='$id_cabang'  group by b.id_karyawan,b.tgl_keluar order by b.tgl_keluar");
			while ($keluarkan = mysqli_fetch_assoc($ck)) {
				mysqli_query($con, "INSERT INTO `anggota` (`id_karyawan`, `tgl_anggota`, `anggota_masuk`, `anggota_keluar`, `net_anggota`,id_cabang) VALUES ('$keluarkan[id_karyawan]', '$keluarkan[tgl_keluar]', '0', '$keluarkan[total]', '-$keluarkan[total]','$id_cabang'); ");
				mysqli_query($con, "UPDATE total_nasabah set total_nasabah = total_nasabah - $keluarkan[total] where id_karyawan='$keluarkan[id_karyawan]' ");
			}
			$upd = mysqli_query($con, "select * from temp_anggota_keluar where status='belum' and id_cabang='$id_cabang'");
			while ($update = mysqli_fetch_assoc($upd)) {
				mysqli_query($con, "UPDATE `temp_anggota_keluar` SET `status` = 'sudah' WHERE `id` = '$update[id]'; ");
				mysqli_query($con, "INSERT INTO `daftar_nasabah_mantan` 
							SELECT * FROM daftar_nasabah where id_nasabah='$update[id_nasabah]' and id_cabang='$id_cabang'
						");
				mysqli_query($con, "delete from daftar_nasabah where id_nasabah='$update[id_nasabah]'");
			}
			pesan("Berhasil ditambahkan!", 'success');
		}

		if (isset($_POST['preview'])) {
			$nama_file = $_FILES['file']['tmp_name'];
			$path = $nama_file;
			$reader = PHPExcel_IOFactory::createReaderForFile($path);
			$objek = $reader->load($path);
			$ws = $objek->getActiveSheet();
			$last_row = $ws->getHighestDataRow();
			$ket_excel =  $ws->getCell("I3")->getValue();
			echo $ket_excel;
			if ($ket_excel == "TGL. KELUAR") {
				for ($row = 2; $row <= $last_row; $row++) {
					$no_id =  $ws->getCell("D" . $row)->getValue();
					if ($no_id == null) {
					} else {
						$agt = (substr($no_id, 0, 3));
						// echo $agt;
						if ($agt == 'AGT' || $agt == 'NSB') {
							$id_nasabah =  $ws->getCell("D" . $row)->getValue();
							$ID = explode("-", $id_nasabah)[1];

							$nasabah =  ($ws->getCell("E" . $row)->getValue());
							$alasan = ($ws->getCell("J" . $row)->getValue());
							$staff = ($ws->getCell("A" . $row)->getValue());
							$tgl = $ws->getCell("I" . $row)->getValue();
							$tgl =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl));
							$ID = sprintf("%0d", $ID);
							$center = $ws->getCell("C" . $row)->getValue();
							$center = str_replace(" ", "", explode("/", $center)[0]);
							$cari_keluar  = mysqli_query($con, "select * from temp_anggota_keluar where id_nasabah='$ID' and id_cabang='$id_cabang' ");
							if (mysqli_num_rows($cari_keluar)) {
							} else {
								$cari_staff  = mysqli_query($con, "select * from center join karyawan on karyawan.id_karyawan=center.id_karyawan where no_center='$center' and karyawan.id_cabang='$id_cabang'");
								$cari_staff  = mysqli_fetch_assoc($cari_staff);

								mysqli_query($con, "INSERT INTO `temp_anggota_keluar` (`id_nasabah`, `tgl_keluar`, `id_karyawan`, `id_cabang`, `status`,`alasan`) 
											VALUES ('$ID', '$tgl', '$cari_staff[id_karyawan]', '$id_cabang', 'belum','$alasan'); ");
		?>

		<?php
							}
						}
					}
				}
			} else {
				alert("DITOLAK BUKAN FILE ANGGOTA KELUAR");
			}



			pindah($url . $menu . "anggota&anggota_keluar");
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
				$cek_keluar = mysqli_query($con, "select *, count(id_nasabah) as total from temp_anggota_keluar b join karyawan a on a.id_karyawan=b.id_karyawan where b.status='belum' and b.id_cabang='$id_cabang' group by b.id_karyawan,b.tgl_keluar order by b.tgl_keluar");
				while ($keluar = mysqli_fetch_assoc($cek_keluar)) {
				?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $keluar['nama_karyawan'] ?></td>
						<td><?= $keluar['tgl_keluar'] ?></td>
						<td><?= $keluar['total'] ?></td>
					</tr>
				<?php
					$total_ak = $keluar['total'] + $total_ak;
				}
				?>
				<tr>
					<td></td>
					<td></td>
					<td><input type="submit" value="KONFIRMASI" name='konfirmasi_keluar' class='btn btn-danger'></td>
					<td><?= $total_ak ?></td>
				</tr>
		</form>

		</table>
	<?php
	} elseif (isset($_GET['sebelum_sinkron'])) {
	?>
		<form action="" method="post">
			<?php
			$qcekbaru = "SELECT max(no_center) as center_max from center where id_cabang='$id_cabang'";

			$cek_max = mysqli_query($con, $qcekbaru);
			$cek_max = mysqli_fetch_assoc($cek_max)['center_max'];
			$cek_baru = mysqli_query($con, "SELECT MAX(SUBSTRING_INDEX( SUBSTRING_INDEX(id_detail_nasabah,'/',-1),'-',1)) AS center  FROM temp_anggota where id_cabang='$id_cabang' and status_input='belum' ");
			$cek_baru = mysqli_fetch_assoc($cek_baru)['center'];
			if ($cek_baru > $cek_max) {
				if (!isset($_GET['lanjutkan'])) {
			?>
					<h2>ADA CENTER BARU MAU DIPINDAH KESIAPA?</h2>
					<table class='table'>
						<tr>
							<th>NO</th>
							<th>STAFF MDIS</th>
							<th>CENTER</th>
							<th>ANGGOTA MASUK</th>
							<th>STAFF</th>
						</tr>
						<?php
						$no = 1;
						$total_am = 0;
						$q_center_baru = "SELECT *,count(tgl_bergabung) as am, SUBSTRING_INDEX( SUBSTRING_INDEX(id_detail_nasabah,'/',-1),'-',1) as center FROM temp_anggota where id_cabang='$id_cabang' and status_input='belum' and SUBSTRING_INDEX( SUBSTRING_INDEX(id_detail_nasabah,'/',-1),'-',1)>$cek_max and  staff is not null GROUP BY SUBSTRING_INDEX( SUBSTRING_INDEX(id_detail_nasabah,'/',-1),'-',1) order by staff asc";
						$kary = mysqli_query($con, "$q_center_baru ");
						while ($nama = mysqli_fetch_assoc($kary)) {
							$total_am = $total_am + $nama['am'];
						?>
							<tr>
								<td><?= $no++ ?></td>
								<td>
									<?= $nama['staff'] ?>
									<input type="hidden" name="nama_mdis[]" value="<?= $nama['staff'] ?>">
									<input type="hidden" name="center[]" value="<?= $nama['center'] ?>">
								</td>
								<td>
									<?= $nama['center'] ?>

								</td>
								<td>
									<?= $nama['am'] ?>
								</td>
								<td>
									<select name="karyawan[]" id="" required class='form-control'>
										<option value="">Pilih Staff</option>
										<?php $data_karyawan  = (karyawan($con, $id_cabang)['data']);
										for ($i = 0; $i < count($data_karyawan); $i++) {
											$nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
											if (strtolower($nama_karyawan) == strtolower($nama['staff'])) {
												echo "<option selected value='" . $data_karyawan[$i]['nama_karyawan'] . "'>" . $nama_karyawan . "</option>";
											} else {
												echo "<option value='" . $data_karyawan[$i]['nama_karyawan'] . "'>" . $nama_karyawan . "</option>";
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
							<td></td>
							<td><?= $total_am ?></td>
							<td>
								<input type="submit" value="SIMPAN" name="ganti_dulu" class='btn btn-info'>
							</td>
						</tr>
					</table>
		</form>
	<?php

				}
				if (isset($_POST['ganti_dulu'])) {

					$staf = $_POST['nama_mdis'];
					$id_k = $_POST['karyawan'];
					$center = $_POST['center'];
					for ($i = 0; $i < count($staf); $i++) {
						if (!empty($id_k[$i])) {
							$text = " UPDATE `temp_anggota` SET `staff` = '$id_k[$i]' WHERE  (SUBSTRING_INDEX( SUBSTRING_INDEX(id_detail_nasabah,'/',-1),'-',1))='$center[$i]' and id_cabang='$id_cabang'; ";
							mysqli_query($con, $text);
							// echo $text;
						}
					}
					pindah($url . $menu . "anggota&sinkron&lanjutkan");
				}
			} else {
				pindah($url . $menu . "anggota&sinkron&lanjutkan");
			}
		} else if (isset($_GET['sinkron'])) {
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
				$cari_tgl = mysqli_query($con, "SELECT tgl_bergabung FROM temp_anggota where id_cabang='$id_cabang' and status_input='belum' GROUP BY tgl_bergabung");
				while ($tgll = mysqli_fetch_assoc($cari_tgl)) {
					$tgl = $tgll['tgl_bergabung'];
					echo $tgl . " Proses <br/>";
					$total_semua = $total_semua;
					$qcariStaff = mysqli_query($con, "select *,count(id) as total_anggota from temp_anggota where tgl_bergabung='$tgl' and id_cabang='$id_cabang' and status_input='belum' group by id_karyawan");
					while ($cariStaff = mysqli_fetch_assoc($qcariStaff)) {
						$total_semua = $total_semua + $cariStaff['total_anggota'];
						// echo $cariStaff['id_karyawan'];
						mysqli_query($con, "INSERT INTO `anggota` (`id_karyawan`, `tgl_anggota`, `anggota_masuk`, `anggota_keluar`, `net_anggota`,`id_cabang`) VALUES ('$cariStaff[id_karyawan]', '$tgl', '$cariStaff[total_anggota]', '0', '$cariStaff[total_anggota]','$id_cabang'); ");
						//UPDATE TOTAL NASABAH
						mysqli_query($con, "UPDATE total_nasabah set total_nasabah = total_nasabah + $cariStaff[total_anggota] where id_karyawan='$cariStaff[id_karyawan]' ");
					}
					mysqli_query($con, "UPDATE temp_anggota set status_input='sudah' where id_cabang='$id_cabang' and tgl_bergabung='$tgl'");
				}

				// mysqli_query($con,"delete from temp_anggota where id_cabang='$id_cabang' ");
				echo "Proses selesai tunggu prosess slanjutnya!";
				pindah($url . $menu . "anggota&tambah");
			}


			if (isset($_GET['lanjutkan'])) {

				if (isset($_GET['lanjutkan'])) {
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
					$total_am = 0;
					$kary1 = mysqli_query($con, "SELECT *,count(tgl_bergabung) as am FROM temp_anggota where id_cabang='$id_cabang' and status_input='belum' and staff is not null GROUP BY staff order by staff asc ");
					while ($nama = mysqli_fetch_assoc($kary1)) {
						$total_am = $total_am + $nama['am'];
				?>
					<tr>
						<td><?= $no++ ?></td>
						<td>
							<?= $nama['staff'] ?>
							<input type="hidden" name="nama_mdis[]" value="<?= $nama['staff'] ?>">
						</td>
						<td><?= $nama['am'] ?></td>
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
					<td><?= $total_am ?></td>
					<td>
						<input type="submit" value="SINKRON" name="konfirmasi" class='btn btn-info'>
					</td>
				</tr>
			</table>
	<?php
				}
			}

	?>

		</form>

	<?php
		} elseif (isset($_GET['edit_anggota'])) {
			include("./proses/edit_anggota.php");
		} else {
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
					if ($masuk[$x] == 0 && $keluar[$x] == 0) {
					} else {

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
				while ($cek_ka = mysqli_fetch_assoc($qk)) {
				?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $cek_ka['nama_karyawan'] ?></td>
						<td>
							<input type='number' class="form-control" style="width: 100px" name='masuk[]'
								id='masuk<?= $cek_ka['id_karyawan'] ?>' value='0'></input>
							<input type='hidden' name='idk[]' class="form-control"
								value='<?= $cek_ka['id_karyawan'] ?>'></input>

						</td>
						<td>
							<input type='number' class="form-control" style="width: 100px" name='keluar[]'
								id='keluar<?= $cek_ka['id_karyawan'] ?>' onkeyup="ganti_net('<?= $cek_ka['id_karyawan'] ?>')"
								value='0'></input>

						</td>
						<td>
							<input type='number' class="form-control" readonly style="width: 100px" name='nett[]'
								id='nett<?= $cek_ka['id_karyawan'] ?>' value='0'></input>

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