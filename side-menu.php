<a href="<?php echo $url?>"  class="w3-bar-item w3-button"><i class="fa fa-home"></i> Halaman Awal</a>
<?php 
if(isset($_SESSION['id']) && $jabatan=='SL'){
?>

	<li><a href="<?php echo $url.$menu?>tmb_laporan"   class="w3-bar-item w3-button"><i class="fa fa-plus"></i> Tambah Laporan</a></li>
	<li><a href="<?php echo $url.$menu?>laporan" class="w3-bar-item w3-button"><i class="fa fa-search"></i> Lihat Laporan</a></li>
	<li><a href="#<?php echo $url.$menu?>laporan" class="w3-bar-item w3-button"><i class="fa fa-gears"></i> Setting</a></li>
	<li><a href="<?php echo $url.$menu?>logout" class="w3-bar-item w3-button"><i class="fa fa-sign-out"></i> Logout</a></li>
	  
  
<?php
}
else if(($_SESSION['id']) && $jabatan=='BM' || $jabatan=='ASM')
{
	?>
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
	<?php
}
?>
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