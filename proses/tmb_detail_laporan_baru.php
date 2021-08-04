<h3 class='page-header'>Detail Center</h3>
<?php
$id_laporan = $_GET['id_laporan'];
$cek_laporan = mysqli_query($con, "select * from laporan where id_laporan='$id_laporan' ");

$cek_laporan = mysqli_fetch_array($cek_laporan);
$id_karyawan = $cek_laporan['id_karyawan'];
$status_laporan = $cek_laporan['status_laporan'];
if ($status_laporan == 'pending')
	$tampil_status = "<button class='btn btn-primary' disabled>Draft</button>";
else
	$tampil_status = "<button class='btn btn-success' disabled>Disimpan</button>";


$detail = detail_karyawan($con, $cek_laporan['id_karyawan']);
$hari = format_hari_tanggal($cek_laporan['tgl_laporan']);
$hari = explode(',', $hari);
$hari = strtolower($hari[0]);
?>
<form method="post" id="formlaporan">
	<table class='table'>
		<tr>
			<th>NAMA STAFF</th>
			<td><?php echo ($detail['nama_karyawan']) ?></td>
		</tr>
		<tr>
			<th>Tanggal</th>
			<td><?php echo format_hari_tanggal($cek_laporan['tgl_laporan']) ?></td>
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

				<br /><small>**Untuk mengedit silahkan input no center tersebut dan ganti dengan data baru</small>
				<br /><small>***Harap ini 0 jika tidak ada pemasukan</small>
			</td>
			<td></td>
		</tr>
	</table>


	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th colspan="9" class='text-center'>
					Center yang telah diinput

				</th>
			</tr>
			<tr>
				<th>No</th>
				<th>No. CTR</th>
				<th>Status</th>
				<th>Doa</th>
				<th>DTD</th>
				<th>Anggota</th>
				<th>Member</th>
				<th>Bayar</th>
				<th>Tidak Bayar</th>

			</tr>
			<?php
			$det = mysqli_query($con, "select * from detail_laporan where id_laporan='$id_laporan' ");
			if (!mysqli_num_rows($det)) {
			?>
				<tr>
					<td colspan=9 style="text-align:center"><i>Tidak ada data disimpan</i> </td>
				</tr>
				<?php
			} else {
				$no = 1;
				$center_ = array();
				while ($det1 = mysqli_fetch_array($det)) {
				?>
					<tr>
						<td><?php echo $no++ ?></td>
						<td><?php echo $ctr =  $det1['no_center'] ?></td>
						<td><?php echo $det1['status'] ?></td>
						<td><?php echo ($det1['doa'] == "t" ? "T" : "Y") ?></td>
						<td><?php echo strtoupper($det1['doortodoor']) ?></td>
						<td><?php echo $det1['member'] ?></td>
						<td><?php echo $det1['total_agt'] ?></td>
						<td><?php echo $det1['total_bayar'] ?></td>
						<td>
							<?php echo $det1['total_tidak_bayar'] ?>
							&nbsp;&nbsp;&nbsp;
							(<?php echo round((($det1['total_bayar'] / $det1['total_agt']) * 100), 2) ?>%)

							<?php if ($status_laporan == 'pending') : ?>
								<a href="#" type="button" id="tombol_edit" data-toggle="modal" data-target="#edit_center" data-id="<?= $det1['id_detail_laporan'] ?>" data-center="<?= $det1['no_center'] ?>" data-status="<?= $det1['status'] ?>" data-agt="<?= $det1['total_agt'] ?>" data-bayar="<?= $det1['total_bayar'] ?>" data-tdk="<?= $det1['total_tidak_bayar'] ?>">
									<i class="fa fa-edit"></i>
								</a> |
							<?php endif ?>

							<?php
							if ($jabatan != "SL") {
							?>
								<a href='<?php echo "$url$menu" . "tmb_laporan&id_laporan=$id_laporan&&id_detail_laporan=$det1[id_detail_laporan]&hapusdetail&center_no={$det1['no_center']}&idk={$detail['id_karyawan']}" ?>' onclick="return window.confirm('Anda yakin menghapus center  <?= $det1['no_center'] ?>')"><i class='fa fa-times'></i></a>
							<?php
							}

							?>



						</td>

					</tr>
			<?php
					$center_[$ctr] = 'ada';
				}
			}
			$i = 0;

			?>



		</table>
	</div>
	<div class=' table-responsive'>
		<table class='table table-bordered'>
			<tr>
				<th>No</th>
				<th>No. CTR</th>
				<th>DTD</th>
				<th>JAM</th>
				<th>Doa</th>
				<th>Anggota</th>
				<th>CLIENT</th>
				<th>Bayar</th>
				<th>Tidak Bayar</th>

			</tr>
			<?php
			$cek_query = mysqli_query($con, "select * from center where id_cabang='$id_cabang' and id_karyawan='$id_karyawan' and hari='$hari'");
			$y = 1;
			$hitung_center = mysqli_num_rows($cek_query);
			while ($cek_detail_center = mysqli_fetch_array($cek_query)) {
				if ($center_[$cek_detail_center['no_center']] != NULL) {
				} else {


					$doa_ = $cek_detail_center['doa_center'];
					if ($doa_ == 'y')
						$doa_ = 'selected';
					else
						$doa_t = 'selected';
					$warna = $cek_detail_center['status_center'];
					if ($warna == 'hijau')
						$border = '#4fff81';
					else if ($warna == 'kuning')
						$border = '#e2ff4f';
					else if ($warna == 'merah')
						$border = '#ff584f';
					else if ($warna == 'hitam')
						$border = '#727d73';


					$dtd_ = $cek_detail_center['doortodoor'];
					// if ($dtd_ == 't') $tidak = "selected";
					// else $tidak = "";
					// if ($dtd_ == 'y') $iya = "selected";
					// else $iya = "";
					// if ($dtd_ == 'r') $ragu = "selected";
					// else $ragu = "";

			?>
					<tr style="background:<?= $border ?>">
						<td class='text-center'>
							<a href='' class='btn btn-danger' id='hapuscenter' data-idhapus="<?= $y ?>">
								<i class="fa fa-times"></i>
							</a>

						</td>
						<td><input type=text class='form-control' name='no_center[]' value='<?= $cek_detail_center['no_center'] ?>' id='hapuscenter<?= $y ?>' data-dd="<?= $y ?>" style="width:60px" /></td>

						<td>
							<!-- sss -->
							<select name='dtd[]' class='form-select ' id='dtd-<?php echo $y ?>' required id="inputGroupSelect01">
								<option value="" >Pilih</option>
								<option value='t' <?= $tidak ?>>TIDAK</option>
								<option value='y' <?= $iya ?>>DTD</option>
								<!-- <option value='r' <?= $ragu ?> >1/2 DTD</option> -->

							</select>
						</td>

						<td><input type=text class='form-control' name='jam[]' id='jam-<?php echo $y ?>' placeholder="12:00" value='<?= $cek_detail_center['jam_center'] ?>' style="width:70px" /></td>


						<td>
							<div class="input-group input-group-lg" >
								<select name='doa[]' class='form-select ' id="inputGroupSelect01">
									<option value='y' <?= $doa_ ?>>doa</option>
									<option value='t' <?= $doa_t ?>>Tdk </option>
								</select>
							</div>
						</td>
						<td><input type=number name='member[]' id='member-<?php echo $y ?>' min="1" value='<?= $cek_detail_center['member_center'] ?>' class='form-control' style="width:60px" /></td>
						<td><input type=number name='total_agt[]' value='<?= $cek_detail_center['anggota_center'] ?>' id='agt-<?php echo $y ?>' onkeyup="ganti_bayar('<?= $y ?>')" class='form-control' style="width:60px" /></td>
						<td><input type=number name='bayar[]' value='<?= $cek_detail_center['center_bayar'] ?>' id='bayar-<?php echo $y ?>' onkeyup="ganti_bayar('<?= $y ?>')" class='form-control' style="width:60px"></td>
						<td><input type=number name='tidak_bayar[]' id='tdk-<?php echo $y ?>' value='<?= $cek_detail_center['anggota_center'] - $cek_detail_center['center_bayar'] ?>' class='form-control' style="width:60px"></td>
					</tr>
			<?php
					$y++;
				}
			}


			?>

			<?php
			$no = 1;
			if ($hitung_center > 1)
				$loop = 24;
			else $loop = 27;
			for ($x = 20; $x <= $loop; $x++) {
			?>


				<tr>
					<td><?php echo $no++ ?></td>
					<td><input type=text class='form-control' name='no_center[]' style="width:60px" /></td>

					<td>



						<select name='dtd[]' class='form-select ' id="inputGroupSelect01">
							<option >pilih</option>
							<option value='t' <?= $merah ?>>TIDAK</option>
							<option value='y' <?= $hijau ?>>DTD</option>
							<!-- <option value='r' <?= $kuning ?> >1/2 DTD</option> -->

						</select>

					</td>

					<td><input type=text class='form-control' name='jam[]' id='jam-<?php echo $y ?>' placeholder="12:00" value='' style="width:70px" /></td>


					<td>
						<div class="input-group input-group-lg">
							<select name='doa[]' class='form-select ' id="inputGroupSelect01">
								<option value='y'>doa</option>
								<option value='t'>Tdk </option>
							</select>
						</div>
					</td>
					<td><input type='number' name='member[]' min="1" class='form-control' style="width:60px" /></td>
					<td><input type='number' name='total_agt[]' id='agt-<?php echo $x ?>' onkeyup="ganti_bayar('<?= $x ?>')" class='form-control' style="width:60px" /></td>
					<td><input type='number' name='bayar[]' id='bayar-<?php echo $x ?>' onkeyup="ganti_bayar('<?= $x ?>')" class='form-control' style="width:60px"></td>
					<td><input type='number' name='tidak_bayar[]' id='tdk-<?php echo $x ?>' onkeyup="" class='form-control' style="width:60px"></td>
				</tr>
			<?php
			}
			?>

		</table>
		<table style="float:right">
			<tr>
				<td colspan=0>
					<small>* SIMPAN artinya menyimpan sebagai draft detail center sebelum di KONFIRMASI</small> <br />
					<small>** Setelah dikonfirmasi tidak dapat diedit/dihapus</small>

				</td>
				<td><a class='btn btn-danger' href="<?php echo "$url$menu" . "tmb_laporan&id_laporan=$id_laporan&dellaporan" ?>" onclick="return window.confirm('Yakin akan hapus laporan ini???')">Hapus</a></td>
				<td><input type="submit" name='simpan_detail' class='btn btn-primary' value="SIMPAN" /></td>
				<td>

					<input type="submit" name='konfirmasi_laporan' class='btn btn-success'  value="KONFIRMASI" onclick="return window.confirm('Apakah sudah benar?')" />

				</td>
			</tr>
		</table>
	</div>
</form>
<!-- //JAVA script code -->
<script>
	
</script>

<?php


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
	$id_detail = $_GET['id_detail_laporan'];
	$center = $_GET['center_no'];
	$idk = $_GET['idk'];
	$urlbaru = $_GET['url'];
	$tglbaru = $_GET['tgl'];
	$q1 = mysqli_query($con, "UPDATE detail_laporan SET status_detail_laporan='sukses' WHERE id_laporan = '$id_laporan' ");
	$q = mysqli_query($con, "UPDATE laporan SET status_laporan='sukses' WHERE id_laporan = '$id_laporan' ");
	if ($q) {
		pesan("Berhasil Di UN-approve");
		pindah("$url$menu" . "edit_laporan&tgl=" . $tglbaru);
	}
}


if (isset($_POST['simpan_detail'])) {
	$no_center = $_POST['no_center'];


	$doa = $_POST['doa'];
	$dtd = $_POST['dtd'];
	$member = $_POST['member'];
	$total_agt = $_POST['total_agt'];
	$total_bayar = $_POST['bayar'];
	$keterangan = $_POST['keterangan'];
	$jam = $_POST['jam'];
	$total_tidak_bayar = $_POST['tidak_bayar'];
	$update1 = mysqli_query($con, "UPDATE laporan SET keterangan_laporan='$keterangan' WHERE id_laporan = '$id_laporan'  ");
	for ($x = 0; $x <= count($no_center); $x++) {
		if ($no_center[$x] != "") {
			$persen = round(($total_bayar[$x] / $total_agt[$x]) * 100);
			if ($persen >= 90) $status = "hijau";
			else if ($persen > 30 && $persen < 90) $status = "kuning";
			else if ($persen >= 1 && $persen < 30) $status = "merah";
			else $status = 'hitam';
			$cek_center = mysqli_query($con, "select * from detail_laporan where id_laporan='$id_laporan' and no_center='" . sprintf("%03d", $no_center[$x]) . "' ");
			if (mysqli_num_rows($cek_center)) {
				$cek_center1 = mysqli_fetch_array($cek_center);
				mysqli_query($con, "UPDATE `detail_laporan` SET `status` = '$status', `doa` = '$doa[$x]', `total_agt` = '$total_agt[$x]', `member` = '$member[$x]', `total_bayar` = '$total_bayar[$x]', `total_tidak_bayar` = '$total_tidak_bayar[$x]' WHERE `detail_laporan`.`id_detail_laporan` = '$cek_center1[id_detail_laporan]';");
			} else {
				$q = mysqli_query($con, "INSERT INTO detail_laporan ( id_laporan, no_center, status, doa,member, total_agt, total_bayar, total_tidak_bayar, status_detail_laporan,doortodoor) VALUES ( '$id_laporan', '" . sprintf("%03d", $no_center[$x]) . "', '" . $status . "', '$doa[$x]', '" . $member[$x] . "','" . $total_agt[$x] . "', '" . $total_bayar[$x] . "', '" . $total_tidak_bayar[$x] . "', 'draft','" . $dtd[$x] . "')");
			}
			center($con, $no_center[$x], $doa[$x], $status, $member[$x], $total_agt[$x], $total_bayar[$x], $id_cabang, $cek_laporan['id_karyawan'], $hari, $id_laporan, $jam[$x], $dtd[$x]);
		}
	}
	echo alert("Berhasil Disimpan");
	pindah("$url$menu" . "tmb_laporan&id_laporan=" . $id_laporan);
}

if (isset($_POST['konfirmasi_laporan'])) {
	$no_center = $_POST['no_center'];
	$keterangan = $_POST['keterangan'];
	$total_agt = $_POST['total_agt'];
	$member = $_POST['member'];
	$doa = $_POST['doa'];
	$dtd = $_POST['dtd'];
	$total_bayar = $_POST['bayar'];
	$total_tidak_bayar = $_POST['tidak_bayar'];
	$jam = $_POST['jam'];
	var_dump($jam);
	$update1 = mysqli_query($con, "UPDATE laporan SET status_laporan = 'sukses', keterangan_laporan='$keterangan' WHERE id_laporan = '$id_laporan'  ");
	$update = mysqli_query($con, "UPDATE detail_laporan SET status_detail_laporan = 'sukses' WHERE id_laporan ='$id_laporan'; ");
	for ($x = 0; $x <= count($no_center); $x++) {
		if ($no_center[$x] != "") {

			$persen = round(($total_bayar[$x] / $total_agt[$x]) * 100);
			if ($persen >= 90) $status = "hijau";
			else if ($persen > 30 && $persen < 90) $status = "kuning";
			else if ($persen >= 1 && $persen < 30) $status = "merah";
			else $status = 'hitam';

			$cek_center = mysqli_query($con, "select * from detail_laporan where id_laporan='$id_laporan' and no_center='" . sprintf("%03d", $no_center[$x]) . "' ");
			if (mysqli_num_rows($cek_center)) {
				$cek_center1 = mysqli_fetch_array($cek_center);
				mysqli_query($con, "UPDATE `detail_laporan` SET `status` = '$status', `doa` = '$doa[$x]',  `member` = '$member[$x]',`total_agt` = '$total_agt[$x]', `total_bayar` = '$total_bayar[$x]', `total_tidak_bayar` = '$total_tidak_bayar[$x]' WHERE `detail_laporan`.`id_detail_laporan` = '$cek_center1[id_detail_laporan]';");
			} else {
				$q = mysqli_query($con, "INSERT INTO detail_laporan ( id_laporan, no_center, status, doa,member, total_agt, total_bayar, total_tidak_bayar, status_detail_laporan,doortodoor) VALUES ( '$id_laporan', '" . sprintf("%03d", $no_center[$x]) . "', '" . $status . "', '$doa[$x]', '" . $member[$x] . "','" . $total_agt[$x] . "', '" . $total_bayar[$x] . "', '" . $total_tidak_bayar[$x] . "', 'sukses','" . $dtd[$x] . "')");
			}
			center($con, $no_center[$x], $doa[$x], $status, $member[$x], $total_agt[$x], $total_bayar[$x], $id_cabang, $cek_laporan['id_karyawan'], $hari, $id_laporan, $jam[$x], $dtd[$x]);
		}
	}
	echo alert("LAPORAN BERHASIL KONFIRMASI, TERIMA KASIH :)");
	pindah("$url");
}


// edit_detail
if (isset($_POST['edit_detail'])) {
	$iddet = $_POST['id'];
	$center = $_POST['center'];
	$status = $_POST['status'];
	$anggota = $_POST['anggota'];
	$bayar = $_POST['bayar'];
	$tdk = $_POST['tdk'];

	$persen = round(($bayar / $anggota) * 100);
	if ($persen >= 90) $status = "hijau";
	else if ($persen > 30 && $persen < 90) $status = "kuning";
	else if ($persen >= 1 && $persen < 30) $status = "merah";
	else $status = 'hitam';

	$edit = mysqli_query($con, "UPDATE `detail_laporan` SET `no_center` = '$center', `status` = '$status', `total_agt` = '$anggota', `total_bayar` = '$bayar', `total_tidak_bayar` = '$tdk', `status_detail_laporan` = 'draft' WHERE `detail_laporan`.`id_detail_laporan` = $iddet; ");
	$edit1 = mysqli_query($con, "UPDATE `center` SET  `anggota_center` = '$anggota', `center_bayar` = '$bayar'  WHERE no_center = '$center' and id_cabang='$id_cabang' and id_karyawan='$id_karyawan'");
	// UPDATE `komida1`.`center` SET `anggota_center` = '14', `center_bayar` = '13' WHERE `center`.`id_center` = 28; 
	// pindah("$url$menu"."tmb_laporan&id_laporan=".$id_laporan);

}

?>


<div class="modal fade" id="edit_center">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Ini adalah Bagian Header Modal -->
			<div class="modal-header">
				<h4 class="modal-title">EDIT DETAIL</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Ini adalah Bagian Body Modal -->
			<div class="modal-body">
				<form method="post">

					<table class="table">
						<tr>
							<td>ID center</td>
							<td><input type=hidden class='form-control' name='id' id='id'></td>
						</tr>
						<tr>
							<td>CENTER</td>
							<td><input type=text readonly="" class='form-control' name='center' id='center'></td>
						</tr>
						<tr>
							<td>STATUS</td>
							<td>
								<input type=text readonly="" class='form-control' name='status' id='status'>
								<!--<select name='status[]' class='form-select ' id="inputGroupSelect01"  >
							<option value='hijau'  >Hijau</option>
							<option value='kuning'  >kuning</option>
							<option value='merah'  >merah</option>
							<option value='hitam'  >hitam</option>
						</select> -->
							</td>
						</tr>
						<tr>
							<td>TOTAL ANGGOTA</td>
							<td><input type=text class='form-control' name='anggota' id='anggota'></td>
						</tr>
						<tr>
							<td>BAYAR</td>
							<td><input type=text class='form-control' name='bayar' id='bayar'></td>
						</tr>
						<tr>
							<td>Tidak Bayar</td>
							<td><input type=text class='form-control' name='tdk' id='tdk'></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type=submit class='btn btn-danger' value='Edit' name='edit_detail' id='edit_detail'></td>
						</tr>
				</form>
				</table>
				<br />
				<small>Hanya dapat edit Total Anggota,Total Bayar, tidak bayar</small>


			</div>

			<!-- Ini adalah Bagian Footer Modal -->
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>