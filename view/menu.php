<li>
	<a href="<?= $url ?>" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
</li>

<?php
$date = date("Y-m-d");
$hari = hari_biasa($date);
$hari = explode("-", $hari)[0];
$hari = strtolower($hari);

$cek_jam = mysqli_query($con, "select count(no_center) as belum from center where konfirmasi='t' and id_karyawan='$id_karyawan' 
");
$cekJam = mysqli_fetch_array($cek_jam);



if (isset($_SESSION['id']) && $jabatan == 'SL') {



?>

	<?php
	if ($cekJam['belum'] == 0) {
	?>
		<li><a href="<?php echo $url . $menu ?>tmb_laporan" class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a></i>
		<?php
	}
		?>
		<li><a href="<?php echo $url . $menu ?>laporan" class="w3-bar-item w3-button"><i class="fa fa-search"></i> Lihat Laporan</a></li>
		<li><a href="<?php echo $url . $menu ?>center-staff" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Center</a></li>
		<li><a href="<?php echo $url . $menu ?>center-blacklist" class="w3-bar-item w3-button"><i class="fa fa-times"></i> Center BlackList</a></li>
		<li><a href="<?php echo $url . $menu ?>cashflow_sl" class="w3-bar-item w3-button"><i class="fa fa-file-excel-o"></i> Cashflow </a></li>

		<li>
			<a href="<?php echo $url . $menu ?>detail_center" class="w3-bar-item w3-button"><i class="fas fa-globe-europe"></i> Data Wilayah</a>
		</li>

		<li>
			<a href="<?php echo $url . $menu ?>list-monitoring" class="w3-bar-item w3-button"><i class="fas fa-globe-europe"></i> MONITORING</a>
		</li>

		<li>
			<a href="<?php echo $url . $menu ?>perbaikan_sl" class="w3-bar-item w3-button"><i class="fas fa-gears"></i> PERBAIKAN DATA</a>
		</li>

		<li>
			<a href="<?php echo $url . $menu ?>penarikan_simpanan" class="w3-bar-item w3-button"><i class="fas fa-dollar"></i> Penarikan Simpanan</a>
		</li>
		<li>
			<a href="<?php echo $url ?>lokasi.php" class="w3-bar-item w3-button"><i class="fa fa-globe"></i> LOKASI</a>
		</li>

		<li><a href="<?php echo $url . $menu ?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
		<li><a href="<?php echo $url . $menu ?>logout" class="w3-bar-item w3-button"><i class="fa fa-sign-out"></i> Logout</a></li>


	<?php
} else if (($_SESSION['id']) && $su == 'y') {
	?>
		<li>
			<a href="#"><i class="fa fa-building"></i> Master Data<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
			<li><a href="<?php echo $url . $menu ?>anggota" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Anggota</a></li>
		</li>
		<li><a href="<?php echo $url . $menu ?>pemb_lain" class="w3-bar-item w3-button"><i class="fa fa-dollar"></i> Pembiayaan Lain</a></li>

				<li>
					<a href="<?php echo $url . $menu ?>karyawan_su" class=""><i class="fa fa-odnoklassniki"></i> Karyawan SU</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>setting_kelompok" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Kelompok</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>center" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Center</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>perbaikan" class="w3-bar-item w3-button"><i class="fa fa-gear"></i> Perbaikan data</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>cashflow" class="w3-bar-item w3-button"><i class="fa fa-file-excel-o"></i> Cashflow</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>cabang" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Cabang</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>wilayah" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Wilayah</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>regional" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Regional</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>daftar_wilayah" class="w3-bar-item w3-button"><i class="fa fa-globes"></i> Daftar Wilayah</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>daftar_nasabah" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Daftar Nasabah</a>
				</li>
			</ul>

		</li>

		<li>
			<a href="<?php echo $url ?>lokasi.php" class="w3-bar-item w3-button"><i class="fa fa-globe"></i> LOKASI</a>
		</li>


		<li>
			<a href="<?php echo $url . $menu ?>upk" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> UPK NR</a>
		</li>

		<li><a href="<?php echo $url . $menu ?>file" class="w3-bar-item w3-button"><i class="fa fa-folder"></i> FILE</a></li>
		<li>
			<a href="#"><i class="fa fa-file-excel-o"></i> Laporan<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
				<li>
					<a href="<?php echo $url . $menu ?>tmb_laporan" class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a>

				</li>

				<li>
					<a href="<?php echo $url . $menu ?>edit_laporan" class="w3-bar-item w3-button"><i class="fa fa-edit"></i> Edit Laporan</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>rekap_laporan"><i class="fa fa-calendar"></i> Laporan Harian</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>rekap_laporan_minggu"><i class="fa fa-calendar"></i> Laporan Mingguan</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>laporan_bulanan"><i class="fa fa-calendar"></i> Laporan Bulanan</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>rekap_bayar"><i class="fa fa-file-excel-o"></i> Rekap Pembayaran</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>rekap_anggota"><i class="fa fa-users"></i> Rekap Anggota</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>list_penarikan"><i class="fa fa-file-o"></i> Penarikan Simpanan</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>list-center-blacklist"><i class="fa fa-building"></i> Center Ditutup</a>
				</li>
			</ul>

		</li>
		<li>
			<a href="<?php echo $url . $menu ?>file_folder" class="w3-bar-item w3-button"><i class="fas fa-folder"></i> #</a>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>quotes" class="w3-bar-item w3-button"><i class="fas fa-folder"></i> Quotes</a>
		</li>
		<li>
			<a href="#"><i class="fa fa-file-excel-o"></i> ANALISA PAR<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
				<li>
					<a href="<?php echo $url . $menu ?>blk_input" class="w3-bar-item w3-button"><i class="fa fa-plus"></i> BLK</a>

				</li>

				<li>
					<a href="<?php echo $url . $menu ?>par" class="w3-bar-item w3-button"><i class="fa fa-bar-chart"></i> Deliquency</a>

				</li>
				
			</ul>

		</li>
		<li><a href="<?php echo $url . $menu ?>spl" class="w3-bar-item w3-button"><i class="fa fa-bar-chart"></i> SPL</a></li>
		<li>
			<a href="<?php echo $url . $menu ?>monitoring" class="w3-bar-item w3-button"><i class="fas fa-folder"></i> MONITORING</a>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>detail_center" class="w3-bar-item w3-button"><i class="fa fa-font-awesome"></i> Data Wilayah</a>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>kelompok" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Kelompok</a>
		</li>
		

		<li><a href="<?php echo $url . $menu ?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>

		<li>
			<a href="<?php echo $url . $menu ?>logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
		</li>

	<?php
} else if (($_SESSION['id']) && $jabatan == 'BM' || $jabatan == 'ASM') {
	?>
		<li>
			<a href="#"><i class="fa fa-building"></i> Master Data<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
				<li>
					<a href="<?php echo $url . $menu ?>karyawan" class=""><i class="fa fa-odnoklassniki"></i> Karyawan</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>setting_kelompok" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Kelompok</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>center" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Center</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>cashflow" class="w3-bar-item w3-button"><i class="fa fa-file-excel-o"></i> Cashflow</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>daftar_wilayah" class="w3-bar-item w3-button"><i class="fa fa-globes"></i> Daftar Wilayah</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>daftar_nasabah" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Daftar Nasabah</a>
				</li>
			</ul>
		</li>


		<li>
			<a href="<?php echo $url . $menu ?>detail_center" class="w3-bar-item w3-button"><i class="fa fa-font-awesome"></i> Data Wilayah</a>
		</li>
		<li>
			<a href="<?php echo $url ?>lokasi.php" class="w3-bar-item w3-button"><i class="fa fa-globe"></i> LOKASI</a>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>kelompok" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Kelompok</a>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>upk" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> UPK NR</a>
		</li>
		<li><a href="<?php echo $url . $menu ?>anggota" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Anggota</a></li>

		<li><a href="<?php echo $url . $menu ?>pemb_lain" class="w3-bar-item w3-button"><i class="fa fa-dollar"></i> Pembiayaan Lain</a></li>
		<li>
			<a href="<?php echo $url . $menu ?>quotes" class="w3-bar-item w3-button"><i class="fas fa-folder"></i> Quotes</a>
		</li>
		<li>
			<a href="#"><i class="fa fa-file-excel-o"></i> Laporan<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
				<li>
					<a href="<?php echo $url . $menu ?>tmb_laporan" class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>edit_laporan" class="w3-bar-item w3-button"><i class="fa fa-edit"></i> Edit Laporan</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>rekap_laporan"><i class="fa fa-calendar"></i> Laporan Harian</a>
				</li>

				<li>
					<a href="<?php echo $url . $menu ?>rekap_laporan_minggu"><i class="fa fa-calendar"></i> Laporan Mingguan</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>laporan_bulanan"><i class="fa fa-calendar"></i> Laporan Bulanan</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>rekap_bayar"><i class="fa fa-file-excel-o"></i> Rekap Pembayaran</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>rekap_anggota"><i class="fa fa-users"></i> Rekap Anggota</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>list_penarikan"><i class="fa fa-file-o"></i> Penarikan Simpanan</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>list-center-blacklist"><i class="fa fa-building"></i> Center Ditutup</a>
				</li>
			</ul>

		</li>
		<li>
			<a href="#"><i class="fa fa-file-excel-o"></i> ANALISA PAR<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
				<li>
					<a href="<?php echo $url . $menu ?>blk_input" class="w3-bar-item w3-button"><i class="fa fa-plus"></i> BLK</a>

				</li>

				<li>
					<a href="<?php echo $url . $menu ?>par" class="w3-bar-item w3-button"><i class="fa fa-bar-chart"></i> Deliquency</a>

				</li>
				
			</ul>

		</li>
		<li><a href="<?php echo $url . $menu ?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
		</li>
	<?php
} else if (($_SESSION['id']) && $jabatan == 'MIS') {
	?>
		<li>
			<a href="#"><i class="fa fa-building"></i> Master Data<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
				<li>
					<a href="<?php echo $url . $menu ?>karyawan" class=""><i class="fa fa-odnoklassniki"></i> Karyawan</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>center" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Center</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>perbaikan" class="w3-bar-item w3-button"><i class="fa fa-gear"></i> Perbaikan data</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>cashflow" class="w3-bar-item w3-button"><i class="fa fa-file-excel-o"></i> Cashflow</a>

				</li>
				<li>
					<a href="<?php echo $url . $menu ?>daftar_wilayah" class="w3-bar-item w3-button"><i class="fa fa-globes"></i> Daftar Wilayah</a>
				</li>

			</ul>
		</li>

		<li><a href="<?php echo $url . $menu ?>file" class="w3-bar-item w3-button"><i class="fa fa-folder"></i> FILE</a></li>
		<li><a href="<?php echo $url . $menu ?>spl" class="w3-bar-item w3-button"><i class="fa fa-bar-chart"></i> SPL</a></li>
	
		<li>
			<a href="<?php echo $url . $menu ?>detail_center" class="w3-bar-item w3-button"><i class="fa fa-font-awesome"></i> Data Wilayah</a>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>upk" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> UPK NR</a>
		</li>
		<li><a href="<?php echo $url . $menu ?>anggota" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Anggota</a></li>
		<li>
			<a href="<?php echo $url . $menu ?>quotes" class="w3-bar-item w3-button"><i class="fas fa-folder"></i> Quotes</a>
		</li>
		<li><a href="<?php echo $url . $menu ?>pemb_lain" class="w3-bar-item w3-button"><i class="fa fa-dollar"></i> Pembiayaan Lain</a></li>
		<li>
			 <a href="#"><i class="fa fa-file-excel-o"></i> Laporan<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
			<li>
				<a href="<?php echo $url . $menu ?>tmb_laporan" class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a>

			</li>
			<li>
				<a href="<?php echo $url . $menu ?>edit_laporan" class="w3-bar-item w3-button"><i class="fa fa-edit"></i> Edit Laporan</a>

			</li>

			<li>
				<a href="<?php echo $url . $menu ?>rekap_anggota"><i class="fa fa-users"></i> Rekap Anggota</a>
			</li>
			<li>
				<a href="<?php echo $url . $menu ?>list_penarikan"><i class="fa fa-file-o"></i> Penarikan Simpanan</a>
			</li>
			<li>
				<a href="<?php echo $url . $menu ?>list-center-blacklist"><i class="fa fa-building"></i> Center Ditutup</a>
			</li>
		</ul>
		</li>
		<li>
			<a href="#"><i class="fa fa-file-excel-o"></i> ANALISA PAR<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
				<li>
					<a href="<?php echo $url . $menu ?>blk_input" class="w3-bar-item w3-button"><i class="fa fa-plus"></i> BLK</a>

				</li>

				<li>
					<a href="<?php echo $url . $menu ?>par" class="w3-bar-item w3-button"><i class="fa fa-bar-chart"></i> Deliquency</a>

				</li>
				
			</ul>

		</li>
		<li><a href="<?php echo $url . $menu ?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
		</li>
	<?php
} else if (($_SESSION['id']) && $jabatan == 'ADM') {
	?>
		<li>
			<a href="<?php echo $url . $menu ?>monitoring" class="w3-bar-item w3-button"><i class="fas fa-folder"></i> MONITORING</a>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>upk" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> UPK NR</a>
		</li>
		<li><a href="<?php echo $url . $menu ?>anggota" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Anggota</a></li>

		<li><a href="<?php echo $url . $menu ?>pemb_lain" class="w3-bar-item w3-button"><i class="fa fa-dollar"></i> Pembiayaan Lain</a></li>
		<li>


			<a href="#"><i class="fa fa-file-excel-o"></i> Laporan<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">

				<li>
					<a href="<?php echo $url . $menu ?>rekap_anggota"><i class="fa fa-users"></i> Rekap Anggota</a>
				</li>
				<li>
					<a href="<?php echo $url . $menu ?>list_penarikan"><i class="fa fa-file-o"></i> Penarikan Simpanan</a>
				</li>

			</ul>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>quotes" class="w3-bar-item w3-button"><i class="fas fa-folder"></i> Quotes</a>
		</li>
		<li><a href="<?php echo $url . $menu ?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
		</li>

	<?php
}


	?>