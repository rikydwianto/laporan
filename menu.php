
<?php 
if(isset($_GET['menu'])){
	$getmenu=$_GET['menu'];
		if($jabatan!="SL"){
			switch($getmenu){
				
			case"cabang":
				include("proses/cabang.php");
			break;
			case"wilayah":
				include("proses/wilayah.php");
			break;
			case"rekap_bayar":
				include("proses/rekap_bayar.php");
			break;
			case"logout":
				include("proses/logout.php");
			break;
			case"tmb_laporan":
				include("proses/tmb_laporan.php");
			break;
			case"tmb_laporan_baru":
				include("proses/tmb_laporan_baru.php");
			break;
			
			case"rekap_laporan":
				include("proses/rekap_laporan.php");
			break;

			case"rekap_laporan_minggu":
				include("proses/rekap_laporan_minggu.php");
			break;

			case"edit_laporan":
				include("proses/edit_laporan.php");
			break;
			
			case"laporan_bulanan":
				include("proses/rekap_bulanan.php");
			break;
			case"karyawan_su":
				include("proses/karyawan_su.php");
			break;
			case"karyawan":
				include("proses/karyawan.php");
			break;
			case"setting":
				include("proses/setting.php");
			break;
			case"center":
				include("proses/center.php");
			break;
			case"laporan":
				include("proses/laporan.php");
			break;
			case"anggota":
				include("proses/anggota.php");
			break;
			case"cashflow":
				include("proses/cashflow.php");
			break;
			case"pemb_lain":
				include("proses/pemb_lain.php");
			break;
			case"rekap_anggota":
				include("proses/rekap_anggota.php");
			break;
			default:include"halaman-index.php";
			break;
			
		}
	}
	else
	{
		switch($getmenu){
			case"logout":
				include("proses/logout.php");
			break;
			case"tmb_laporan":
				include("proses/tmb_laporan.php");
			break;
			
			case"cashflow_sl":
				include("proses/cashflow_sl.php");
			break;
			
			case"tmb_laporan_baru":
				include("proses/tmb_laporan_baru.php");
			break;

			case"setting":
				include("proses/setting.php");
			break;

			case"center-staff":
				include("proses/center-staff.php");
			break;

			case"laporan":
				include("proses/laporan.php");
			break;

			default:include"halaman-403.php";
			break;
		}
	}
	
	
}
else{
	include"halaman-index.php";
}

?>