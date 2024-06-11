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
    $jsonData = file_get_contents($url . 'api/priode.json');
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
<h2>REKAP MONITORING PER BULAN </h2>
<table class='table'>
    <thead>
        <tr>
            <th>NO</th>
            <th>BULAN - TAHUN</th>
            <th>TOTAL</th>
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
            $hitung_monitoring = mysqli_query($con, "select count(*) as total from pinjaman where id_cabang='$id_cabang' and tgl_cair like '$tahun-$mon-%' and monitoring='belum' and input_mtr='sudah'");
            echo "";
            echo mysqli_error($con);
            $hitung_monitoring = mysqli_fetch_assoc($hitung_monitoring)['total'];
            $hitung_total += $hitung_monitoring;
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><a
                    href="<?= $url . $menu ?>monitoring&filter_bulan=<?= $tahun . '-' . sprintf("%02d", $bulan['bulan']) ?>"><?= bulan_indo($bulan['bulan']) ?>
                    - <?= $tahun ?></a>

            </td>
            <td><?= $hitung_monitoring ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2"></th>
            <th colspan="1"><?= $hitung_total ?></th>
        </tr>
    </tfoot>
</table>
<h2>REKAP MONITORING PER BULAN / MINGGU</h2>
<table class='table'>
    <thead>
        <tr>
            <th>NO</th>
            <th>BULAN - TAHUN</th>
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
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><a
                    href="<?= $url . $menu ?>monitoring&filter_bulan=<?= $tahun . '-' . sprintf("%02d", $bulan['bulan']) ?>"><?= bulan_indo($bulan['bulan']) ?>
                    - <?= $tahun ?></a>
                <table class="table table-bordered">
                    <tr>
                        <th>Minggu</th>
                        <th>Priode</th>
                        <th>Total</th>
                    </tr>
                    <?php
                        // $minggu = cari_minggu($bulan['bulan'], 2024, $url);
                        $minggu = cari_minggu($bulan['bulan'], $tahun, $url);
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
                        ?>
                    <tr>
                        <td>
                            <?= "Minggu ke - " . $a['minggu_rill'] ?>
                        </td>
                        <td>
                            <?= $a['tgl_awal'] . ' sd ' . $a['tgl_akhir'] ?>
                        </td>
                        <td>
                            <?= $hitung_monitoring ?>
                        </td>
                    </tr>
                    <?php
                        }

                        ?>

                </table>
            </td>

        </tr>
        <?php
        }
        ?>
    </tbody>
</table>