
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


$qdisburse =mysqli_query($con,"SELECT SUM(jumlah_pinjaman) AS total FROM pinjaman where id_karyawan='$id_karyawan' and tgl_cair >= '$tglawal' and tgl_cair <= '$tglakhir'");
$disburse =mysqli_fetch_array($qdisburse);
$disburse = $disburse['total'];
$kurang_tujuh= date('Y-m-d', strtotime('-7 day', strtotime($tglakhir)));
$qdisburseminggu =mysqli_query($con,"SELECT SUM(jumlah_pinjaman) AS total FROM pinjaman where id_karyawan='$id_karyawan' and tgl_cair >= '$kurang_tujuh' and tgl_cair <= '$tglakhir'");
$disburseminggu =mysqli_fetch_array($qdisburseminggu);
$disburseminggu = $disburseminggu['total'];


$qdisbursetahun =mysqli_query($con,"SELECT SUM(jumlah_pinjaman) AS total FROM pinjaman where id_karyawan='$id_karyawan' and YEAR(tgl_cair)= YEAR('$tglawal') ");
$disbursetahun =mysqli_fetch_array($qdisbursetahun);
$disbursetahun = $disbursetahun['total'];



$qtarget =mysqli_query($con,"SELECT * FROM target_disburse where id_karyawan='$id_karyawan' ");
$target =mysqli_fetch_array($qtarget);
$target_tahun = $target['target_tahun'];
$target_bulan = $target['target_bulan'];
$target_minggu = $target['target_minggu'];
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
				<th>Kurang/Lebih</th>
				<th>%</th>
			</tr>
			<tr>
				<th><?=$tampilData['nama_karyawan']?></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<th>Anggota Masuk</th>
				<th><?=$masuk=$tampilData['masuk']?></th>
				<th><?=$cmasuk=$cashflow['masuk']?></th>
				<th><?=$masuk - $cmasuk?></th>
				<th><?=round(($masuk/$cmasuk)*100)?>%</th>
			</tr>
			<tr>
				<th>Anggota Keluar</th>
				<th><?=$keluar=$tampilData['keluar']?></th>
				<th><?=$ckeluar=$cashflow['keluar']?></th>
				<th><?=$keluar - $ckeluar?></th>
				<th><?=round(($keluar/$ckeluar)*100)?>%</th>
			</tr>
			<tr>
				<th>NETT</th>
				<th><?=$nett=$tampilData['nett']?></th>
				<th><?=$cnett=$cashflow['nett']?></th>
				<th><?=$nett - $cnett?></th>
				<th><?=round(($nett/$cnett)*100)?>%</th>
			</tr>
			<tr>
				<th>DISBURSE</th>
				<th colspan="4"></th>
				
			</tr>
			<tr>
				<th>--  MINGGU <br/> <?=$target_akhir?> dikurangi 7 hari  </th>
				<th><?=rupiah($disburseminggu)?></th>
				<th><?=rupiah($target_minggu)?></th>
				<th><?=rupiah( $disburseminggu -$target_minggu)?></th>
				<th><?=round(($disburseminggu/$target_minggu)*100)?>%</th>
			</tr>
			<tr>
				<th>--  BULAN</th>
				<th><?=rupiah($disburse)?></th>
				<th><?=rupiah($target_bulan)?></th>
				<th><?=rupiah( $disburse -$target_bulan)?></th>
				<th><?=round(($disburse/$target_bulan)*100)?>%</th>
			</tr>
			<tr>
				<th>--  TAHUN</th>
				<th><?=rupiah($disbursetahun)?></th>
				<th><?=rupiah($target_tahun)?></th>
				<th><?=rupiah( $disbursetahun -$target_tahun)?></th>
				<th><?=round(($disbursetahun/$target_tahun)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. Mikro Bisnis</th>
				<th><?=$pmb=$tampilData['pmb']?></th>
				<th><?=$cpmb=$cashflow['pmb']?></th>
				<th><?=$pmb- $cpmb?></th>
				<th><?=round(($pmb/$cpmb)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. Sanitasi</th>
				<th><?=$psa=$tampilData['psa']?></th>
				<th><?=$cpsa=$cashflow['psa']?></th>
				<th><?=$psa- $cpsa?></th>
				<th><?=round(($psa/$cpsa)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. Pendidikan</th>
				<th><?=$ppd=$tampilData['ppd']?></th>
				<th><?=$cppd=$cashflow['ppd']?></th>
				<th><?=$ppd- $cppd?></th>
				<th><?=round(($ppd/$cppd)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. Renovasi Rumah</th>
				<th><?=$prr=$tampilData['prr']?></th>
				<th><?=$cprr=$cashflow['prr']?></th>
				<th><?=$prr- $cprr?></th>
				<th><?=round(($prr/$cprr)*100)?>%</th>
			</tr>
			<tr>
				<th>Pemb. ARTA</th>
				<th><?=$arta=$tampilData['arta']?></th>
				<th><?=$carta=$cashflow['arta']?></th>
				<th><?=$arta- $carta?></th>
				<th><?=round(($arta/$carta)*100)?>%</th>
			</tr>
		</table>
	
	</div>
</div>
			