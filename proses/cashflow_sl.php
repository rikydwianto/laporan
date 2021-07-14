
<?php
 if(isset($_GET['tglawal']) || isset($_GET['tglakhir']))
{
	$tglawal = $_GET['tglawal'];
	$tglakhir = $_GET['tglakhir'];
}
else{
	$tglawal = date("Y-01-01") ;
	$tglakhir = date("Y-m-d");
}
	 

		$q="SELECT sum(anggota.anggota_masuk) as masuk,
sum(anggota.anggota_keluar) as keluar,
sum(anggota.net_anggota) as nett,
sum(anggota.psa) as psa,
sum(anggota.ppd) as ppd,
sum(anggota.prr) as prr,
sum(anggota.arta) as arta,
sum(anggota.pmb) as pmb,
karyawan.nama_karyawan FROM `anggota`,karyawan 
where anggota.id_karyawan=karyawan.id_karyawan and karyawan.id_cabang=$cabang 
and anggota.tgl_anggota BETWEEN '$tglawal' and  '$tglakhir' and
karyawan.id_karyawan='$id_karyawan'
GROUP by anggota.id_karyawan order by karyawan.nama_karyawan asc";
$tampilData = mysqli_query($con,$q);
$tampilData = mysqli_fetch_array($tampilData);
$q2="SELECT sum(cashflow.cashflow_masuk) as masuk,
sum(cashflow.cashflow_keluar) as keluar,
sum(cashflow.net_cashflow) as nett,
sum(cashflow.psa) as psa,
sum(cashflow.ppd) as ppd,
sum(cashflow.prr) as prr,
sum(cashflow.arta) as arta,
sum(cashflow.pmb) as pmb,
karyawan.nama_karyawan FROM `cashflow`,karyawan 
where cashflow.id_karyawan=karyawan.id_karyawan and karyawan.id_cabang=$cabang 
and cashflow.tahun_cashflow >= '$tglawal' and cashflow.tahun_cashflow <= '$tglakhir' and
karyawan.id_karyawan='$id_karyawan'
GROUP by cashflow.id_karyawan order by karyawan.nama_karyawan asc";
$cashflow = mysqli_query($con,$q2);
$cashflow = mysqli_fetch_array($cashflow);
?>
<div class="col-md-12" >
	<div class="panel-body post-body table-responsive " >
		<h3 class="page-header">
			Capaian Dan Target Staff <?=$tampilData['nama_karyawan']?> <?=date("Y")?>
		</h3>
		<form method='get' action='<?php echo $url.$menu ?>rekap_laporan'>
		<input type="hidden" name='menu' value='cashflow_sl'/>
				<input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-01-01") )?>" class=""/>
				<input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
				<input type='submit' class="btn btn-info" name='cari' value='FILTER'/>
		</form>
		<table class='table'>
			<tr>
				<th>KETERANGAN</th>
				<th>Capaian</th>
				<th>Target</th>
				<th>%</th>
			</tr>
			<tr>
				<th><?=$tampilData['nama_karyawan']?></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<th>Anggota Masuk</th>
				<th><?=$masuk=$tampilData['masuk']?></th>
				<th><?=$cmasuk=$cashflow['masuk']?></th>
				<th><?=round(($masuk/$cmasuk)*100)?>%</th>
			</tr>
			<tr>
				<th>Anggota Keluar</th>
				<th><?=$keluar=$tampilData['keluar']?></th>
				<th><?=$ckeluar=$cashflow['keluar']?></th>
				<th><?=round(($keluar/$ckeluar)*100)?>%</th>
			</tr>
			<tr>
				<th>NETT</th>
				<th><?=$nett=$tampilData['nett']?></th>
				<th><?=$cnett=$cashflow['nett']?></th>
				<th><?=round(($nett/$cnett)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. Mikro Bisnis</th>
				<th><?=$pmb=$tampilData['pmb']?></th>
				<th><?=$cpmb=$cashflow['pmb']?></th>
				<th><?=round(($pmb/$cpmb)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. Sanitasi</th>
				<th><?=$psa=$tampilData['psa']?></th>
				<th><?=$cpsa=$cashflow['psa']?></th>
				<th><?=round(($psa/$cpsa)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. Pendidikan</th>
				<th><?=$ppd=$tampilData['ppd']?></th>
				<th><?=$cppd=$cashflow['ppd']?></th>
				<th><?=round(($ppd/$cppd)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. Renovasi Rumah</th>
				<th><?=$prr=$tampilData['prr']?></th>
				<th><?=$cprr=$cashflow['prr']?></th>
				<th><?=round(($prr/$cprr)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. ARTA</th>
				<th><?=$arta=$tampilData['arta']?></th>
				<th><?=$carta=$cashflow['arta']?></th>
				<th><?=round(($arta/$carta)*100)?>%</th>
			</tr>
		</table>
	
	</div>
</div>
			