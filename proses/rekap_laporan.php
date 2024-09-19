<?php
if (isset($_GET['tgl'])) {
	$qtgl = $_GET['tgl'];
} else {
	$qtgl = date("Y-m-d");
}
$hari = strtolower(format_hari_tanggal($qtgl));
$hari = explode(',', $hari)[0];
?>
<div class="col-md-12">
    <div class="panel-body post-body table-responsive ">
        <h3 class="page-header">
            REKAP LAPORAN HARIAN
        </h3>
        <form method='get' action='<?php echo $url . $menu ?>rekap_laporan'>
            <input type=hidden name='menu' value='rekap_laporan' />
            <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>'
                onchange="submit()" />
            <input type=submit name='cari' value='CARI' />
        </form>
        <a href="<?= $url ?>/export/laporan_harian.php?tgl=<?= $qtgl ?>" class='btn btn-success'>
            <i class="fa fa-file-excel-o"></i> Export To Excel
        </a>
        <table class='table table-bordered'>
            <tr>
                <th style="vertical-align: middle;" rowspan=3>NO</th>
                <th style="vertical-align: middle;" rowspan=3>NAMA</th>
                <th style="vertical-align: middle;" colspan=11 style="text-align:center">LAPORAN
                    <?php echo format_hari_tanggal($qtgl); ?></th>
            </tr>
            <tr>

                <th style="vertical-align: middle;" rowspan="2">CTR</th>
                <th style="vertical-align: middle;" rowspan="2">AGT</th>
                <th style="vertical-align: middle;" rowspan="2">CLIENT</th>
                <th style="vertical-align: middle;" rowspan="2">Bayar</th>
                <th style="vertical-align: middle;" rowspan="2">Tdk Bayar</th>
                <th style="vertical-align: middle;" rowspan="2">%</th>
                <th style="vertical-align: middle;" rowspan="2">Keterangan</th>
                <th style="vertical-align: middle;" rowspan="2"></th>
                <th style="vertical-align: middle;text-align:center" colspan="3" style='text-align:center'>RILL </th>
            </tr>
            <tr>
                <th style="vertical-align: middle;">CTR</th>
                <th style="vertical-align: middle;">AGT</th>
                <th style="vertical-align: middle;">MEMBER</th>
            </tr>
            <?php
			$x = "<i style='color:red;font-weight:bold'>X</i>";
			$ok = "<b style='color:green;font-weight:bold'>OK</b>";
			$cek_ka = mysqli_query($con, "SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
			$hitung_member = 0;
			$hitung_agt = 0;
			$hitung_bayar = 0;
			$hitung_tdk_bayar = 0;
			$hitung_center = 0;
			while ($tampil = mysqli_fetch_assoc($cek_ka)) {
				$cek_l1 = mysqli_query($con, "select * from laporan where id_karyawan='$tampil[id_karyawan]' and tgl_laporan='$qtgl'");
				$cek_l = mysqli_query($con, "SELECT sum(detail_laporan.total_agt)as anggota,sum(detail_laporan.member)as member, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan='$qtgl' and laporan.id_karyawan='$tampil[id_karyawan]'");
				if (mysqli_num_rows($cek_l)) {
					$tampil_lapor = mysqli_fetch_assoc($cek_l);
					if ($tampil_lapor['bayar'] != NULL) {
						$hitung_member = $hitung_member + $tampil_lapor['member'];
						$hitung_agt = $hitung_agt + $tampil_lapor['anggota'];
						$hitung_bayar = $hitung_bayar + $tampil_lapor['bayar'];
						$hitung_tdk_bayar = $hitung_tdk_bayar + $tampil_lapor['tidak_bayar'];
						$hitung_center = $hitung_center + $tampil_lapor['hitung_center'];
						$qril = mysqli_query($con, "select count(*) as total_center, sum(anggota_center) as total_member, sum(member_center) as total_anggota from center where id_cabang='$id_cabang' and id_karyawan='$tampil[id_karyawan]' and hari='$hari'");
						$rill = mysqli_fetch_assoc($qril);
						echo mysqli_error($con);
			?>
            <tr>
                <td><?php echo $no++ ?>.</td>

                <td><?php echo $tampil['nama_karyawan'] ?></td>
                <td><?php echo $tampil_lapor['hitung_center'] ?></td>
                <td><?php echo $tampil_lapor['member'] ?></td>
                <td><?php echo $tampil_lapor['anggota'] ?></td>
                <td><?php echo $tampil_lapor['bayar'] ?></td>
                <td><?php echo $tidak_bayar = $tampil_lapor['anggota'] - $tampil_lapor['bayar'] ?></td>
                <td><?php echo round(($tampil_lapor['bayar'] / $tampil_lapor['anggota'] * 100)) ?>%</td>

                <td><?php echo $tampil_lapor['keterangan_laporan'] ?></td>
                <td>
                    <small><?php echo $tampil_lapor['status_laporan'] ?></small>
                </td>
                <td><?= $rill['total_center'] ?>|<?= ($rill['total_center'] === $tampil_lapor['hitung_center'] ? "$ok" : "$x") ?>
                </td>
                <td><?= $rill['total_anggota'] ?>|<?= ($rill['total_anggota'] === $tampil_lapor['member'] ? "$ok" : "$x") ?>
                </td>
                <td><?= $rill['total_member'] ?>|<?= ($rill['total_member'] === $tampil_lapor['anggota'] ? "$ok" : "$x") ?>
                </td>

            </tr>
            <?php
						$hitung_tdk_bayar = $hitung_tdk_bayar + $tidak_bayar;
					} else {
						if (mysqli_num_rows($cek_l1)) {
							$tampil_lapor1 = mysqli_fetch_assoc($cek_l1);

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
                <td>0</td>
                <td>0</td>

                <td><?php echo $tampil_lapor1['keterangan_laporan'] ?></td>
                <td><small><i><?php echo $tampil_lapor1['status_laporan'] ?></i></small></td>

            </tr>
            <?php
						} else {

						?>
            <tr>
                <td><?php echo $no++ ?>.</td>

                <td><?php echo $tampil['nama_karyawan'] ?></td>
                <td colspan=11><i>belum membuat laporan</td>

            </tr>
            <?php
						}
					}
				} else {
					?>
            <tr>
                <td colspan=6>Belum bikin laporan </td>
            </tr>
            <?php
				}
				?>

            <?php

			}
			?>
            <tr>
                <th colspan=2 class='text-center'>Total</th>
                <th><?php echo $hitung_center ?></th>
                <th><?php echo $hitung_member ?></th>
                <th><?php echo $hitung_agt ?></th>
                <th><?php echo $hitung_bayar ?></th>
                <th><?php echo $hitung_tdk_bayar ?></th>
                <th colspan=9><?php echo $persen = round(($hitung_bayar / $hitung_agt) * 100, 2) ?>%</th>
            </tr>
        </table>

    </div>
</div>