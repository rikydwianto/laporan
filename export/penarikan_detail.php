<?php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_karyawan = $_SESSION['id'];
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan = $_SESSION['jabatan'];
$cabang = $_SESSION['cabang'];
$id_cabang = $_SESSION['cabang'];
$su = $_SESSION['su'];
$d = detail_karyawan($con, $id_karyawan);
$nama_jabatan = $d['singkatan_jabatan'];
if (isset($_GET['tglawal']) || isset($_GET['tglakhir'])) {
    $tglawal = aman($con,$_GET['tglawal']);
    $tglakhir = aman($con,$_GET['tglakhir']);
} else {
    $tglawal = date("Y-m-d");
    $tglakhir = date("Y-m-d", strtotime('+4 day', strtotime(date("Y-m-d"))));
}

if (isset($_GET['tgl'])) {
    $qtgl =aman($con, $_GET['tgl']);
} else {
    $qtgl = date("Y-m-d");
}

$hari = hari_biasa($qtgl);
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=penarikansimpanan.xls");
?>
<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        table,
        thead,
        tr,
        th {
            text-align: center;
        }

        td {
            height: 30px;
            vertical-align: middle;
        }
    </style>
    <title></title>
</head>

<body>

    <h2 class='page-header'>Penarikan Simpanan oleh Manager</h2>

    <table class='table' border="1">
        <tr>
            <th>No</th>
            <th>ClientID</th>
            <th>NAMA</th>
            <th>CENTER</th>
            <th>Grup</th>
            <th>Disbursement</th>
            <th>J. Waktu</th>
            <th>Tanggal Disburse</th>
            <th>Total Balance</th>
            <th>OutStanding Menunggak</th>
            <th>Tunggakan <br> "Week Pass Due"</th>
            <th>CIcilan Perminggu</th>
            <th>Cicilan melalui simpanan</th>
            <th>Penarikan simpanan Wajib</th>
            <th>Saldo Simpanan Wajib</th>
            <th>Sisa Simpanan</th>
            <th>Sisa OS Par</th>
            <th>Alasan Penarikan</th>
            <th>Hari</th>
            <th>STAFF</th>
        </tr>

        <?php
        $tgl = date("Y-m-d");
        $total_penarikan = 0;
        $penarikan = mysqli_query($con, "SELECT * FROM penarikan_simpanan 
        JOIN (select * from daftar_nasabah union select * from daftar_nasabah_mantan where id_cabang='$id_cabang') as daftar_nasabah ON daftar_nasabah.`id_nasabah`=penarikan_simpanan.`id_anggota` join karyawan on karyawan.id_karyawan=penarikan_simpanan.id_karyawan where penarikan_simpanan.tgl_penarikan='$qtgl' and daftar_nasabah.id_cabang='$id_cabang' and penarikan_simpanan.id_cabang='$id_cabang'
        group by penarikan_simpanan.id_anggota order by karyawan.nama_karyawan asc");
        $total_wajib = 0;
        $total_sukarela = 0;
        $total_pensiun = 0;
        $total_hariraya = 0;
        while ($simp = mysqli_fetch_array($penarikan)) {
            $total_penarikan = $total_penarikan + $simp['nominal_penarikan'];
            $kel = $simp['id_detail_nasabah'];
            $kel = explode("/", $kel);
            $kel = $kel[2];
            $total_wajib += $simp['wajib'];
            $total_sukarela += $simp['sukarela'];
            $total_pensiun += $simp['pensiun'];
            $total_hariraya += $simp['hariraya'];
            $cek_delin = mysqli_query($con,"select * from deliquency where id_cabang='$id_cabang' and id_detail_nasabah='$simp[id_detail_nasabah]' order by tgl_input desc");
            $delin = mysqli_fetch_array($cek_delin);
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $simp['id_detail_nasabah'] ?></td>
                <td><?= $simp['nama_nasabah'] ?></td>
                <td><?= sprintf("%03d",$simp['no_center']) ?></td>
                <td><?= sprintf("%03d",$kel) ?></td>
                <td><?=angka($delin['amount'])?></td>
                <td><?=$delin['priode']?></td>
                <td><?=$delin['tgl_disburse']?></td>
                <td><?=angka($delin['sisa_saldo'])?></td>
                <td><?=angka($delin['tunggakan'])?></td>
                <td><?=$delin['minggu']?></td>
                <td>CIcilan Perminggu</td>
                <td><?=$simp['angsuran_masuk']?></td>
                <td><?= angka($simp['wajib']) ?></td>
                <td><?=angka($delin['wajib'])?></td>
                <td><?=angka($delin['wajib'] - $simp['wajib'])?></td>
                <td>Sisa OS Par</td>
                <td><?=$simp['alasan']?></td>
                <td><?=format_hari_tanggal($simp['tgl_penarikan'])?></td>
                <td><?= $simp['nama_karyawan'] ?></td>

                <!-- <td>.</td>
                <td></td>
                <td></td>
                <td><?= $simp['id_anggota'] ?></td>

                <td></td>
                <td></td>

                <td></td>
                <td></td>
                <td><?= angka($simp['sukarela']) ?></td>
                <td><?= angka($simp['pensiun']) ?></td>
                <td><?= angka($simp['hariraya']) ?></td>
                <td><?= angka($simp['nominal_penarikan']) ?></td> -->
            </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="13">Total Penarikan</th>
            <th><?= angka($total_wajib) ?></th>
            <!-- <th><?= angka($total_sukarela) ?></th>
            <th><?= angka($total_pensiun) ?></th>
            <th><?= angka($total_hariraya) ?></th>
            <th><?= angka($total_penarikan) ?></th> -->
        </tr>
    </table>


</body>

</html>