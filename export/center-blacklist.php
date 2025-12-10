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
    $qtgl = aman($con,$_GET['tgl']);
} else {
    $qtgl = date("Y-m-d");
}
$hari = hari_biasa($qtgl);
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Centerblacklist.xls");
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

    <h2>DAFTAR CENTER BlackList</h2>

    <table id='data_center1' class='table'  border="1" class='border: 1px solid;width:100%'>
        <thead>
            <tr>
                <th>NO</th>
                <th>STAFF</th>
                <th>CENTER</th>
                <th>HARI</th>
                <th>JAM</th>
                <th>ANGGOTA</th>
                <th>STATUS</th>
                <th>MAPS</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $q = mysqli_query($con, "select * from center where id_cabang='$id_cabang' and  blacklist!='t' order by FIELD(blacklist,'y','r') desc");
            while ($center = mysqli_fetch_assoc($q)) {
                $data = detail_karyawan($con, $center['id_karyawan']);
                if ($center['blacklist'] == 'y') {
                    $status_bl = "Tutup Total";
                    $aktif_tutup = "selected";
                    $merah = "'";
                } else if ($center['blacklist'] == 'r') {
                    $status_bl = "Tutup ada pemasukan";
                    $aktif_r = 'seleceted';
                    $merah = "'";
                } else {
                    $status_bl = "Tidak Ditutup";
                    $aktif_tidak = 'selected';
                    $merah = '';
                }
            ?>
                <tr <?= $merah ?>>
                    <td><?= $no++; ?></td>
                    <td><?= $data['nama_karyawan']; ?></td>
                    <td><?= $center['no_center']; ?></td>
                    <td><?= $center['hari']; ?></td>
                    <td><?= $center['jam_center']; ?></td>
                    <td><?= $center['anggota_center']; ?></td>
                    <td>

                        <?php
                        echo $status_bl;
                        ?>

                    </td>
                    <td>
                        <?php if ($center['latitude'] != null || $center['longitude'] != NULL) : ?>
                            <a href="<?= link_maps($center['latitude'], $center['longitude']) ?>">Arahkan</a>
                        <?php endif; ?>
                    </td>

                    <td>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>


</body>

</html>