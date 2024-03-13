<?php
$tgl1 = date("Y-m-d"); // pendefinisian tanggal awal
$tgl2 = date('Y-m-d', strtotime('-7 days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
$hari = format_hari_tanggal($tgl1);
$hari = explode(',', $hari);
$hari = strtolower($hari[0]);
$query = mysqli_query($con, "SELECT * FROM karyawan,jabatan,cabang,wilayah where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang
	and cabang.id_wilayah=wilayah.id_wilayah
 and karyawan.id_karyawan='$id_karyawan' ");
$karyawan = mysqli_fetch_array($query);

if (!$_SESSION['jabatan']) {
	$_SESSION['jabatan'] = $karyawan['singkatan_jabatan'];
	$_SESSION['cabang'] = $karyawan['id_cabang'];
	pindah($url);
}
$cek_laporan1 = mysqli_query($con, "select * from laporan where tgl_laporan='$tgl1' and id_karyawan='$id_karyawan' ");

$cek_laporan = mysqli_fetch_array($cek_laporan1);

?>
<?php
if ($jabatan !== "SL")
	include("view/statistik.php");
?>

<div class="col-md-4 ">
    <div class="panel panel-default ">



        <div class="panel-body ">
            <h3 class='page-header'>INFORMASI ! </h3>
            <?php
			$cari_kuis = mysqli_query($con, "select * from kuis where id_cabang='$id_cabang' and status='aktif' order by id_kuis desc");
			if (mysqli_num_rows($cari_kuis) > 0) {
				$kuis = mysqli_fetch_array($cari_kuis);
				pesan("KUIS AKTIF <br> NAMA KUIS : $kuis[nama_kuis]", "danger");
				$id_karyawan = base64_encode(base64_encode($id_karyawan));
				$id_cabang = base64_encode(base64_encode($id_cabang));
				$id_kuis = base64_encode(base64_encode($kuis['id_kuis']));
				$link_kuis = $url . "isi_kuis.php?idk=$id_karyawan&cab=$id_cabang&kuis=$id_kuis";
			?>


            <a href="<?= $link_kuis ?>" class="btn btn-danger">KLIK DISINI UNTUK MEMULAI</a>
            <hr>
            <?php
			} else {
			}
			$id_karyawan = (($id_karyawan));

			$id_cabang = (($id_cabang));
			$id_karyawan = $_SESSION['id'];
			$id_cabang = $_SESSION['cabang'];
			$id_kuis = base64_decode(base64_decode($kuis['id_kuis']));
			?>
            <div class="card">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><?php echo strtoupper($karyawan['nama_karyawan']) ?> </li>
                    <li class="list-group-item">Jabatan : (<?php echo $karyawan['singkatan_jabatan'] ?>)
                        <?php echo $karyawan['nama_jabatan'] ?></li>
                    <li class="list-group-item">Cabang: (<?php echo $karyawan['kode_cabang'] ?>)
                        <?php echo strtoupper($karyawan['nama_cabang']) ?></li>
                    <li class="list-group-item"> Wilayah <?php echo strtoupper($karyawan['wilayah']) ?></li>
                </ul>
            </div>



            <?php
			if ($jabatan == 'SL') {
				$qpin = mysqli_query($con, "SELECT id_karyawan,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >=0 AND (DATEDIFF('$tgl1', tgl_cair)) <=2 THEN 1 ELSE 0 END) AS tiga,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >2 AND (DATEDIFF('$tgl1', tgl_cair)) <=14 THEN 1 ELSE 0 END) AS normal,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >14  THEN 1 ELSE 0 END) AS kurang_normal,
			COUNT(*) as total
				 FROM pinjaman WHERE monitoring='belum' and id_karyawan='$id_karyawan' and input_mtr='sudah' GROUP BY id_karyawan ");
				$mon = mysqli_fetch_array($qpin);
				$mon1 = $mon['total'];
			?>
            <h3 class='page-header'>MONITORING !
                <hr />
            </h3>
            <div class="card">
                <h4> Monitoring 0 - 3 hari : <?= $mon['tiga'] ?> </h4>
                <h4> Monitoring 4 - 14 hari : <?= $mon['normal'] ?> </h4>
                <h4> lebih 14 hari : <?= $mon['kurang_normal'] ?> </h4>
                <h3><a href='<?= $url . $menu ?>list-monitoring'>Total Monitoring : <?= $mon1 ?></a> </h3>
            </div>
            <?php
			}




			//STATISTIK
			if ($jabatan != 'SL') {
			?>
            <h3 class='page-header'>STATUS CENTER
                <hr />
            </h3>
            <div class="card divider">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>MEMBER : <span style='float:right;background: blue'
                                class="badge rounded-pill bg-primary"><?= $hitung->hitung_client($con, $id_cabang); ?>
                            </span></b> </li>
                    <li class="list-group-item"><b>Total Center Doa <span style='float:right;background: blue'
                                class="badge rounded-pill bg-primary"><?= $status['doa'] ?> </span></b> </li>
                    <li class="list-group-item"><b>Total Center Tidak Doa <span style='float:right;background: black'
                                class="badge rounded-pill bg-danger"><?= $status['tidak_doa'] ?> </span></b> </li>
                    <li class="list-group-item"><b>Total Center Hijau <span style='float:right;background: green'
                                class="badge rounded-pill bg-success"><?= $status['tidak_doa'] ?> </span></b> </li>
                    <li class="list-group-item"><b>Total Center Merah <span style='float:right;background: red'
                                class="badge rounded-pill bg-danger"><?= $status['merah'] ?> </span></b> </li>
                    <li class="list-group-item"><b>Total Center Kuning <span
                                style='float:right;background: #eea236;text-decoration-color: black;'
                                class="badge rounded-pill bg-danger"><?= $status['kuning'] ?> </span></b> </li>
                    <li class="list-group-item"><b>Total Center hitam <span style='float:right;background: black;'
                                class="badge rounded-pill bg-danger "><?= $status['hitam'] ?> </span></b> </li>
                    <li class="list-group-item"><b>DOORTODOOR <span style='float:right;background: black;'
                                class="badge rounded-pill bg-danger "><?= $status['iya'] ?> </span></b> </li>
                    <li class="list-group-item"><b>SETENGAH DOORTODOOR <span style='float:right;background: #eea236;'
                                class="badge rounded-pill bg-warning "><?= $status['ragu'] ?> </span></b> </li>
                    <li class="list-group-item"><b>KUMPULAN <span style='float:right;background: green;'
                                class="badge rounded-pill bg-yellow "><?= $status['tidak'] ?> </span></b> </li>
                    <li class="list-group-item"><b>Total Semua Center <span style='float:right'
                                class="badge rounded-pill bg-danger"><?= $hitung->hitung_center($con, $id_cabang) ?>
                            </span></b> </li>
                </ul>

            </div>
            <?php
				$qpin = mysqli_query($con, "SELECT id_karyawan,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >=0 AND (DATEDIFF('$tgl1', tgl_cair)) <=2 THEN 1 ELSE 0 END) AS tiga_hari,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >2 AND (DATEDIFF('$tgl1', tgl_cair)) <=14 THEN 1 ELSE 0 END) AS normal,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >14  THEN 1 ELSE 0 END) AS kurang_normal,
			COUNT(*) as total
				 FROM pinjaman WHERE monitoring='belum' and id_cabang='$id_cabang' and input_mtr='sudah' GROUP BY id_cabang ");
				$mon = mysqli_fetch_array($qpin);
				$mon1 = $mon['total'];

				?>
            <div class="card">
                <h4> KELUHAN MONITORING : <?= $hitung_banding ?> </h4>
                <h4> Monitoring 0 - 3 hari : <?= $mon['tiga_hari'] ?> </h4>
                <h4> Monitoring 4 - 14 hari : <?= $mon['normal'] ?> </h4>
                <h4> lebih 14 hari : <?= $mon['kurang_normal'] ?> </h4>
                <h3><a href='<?= $url . $menu ?>monitoring'>Total Monitoring : <?= $mon1 ?></a> </h3>
            </div>
            <?php
			}
			?>

        </div>





    </div>

</div>

<div class="col-md-8">
    <div class="panel panel-default  ">
        <?php

		if ($jabatan == 'SL') {
			//ADA DI VIEW/MENU.PHP
			if ($cekJam['belum'] > 0) {
				include "proses/konfirmasi_center.php";
			} else {
				include "index-sl.php";
			}
		} else if ($jabatan == 'BM' || $jabatan == 'ASM' || $jabatan == 'MIS') {
		?>
        <h2 class="page-header">
            <?php echo format_hari_tanggal(date("Y-m-d")) . ""; ?>
        </h2>
        <a href="<?= $url ?>/export/laporan_harian.php?tgl=<?= date("Y-m-d") ?>" class='btn btn-success'>
            <i class="fa fa-file-excel-o"></i> Export To Excel
        </a>
        <a href="<?= $url . $menu ?>laporan_manual" class="btn btn-danger">MEMBUAT SEMUA LAPORAN</a>
        <a href="<?= $url . $menu ?>rekap_setoran&tgl=<?= date("Y-m-d") ?>" class="btn btn-info">REKAP SETORAN</a>
        <a href="<?= $url . $menu ?>center_kosong&tgl=<?= date("Y-m-d") ?>" class="btn btn-primary">CEK CENTER
            KOSONG</a>
        <div class='table-responsive'>
            <br>
            <table class='table'>
                <tr>
                    <th rowspan=2>NO</th>
                    <th rowspan=2>NAMA</th>
                    <th colspan=10 style="text-align:center">LAPORAN</th>
                </tr>
                <tr>

                    <td>CTR</td>
                    <td>AGT</td>
                    <td>Client</td>
                    <td>Bayar</td>
                    <td>Tdk Bayar</td>
                    <td>%</td>
                    <td>Change</td>
                    <td>Keterangan</td>
                    <td>#</td>
                </tr>
                <?php

					$cek_ka = mysqli_query($con, "SELECT distinct k.nama_karyawan, k.id_karyawan from center c join karyawan k on k.id_karyawan=c.id_karyawan where c.id_cabang='$id_cabang' and c.hari='$hari' order by k.nama_karyawan asc");
					$hitung_member = 0;
					$hitung_agt = 0;
					$hitung_bayar = 0;
					$hitung_tdk_bayar = 0;
					$hitung_center = 0;
					$hitung_chg = 0;
					while ($tampil = mysqli_fetch_array($cek_ka)) {
						$cek_l1 = mysqli_query($con, "select * from laporan where id_karyawan='$tampil[id_karyawan]' and tgl_laporan='$tgl1'");

						$cek_l = mysqli_query($con, "SELECT sum(detail_laporan.total_agt)as anggota, sum(detail_laporan.member)as member, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan='$tgl1' and laporan.id_karyawan='$tampil[id_karyawan]'");

						// echo "SELECT sum(detail_laporan.total_agt)as anggota, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan='$tgl1' and laporan.id_karyawan='$tampil[id_karyawan]'";
						if (mysqli_num_rows($cek_l)) {
							$tampil_lapor = mysqli_fetch_array($cek_l);
							if ($tampil_lapor['anggota'] != NULL) {
								$hitung_member = $hitung_member + $tampil_lapor['member'];
								$hitung_agt = $hitung_agt + $tampil_lapor['anggota'];
								$hitung_bayar = $hitung_bayar + $tampil_lapor['bayar'];

								$hitung_center = $hitung_center + $tampil_lapor['hitung_center'];



								$qchg = mysqli_query($con, "SELECT SUM(detail_laporan.`total_bayar`) AS bayar,
						SUM(detail_laporan.`total_tidak_bayar`) AS tidak_bayar,
						(SUM(detail_laporan.`total_bayar`)/SUM(detail_laporan.`total_agt`) *100) AS persen
						 FROM laporan JOIN detail_laporan ON laporan.`id_laporan`=detail_laporan.`id_laporan` 
						
						WHERE laporan.`tgl_laporan`='$tgl2' AND laporan.id_karyawan='$tampil[id_karyawan]'
						 GROUP BY laporan.`id_karyawan`
						 
						");
								$persen = round(($tampil_lapor['bayar'] / $tampil_lapor['anggota'] * 100), 2);
								$chg = mysqli_fetch_array($qchg);
								$chg = round($chg['persen'], 2);
								$rubah = $persen - $chg;
								$hitung_chg = $rubah + $hitung_chg;
								if ($rubah > 0) {
									$warna_chg = "#52eb34";
								} else {
									$warna_chg = "#e4544d";
								}
					?>
                <tr>
                    <td><?php echo $no++ ?>.</td>

                    <td><?php echo $tampil['nama_karyawan'] ?></td>
                    <td><?php echo $tampil_lapor['hitung_center'] ?></td>
                    <td><?php echo $tampil_lapor['member'] ?></td>
                    <td><?php echo $tampil_lapor['anggota'] ?></td>
                    <td><?php echo $tampil_lapor['bayar'] ?></td>
                    <td><?php echo $tidak_bayar = ($tampil_lapor['anggota'] - $tampil_lapor['bayar']) ?></td>
                    <td><?php echo $persen ?>%</td>
                    <td style="color:<?= $warna_chg ?>">
                        <?php echo ($rubah == null ? "0" : round($rubah, 2) . "%") ?>

                    </td>

                    <td><?php echo $tampil_lapor['keterangan_laporan'] ?></td>
                    <td><small><i><?php echo $tampil_lapor['status_laporan'] ?></i></small></td>

                </tr>
                <?php
								$hitung_tdk_bayar = $hitung_tdk_bayar + $tidak_bayar;
							} else {
								if (mysqli_num_rows($cek_l1)) {
									$tampil_lapor1 = mysqli_fetch_array($cek_l1);
								?>
                <tr>

                    <td><?php echo $no++ ?>.</td>

                    <td><?php echo $tampil['nama_karyawan'] ?></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0%</td>

                    <td><?php echo $tampil_lapor1['keterangan_laporan'] ?></td>
                    <td><small><i><?php echo $tampil_lapor1['status_laporan'] ?></i></small></td>
                </tr>
                <?php
								} else {

								?>
                <tr>
                    <td><?php echo $no++ ?>.</td>

                    <td><?php echo $tampil['nama_karyawan'] ?></td>
                    <td colspan=9><i>belum membuat laporan</td>

                </tr>
                <?php
								}
							}
						} else {

							?>
                <tr>
                    <td colspan=5>Belum bikin laporan </td>
                </tr>
                <?php
						}
						?>

                <?php

					}

					$total__cgh = mysqli_query($con, "SELECT SUM(detail_laporan.`total_bayar`) AS bayar,
						SUM(detail_laporan.`total_tidak_bayar`) AS tidak_bayar,
						(SUM(detail_laporan.`total_bayar`)/SUM(detail_laporan.`total_agt`) *100) AS persen
						 FROM laporan JOIN detail_laporan ON laporan.`id_laporan`=detail_laporan.`id_laporan` 
						
						WHERE laporan.`tgl_laporan`='$tgl2' 
						 GROUP BY laporan.`id_karyawan`
						 
						");
					$total_chg_persen = mysqli_fetch_array($total__cgh);
					$persen = round(($hitung_bayar / $hitung_agt) * 100, 2);
					$hitung_chg = $total_chg_persen['persen'] - $persen;
					if ($hitung_chg > 0) {
						$warna_chg = "#52eb34";
					} else {
						$warna_chg = "#e4544d";
					}
					?>
                <tr>
                    <th colspan=2>Total</th>
                    <th><?php echo $hitung_center ?></th>
                    <th><?php echo $hitung_member ?></th>
                    <th><?php echo $hitung_agt ?></th>
                    <th><?php echo $hitung_bayar ?></th>
                    <th><?php echo $hitung_tdk_bayar ?></th>
                    <th><?php echo $persen ?>%</th>
                    <th style="color:<?= $warna_chg ?>"><?php echo round($hitung_chg, 2) ?></th>
                </tr>
            </table>
        </div>
        <?php

		}
		if ($jabatan == 'ADM') {
		?>
        <h2 class='page-header' style='text-align:center'>SISA MONITORING
            <hr />
        </h2>
        <table class="table table-bordered">
            <tr>
                <td>
                    NO
                </td>
                <td>NIK</td>
                <td>STAFF</td>
                <td>SISA MONITORING</td>
                <td></td>
            </tr>
            <?php
				$total_monitoring = 0;
				$cek_ka = mysqli_query($con, "SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
				while ($karyawan = mysqli_fetch_array($cek_ka)) {
				?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $karyawan['nik_karyawan'] ?></td>
                <td><?= $karyawan['nama_karyawan'] ?></td>
                <td>
                    <?php
							$q = mysqli_query($con, "select count(id_detail_nasabah) as total from pinjaman where monitoring='belum' and id_karyawan='$karyawan[id_karyawan]' and id_cabang='$id_cabang' and input_mtr='sudah'");
							$total = mysqli_fetch_array($q);
							$total = $total['total'];
							$total_monitoring = $total + $total_monitoring;
							echo $total;
							?>

                </td>
                <td><a href="<?= $url . $menu ?>monitoring&id=<?= $karyawan['id_karyawan'] ?>"> Detail</a> </td>
            </tr>
            <?php
				}
				?>
            <tr>
                <td colspan="3"></td>
                <td><?= $total_monitoring ?></td>
            </tr>
        </table>
        <?php

		}

		?>





    </div>


</div>