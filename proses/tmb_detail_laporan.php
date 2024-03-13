<h3 class='page-header'>Detail Center</h3>
<?php
$id_laporan = $_GET['id_laporan'];
$cek_laporan = mysqli_query($con, "select * from laporan where id_laporan='$id_laporan' ");

$cek_laporan = mysqli_fetch_assoc($cek_laporan);
$status_laporan = $cek_laporan['status_laporan'];
if ($status_laporan == 'pending')
	$tampil_status = "<button class='btn btn-primary' disabled>Draft</button>";
else
	$tampil_status = "<button class='btn btn-success' disabled>Disimpan</button>";


$detail = detail_karyawan($con, $cek_laporan['id_karyawan']);

?>
<form method=post>
	<table class='table'>
		<tr>
			<th>NAMA STAFF</th>
			<td><?php echo ($detail['nama_karyawan']) ?></td>
		</tr>
		<tr>
			<th>Tanggal</th>
			<td><?php echo $hari = format_hari_tanggal($cek_laporan['tgl_laporan']) ?></td>
		</tr>
		<tr>
			<th>Keterangan</th>
			<td><?php echo ($cek_laporan['keterangan_laporan']) ?></td>
		</tr>
		<tr>
			<th>Tambah Keterangan</th>
			<td>
				<textarea name='keterangan' class='form-control'><?php echo ($cek_laporan['keterangan_laporan']) ?></textarea>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<?php echo $tampil_status ?>

				<br /><small>*Menghapus laporan ini berarti menghapus data dibawah</small>
				<br /><small>**Untuk mengedit silahkan input no center tersebut dan ganti dengan data baru</small>
				<br /><small>***Harap ini 0 jika tidak ada pemasukan</small>
			</td>
			<td></td>
		</tr>
	</table>


	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th>No</th>
				<th>No. CTR</th>
				<th>Status</th>
				<th>Doa</th>
				<th>Total Anggota</th>
				<th>Bayar</th>
				<th>Tidak Bayar</th>

			</tr>
			<?php
			$det = mysqli_query($con, "select * from detail_laporan where id_laporan='$id_laporan' ");
			if (!mysqli_num_rows($det)) {
			?>
				<tr>
					<td colspan=7 style="text-align:center"><i>Tidak ada data disimpan</i> </td>
				</tr>
				<?php
			} else {
				$no = 1;

				while ($det1 = mysqli_fetch_assoc($det)) {
				?>
					<tr>
						<td><?php echo $no++ ?></td>
						<td><?php echo $det1['no_center'] ?></td>
						<td><?php echo $det1['status'] ?></td>
						<td><?php echo ($det1['doa'] == "t" ? "T" : "Y") ?></td>
						<td><?php echo $det1['total_agt'] ?></td>
						<td><?php echo $det1['total_bayar'] ?></td>
						<td>
							<?php echo $det1['total_tidak_bayar'] ?>
							&nbsp;&nbsp;&nbsp;
							(<?php echo round((($det1['total_bayar'] / $det1['total_agt']) * 100), 2) ?>%)
							<a href='<?php echo "$url$menu" . "tmb_laporan&id_laporan=$id_laporan&&id_detail_laporan=$det1[id_detail_laporan]&hapusdetail&center_no={$det1['no_center']}&idk={$detail['id_karyawan']}" ?>' onclick="return window.confirm('apakah +yakin ingin menghapus center <?= $det1['no_center'] ?>')"><i class='fa fa-times'></i></a>

						</td>

					</tr>
			<?php
				}
			}
			$i = 0;
			?>

		</table>
	</div>
	<div class='table-responsive'>
		<table class='table table-bordered'>
			<tr>
				<th>No</th>
				<th>No. CTR</th>
				<th>Status</th>
				<th>Doa</th>
				<th>Total Anggota</th>
				<th>Bayar</th>
				<th>Tidak Bayar</th>

			</tr>
			<?php
			for ($x = 1; $x <= 7; $x++) {
			?>


				<tr>
					<td><?php echo $x ?></td>
					<td><input type=text class='form-control' name='no_center[]' style="width:70px" /></td>
					<td>

						<select name='status[]' class='form-select ' id="inputGroupSelect01">
							<option value='hijau'>Hijau</option>
							<option value='kuning'>kuning</option>
							<option value='merah'>merah</option>
							<option value='hitam'>hitam</option>
						</select>

					</td>
					<td>
						<div class="input-group input-group-lg">
							<select name='doa[]' class='form-select ' id="inputGroupSelect01">
								<option value='y'>doa</option>
								<option value='t'>Tdk Doa</option>
							</select>
						</div>
					</td>
					<td><input type=number name='total_agt[]' id='agt-<?php echo $x ?>' onkeyup="ganti_bayar('<?= $x ?>')" class='form-control' style="width:70px" /></td>
					<td><input type=number name='bayar[]' id='bayar-<?php echo $x ?>' onkeyup="ganti_bayar('<?= $x ?>')" class='form-control' style="width:70px"></td>
					<td><input type=number name='tidak_bayar[]' id='tdk-<?php echo $x ?>' onkeyup="" class='form-control' style="width:70px"></td>

				</tr>
			<?php
			}
			?>
			<tr>
				<td colspan=4>
					<small>* SIMPAN artinya menyimpan sebagai draft detail center sebelum di KONFIRMASI</small> <br />
					<small>** Setelah dikonfirmasi tidak dapat diedit/dihapus</small>

				</td>
				<td><a class='btn btn-danger' href="<?php echo "$url$menu" . "tmb_laporan&id_laporan=$id_laporan&dellaporan" ?>" onclick="return window.confirm('Yakin akan hapus laporan ini???')">Hapus</a></td>
				<td><input type="submit" name='simpan_detail' class='btn btn-primary' value="SIMPAN" /></td>
				<td>

					<input type="submit" name='konfirmasi_laporan' class='btn btn-success' style="" value="KONFIRMASI" />

				</td>
			</tr>
		</table>
	</div>
</form>
<?php
$hari = explode(',', $hari);
$hari = strtolower($hari[0]);

//PROSES  HAPUS
if (isset($_GET['dellaporan'])) {
	$q = mysqli_query($con, "DELETE FROM `detail_laporan` WHERE `id_laporan` = '$id_laporan'");
	$q = mysqli_query($con, "DELETE FROM `laporan` WHERE `id_laporan` = '$id_laporan'");
	if ($q) {
		alert("Berhasil Dihapus");
		if (isset($_GET['url']))
			pindah($url . $menu . "$_GET[url]");

		pindah("$url");
	}
}

//HAPUS detail laporan
if (isset($_GET['hapusdetail'])) {
	$id_detail = $_GET['id_detail_laporan'];
	$center = $_GET['center_no'];
	$idk = $_GET['idk'];
	$qq = mysqli_query($con, "DELETE FROM center WHERE no_center ='$center' and id_karyawan = $idk;");
	$q = mysqli_query($con, "DELETE FROM `detail_laporan` WHERE `id_detail_laporan` = '$id_detail'");
	if ($q) {
		pesan("Berhasil Dihapus");
		pindah("$url$menu" . "tmb_laporan&id_laporan=" . $id_laporan);
	}
}

//UN-approve laporan
if (isset($_GET['non_app'])) {
	$id_detail = $_GET['id_detail_laporan'];
	$center = $_GET['center_no'];
	$idk = $_GET['idk'];
	$urlbaru = $_GET['url'];
	$tglbaru = $_GET['tgl'];
	$q1 = mysqli_query($con, "UPDATE detail_laporan SET status_detail_laporan='draft' WHERE id_laporan = '$id_laporan' ");
	$q = mysqli_query($con, "UPDATE laporan SET status_laporan='pending' WHERE id_laporan = '$id_laporan' ");
	if ($q) {
		pesan("Berhasil Di UN-approve");
		pindah("$url$menu" . "edit_laporan&tgl=" . $tglbaru);
	}
}

//approve laporan
if (isset($_GET['approve'])) {
	$id_detail = aman($con, $_GET['id_detail_laporan']);
	$center = aman($con, $_GET['center_no']);
	$idk = aman($con, $_GET['idk']);
	$urlbaru = aman($con, $_GET['url']);
	$tglbaru = aman($con, $_GET['tgl']);
	$q1 = mysqli_query($con, "UPDATE detail_laporan SET status_detail_laporan='sukses' WHERE id_laporan = '$id_laporan' ");
	$q = mysqli_query($con, "UPDATE laporan SET status_laporan='sukses' WHERE id_laporan = '$id_laporan' ");
	if ($q) {
		pesan("Berhasil Di UN-approve");
		pindah("$url$menu" . "edit_laporan&tgl=" . $tglbaru);
	}
}


if (isset($_POST['simpan_detail'])) {
	$no_center = $_POST['no_center'];
	$status = $_POST['status'];
	$doa = $_POST['doa'];
	$total_agt = $_POST['total_agt'];
	$total_bayar = $_POST['bayar'];
	$keterangan = $_POST['keterangan'];
	$total_tidak_bayar = $_POST['tidak_bayar'];
	$update1 = mysqli_query($con, "UPDATE laporan SET keterangan_laporan='$keterangan' WHERE id_laporan = '$id_laporan'  ");
	for ($x = 0; $x <= count($no_center); $x++) {
		if ($no_center[$x] != "") {

			$cek_center = mysqli_query($con, "select * from detail_laporan where id_laporan='$id_laporan' and no_center='" . sprintf("%03d", $no_center[$x]) . "' ");
			if (mysqli_num_rows($cek_center)) {
				$cek_center1 = mysqli_fetch_assoc($cek_center);
				mysqli_query($con, "UPDATE `detail_laporan` SET `status` = '$status[$x]', `doa` = '$doa[$x]', `total_agt` = '$total_agt[$x]', `total_bayar` = '$total_bayar[$x]', `total_tidak_bayar` = '$total_tidak_bayar[$x]' WHERE `detail_laporan`.`id_detail_laporan` = '$cek_center1[id_detail_laporan]';");
			} else {
				$q = mysqli_query($con, "INSERT INTO detail_laporan ( id_laporan, no_center, status, doa, total_agt, total_bayar, total_tidak_bayar, status_detail_laporan) VALUES ( '$id_laporan', '" . sprintf("%03d", $no_center[$x]) . "', '" . $status[$x] . "', '$doa[$x]', '" . $total_agt[$x] . "', '" . $total_bayar[$x] . "', '" . $total_tidak_bayar[$x] . "', 'sukses')");
			}
			center($con, $no_center[$x], $doa[$x], $status[$x], $total_agt[$x], $total_bayar[$x], $id_cabang, $cek_laporan['id_karyawan'], $hari, $id_laporan);
			// center($con,$no_center[$x],$doa,$status[$x],$total_agt[$x],$total_bayar[$x],$id_cabang,$cek_laporan['id_karyawan'],$hari,$id_laporan);
			// enter($con,$no_center,$doa,$status,$anggota_center,$bayar,$id_cabang,$id_karyawan,$hari,$idlaporan)
		}
	}
	echo alert("Berhasil Disimpan");
	pindah("$url$menu" . "tmb_laporan&id_laporan=" . $id_laporan);
}

if (isset($_POST['konfirmasi_laporan'])) {
	$no_center = $_POST['no_center'];
	$status = $_POST['status'];
	$keterangan = $_POST['keterangan'];
	$total_agt = $_POST['total_agt'];
	$doa = $_POST['doa'];
	$total_bayar = $_POST['bayar'];
	$total_tidak_bayar = $_POST['tidak_bayar'];
	$update1 = mysqli_query($con, "UPDATE laporan SET status_laporan = 'sukses', keterangan_laporan='$keterangan' WHERE id_laporan = '$id_laporan'  ");
	$update = mysqli_query($con, "UPDATE detail_laporan SET status_detail_laporan = 'sukses' WHERE id_laporan ='$id_laporan'; ");
	for ($x = 0; $x <= count($no_center); $x++) {
		if ($no_center[$x] != "") {

			$cek_center = mysqli_query($con, "select * from detail_laporan where id_laporan='$id_laporan' and no_center='" . sprintf("%03d", $no_center[$x]) . "' ");
			if (mysqli_num_rows($cek_center)) {
				$cek_center1 = mysqli_fetch_assoc($cek_center);
				mysqli_query($con, "UPDATE `detail_laporan` SET `status` = '$status[$x]', `doa` = '$doa[$x]', `total_agt` = '$total_agt[$x]', `total_bayar` = '$total_bayar[$x]', `total_tidak_bayar` = '$total_tidak_bayar[$x]' WHERE `detail_laporan`.`id_detail_laporan` = '$cek_center1[id_detail_laporan]';");
			} else {
				$q = mysqli_query($con, "INSERT INTO detail_laporan ( id_laporan, no_center, status, doa, total_agt, total_bayar, total_tidak_bayar, status_detail_laporan) VALUES ( '$id_laporan', '" . sprintf("%03d", $no_center[$x]) . "', '" . $status[$x] . "', '$doa[$x]', '" . $total_agt[$x] . "', '" . $total_bayar[$x] . "', '" . $total_tidak_bayar[$x] . "', 'sukses')");
			}
			center($con, $no_center[$x], $doa[$x], $status[$x], $total_agt[$x], $total_bayar[$x], $id_cabang, $cek_laporan['id_karyawan'], $hari, $id_laporan);
			// center($con,$no_center[$x],$doa,$status[$x],$total_agt[$x],$total_bayar[$x],$id_cabang,$cek_laporan['id_karyawan'],$hari,$id_laporan);
			// enter($con,$no_center,$doa,$status,$anggota_center,$bayar,$id_cabang,$id_karyawan,$hari,$idlaporan)
		}
	}
	echo alert("LAPORAN BERHASIL KONFIRMASI, TERIMA KASIH :)");
	pindah("$url");
}
?>