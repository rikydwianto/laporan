<?php
// Read the JSON file
function ubahTanggal($tanggalAwal, $jumlahHari)
{
    // Buat objek DateTime dari tanggal awal
    $date = new DateTime($tanggalAwal);

    // Tentukan apakah jumlah hari positif atau negatif dan ubah tanggal sesuai
    if ($jumlahHari >= 0) {
        $date->modify("+$jumlahHari day");
    } else {
        $date->modify("$jumlahHari day"); // $jumlahHari sudah negatif
    }

    // Kembalikan tanggal hasil pengubahan dalam format 'Y-m-d'
    return $date->format('Y-m-d');
}
function selisihHari($tanggalAwal, $tanggalAkhir)
{
    // Buat objek DateTime dari tanggal awal dan tanggal akhir
    $startDate = new DateTime($tanggalAwal);
    $endDate = new DateTime($tanggalAkhir);

    // Hitung selisih antara dua tanggal
    $interval = $startDate->diff($endDate);

    // Kembalikan selisih dalam jumlah hari
    return $interval->days;
}




function cari_minggu($bulan, $tahun, $url)
{
    $path = $_SERVER['DOCUMENT_ROOT'];
    // $url = "";
    // $jsonData = file_get_contents($url . 'api/priode.json');
    $jsonData = file_get_contents('https://not.comdev.my.id/api/priode.json');
    $weeksOfYears = json_decode($jsonData, true);
    // Filter dates for May 2024 and get the corresponding week
    $bulan_array = array();
    $year = $tahun;
    $month = sprintf("%02d", $bulan);

    if (isset($weeksOfYears[$year])) {
        foreach ($weeksOfYears[$year] as $weekNumber => $dates) {
            foreach ($dates as $date) {
                if (strpos($date, "$year-$month") === 0) {
                    if (!isset($bulan_array[$weekNumber])) {
                        $bulan_array[$weekNumber] = array();
                    }
                    $bulan_array[$weekNumber][] = $date;
                }
            }
        }
    }

    $hasil = json_encode($bulan_array, JSON_PRETTY_PRINT);
    $data = json_decode($hasil, true);
    $totalCount = 0;

    $hitung_minggu = count($data);
    // Loop through the weeks and count the number of dates
    $minggu_ke = 1;
    $minggu_simpan = [];
    $minggu_rill = 1;
    foreach ($data as $week => $dates) {
        $count = count($dates);
        $tgl_awal = $dates[0];
        $tgl_akhir = $dates[$count - 1];
        $hari_awal = strtolower(explode(",", format_hari_tanggal($tgl_awal))[0]);
        $hari_akhir = strtolower(explode(",", format_hari_tanggal($tgl_akhir))[0]);
        if ($minggu_ke == 1) {
            if ($hari_awal == 'kamis' || $hari_awal == 'jumat' || $hari_awal == 'sabtu' || $hari_awal == 'minggu') {
                // echo "hari libur tidak perlu ditampilkan";
            } else {
                $selisih = selisihHari($tgl_awal, ubahTanggal($tgl_akhir, 1));
                $kurang = $selisih - 7;
                if ($kurang == 0) {
                    $tgl_awal = $dates[0];
                } else {
                    $tgl_awal = ubahTanggal($tgl_awal, $kurang);
                }

                array_push($minggu_simpan, ['minggu_rill' => $minggu_rill, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]);
                $minggu_rill++;
            }
        } else if ($minggu_ke == $hitung_minggu) {
            $selisih = selisihHari($tgl_awal, $tgl_akhir);
            if ($selisih == 0 || $selisih == 1) {
            } else {
                if ($hari_akhir == 'sabtu' || $hari_akhir == 'minggu') {
                    array_push($minggu_simpan, ['minggu_rill' => $minggu_rill, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]);
                    $minggu_rill++;
                } else {
                    $selisih = 7 - selisihHari($tgl_awal, ubahTanggal($tgl_akhir, 1));
                    $tgl_akhir = ubahTanggal($tgl_akhir, $selisih);
                    array_push($minggu_simpan, ['minggu_rill' => $minggu_rill, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]);
                    $minggu_rill++;
                }
            }
        } else {

            array_push($minggu_simpan, ['minggu_rill' => $minggu_rill, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]);
            $minggu_rill++;
        }


        $minggu_ke++;
    }
    return ($minggu_simpan);
}

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Rekap Monitoring </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id='rekap_monitoring'>
                        <thead>

                            <tr>
                                <th>NO</th>
                                <th>Bulan - Tahun</th>
                                <th class="text-center">Total</th>
                                <th class="">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $hitung_total = 0;
                            $qbulan = mysqli_query($con, "SELECT YEAR(tgl_cair) AS tahun,MONTH(tgl_cair) AS bulan FROM pinjaman p  WHERE p.id_cabang='$id_cabang' and monitoring='belum' GROUP BY MONTH(tgl_cair),YEAR(tgl_cair) ORDER BY p.tgl_cair DESC");
                            while ($bulan = mysqli_fetch_assoc($qbulan)) {
                                $mon = $bulan['bulan'];
                                $tahun = $bulan['tahun'];
                                $mon = sprintf("%02d", $mon);
                                $hitung_monitoring = mysqli_query($con, "select count(*) as total from pinjaman where id_cabang='$id_cabang' and tgl_cair like '$tahun-$mon-%' and monitoring='belum' and input_mtr='sudah' ");
                                echo mysqli_error($con);
                                $hitung_monitoring = mysqli_fetch_assoc($hitung_monitoring)['total'];
                                $hitung_total += $hitung_monitoring;

                                $staff = mysqli_query($con, "SELECT
                                                            p.id_karyawan,
                                                            k.`nama_karyawan` as nama_karyawan,
                                                            COUNT(id_pinjaman) AS total_per_staff
                                                            FROM
                                                            pinjaman p
                                                            JOIN karyawan k
                                                                ON k.id_karyawan = p.id_karyawan
                                                            WHERE p.id_cabang = '$id_cabang'
                                                            AND p.tgl_cair LIKE '$tahun-$mon-%'
                                                            AND p.monitoring = 'belum'
                                                            AND p.input_mtr = 'sudah'
                                                            GROUP BY p.id_karyawan");
                                $data_staff = [];
                                while ($s = mysqli_fetch_assoc($staff)) {
                                    $data_staff[] = array(
                                        'id' => $s['id_karyawan'],
                                        'staff' => $s['nama_karyawan'],
                                        'total' => $s['total_per_staff']
                                    );
                                }

                                $json = array(
                                    'monitoring' => $data_staff


                                );
                                $json = json_encode($json);
                            ?>
                            <tr data-detail='<?= $json ?>'>
                                <td><?= $no++ ?></td>
                                <td><a
                                        href="index.php?menu=pinjaman&filter_bulan=<?= $tahun . '-' . sprintf("%02d", $bulan['bulan']) ?>"><?= bulan_indo($bulan['bulan']) ?>
                                        - <?= $tahun ?></a>

                                </td>
                                <td style="text-align: center;"><?= $hitung_monitoring ?></td>

                                <td>
                                    <button class="btn btn-primary detail-btn">Detail</button>
                                </td>
                            </tr>
                            <?php
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <h2>REKAP MONITORING PER BULAN / MINGGU</h2>
                    <table class='table' id="list_rekap_minggu">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Bulan - Tahun</th>
                                <th>Total</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $no = 1;
                            $hitung_total = 0;
                            $qbulan = mysqli_query($con, "SELECT YEAR(tgl_cair) AS tahun,MONTH(tgl_cair) AS bulan FROM pinjaman p  WHERE p.id_cabang='$id_cabang' and monitoring='belum' GROUP BY MONTH(tgl_cair),YEAR(tgl_cair) ORDER BY p.tgl_cair DESC");
                            while ($bulan = mysqli_fetch_assoc($qbulan)) {
                                $mon = $bulan['bulan'];
                                $tahun = $bulan['tahun'];
                                // $tahun = 2024;
                                $mon = sprintf("%02d", $mon);
                                $hitung_monitoring = mysqli_query($con, "select count(*) as total from pinjaman where id_cabang='$id_cabang' and tgl_cair like '$tahun-$mon-%' and monitoring='belum' and input_mtr='sudah'");
                                echo "";
                                echo mysqli_error($con);
                                $hitung_monitoring = mysqli_fetch_assoc($hitung_monitoring)['total'];
                                $hitung_total += $hitung_monitoring;
                                $minggu = cari_minggu($bulan['bulan'], $tahun, $url);
                                $mi = [];
                                foreach ($minggu as $m => $a) {
                                    $tgl_awal  = $a['tgl_awal'];
                                    $tgl_akhir = $a['tgl_akhir'];
                                    $hitung_monitoring = mysqli_query($con, "SELECT COUNT(*) AS total 
                                                                             FROM pinjaman 
                                                                            WHERE id_cabang='$id_cabang' 
                                                                            AND tgl_cair >= '$tgl_awal' 
                                                                            AND tgl_cair <= '$tgl_akhir' 
                                                                            AND monitoring='belum' 
                                                                            AND input_mtr='sudah'");
                                    echo mysqli_error($con);
                                    $hitung_monitoring = mysqli_fetch_assoc($hitung_monitoring)['total'];
                                    $mi[] = array(
                                        'minggu_ke' => $a['minggu_rill'],
                                        'tgl_awal' => $a['tgl_awal'],
                                        'tgl_akhir' => $a['tgl_akhir'],
                                        'total' => $hitung_monitoring,
                                    );
                                }
                                $json = json_encode(array('minggu' => $mi));

                            ?>
                            <tr data-detail="<?= $json ?>">
                                <td><?= $no++ ?></td>
                                <td><a
                                        href="<?= $url . $menu ?>monitoring&filter_bulan=<?= $tahun . '-' . sprintf("%02d", $bulan['bulan']) ?>"><?= bulan_indo($bulan['bulan']) ?>
                                        - <?= $tahun ?></a>
                                </td>
                                <td><?= $hitung_monitoring ?></td>
                                <td><button class="btn btn-primary detail-btn">Detail</button></td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>