
<?php
if (isset($_GET['menu'])) {
	$getmenu = $_GET['menu'];
	if ($jabatan != "SL") {
		switch ($getmenu) {
			case "rekap_bayar":
				include("proses/rekap_bayar.php");
				break;
			

			case "tmb_laporan":
				include("proses/tmb_laporan.php");
				break;
			case "tmb_laporan_baru":
				include("proses/tmb_laporan_baru.php");
				break;

			case "rekap_laporan":
				include("proses/rekap_laporan.php");
				break;

			case "rekap_laporan_minggu":
				include("proses/rekap_laporan_minggu.php");
				break;

			case "edit_laporan":
				include("proses/edit_laporan.php");
				break;

			case "laporan_bulanan":
				include("proses/rekap_bulanan.php");
				break;

			case "setting":
				include("proses/setting.php");
				break;
			case "center":
				include("proses/center.php");
				break;
			case "laporan":
				include("proses/laporan.php");
				break;
			case "anggota":
				include("proses/anggota.php");
				break;
			case "pemb_lain":
				include("proses/pemb_lain.php");
				break;
			case "rekap_anggota":
				include("proses/rekap_anggota.php");
				break;
			case "daftar_wilayah":
				include("proses/tambah_wilayah.php");
				break;
			
			
			default:
				if (file_exists("proses/$getmenu.php")) {
					include "proses/$getmenu.php";
				} else {
					include "halaman-index.php";
				}

				break;
		}
	} else {
		switch ($getmenu) {
			case "logout":
				include("proses/logout.php");
				break;
				case "ket_laporan":
					include("proses/ket_laporan.php");
					break;
			case "deliquency_sl":
				include("./proses/deliquency_sl.php");
				break;
			case "laporan_kegiatan":
				include("./proses/laporan_kegiatan.php");
				break;
			case "cek_nik":
				include("proses/cek_nik.php");
				break;
			case "quotes":
				include("proses/quotes.php");
				break;
			case "perbaikan_sl":
				include("proses/perbaikan_sl.php");
				break;
			case "detail_center":
				include("proses/detail_center.php");
				break;
			case "file_folder":
				include("proses/file_folder.php");
				break;

			case "lokasi":
				include("proses/lokasi.php");
				break;
			case "laporan-teman":
				include("proses/laporan-teman.php");
				break;
			case "tmb_laporan":
				include("proses/tmb_laporan.php");
				break;

			case "cashflow_sl":
				include("proses/cashflow_sl.php");
				break;

			case "tmb_laporan_baru":
				include("proses/tmb_laporan_baru.php");
				break;

			case "setting":
				include("proses/setting.php");
				break;

			case "center-staff":
				include("proses/center-staff.php");
				break;

			case "laporan":
				include("proses/laporan.php");
				break;
			case "penarikan_simpanan":
				include("proses/penarikan_simpanan.php");
			break;
			case "center-blacklist":
				include("proses/center-blacklist.php");
			break;
			case "list-monitoring":
				include("proses/list-monitoring.php");
			break;
			case "daftar_backup_sl":
				include("proses/daftar_backup_sl.php");
				break;
			
			default:
				include "halaman-403.php";
				break;
		}
	}
} else {
	include "halaman-index.php";
}

?>