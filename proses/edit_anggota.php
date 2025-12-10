<form method='get' action='<?php echo $url . $menu ?>anggota'>
	<input type=hidden name='menu' value='anggota' />
	<input type=hidden name='edit_anggota' value='edit_anggota' />
	<input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
	<input type=submit name='cari' value='CARI' />
</form>
<table class='table'>
	<tr>
		<th>NO</th>
		<th>NAMA</th>
		<th>TGL</th>
		<th>AM</th>
		<th>AK</th>
		<th>#</th>
	</tr>
	<?php
	if (isset($_GET['tgl'])) {
		$tgl = $_GET['tgl'];
		if (isset($_GET['id'])) {
			$id = aman($con, $_GET['id']);
			mysqli_query($con, "DELETE from anggota where id_anggota='$id'");
			pindah($url . $menu . "anggota&edit_anggota&tgl=" . $tgl);
		}
	} else $tgl = date("Y-m-d");
	$que = mysqli_query($con, "select * from anggota a join karyawan k on k.id_karyawan=a.id_karyawan where a.id_cabang='$id_cabang' and tgl_anggota='$tgl'");
	while ($r = mysqli_fetch_assoc($que)) {
	?>
		<tr>
			<td><?= $no++ ?></td>
			<td><?= $r['nama_karyawan'] ?></td>
			<td><?= $r['tgl_anggota'] ?></td>
			<td><?= $r['anggota_masuk'] ?></td>
			<td><?= $r['anggota_keluar'] ?></td>
			<td>
				<a href="<?= $url . $menu ?>anggota&edit_anggota&tgl=<?= $tgl ?>&id=<?= $r['id_anggota'] ?>" onclick="return confirm('yakin ingin menghappus ini?')" class='btn btn-danger'>Hapus</a>
			</td>

		</tr>
	<?php
	}
	?>
</table>