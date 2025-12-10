<?php
$tgl = date("Y-m-d");
if (!isset($_GET['id_laporan'])) {
	$cek_laporan = mysqli_query($con, "select * from laporan where id_karyawan='$id_karyawan' and status_laporan='pending' and tgl_laporan=curdate()");

	if (mysqli_num_rows($cek_laporan)) {
		$hitung = mysqli_fetch_assoc($cek_laporan);
?>
		<h4>Anda Telah membuat laporan hari ini, silahkan klik tombol konfirmasi <br />silahkan isi detail center

			<a href='<?php echo "$url$menu" . "tmb_laporan&id_laporan=" . $hitung['id_laporan'] ?>'>disini </a>
		</h4>
		<small>**hanya dapat membuat laporan 1 per hari</small>
	<?php
	} else {
		echo "<h3 class='page-header'>Tambah Laporan</h3>";
	}
	?>

	<form method="post">
		<table class='table table-bordered'>
			<tr>
				<td>Tanggal</td>
				<td><input type="date" name="tanggal" class='form-control' value="<?php echo date("Y-m-d") ?>" placeholder="YYYY-MM-DD(<?php echo date("Y-m-d") ?>)"></td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td><textarea required class='form-control' minlength='6' name='keterangan'></textarea>
					</br>
					** Keterangan : Minimal 6 huruf
				</td>
			</tr>
			<?php
			if ($jabatan != 'SL') {
			?>
				<tr>
					<td>Staff Lapang</td>
					<td>

						<select name='id_karyawan' class='form-control form-select' required>
							<option value="">Silahkan Pilih Staff</option>
							<?php
							$qk = mysqli_query($con, "select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
							while ($cek_ka = mysqli_fetch_assoc($qk)) {
							?>
								<option value='<?= $cek_ka['id_karyawan'] ?>'><?= $cek_ka['nama_karyawan'] ?></option>
							<?php

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
				<td>
					<input type="submit" name="tmb_lap" class='btn btn-primary' value="SIMPAN">
					<a href="<?= $url . $menu ?>laporan-teman" class="btn btn-danger">laporan Teman</a>
				</td>
			</tr>
		</table>

	</form>
<?php


}
if (isset($_POST['tmb_lap'])) {
	$tgl_lap = $_POST['tanggal'];
	$ket_lap = $_POST['keterangan'];
	if ($jabatan != 'SL')
		$id_karyawan = $_POST['id_karyawan'];
	else $id_karyawan = $id_karyawan;

	$cek_laporan = mysqli_query($con, "select * from laporan where id_karyawan='$id_karyawan'  and tgl_laporan='$tgl_lap'");

	if (!mysqli_num_rows($cek_laporan)) {
		$q = mysqli_query($con, "INSERT INTO `laporan` (`id_laporan`, `id_karyawan`, `tgl_laporan`, `keterangan_laporan`, `status_laporan`) VALUES (NULL, '$id_karyawan', '$tgl_lap', '$ket_lap', 'pending');");

		if ($q) {
			$ambil = (mysqli_query($con, "SELECT * FROM `laporan` WHERE id_karyawan='$id_karyawan' and tgl_laporan='$tgl_lap' and keterangan_laporan='$ket_lap' "));
			$ambil = mysqli_fetch_assoc($ambil);
			$id_laporan = $ambil['id_laporan'];
			//echo alert("Berhasil ditembahkan silahkan isi detail Center");


			pindah("$url$menu" . "tmb_laporan&id_laporan=$id_laporan");
		} else {
			echo "Gagal Disimpan";
			echo mysqli_error($con);
		}
	} else {
		$ambil_laporan = mysqli_fetch_assoc($cek_laporan);
		if ($ambil_laporan['status_laporan'] == 'sukses') {
			alert("Telah membuat laporan $tgl_lap , Laporan sudah dikonfimasi tidak dapat mengedit/hapus, tidak dapat membuat laporan lebih dari 1");
			pindah("$url$menu" . "tmb_laporan");
		} else
			pindah("$url$menu" . "tmb_laporan&id_laporan=$ambil_laporan[id_laporan]");
		alert(" Anda telah membuat laporan di tanggal $tgl_lap, silahkan isi detail center");
		pindah("$url$menu" . "tmb_laporan&id_laporan=" . $ambil_laporan['id_laporan']);
	}
}

if (isset($_GET['id_laporan'])) {
	include "proses/tmb_detail_laporan_baru.php";
}
?>