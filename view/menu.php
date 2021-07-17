<li>
	<a href="<?= $url ?>" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
</li>

<?php
$date = date("Y-m-d");
$hari = hari_biasa($date);
 $hari = explode("-",$hari)[0];
 $hari=strtolower($hari);
 
$cek_jam = mysqli_query($con,"select count(no_center) as belum from center where jam_center BETWEEN '00:00:00' and '07:00:00'
and id_karyawan='$id_karyawan' and hari='$hari'
");
$cekJam = mysqli_fetch_array($cek_jam);
	


if (isset($_SESSION['id']) && $jabatan == 'SL') {

	
	
?>

<?php  
if($cekJam['belum']==0){
	?>
	<li><a href="<?php echo $url . $menu ?>tmb_laporan" class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a></i>
	<?php
}
?>
	<li><a href="<?php echo $url . $menu ?>laporan" class="w3-bar-item w3-button"><i class="fa fa-search"></i> Lihat Laporan</a></li>
	<li><a href="<?php echo $url . $menu ?>center-staff" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Center</a></li>
	<li><a href="<?php echo $url . $menu ?>cashflow_sl" class="w3-bar-item w3-button"><i class="fa fa-file-excel-o"></i> Cashflow </a></li>
	
	<li>
		<a href="<?php echo $url . $menu ?>detail_center" class="w3-bar-item w3-button"><i class="fas fa-globe-europe"></i> Data Wilayah</a>
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
		</ul>

	</li>

	<li>
		<a href="<?php echo $url ?>lokasi.php" class="w3-bar-item w3-button"><i class="fa fa-globe"></i> LOKASI</a>
	</li>

	<li>
		<a href="<?php echo $url . $menu ?>detail_center" class="w3-bar-item w3-button"><i class="fa fa-font-awesome"></i> Data Wilayah</a>
	</li>
	<li>
		<a href="<?php echo $url . $menu ?>upk" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> UPK NR</a>
	</li>
	<li>
		<a href="<?php echo $url . $menu ?>kelompok" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Kelompok</a>
	</li>
	<li><a href="<?php echo $url . $menu ?>anggota" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Anggota</a></li>
	</li>

	<li><a href="<?php echo $url . $menu ?>file" class="w3-bar-item w3-button"><i class="fa fa-folder"></i> FILE</a></li>
	<li>
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
		</ul>

	</li>
	<li><a href="<?php echo $url . $menu ?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
	</li>
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
				<a href="<?php echo $url . $menu ?>cashflow" class="w3-bar-item w3-button"><i class="fa fa-file-excel-o"></i> Cashflow</a>

			</li>
			<li>
				<a href="<?php echo $url . $menu ?>daftar_wilayah" class="w3-bar-item w3-button"><i class="fa fa-globes"></i> Daftar Wilayah</a>
			</li>
		</ul>
	</li>

	<li><a href="<?php echo $url . $menu ?>file" class="w3-bar-item w3-button"><i class="fa fa-folder"></i> FILE</a></li>
	<li>

	</li>
	
	<li>
		<a href="<?php echo $url . $menu ?>detail_center" class="w3-bar-item w3-button"><i class="fa fa-font-awesome"></i> Data Wilayah</a>
	</li>

	<li><a href="<?php echo $url . $menu ?>anggota" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Anggota</a></li>

	<li><a href="<?php echo $url . $menu ?>pemb_lain" class="w3-bar-item w3-button"><i class="fa fa-dollar"></i> Pembiayaan Lain</a></li>
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

		</li>
		<li><a href="<?php echo $url . $menu ?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
		</li>
		<li>
			<a href="<?php echo $url . $menu ?>logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
		</li>
	<?php
}


	?>


	<!--
<li>
	<a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
		<li>
			<a href="#">Second Level Item</a>
		</li>
		<li>
			<a href="#">Third Level <span class="fa arrow"></span></a>
			<ul class="nav nav-third-level">
				<li>
					<a href="#">Third Level Item</a>
				</li>
			</ul>
		</li>
	</ul>
</li> -->