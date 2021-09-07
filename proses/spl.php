<style>
	.tengah {
		text-align: center;
		font-weight: bold;
	}

	.kotak {
		width: 50px;
		text-align: center;
	}

	table {
		width: 60%;
	}
</style>
<div class='content table-responsive'>
	<h2 class='page-header'>STATISTIK PETUGAS LAPANG </h2>
	<i></i> <br />
	<a href="<?= $url . $menu ?>spl" class='btn btn-success'> <i class="fa fa-eye"></i> Lihat</a>
	<a href="#" class='btn btn-danger' data-toggle="modal" data-target="#modalku"> <i class="fa fa-plus"></i> Tambah</a>

	<?php
	if (isset($_SESSION['nama_file'])) {
	?>
		<a href="<?= $url ?>export/excel/<?= $_SESSION['nama_file'] ?>.xlsx" class='btn btn-info'> <i class="fa fa-download"></i> UNDUH</a>
	<?php
	}
	?>
	<hr />

	<?php
	if (isset($_GET['tambah'])) {
		$id = aman($con,$_GET['id']);
	?>
		<form action="" method="post">
			<textarea name="query" class='form-control' id="" cols="50" rows="20"></textarea>
			<input type="submit" value="Execute" name='ekse' />
			<?php
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
		</form>
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
		<div class="col-2">

		</div>
		<div class="col-8">
			<table border="1">
				<tr>
					<th colspan="9" style="text-align:center;font-size:20px"><?= format_hari_tanggal($tgl) ?></th>
				</tr>
				<tr>
					<th>No.</th>
					<th>Nama Staff</th>
					<th> Center</th>
					<th>Member</th>
					<th>CLIENT</th>
					<th>disburse</th>
					<th>outstanding</th>
					<th>outstanding<br>masalah </th>
					<th>par</th>
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
				foreach ($array as $val) {

				?>
					<?php

					$disburse =  angka_mentah($val['disburse']);
					$outstanding = angka_mentah($val['outstanding']);
					$total_disburse = $total_disburse + $disburse;
					$total_outstanding = $total_outstanding + $outstanding;
					?>
					<?php

					$disburse =  angka_mentah($val['disburse']);
					$masalah = angka_mentah($val['masalah']);
					$total_disburse = $total_disburse + $disburse;
					$total_masalah = $total_masalah + $masalah;
					$new_member = $val['new_member'];
					$total_new_member = $total_new_member + $new_member;
					$jumlah_center = $jumlah_center + $val['jumlah_center'];
					$jumlah_member = $jumlah_member + $val['member'];
					$jumlah_client = $jumlah_client + $val['client'];
					?>
					<tr>
						<td class='nama'><?= $no++ ?></td>
						<td class='nama'><?= str_replace("Sub. Tot ", "", $val['nama_karyawan']) ?></td>
						<td class='kotak'><?= $val['jumlah_center'] ?></td>
						<td class='kotak'><?= $val['member'] ?></td>
						<td class='kotak'><?= $val['client'] ?></td>
						<td class='kotak'><?= angka($disburse); ?></td>
						<td class='kotak'><?= angka($outstanding) ?> </td>
						<td class='kotak'><?= angka($masalah) ?> </td>
						<td class='kotak'><?= round($val['par'],2) ?></td>
					</tr>

				<?php

				}
				?>
				<tr class='tengah'>
					<td></td>
					<td></td>
					<td><?= $jumlah_center ?></td>
					<td><?= $jumlah_member ?></td>
					<td><?= $jumlah_client ?></td>
					<td><?= angka($total_disburse) ?></td>
					<td><?= angka($total_outstanding) ?></td>
					<td><?= angka($total_masalah) ?></td>
					<td class='kotak'><?= round(($total_masalah / $total_outstanding) * 100, 2) ?></td>
				</tr>


			</table>

		</div>
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
						$mdis = $_POST['nama_mdis'];

						for ($i = 0; $i < count($mdis); $i++) {
							if (!empty($karyawan[$i])) {
								$text = " UPDATE `spl` SET `staff` = null , id_karyawan='$karyawan[$i]' WHERE `staff` = '$mdis[$i]'; ";
								$q = mysqli_query($con, "$text");
							}
						}
						pindah($url.$menu."spl");
					}


					$q = mysqli_query($con, "select staff from spl where id_karyawan is  null group by staff order by staff asc ");
					while ($pinj = mysqli_fetch_array($q)) {
					?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= $pinj['staff'] ?>
								<input type="hidden" name="nama_mdis[]" value="<?= $pinj['staff'] ?>">
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
	pindah($url.$menu."spl&tambah&id=$last&tanggal=$_POST[tanggal]");
}
?>
<script>
	var url = "<?= $url ?>";
	var cabang = "<?= $id_cabang ?>";
</script>