<h2>Daftar Surat </h2>
<?php
$qbulan = mysqli_query($con, "SELECT YEAR(tgl_surat) AS tahun,MONTH(tgl_surat) AS bulan FROM surat s  WHERE s.id_cabang='$id_cabang' GROUP BY MONTH(tgl_surat),YEAR(tgl_surat) ORDER BY s.tgl_surat DESC");
while ($bulan = mysqli_fetch_assoc($qbulan)) {
    $mon = $bulan['bulan'];
    $tahun = $bulan['tahun'];
?>
    <table class='table table-bordered'>
        <tr>
            <th colspan="8" style="text-align:right">
                <h3><?= bulan_indo($bulan['bulan']) ?> - <?= $tahun ?></h3>
            </th>
        </tr>
        <tr>
            <th>NO</th>
            <th>NO SURAT</th>
            <th>PERIHAL</th>
            <th>KATEGORI</th>
            <th>TANGGAL</th>
            <th>TYPE</th>
            <th>PIC</th>
            <th>

            </th>
        </tr>
        <?php
        $mon = sprintf("%02d", $mon);
        $qsa = mysqli_query($con, "select * from surat s
         join karyawan k on s.id_karyawan=k.id_karyawan where s.id_cabang='$id_cabang' and tgl_surat like '$tahun-$mon-%' order by s.no_urut desc");
        while ($surat = mysqli_fetch_assoc($qsa)) {
            $kat = mysqli_query($con, "SELECT * from kategori_surat where id_cabang='$id_cabang' and kode_kategori='$surat[kategori_surat]'");
            $kat = mysqli_fetch_assoc($kat);
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $surat['no_surat'] ?></td>
                <td><?= $surat['perihal_surat'] ?></td>
                <td><?= $surat['kategori_surat'] ?>/<?= $kat['kategori_surat'] ?></td>
                <td><?= $surat['tgl_surat'] ?></td>
                <td><?= $surat['type_surat'] ?></td>
                <td><?= $surat['nama_karyawan'] ?></td>
                <td>
                    <a href="<?= $url . $menu ?>surat&lihat&delid=<?= $surat['id_surat'] ?>" onclick="return window.confirm('Yakin?')" class="btn "> <i class="fa fa-times"></i> </a>
                </td>
            </tr>
        <?php

        }
        ?>
    </table>
<?php
}
if (isset($_GET['delid'])) {
    $id = aman($con, $_GET['delid']);
    mysqli_query($con, "DELETE from surat where id_cabang='$id_cabang' and id_surat='$id'");
    pindah($url . $menu . "surat&lihat");
}
?>