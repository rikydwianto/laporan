<?php
$tgl1 = date("Y-m-d"); // pendefinisian tanggal awal
$tgl2 = date('Y-m-d', strtotime('-7 days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari

$qpin = mysqli_query($con, "SELECT id_karyawan,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >=0 AND (DATEDIFF('$tgl1', tgl_cair)) <=2 THEN 1 ELSE 0 END) AS tiga_hari,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >2 AND (DATEDIFF('$tgl1', tgl_cair)) <=14 THEN 1 ELSE 0 END) AS normal,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >14  THEN 1 ELSE 0 END) AS kurang_normal,
			COUNT(*) as total
				 FROM pinjaman WHERE monitoring='belum' and id_cabang='$id_cabang' and input_mtr='sudah' GROUP BY id_cabang ");
$mon = mysqli_fetch_array($qpin);
$mon1 = $mon['total'];
$hitung = new Hitung();
$status = ($hitung->hitung_status($con, $id_cabang));
$total_sl = $hitung->hitung_staff($con, $id_cabang);
$q_update_terakhir = "SELECT MAX(tgl_cair) as akhir FROM pinjaman where id_cabang='$id_cabang'";
$update_pinjaman_akhir = mysqli_fetch_assoc(mysqli_query($con, $q_update_terakhir))['akhir'];
?>
<input type="hidden" id="tiga_hari" value="<?= $mon['tiga_hari'] ?>">
<input type="hidden" id="normal" value="<?= $mon['normal'] ?>">
<input type="hidden" id="kurang_normal" value="<?= $mon['kurang_normal'] ?>">
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6" id='staff'>
        <a href="index.php?menu=staff">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5 col-md-4">
                            <div class="icon-big text-center icon-warning">
                                <i class="nc-icon nc-single-02 text-warning"></i>
                            </div>
                        </div>
                        <div class="col-7 col-md-8">
                            <div class="numbers">
                                <p class="card-category">Staff Lapang</p>
                                <p class="card-title"><?= $total_sl ?></p>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr />
                    <div class="stats">
                        Staff Lapang Aktif
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <a href="index.php?menu=pinjaman">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5 col-md-4">
                            <div class="icon-big text-center icon-warning">
                                <i class="nc-icon nc-money-coins text-success"></i>
                            </div>
                        </div>
                        <div class="col-7 col-md-8">
                            <div class="numbers">
                                <p class="card-category">Monitoring</p>
                                <p class="card-title"><?= $mon1 ?></p>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr />
                    <div class="stats">
                        <i class="fa fa-calendar-o"></i>
                        Pinjaman belum dimonitoring
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header ">
                <h5 class="card-title">Statistik Monitoring</h5>
                <p class="card-category">Update Terkahir pada <?= hari_biasa($update_pinjaman_akhir) ?></p>
            </div>
            <div class="card-body ">
                <canvas id="chartEmail"></canvas>
            </div>
            <div class="card-footer ">
                <div class="legend">

                </div>
                <hr>
                <div class="stats">
                    <i class="fa fa-circle text-primary"></i> 3-14 Hari
                    <i class="fa fa-circle text-warning"></i> Kurang dari 3 Hari
                    <i class="fa fa-circle text-danger"></i> Lebih dari 14 Hari
                </div>
            </div>
        </div>
    </div>
</div>