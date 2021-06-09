<?php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

@$prov = $_GET['prov'];
@$kab = $_GET['kab'];
@$kec = $_GET['kec'];
@$desa = $_GET['desa'];
if (!empty($prov)) {
?>
	<select name="kab" id="kab" class='form-control' onchange="pilih_kec()">
		<option value="">Silahkan pilih Kabupaten</option>

		<?php
		$idkab = $_GET['kab'];
		$qkab  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,2)=$prov AND CHAR_LENGTH(kode)=5 ORDER BY nama");

		while ($Kab = mysqli_fetch_array($qkab)) {
			if ($Kab['kode'] == $idkab)
				echo "<option selected value='$Kab[kode]'>  $Kab[nama]</option>";
			else
				echo "<option  value='$Kab[kode]'>$Kab[nama]</option>";
		}
		?>
	</select>
<?php
} 
else if (!empty($kab)) {
?>
	<select name="kec" id="kec" class='form-control' onchange="pilih_desa()" >
		<option value="">Silahkan pilih Kecamatan</option>

		<?php
		$idkec = $_GET['kec'];
		$qkec  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,5)='$kab' AND CHAR_LENGTH(kode)=8 ORDER BY nama");

		while ($kec = mysqli_fetch_array($qkec)) {
			if ($kec['kode'] == $idkec)
				echo "<option selected value='$kec[kode]'>  $kec[nama]</option>";
			else
				echo "<option  value='$kec[kode]'>$kec[nama]</option>";
		}
		?>
	</select>
<?php
} 
else if (!empty($kec)) {
?>
	<select name="desa" id="desa" class='form-control' >
		<option value="">Silahkan pilih DESA</option>

		<?php
		$iddesa = $_GET['desa'];
		$qdesa  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,8)='$kec' AND CHAR_LENGTH(kode)=13 ORDER BY nama");

		while ($desa = mysqli_fetch_array($qdesa)) {
			if ($desa['kode'] == $iddesa)
				echo "<option selected value='$desa[kode]'>  $desa[nama]</option>";
			else
				echo "<option  value='$desa[kode]'>$desa[nama]</option>";
		}
		?>
	</select>
	<?php
		?>
				Tambahkan 1 Desa saja 
				<button type="submit" class='btn btn-info' name='satu_desa'>Tambahkan Desa <?= wilayah($con, $iddesa) ?></button>
<?php
} 
else {

?>
	<select name="prov" id="prov" class='form-control' onchange="pilih_kab()">
		<option value="">Silahkan pilih Provinsi</option>

		<?php
		$idprov = $_GET['prov'];
		$qprov  = mysqli_query($con, "SELECT kode,nama FROM daftar_wilayah WHERE CHAR_LENGTH(kode)=2 ORDER BY nama");
		while ($prov = mysqli_fetch_array($qprov)) {
			if ($prov['kode'] == $idprov)
				echo "<option selected value='$prov[kode]'>$prov[nama]</option>";
			else
				echo "<option  value='$prov[kode]'>$prov[nama]</option>";
		}
		?>
	</select>
<?php
} ?>


<?php
if (!empty($kec)) {
?>
<br>
<br>
<br>
<br>
	<div class="card" style="width: 18rem;">
		<ul class="list-group list-group-flush">
			<li class="list-group-item"> <b>
					<center>Kecamatan <?= wilayah($con, $kec) ?></center>
				</b></li>
			<?php
			$qdesa1  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,8)='$kec' AND CHAR_LENGTH(kode)=13 ORDER BY nama");
			while ($desa1 = mysqli_fetch_array($qdesa1)) {

				echo "<li class='list-group-item'>$desa1[nama]</li>";
			}
			?>
			<li class='list-group-item'> <button type="submit" class='btn btn-danger' name='kecamatan_desa'>Tambahkan Semua </button></li>

		</ul>
	</div>
<?php
}
?>