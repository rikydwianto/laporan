<?php
class Hitung{
	public function hitung_center($con,$cabang){
	$q=mysqli_query($con,"select count(no_center) as center from center where id_cabang=$cabang");
	$hit = mysqli_fetch_array($q);
	return $hit['center'];	


	}

	public function hitung_laporan($con,$cabang){
		$q=mysqli_query($con,"
			SELECT count(if (laporan.status_laporan='sukses',1,0)) as hitung from laporan,karyawan
WHERE laporan.id_karyawan=karyawan.id_karyawan
and laporan.status_laporan='sukses' and karyawan.id_cabang=$cabang and tgl_laporan=curdate()

			");
		if(mysqli_num_rows($q))
		{
			$hit = mysqli_fetch_array($q);
			return $hit['hitung'];	
		}
		else return 0 ;
	}



	public function hitung_staff($con,$cabang){
	$q=mysqli_query($con,"select count(if(status_karyawan='aktif',1,NULL)) as karyawan from karyawan WHERE id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') and status_karyawan='aktif' and id_cabang=$cabang ");
	$hit = mysqli_fetch_array($q);
	return $hit['karyawan'];	


	}

	public function hitung_member($con,$cabang){
		$q=mysqli_query($con,"select sum(total_nasabah) as member from total_nasabah where  id_cabang=$cabang");
		$hit = mysqli_fetch_array($q);
		if ($hit['member']>0)
			return $hit['member'];
		else return 0;	
	}

	public function hitung_client($con,$cabang){
		$q=mysqli_query($con,"select sum(anggota_center) as member from center where  id_cabang=$cabang");
		$hit = mysqli_fetch_array($q);
		if ($hit['member']>0)
			return $hit['member'];
		else return 0;	
	}
	public function hitung_status($con,$cabang){
		$q=mysqli_query($con,"select count(if(doa_center='y',1,NULL) ) as doa,
			count(if(doa_center='t',1,NULL) ) as tidak_doa,
			count(if(status_center='hijau',1,NULL) ) as hijau,
			count(if(status_center='kuning',1,NULL) ) as kuning,
			count(if(status_center='merah',1,NULL) ) as merah,
			count(if(status_center='hitam',1,NULL) ) as hitam,
			count(if(doortodoor='y',1,NULL) ) as iya,
			count(if(doortodoor='t',1,NULL) ) as tidak,
			count(if(doortodoor='r',1,NULL) ) as ragu

			from center

		 where  id_cabang=$cabang");
		$hit = mysqli_fetch_array($q);
		return $hit;	
	}


	public function rekap_laporan($con,$cabang,$tglawal,$tglakhir,$su,$filter){


		if($su!='y')
			$tam="and karyawan.id_cabang='$cabang'";
		else{
			if($filter){
				$tam="and karyawan.id_cabang='$filter'";
			}
			else
				$tam="";
		}
		$q=mysqli_query($con,"
SELECT 
karyawan.id_cabang,
sum(total_agt) as anggota, 
sum(member) as member, 
sum(detail_laporan.total_bayar) as anggota_bayar,
sum(detail_laporan.total_tidak_bayar) as anggota_tidak_bayar,
count(detail_laporan.no_center) as hitung_center,
count(if(doa='y',1,NULL) ) as doa,
count(if(doa='t',1,NULL) ) as tidak_doa,
count(if(status='hijau',1,NULL) ) as hijau,
count(if(status='kuning',1,NULL) ) as kuning,
count(if(status='merah',1,NULL) ) as merah,
count(if(status='hitam',1,NULL) ) as hitam,
count(if(doortodoor='y',1,NULL) ) as iya,
count(if(doortodoor='r',1,NULL) ) as ragu,
count(if(doortodoor='t',1,NULL) ) as tidak

FROM `laporan`,detail_laporan,karyawan where laporan.id_laporan=detail_laporan.id_laporan
and laporan.id_karyawan=karyawan.id_karyawan



$tam

and laporan.tgl_laporan >= '$tglawal' and laporan.tgl_laporan <='$tglakhir'
and laporan.status_laporan='sukses'
 group by karyawan.id_cabang

			");
		
		if($hitung = mysqli_num_rows($q))
		{
			while($hit=mysqli_fetch_array($q)){
				$data[]=$hit;
			}	
			return $data;

		}
		else
		{
			return $data[]= array('anggota' => 0 );;
		}
	}


	function cek_cabang($con,$id){
		$q=mysqli_query($con,"select * from cabang where id_cabang=$id");
		$qq=mysqli_fetch_array($q);
		return $qq;

	}


	public function cari_anggota($con,$cabang,$tglawal,$tglakhir)
	{
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
and anggota.tgl_anggota >= '$tglawal' and anggota.tgl_anggota <= '$tglakhir'
GROUP by anggota.id_karyawan order by karyawan.nama_karyawan asc";

	$qq=mysqli_query($con,$q);
	if(mysqli_num_rows($qq)>0)
	{
		while($arr=mysqli_fetch_array($qq))
		{
			$data[]=$arr;
		}

	}
	else
		$data['anggota']=0;
		

	return $data;
	} 
}