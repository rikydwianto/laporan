<style>
	.tengah {
		text-align: center;
		font-weight: bold;
	}

	.kotak {
		width: 50px;
		text-align: center;
	}

</style>
<div class='content table-responsive'>
	<h2 class='page-header'>STATISTIK PETUGAS LAPANG </h2>
	<i></i> <br />
	<a href="<?= $url . $menu ?>spl" class='btn btn-success'> <i class="fa fa-eye"></i> Lihat</a>
	<a href="#" class='btn btn-danger' data-toggle="modal" data-target="#modalku"> <i class="fa fa-plus"></i> Tambah</a>


	<hr />

	<?php
	if (isset($_GET['tambah'])) {
		$id = aman($con,$_GET['id']);
		$tgl = $_GET['tgl'];
	?>
		<!-- <form action="" method="post">
			<textarea name="query" class='form-control' id="" cols="50" rows="20"></textarea>
			<input type="submit" value="Execute" name='ekse' />
		</form> -->
			<?php
			//CODE FOR EKSE QUERY
			if (isset($_POST['ekse'])) {
				$text = ($_POST['query']);
				$text = str_replace("**", ",id_statistik,id_cabang) ", $text);
				$text = str_replace("###", ",'$id','$id_cabang'); ", $text);
				// echo $text;
				$query = mysqli_multi_query($con, $text);
				if ($query) {
					// sleep(5);
					alert("Terima Kasih telah menunggu, Data berhasil input ...");
					pindah($url.$menu."spl");
				} else {
					pesan("Gagal <br/> $text", 'danger');
				}
			}
			?>
			<form method="post" enctype="multipart/form-data">
			<div class="col-md-6">
				<label for="formFile" class="form-label">SILAHKAN PILIH FILE : SPL/ STATISTIK PETUGAS LAPANG TGL <?=$tgl?></label>
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
			
			if (isset($_POST['preview'])) {
				$nama_file = $_FILES['file']['tmp_name'];
				$_SESSION['nama_file'] = $nama_file;
				$path = $_SESSION['nama_file'];
				$id=$_GET['id'];
				$reader = PHPExcel_IOFactory::createReaderForFile($path);
				$objek = $reader->load($path);
				$ws = $objek->getActiveSheet();
				$last_row = $ws->getHighestDataRow();
				for ($row = 2; $row <= $last_row; $row++) {
					$params =  $ws->getCell("D" . $row)->getValue();
					if ($params == null) {
					} else {
						$subtot = ganti_karakter(substr($params, 0, 4));
						if ($subtot == 'Sub.') {
							$staff =  ganti_karakter($ws->getCell("W" . $row)->getValue());
							$nama =  substr($ws->getCell("D" . $row)->getValue(),9);
							$jumlah_center = ganti_karakter($ws->getCell("E".$row)->getValue());
							$member = ganti_karakter($ws->getCell("G".$row)->getValue());
							$client = ganti_karakter($ws->getCell("H".$row)->getValue());
							$saving = ganti_karakter($ws->getCell("O".$row)->getValue()) + ganti_karakter($ws->getCell("P".$row)->getValue()) + ganti_karakter($ws->getCell("Q".$row)->getValue()) + ganti_karakter($ws->getCell("R".$row)->getValue()) + ganti_karakter($ws->getCell("S".$row)->getValue());
							
							$group = ganti_karakter($ws->getCell("F".$row)->getValue());
							$disburse = ganti_karakter($ws->getCell("K".$row)->getValue());

							$os = ganti_karakter($ws->getCell("L".$row)->getValue());
							$masalah = ganti_karakter($ws->getCell("M".$row)->getValue());
							$par = ganti_karakter($ws->getCell("N".$row)->getValue());
							$new_member = ganti_karakter($ws->getCell("U".$row)->getValue());
							$cek_anggota = mysqli_num_rows(mysqli_query($con,"SELECT * FROM spl JOIN statistik ON spl.`id_statistik`=statistik.`id_statistik`
							 WHERE spl.id_statistik='$id' AND spl.`id_cabang`='$id_cabang' AND statistik.tgl_statistik='$tgl' and statistik.staff='$nama' "));
							 if($cek_anggota){

							 }
							 else{
								 $text = " INSERT INTO `spl` 
								 (`id_spl`, `id_statistik`, `staff`, `jumlah_center`, `member`, `client`, `disburse`, `outstanding`, `masalah`, `par`, `new_member`, `id_karyawan`, `id_cabang`,`saving`,`group`) 
								 VALUES (NULL, '$id', '$nama', '$jumlah_center', '$member', '$client', '$disburse', '$os', '$masalah', '$par', '$new_member', null, '$id_cabang','$saving','$group');";
								 // echo $text;
								 mysqli_query($con,$text);

							 }
							pindah($url.$menu."spl");
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
			// pindah($url.$menu."anggota&sinkron");
			}

			?>
		</table>
	<?php
	} 
	else if(isset($_GET['hapus'])){
        $id = aman($con,$_GET['id']);
        // $detail = aman($con,$_GET['detail']);
        
        $q1 = mysqli_query($con,"DELETE FROM `statistik` WHERE `id_statistik` = '$id' ; ");
        $q1 = mysqli_query($con,"DELETE FROM `spl` WHERE `id_statistik` = '$id' ; ");
        pindah("$url$menu".'spl');

    }
	elseif (isset($_GET['detail'])) {
		$id = aman($con, $_GET['id']);
		$tgl = aman($con, $_GET['tgl']);
		$sql = "select * from spl join karyawan on karyawan.id_karyawan=spl.id_karyawan where id_statistik='$id' ";
		$query = mysqli_query($con, $sql);
		$array = array();
		while ($data = mysqli_fetch_assoc($query)) $array[] = $data;

	?>
		<div class="col-md-0">

		</div>
		<div class="col-md-10">
			<table class='table' >
				<tr>
					<th colspan="10" style="text-align:center;font-size:20px"><?= format_hari_tanggal($tgl) ?></th>
				</tr>
				<tr>
					<th>No.</th>
					<th>Nama Staff</th>
					<th>Client</th>
					<th>Member</th>
					<th> Group</th>
					<th> Center</th>
					<th>Arrea</th>
					<th>Disburse</th>
					<th>Outstanding</th>
					<th>Saving</th>
					<th>Outstanding<br>Masalah </th>
					<th>Par</th>
					<th>New Member</th>
				</tr>
				<?php
				$total_disburse = 0;
				$total_outstanding = 0;
				$total_disburse = 0;
				$total_masalah = 0;
				$total_new_member = 0;
				$jumlah_center = 0;
				$jumlah_member = 0;
				$jumlah_client = 0;
				$jumlah_group = 0;
				$t_saving=0;
				foreach ($array as $val) {

					$disburse =  angka_mentah($val['disburse']);
					$outstanding = angka_mentah($val['outstanding']);
					$total_disburse = $total_disburse + $disburse;
					$total_outstanding = $total_outstanding + $outstanding;
					

					$disburse =  angka_mentah($val['disburse']);
					$masalah = angka_mentah($val['masalah']);
					$total_masalah = $total_masalah + $masalah;
					$new_member = $val['new_member'];
					$total_new_member = $total_new_member + $new_member;
					$jumlah_center = $jumlah_center + $val['jumlah_center'];
					$jumlah_member = $jumlah_member + $val['member'];
					$jumlah_client = $jumlah_client + $val['client'];
					$group=$val['group'];
					$jumlah_group = $jumlah_group + $group;
					$saving = $val['saving'];
					$t_saving = $saving + $t_saving
					?>
					<tr>
						<td class='nama'><?= $no++ ?></td>
						<td class='nama'><?= str_replace("Sub. Tot ", "", $val['nama_karyawan']) ?></td>
						<td class='kotak'><?= $val['client'] ?></td>
						<td class='kotak'><?= $val['member'] ?></td>
						<td class='kotak'><?= $group?></td>
						<td class='kotak'><?= $val['jumlah_center'] ?></td>
						<td class='kotak'><?= 0 ?></td>
						<td class='kotak'><?= angka($disburse); ?></td>
						<td class='kotak'><?= angka($outstanding) ?> </td>
						<td class='kotak'><?= angka($saving) ?> </td>
						<td class='kotak'><?= angka($masalah) ?> </td>
						<td class='kotak'><?= round($val['par'],2) ?></td>
						<td class='kotak'><?= ($new_member) ?></td>
					</tr>

				<?php
				$new_member += ($val['new_member']);
				}
				?>
				<tr class='tengah'>
					<td></td>
					<td></td>
					<td><?= $jumlah_client ?></td>
					<td><?= $jumlah_member ?></td>
					<td><?= $jumlah_group ?></td>
					<td><?= $jumlah_center ?></td>
					<td><?= 0 ?></td>
					<td><?= angka($total_disburse) ?></td>
					<td><?= angka($total_outstanding) ?></td>
					<td><?= angka($t_saving) ?></td>

					<td><?= angka($total_masalah) ?></td>
					<td class='kotak'><?= round(($total_masalah / $total_outstanding) * 100, 2) ?></td>
					<td class='kotak'><?= $total_new_member ?></td>
				</tr>


			</table>

		</div>
		<div class='col-md-2'></div>
	<?php

	} else if (isset($_GET['ganti'])) {
	?>
		<form action="" method="post">
			<!-- <input type="submit" value="SIMPAN" name='mtr' class='btn btn-danger'> -->
			<TABLE class='table'>
				<thead>
					<tr>
						<!-- <th>no</th> -->
						<th>NO </th>
						<th>NAMA MDIS</th>
						<th>NAMA </th>
					</tr>
				</thead>
				<tbody>


					<?php
					if (isset($_POST['ganti'])) {
						$karyawan = $_POST['karyawan'];
						$agt = $_POST['agt'];
						$mdis = $_POST['nama_mdis'];

						for ($i = 0; $i < count($mdis); $i++) {
							if (!empty($karyawan[$i])) {
								$text = " UPDATE `spl` SET `staff` = null , id_karyawan='$karyawan[$i]' WHERE `staff` = '$mdis[$i]'; ";

								$q = mysqli_query($con, "$text");
								$cek_total_anggota = mysqli_num_rows(mysqli_query($con,"select * from total_nasabah where id_cabang='$id_cabang' and id_karyawan='$karyawan[$i]'"));
								if($cek_total_anggota>0){
									mysqli_query($con,"UPDATE `total_nasabah` SET `total_nasabah` = '$agt[$i]' WHERE `id_karyawan` = '$karyawan[$i]';");
								} else{
									mysqli_query($con,"INSERT into total_nasabah(id_karyawan,total_nasabah,id_cabang) values('$karyawan[$i]','$agt[$i]','$id_cabang')");
								}
							}
						}
						// pindah($url.$menu."spl");
					}


					$q = mysqli_query($con, "select staff,member from spl where id_karyawan is  null group by staff order by staff asc ");
					while ($pinj = mysqli_fetch_array($q)) {
					?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= $pinj['staff'] ?>
								<input type="hidden" name="nama_mdis[]" value="<?= $pinj['staff'] ?>">
								<input type="hidden" name="agt[]" value="<?= $pinj['member'] ?>">
							</td>
							<td>

								<select name="karyawan[]" id="" required class='form-control'>
									<option value="">Pilih Staff</option>
									<?php $data_karyawan  = (karyawan($con, $id_cabang)['data']);
									for ($i = 0; $i < count($data_karyawan); $i++) {
										$nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
										if (strtolower($nama_karyawan) == strtolower($pinj['staff'])) {
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
						<td>
							<input type="submit" class='btn btn-success' value='KONFIRMASI' name='ganti' />
						</td>
					</tr>
				</tbody>
			</TABLE>
		</form>
	<?php
	} else {
	?>
		<table class='table'>
			<tr>
				<th>No</th>
				<th>Priode</th>
				<th>#</th>
			</tr>
			<?php
			$qstatistik = mysqli_query($con, "select * from statistik where id_cabang='$id_cabang' order by tgl_statistik desc");
			while ($statistik = mysqli_fetch_array($qstatistik)) {
			?>
				<tr>
					<td><?= $no++ ?></td>
					<td>
						<a href="<?= $url . $menu ?>spl&detail&id=<?= $statistik['id_statistik'] ?>&tgl=<?= $statistik['tgl_statistik'] ?>"> <?= format_hari_tanggal($statistik['tgl_statistik']) ?></a>
					</td>
					<td>
						<?php 
						$hitung_spl = mysqli_query($con,"select count(*) as hitung from spl where id_statistik='$statistik[id_statistik]'"); 
						$hitung_spl = mysqli_fetch_array($hitung_spl);

						if($hitung_spl['hitung']<1){
							?>
							<a href="<?= $url . $menu ?>spl&tambah&id=<?= $statistik['id_statistik'] ?>&tgl=<?= $statistik['tgl_statistik'] ?>" class="btn btn-danger"> <i class="fa fa-plus"></i></a>

							<?php
						}
						else{
							?>
							<a href="<?= $url . $menu ?>spl&ganti&tgl=<?= $statistik['tgl_statistik'] ?>" class="btn btn-success">Syncron</a>

							<?php
						} 
						?>
						
						<a href="<?= $url ?>export/spl.php?id=<?= $statistik['id_statistik'] ?>" class="btn btn-info"> <i class="fa fa-print"></i></a>
						<a href="<?= $url . $menu ?>spl&hapus&tgl=<?= $statistik['tgl_statistik'] ?>&id=<?= $statistik['id_statistik'] ?>" class="btn btn-danger" onclick="return window.confirm('Yakin menghapus SPL ini?')"> <i class="fa fa-times"></i> </a>
					</td>
				</tr>
			<?php

			}
			?>
		</table>
		<?php

		?>


	<?php

	}
	?>

</div>
<div class="modal fade" id="modalku">
	<div class="modal-dialog ">
		<div class="modal-content">

			<!-- Ini adalah Bagian Header Modal -->
			<div class="modal-header">
				<h2 class="modal-title">TAMBAH STATISTIK</h2>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Ini adalah Bagian Body Modal -->
			<div class="modal-body">
				<form action="" method="post">
					<div class="form-group">
						<label class="col-md-4 control-label" for="tanggal">TANGGAL</label>
						<div class="col-md-5">
							<input id="tanggal" name="tanggal" type="date" placeholder="tanggal" class="form-control input-md">

						</div>
					</div>

					<!-- Button -->
					<div class="form-group">
						<label class="col-md-4 control-label" for="tambah"></label>
						<div class="col-md-4">
							<button id="tambah" name="tambah_statistik" class="btn btn-primary">Tambah</button>
						</div>
					</div>

				</form>

				<br><br>
				<br><br>

			</div>

			<!-- Ini adalah Bagian Footer Modal -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">close</button>
			</div>

		</div>
	</div>
</div>

<?php


if(isset($_POST['tambah_statistik'])){
	mysqli_query($con,"INSERT INTO `statistik` (`tgl_statistik`, `id_cabang`) VALUES ('$_POST[tanggal]', '$id_cabang'); ");
	$last = mysqli_insert_id($con);
	pindah($url.$menu."spl&tambah&id=$last&tgl=$_POST[tanggal]");
}
?>
<script>
	var url = "<?= $url ?>";
	var cabang = "<?= $id_cabang ?>";
</script>