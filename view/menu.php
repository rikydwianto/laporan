<li>
	<a href="<?=$url ?>" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
</li>
<?php 
if(isset($_SESSION['id']) && $jabatan=='SL'){
?>

	<li><a href="<?php echo $url.$menu?>tmb_laporan"   class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a></i>
	<li><a href="<?php echo $url.$menu?>laporan" class="w3-bar-item w3-button"><i class="fa fa-search"></i> Lihat Laporan</a></li>
	<li><a href="<?php echo $url.$menu?>center-staff" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Center</a></li>
	<li><a href="<?php echo $url.$menu?>cashflow_sl" class="w3-bar-item w3-button"><i class="fa fa-file-excel-o"></i> Cashflow AGT</a></li>
	<li><a href="<?php echo $url.$menu?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
	<li><a href="<?php echo $url.$menu?>logout" class="w3-bar-item w3-button"><i class="fa fa-sign-out"></i> Logout</a></li>
	  
  
<?php
} 
else if(($_SESSION['id']) && $su=='y'){
?>
	<li>
		<a href="#"><i class="fa fa-building"></i> Master Data<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
				<li>
					<a href="<?php echo $url.$menu?>karyawan" class=""><i class="fa fa-odnoklassniki"></i> Karyawan</a>
				</li>
				<li>
					<a href="<?php echo $url.$menu?>center" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Center</a>

				</li>
				<li>
					<a href="<?php echo $url.$menu?>cashflow" class="w3-bar-item w3-button"><i class="fa fa-file-excel-o"></i> Cashflow</a>

				</li>
				<li>
					<a href="<?php echo $url.$menu?>cabang" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Cabang</a>
				</li>
				<li>
					<a href="<?php echo $url.$menu?>wilayah" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Wilayah</a>
				</li>
				<li>
					<a href="<?php echo $url.$menu?>regional" class="w3-bar-item w3-button"><i class="fa fa-building"></i> Regional</a>
				</li>
			</ul>
		
		</li>
		<li><a href="<?php echo $url.$menu?>anggota" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Anggota</a></li>
		<li><a href="<?php echo $url.$menu?>pemb_lain" class="w3-bar-item w3-button"><i class="fa fa-dollar"></i> Pembiayaan Lain</a></li>
		<li>
			<a href="#"><i class="fa fa-file-excel-o"></i> Laporan<span class="fa arrow"></span></a>
			<ul class="nav nav-second-level">
				<li>
					<a href="<?php echo $url.$menu?>tmb_laporan"   class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a>

				</li>

				<li>
					<a href="<?php echo $url.$menu?>tmb_laporan_baru"   class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Baru</a>

				</li>

				<li>
					<a href="<?php echo $url.$menu?>edit_laporan"   class="w3-bar-item w3-button"><i class="fa fa-edit"></i> Edit Laporan</a>

				</li>
				<li>
					<a href="<?php echo $url.$menu?>rekap_laporan"><i class="fa fa-calendar"></i> Laporan Harian</a>
				</li>
				<li>
					<a href="<?php echo $url.$menu?>rekap_laporan_minggu"><i class="fa fa-calendar"></i> Laporan Mingguan</a>
				</li>
				<li>
					<a href="<?php echo $url.$menu?>laporan_bulanan"><i class="fa fa-calendar"></i> Laporan Bulanan</a>
				</li>
				<li>
					<a href="<?php echo $url.$menu?>rekap_bayar"><i class="fa fa-file-excel-o"></i> Rekap Pembayaran</a>
				</li>
				<li>
					<a href="<?php echo $url.$menu?>rekap_anggota"><i class="fa fa-users"></i> Rekap Anggota</a>
				</li>
			</ul>
			
		</li>
		<li><a href="<?php echo $url.$menu?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
		</li>	
	<li>
		<a href="<?php echo $url.$menu ?>logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
	</li>

<?php
}
else if(($_SESSION['id']) && $jabatan=='BM' || $jabatan=='ASM' )
{
	?>
	<li>
	<a href="#"><i class="fa fa-building"></i> Master Data<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
			<li>
				<a href="<?php echo $url.$menu?>karyawan" class=""><i class="fa fa-odnoklassniki"></i> Karyawan</a>
			</li>
			<li>
				<a href="<?php echo $url.$menu?>center" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Center</a>

			</li>
		</ul>
	</li>
	<li>
		<a href="#"><i class="fa fa-file-excel-o"></i> Laporan<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
			<li>
				<a href="<?php echo $url.$menu?>tmb_laporan"   class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a>

			</li>
			<li>
				<a href="<?php echo $url.$menu?>edit_laporan"   class="w3-bar-item w3-button"><i class="fa fa-edit"></i> Edit Laporan</a>

			</li>
			<li>
				<a href="<?php echo $url.$menu?>rekap_laporan"><i class="fa fa-calendar"></i> Laporan Harian</a>
			</li>
			
			<li>
				<a href="<?php echo $url.$menu?>rekap_laporan_minggu"><i class="fa fa-calendar"></i> Laporan Mingguan</a>
			</li>
			<li>
				<a href="<?php echo $url.$menu?>laporan_bulanan"><i class="fa fa-calendar"></i> Laporan Bulanan</a>
			</li>
			<li>
				<a href="<?php echo $url.$menu?>rekap_bayar"><i class="fa fa-file-excel-o"></i> Rekap Pembayaran</a>
			</li>
			<li>
					<a href="<?php echo $url.$menu?>rekap_anggota"><i class="fa fa-users"></i> Rekap Anggota</a>
				</li>
			
		</ul>
		
	</li>
	<li><a href="<?php echo $url.$menu?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
	</li>	
<li>
	<a href="<?php echo $url.$menu ?>logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
</li>
	<?php
}
else if(($_SESSION['id']) && $jabatan=='MIS')
{
	?>
	<li>
	<a href="#"><i class="fa fa-building"></i> Master Data<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
			<li>
				<a href="<?php echo $url.$menu?>karyawan" class=""><i class="fa fa-odnoklassniki"></i> Karyawan</a>
			</li>
			<li>
				<a href="<?php echo $url.$menu?>center" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Center</a>

			</li>
		</ul>
	</li>

		<li><a href="<?php echo $url.$menu?>anggota" class="w3-bar-item w3-button"><i class="fa fa-users"></i> Anggota</a></li>
	<li>
		
		<li><a href="<?php echo $url.$menu?>pemb_lain" class="w3-bar-item w3-button"><i class="fa fa-dollar"></i> Pembiayaan Lain</a></li>
		<a href="#"><i class="fa fa-file-excel-o"></i> Laporan<span class="fa arrow"></span></a>
		<ul class="nav nav-second-level">
			<li>
				<a href="<?php echo $url.$menu?>tmb_laporan"   class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a>

			</li>
			<li>
				<a href="<?php echo $url.$menu?>edit_laporan"   class="w3-bar-item w3-button"><i class="fa fa-edit"></i> Edit Laporan</a>

			</li>
			
			<li>
					<a href="<?php echo $url.$menu?>rekap_anggota"><i class="fa fa-users"></i> Rekap Anggota</a>
				</li
			
		</ul>
		
	</li>
	<li><a href="<?php echo $url.$menu?>setting" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
	</li>	
<li>
	<a href="<?php echo $url.$menu ?>logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
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