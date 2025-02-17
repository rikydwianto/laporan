<div class='content table-responsive'>
	<h2 class='page-header'>CENTER</h2>
	<i>Center otomatis dibuat ketika Staff membuat laporan</i>
	<hr />
	<!-- Button to Open the Modal -->
	<a href='<?= $url . $menu . "center" ?>' class="btn btn-primary">
		<i class="fa fa-eye"></i> Center
	</a>
	<a href='<?= $url . $menu ?>center&ganti_staff' class="btn btn-success">
		<i class="fa fa-plus"></i> Ganti Staff
	</a>
	<a href="<?= $url ?>/export/center.php" class='btn btn-success'>
		<i class="fa fa-file-excel-o"></i> Export To Excel
	</a>
	<a href="<?= $url ?>/export/center_papan.php" class='btn btn-success'>
		<i class="fa fa-file-excel-o"></i> JADWAL PAPAN
	</a>
	<a href="<?= $url ?>/export/center_papan1.php" class='btn btn-success'>
		<i class="fa fa-file-excel-o"></i> JADWAL PAPAN JAM
	</a>
	<a href="<?= $url ?>/export/center_papan2.php" class='btn btn-primary'>
		<i class="fa fa-file-excel-o"></i> JADWAL PAPAN JAM + DESA
	</a>
	<a href="<?= $url ?>/export/center_papan_nama.php" class='btn btn-warning'>
		<i class="fa fa-file-excel-o"></i> JADWAL PAPAN JAM + NAMA CENTER
	</a>
	<!-- <a href="<?= $url . $menu ?>sync_center" class='btn btn-danger'>
		<i class="fa fa-gears"></i> SYNC NEW
	</a> -->
	<!-- <a href="<?= $url . $menu ?>center&konfirmasi" class='btn btn-info'>
				  <i class="fa fa-file-excel-o"></i> KONFIRMASI
			  </a> -->
	<!-- <a href="<?= $url . $menu ?>center&unkonfirmasi" class='btn btn-info'>
			<i class="fa fa-file-excel-o"></i> UN - KONFIRMASI
		</a> -->
	<br>
	<br>
	<?php
	$qqq = mysqli_query($con, "select * from center where konfirmasi='t' and id_cabang='$id_cabang' group by id_cabang ");
	if (mysqli_num_rows($qqq)) {
		pesan("Ada center belum dikonfirmasi silahkan konfirmasi dengan menekan tomnol dibawah!<br>Lalu scroll paling bawah tekan konfirmasi jika sudah sesuai!", 'danger')
	?>

		<a href="<?= $url . $menu ?>center&sinkron" class='btn btn-success'>
			<i class="fa fa-rotate-right"></i> SINGKRON DISINI
		</a>
	<?php
	} else {
	?>
		<form method="post" enctype="multipart/form-data">
			<div class="col-md-4">
				<label for="formFile" class="form-label">SILAHKAN PILIH FILE : JADWAL CENTER MEETING</label>
				<input class="form-control" type="file" name='file' accept=".xml" id="formFile">
				<!-- <input type="submit" value="Proses"  class='btn btn-danger' name='preview'> -->
				<input type="submit" value="Proses" class='btn btn-info' name='xml-preview'>
				<input type="submit" value="Proses Versi LAMA" class='btn btn-info' name='xml-preview-2'>
			</div>
		</form>
	<?php
	}
	?>

	<?php
	//   XML  //

	if (isset($_POST['xml-preview'])) {
		$file = $_FILES['file']['tmp_name'];
		$path = $file;
		// libxml_use_internal_errors(true);

		libxml_use_internal_errors(true); // Abaikan error XML
		$xml = simplexml_load_file($path, null, LIBXML_NOCDATA);
		if (!$xml) {
			echo "Error saat memuat XML:<br>";
			foreach (libxml_get_errors() as $error) {
				echo "Line {$error->line}: {$error->message}<br>";
			}
			// exit;
		}

		$validate = $xml['Name'];

		if ($xml) {
			$hariCollection = $xml->Tablix2->HARI_Collection;
			$total_center = 0;
			$center = [];

			foreach ($hariCollection->HARI as $day) {
				$days = strtolower((string)$day['HARI1']);

				foreach ($day->OfficerName_Collection->OfficerName as $staff) {
					$nama_staff = explode("Total ", (string)$staff['OfficerName1'])[0];
					$nama_staff = trim(preg_replace('/[\r\n]+/', ' ', $nama_staff));

					foreach ($staff->CenterID_Collection->CenterID as $ctr_staf) {
						$no_center = rubahkata(htmlspecialchars((string)$ctr_staf['CenterID'], ENT_QUOTES, 'UTF-8'));

						$detail_center = $ctr_staf->CenterName->Details_Collection->Details;

						// Escape and validate each field, lalu hapus kata-kata terlarang
						$jam = rubahkata(htmlspecialchars((string)$detail_center['MeetingTime'], ENT_QUOTES, 'UTF-8'));
						$agt = intval($detail_center['Textbox128']); // Tetap angka, tidak perlu filter kata
						$client = intval($detail_center['JumlahClient']); // Tetap angka, tidak perlu filter kata
						$desa = rubahkata(htmlspecialchars((string)$detail_center['DusunName'], ENT_QUOTES, 'UTF-8'));
						$kecamatan = rubahkata(htmlspecialchars((string)$detail_center['KecamatanName'], ENT_QUOTES, 'UTF-8'));
						$kab = rubahkata(htmlspecialchars((string)$detail_center['KabupatenName'], ENT_QUOTES, 'UTF-8'));
						$centerName = rubahkata(htmlspecialchars((string)$ctr_staf->CenterName['CenterName'], ENT_QUOTES, 'UTF-8'));

						$qcek = mysqli_query($con, "SELECT no_center FROM center WHERE id_cabang='$id_cabang' AND no_center='$no_center'");
						if (!$qcek) {
							die("Error in SQL Query (SELECT): " . mysqli_error($con));
						}

						$center[] = $no_center;
						$hitung = mysqli_num_rows($qcek);

						if ($hitung > 0) {
							$txt = "UPDATE `center` SET 
									`member_center` = '$agt',
									`anggota_center` = '$client',
									`center_bayar` = '$client',
									`jam_center` = '$jam',
									`hari` = '$days',
									`konfirmasi` = 't',
									`anggota_hadir` = '$agt',
									`staff` = '$nama_staff', 
									`desa` = '$desa', 
									`kecamatan` = '$kecamatan', 
									`kabupaten` = '$kab',
									`nama_center`='$centerName'
								WHERE `no_center` = '$no_center' AND `id_cabang` = '$id_cabang';";
							if (!mysqli_query($con, $txt)) {
								die("Error in SQL Query (UPDATE): " . mysqli_error($con));
							}
						} else {
							$qtxt = "INSERT INTO 
									`center` (`id_center`, `no_center`, `doa_center`, `hari`, `status_center`, `member_center`, `anggota_center`, `center_bayar`, `id_cabang`, `id_karyawan`, `id_laporan`, `jam_center`, `latitude`, `longitude`, `doortodoor`, `blacklist`, `konfirmasi`, `staff`, `desa`, `kecamatan`, `kabupaten`, `anggota_hadir`,`nama_center`) 
								VALUES (NULL, '$no_center', 'y', '$days', 'hijau', '$agt', '$client', '$client', '$id_cabang', '0', '0', '$jam', 'null', 'null', 't', 't', 't', '$nama_staff', '$desa', '$kecamatan', '$kab', '$agt','$centerName');";
							if (!mysqli_query($con, $qtxt)) {
								die("Error in SQL Query (INSERT): " . mysqli_error($con));
							}
						}
						$total_center++;
					}
				}
			}

			$gabung_center = implode("','", $center);
			if (!mysqli_query($con, "DELETE FROM center WHERE no_center NOT IN ('$gabung_center') AND id_cabang='$id_cabang'")) {
				die("Error in SQL Query (DELETE): " . mysqli_error($con));
			}
			pindah($url . $menu . "center&sinkron");
		} else {
			echo "Nama dokumen XML tidak valid.";
		}
		// exit;
	}


	if (isset($_POST['xml-preview-2'])) {
		$file = $_FILES['file']['tmp_name'];
		$path = $file;

		// Abaikan error XML
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file($path, null, LIBXML_NOCDATA);
		if (!$xml) {
			echo "Error saat memuat XML:<br>";
			foreach (libxml_get_errors() as $error) {
				echo "Line {$error->line}: {$error->message}<br>";
			}
			// exit; // Anda bisa menghapus exit jika ingin melanjutkan dengan eksekusi
		}

		$validate = $xml['Name'];

		if ($xml) {
			$hariCollection = $xml->Tablix2->HARI_Collection;
			$total_center = 0;
			$center = [];

			foreach ($hariCollection->HARI as $day) {
				$days = strtolower((string)$day['HARI1']);

				foreach ($day->OfficerName_Collection->OfficerName as $staff) {
					$nama_staff = explode("Total ", (string)$staff['OfficerName1'])[0];
					$nama_staff = trim(preg_replace('/[\r\n]+/', ' ', $nama_staff));

					foreach ($staff->CenterID_Collection->CenterID as $ctr_staf) {
						$no_center = rubahkata(htmlspecialchars((string)$ctr_staf['CenterID'], ENT_QUOTES, 'UTF-8'));

						$detail_center = $ctr_staf->CenterName->Details_Collection->Details;

						// Escape and validate each field, lalu hapus kata-kata terlarang
						$jam = rubahkata(htmlspecialchars((string)$detail_center['MeetingTime'], ENT_QUOTES, 'UTF-8'));
						$agt = intval($detail_center['Textbox128']); // Tetap angka, tidak perlu filter kata
						$client = intval($detail_center['JumlahClient']); // Tetap angka, tidak perlu filter kata
						$desa = rubahkata(htmlspecialchars((string)$detail_center['DusunName'], ENT_QUOTES, 'UTF-8'));
						$kecamatan = rubahkata(htmlspecialchars((string)$detail_center['KecamatanName'], ENT_QUOTES, 'UTF-8'));
						$kab = rubahkata(htmlspecialchars((string)$detail_center['KabupatenName'], ENT_QUOTES, 'UTF-8'));
						$centerName = rubahkata(htmlspecialchars((string)$ctr_staf->CenterName['CenterName'], ENT_QUOTES, 'UTF-8'));

						// Cek apakah center sudah ada dalam database
						$qcek = mysqli_query($con, "SELECT no_center FROM center WHERE id_cabang='$id_cabang' AND no_center='$no_center'");
						if (!$qcek) {
							die("Error in SQL Query (SELECT): " . mysqli_error($con));
						}

						$center[] = $no_center;
						$hitung = mysqli_num_rows($qcek);

						// Update jika center sudah ada
						if ($hitung > 0) {
							$txt = "UPDATE `center` SET 
									`member_center` = '$agt',
									`anggota_center` = '$client',
									`center_bayar` = '$client',
									`jam_center` = '$jam',
									`hari` = '$days',
									`konfirmasi` = 't',
									`anggota_hadir` = '$agt',
									`staff` = '$nama_staff', 
									`desa` = '$desa', 
									`kecamatan` = '$kecamatan', 
									`kabupaten` = '$kab',
									`nama_center` = '$centerName'
								WHERE `no_center` = '$no_center' AND `id_cabang` = '$id_cabang';";
							if (!mysqli_query($con, $txt)) {
								die("Error in SQL Query (UPDATE): " . mysqli_error($con));
							}
						} else {
							// Jika center belum ada, insert data baru
							$qtxt = "INSERT INTO 
									`center` (`id_center`, `no_center`, `doa_center`, `hari`, `status_center`, `member_center`, `anggota_center`, `center_bayar`, `id_cabang`, `id_karyawan`, `id_laporan`, `jam_center`, `latitude`, `longitude`, `doortodoor`, `blacklist`, `konfirmasi`, `staff`, `desa`, `kecamatan`, `kabupaten`, `anggota_hadir`, `nama_center`) 
								VALUES (NULL, '$no_center', 'y', '$days', 'hijau', '$agt', '$client', '$client', '$id_cabang', '0', '0', '$jam', 'null', 'null', 't', 't', 't', '$nama_staff', '$desa', '$kecamatan', '$kab', '$agt', '$centerName');";
							if (!mysqli_query($con, $qtxt)) {
								die("Error in SQL Query (INSERT): " . mysqli_error($con));
							}
						}
						$total_center++;
					}
				}
			}

			// Menghapus center yang tidak ada dalam XML
			$gabung_center = implode("','", $center);
			if (!mysqli_query($con, "DELETE FROM center WHERE no_center NOT IN ('$gabung_center') AND id_cabang='$id_cabang'")) {
				die("Error in SQL Query (DELETE): " . mysqli_error($con));
			}
			pindah($url . $menu . "center&sinkron");
		} else {
			echo "Nama dokumen XML tidak valid.";
		}
		// exit;
	}






	if (isset($_POST['preview'])) {
		set_time_limit(5000);
		// alert("tunggu ya proses ini akan memakan waktu agak lama, karena banyak nya data, jangan diclose sampe proses selesai!!");
	?>
		<table border=1>


			<?php
			$file = $_FILES['file']['tmp_name'];
			$path = $file;
			$reader = PHPExcel_IOFactory::createReaderForFile($path);
			$objek = $reader->load($path);
			$ws = $objek->getActiveSheet();
			$last_row = $ws->getHighestDataRow();
			$no_input = 0;
			for ($row = 5; $row <= $last_row; $row++) {
				$jam =  $ws->getCell("E" . $row)->getValue();
				if ($jam == null) {
				} else {
					$no_center = $ws->getCell("D" . $row)->getValue();
					$desa = $ws->getCell("I" . $row)->getValue();
					$kab = $ws->getCell("K" . $row)->getValue();
					$member = (int) $ws->getCell("G" . $row)->getValue();
					$client = (int) $ws->getCell("H" . $row)->getValue();
					mysqli_query($con, "UPDATE center set member_center='$member' , anggota_center='$client', jam_center = '$jam',konfirmasi='y' where id_cabang='$id_cabang' and no_center='$no_center'");
				}
			}
			alert("Berhasil di update");
			pindah($url . $menu . "center");
			exit;
		}

		if (isset($_GET['del'])) {
			$iddet = $_GET['iddet'];
			$del = mysqli_query($con, "delete from center where id_center='$iddet'");
			if ($del) {
				pesan("Center Berhasil dihapus", 'success');
			}
		}

		if (isset($_GET['unkonfirmasi'])) {
			$del = mysqli_query($con, "UPDATE `center` SET `konfirmasi` = 't' WHERE `id_cabang` = '$id_cabang'; ");
			if ($del) {
				pesan("Center Berhasil di UN-Confirm", 'success');
			}
		}
		if (isset($_GET['konfirmasi'])) {
			$del = mysqli_query($con, "UPDATE `center` SET `konfirmasi` = 'y' WHERE `id_cabang` = '$id_cabang'; ");
			if ($del) {
				pesan("Center Berhasil di Confirm", 'success');
			}
		}

		if (isset($_POST['edit_center'])) {
			$iddet = $_GET['iddet'];
			$jam = $_POST['jam'];
			$hari = $_POST['hari'];
			$staff = $_POST['staff'];
			$q = mysqli_query($con, "UPDATE `center` SET `jam_center` = '$jam',hari='$hari',id_karyawan='$staff' WHERE `id_center` = '$iddet';  ");

			if ($q) {
				alert("Berhasil");
			} else {
				echo "gagal";
			}
		}


		if (isset($_POST['pindah_staff'])) {
			$idlama = $_POST['staf_lama'];
			$idbaru = $_POST['staf_baru'];
			$sql = "SHOW TABLES";
			$result = mysqli_query($con, $sql);
			// echo  mysqli_error($con);
			$larang = ['karyawan', 'daftar_wilayah', 'jabatan', 'kategori_surat', 'rekap_bayar', 'rekap_center', 'spl', 'statistik', 'surat'];
			while ($row = mysqli_fetch_row($result)) {
				if (in_array($row[0], $larang)) {
				} else {
					// echo"tidak ada";
					//  $query8 = mysqli_query($con,"UPDATE `perbaikan` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					$query = mysqli_query($con, "UPDATE $row[0] SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					//   echo "UPDATE $row[0] SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  <br/>";
					// $query1 = mysqli_query($con,"UPDATE `laporan` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					// $query2 = mysqli_query($con,"UPDATE `center` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					// $query3 = mysqli_query($con,"UPDATE `group_user` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					// $query4 = mysqli_query($con,"UPDATE `cashflow` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					// $query5 = mysqli_query($con,"UPDATE `image` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					// $query6 = mysqli_query($con,"UPDATE `pinjaman` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					// $query7 = mysqli_query($con,"UPDATE `total_nasabah` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					// $query8 = mysqli_query($con,"UPDATE `disburse` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
					// $query9 = mysqli_query($con,"UPDATE `target_disburse` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");

				}

				// exit;
			}
		}


		if (isset($_GET['edit'])) {
			$iddet = $_GET['iddet'];
			$cek_center = mysqli_query($con, "select * from center where id_center='$iddet'");
			$cek_center = mysqli_fetch_assoc($cek_center);
			?>
			<div class="col-md-7">
				<form method="post">
					<h3 class="page-header">EDIT CENTER <?= $cek_center['no_center'] ?></h3>
					<hr>
					<table class='table'>
						<tr>
							<td>No Center</td>
							<td><input type="number" disabled class='form-control' name="center"
									value="<?= $cek_center['no_center'] ?>" id=""></td>
						</tr>
						<tr>
							<td>Status</td>
							<td><input type="text" disabled class='form-control' value="<?= $cek_center['status_center'] ?>"
									id=""></td>
						</tr>
						<tr>
							<td>Staff</td>
							<td>
								<select name='staff' class='form-control form-select' required>
									<option value="">Silahkan Pilih Staff</option>
									<?php
									$qk = mysqli_query($con, "select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
									while ($cek_ka = mysqli_fetch_assoc($qk)) {
										if ($cek_ka['id_karyawan'] == $cek_center['id_karyawan']) {
									?>
											<option value='<?= $cek_ka['id_karyawan'] ?>' selected><?= $cek_ka['nama_karyawan'] ?>
											</option>
										<?php
										} else {
										?>
											<option value='<?= $cek_ka['id_karyawan'] ?>'><?= $cek_ka['nama_karyawan'] ?></option>
										<?php
										}
										?>

									<?php

									}
									?>
								</select>

							</td>
						</tr>
						<tr>
							<td>JAM</td>
							<td><input type="text" class='form-control' name="jam" value="<?= $cek_center['jam_center'] ?>"
									id=""></td>
						</tr>
						<tr>
							<td>HARI</td>
							<td>
								<select name='hari' class='form-control'>
									<?php $hari = hari();
									for ($i = 0; $i < count($hari); $i++) {
										if (strtolower($hari[$i]) == $cek_center['hari']) {
											echo "<option value='" . strtolower($hari[$i]) . "' selected >$hari[$i]</option>";
										} else {
											echo "<option value='" . strtolower($hari[$i]) . "' >$hari[$i]</option>";
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="submit" name='edit_center' class='btn btn-success' value="EDIT">
							</td>
						</tr>
					</table>
					<br />

				</form>

			</div>
		<?php
		}


		if (isset($_GET['ganti_staff'])) {
		?>
			<div class="col-md-6">
				<form method="post">
					<h3 class="page-header">Pindahkan SEMUA CENTER Ke STAFF BARU</h3>
					<small class="page-header">memindahkan center berarti memindahkan semua data laporan dan data center ke
						staff baru</small>
					<hr>
					<table>
						<tr>
							<td> Staff Lama</td>
							<td><select name='staf_lama' class='form-control form-select' required>
									<option value="">Silahkan Pilih Staff</option>
									<?php
									$qk = mysqli_query($con, "select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
									while ($cek_ka = mysqli_fetch_assoc($qk)) {
									?>
										<option value='<?= $cek_ka['id_karyawan'] ?>'><?= $cek_ka['nama_karyawan'] ?></option>
									<?php

									}
									?>
								</select></td>

						</tr>
						<tr>
							<td> Staff BARU</td>
							<td><select name='staf_baru' class='form-control form-select' required>
									<option value="">Silahkan Pilih Staff</option>
									<?php
									$qk = mysqli_query($con, "select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
									while ($cek_ka = mysqli_fetch_assoc($qk)) {
									?>
										<option value='<?= $cek_ka['id_karyawan'] ?>'><?= $cek_ka['nama_karyawan'] ?></option>
									<?php

									}
									?>
								</select></td>
						</tr>
						<tr>
							<td></td>
							<td>

								<input type='submit' class='btn btn-danger'
									onclick="window.confirm('Apakah anda yakin memindahkan, semua laporan juga akan diganti oleh staff baru')"
									name='pindah_staff' value="PINDAHKAN CENTER" />
							</td>
						</tr>
					</table>



				</form>

			</div>
		<?php
		}

		?>
		<br>
		<br>
		<br>
		<?php
		if (isset($_GET['sinkron'])) {
		?>
			<form action="" method="post">
				<div class="col-md-12">
					<table class='table'>
						<tr>
							<th>NO</th>
							<th>NAMA MDIS</th>
							<th>TOTAL AGT </th>
							<th>GANTI </th>
						</tr>
						<?php
						$total_n = 0;
						$q_nama = mysqli_query($con, "select sum(member_center) as total, staff from center where id_cabang='$id_cabang'  and konfirmasi='t' group by staff");
						while ($nama = mysqli_fetch_assoc($q_nama)) {
							$total_n += $nama['total'];
						?>
							<tr>
								<td><?= $no++ ?></td>
								<td><?= $nama['staff'] ?></td>
								<td><?= $nama['total'] ?></td>
								<td>
									<input type="hidden" name="nama_mdis[]" value="<?= $nama['staff'] ?>">
									<input type="hidden" name="total_nasabah[]" value="<?= $nama['total'] ?>">
									<select name="karyawan[]" id="" required class='form-control'>
										<option value="">Pilih Staff</option>
										<?php $data_karyawan  = (karyawan($con, $id_cabang)['data']);
										for ($i = 0; $i < count($data_karyawan); $i++) {
											$nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
											if (strtolower($nama_karyawan) == trim(strtolower($nama['staff']))) {
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
							<td colspan="2"></td>
							<td colspan="1"><?= $total_n ?></td>
							<td>
								<input type="submit" class='btn btn-success' value='KONFIRMASI' name='ganti' />
							</td>
						</tr>

					</table>
				</div>
			</form>
		<?php

		} else {
		?>
			<table id='data_karyawan'>
				<thead>
					<tr>
						<!-- <th>NO</th> -->
						<th>CENTER</th>
						<th>ANGGOTA</th>
						<th>CLIENT</th>
						<th>HARI</th>
						<th>JAM</th>
						<th>DOA</th>
						<th>STATUS</th>
						<th>STAFF</th>
						<th>Nama Center</th>
						<th>Lat,Lng</th>

						<th>#</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$q = mysqli_query($con, "select * from center where id_cabang='$id_cabang' order by no_center desc");
					while ($center = mysqli_fetch_assoc($q)) {
						$data = detail_karyawan($con, $center['id_karyawan']);
					?>
						<tr>
							<!-- <td><?= $no++; ?></td> -->
							<td><?= $center['no_center']; ?></td>
							<td><?= $center['member_center']; ?></td>
							<td><?= $center['anggota_center']; ?></td>
							<td><?= $center['hari']; ?></td>
							<td><?= $center['jam_center']; ?></td>
							<td><?= $center['doa_center']; ?></td>
							<td><?= $center['status_center']; ?></td>
							<td><?= $data['nama_karyawan']; ?></td>
							<td><?= $center['nama_center']; ?></td>
							<td>
								<?php if ($center['latitude'] != null || $center['longitude'] != NULL) : ?>
									<a href="<?= link_maps($center['latitude'], $center['longitude']) ?>">Arahkan</a>
								<?php endif; ?>
							</td>

							<td>

								<a href="<?= $url . $menu ?>center&del&iddet=<?= $center['id_center'] ?>"
									onclick="return window.confirm('Apakah yakin menghapus center ini')"> <i
										class='fa fa-times'></i> Hapus</a>
								<a href="<?= $url . $menu ?>center&edit&iddet=<?= $center['id_center'] ?>"> <i
										class='fa fa-edit'></i>
									Edit</a>
							</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		<?php
		}
		?>
</div>
<?php
if (isset($_POST['ganti'])) {
	$karyawan = $_POST['karyawan'];
	$mdis = $_POST['nama_mdis'];
	$total = $_POST['total_nasabah'];
	$gabung_kar = array();
	for ($i = 0; $i < count($mdis); $i++) {
		mysqli_query($con, "UPDATE center set id_karyawan ='$karyawan[$i]', konfirmasi='y' where id_cabang='$id_cabang' and staff='$mdis[$i]'");
		$cek_total = mysqli_query($con, "select * from total_nasabah where id_cabang='$id_cabang' and id_karyawan='$karyawan[$i]'");
		if (mysqli_num_rows($cek_total)) {
			$total_nasabah = mysqli_query($con, "UPDATE total_nasabah set total_nasabah='$total[$i]'  where id_cabang='$id_cabang' and id_karyawan='$karyawan[$i]' ");
			$gabung_kar[] = $karyawan[$i];
		} else {
			echo "tidak ada";
		}
	}
	$gabungan = implode(",", $gabung_kar);
	$sql = mysqli_query($con, "delete from total_nasabah where id_cabang='$id_cabang' and id_karyawan not in($gabungan)");
	alert("DAFTAR CENTER BERHASIL DIUPDATE");
	pindah($url . $menu . "center");
}
?>