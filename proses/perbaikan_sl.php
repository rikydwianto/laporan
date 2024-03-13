<div class='content table-responsive'>
	<h2 class='page-header'>PERBAIKAN DATA </h2>
	<i></i>
	<a href="<?= $url . $menu ?>perbaikan_sl" class="btn btn-info"> Lihat Data</a>
	<hr />
	<!-- Button to Open the Modal -->


	<?php
	if (isset($_GET['edit'])) {
		include("./proses/proses_perbaikan.php");
	} else {
	?>
		<table id='data_center'>
			<thead>
				<tr>
					<th>Center</th>
					<th>ID</th>
					<th>NAMA</th>
					<th>KESALAHAN</th>
					<th>HARI</th>
					<th>STAFF</th>

					<th>#</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$q = mysqli_query($con, "SELECT * from perbaikan 
        JOIN karyawan on perbaikan.id_karyawan=karyawan.id_karyawan
        JOIN center on perbaikan.no_center=center.no_center where perbaikan.status='belum' and perbaikan.id_karyawan='$id_karyawan'");
				while ($kes = mysqli_fetch_assoc($q)) {
				?>
					<tr>
						<td><?= $kes['no_center'] ?></td>
						<td><?= $kes['id_detail_nasabah'] ?></td>
						<td><?= $kes['nama_nasabah'] ?></td>
						<td><?= $kes['kesalahan'] ?></td>
						<td><?= strtoupper($kes['hari']) ?></td>
						<td><?= ($kes['nama_karyawan']) ?></td>

						<td>

							<a href="<?= $url . $menu ?>perbaikan_sl&edit&id_perbaikan=<?= $kes['id_perbaikan'] ?>" class="btn btn-danger">EDIT</a>
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