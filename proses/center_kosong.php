<?php
if (isset($_GET['tglawal']) || isset($_GET['tglakhir'])) {
    $tglawal = $_GET['tglawal'];
    $tglakhir = $_GET['tglakhir'];
} else {
    $tglawal = date("Y-m-d", strtotime('-5 day', strtotime(date("Y-m-d"))));
    $tglakhir = date("Y-m-d");
}

$tglbanding_awal = date("Y-m-d", strtotime('-7 day', strtotime(date($tglawal))));
$tglbanding_akhir = date("Y-m-d", strtotime('-7 day', strtotime(date($tglakhir))));
// echo $tglbanding_awal.'----'.$tglbanding_akhir;
?>
<div class='content table-responsive'>
    <h2 class='page-header'>REKAP CENTER KOSONG</h2>
    <!-- <i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/> -->
    <!-- Button to Open the Modal -->
    <div class="col-md-12">


        <div class='col-md-8'>
            <form action="">
                <input type="hidden" name='menu' value='center_kosong' />
                <input type="date" name='tglawal' value="<?= (isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d", (strtotime('-5 day', strtotime(date("Y-m-d")))))) ?>" class="" />
                <input type="date" name='tglakhir' value="<?= (isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d")) ?>" class="" />
                <input type='submit' class="btn btn-info" name='cari' value='FILTER' />
            </form>
        </div>
    </div>
    <div class="col-lg-12">
        <h2 style='text-align:center'>Rekap Center Tidak Ada Pendapatan <br> <?= format_hari_tanggal($tglawal) ?> s/d <?= format_hari_tanggal($tglakhir) ?></h2>
        <br>
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>CENTER</th>
                    <th>HARI</th>
                    <th>TANGGAL</th>
                    <th>WARNA CENTER</th>
                    <th>KETERANGAN</th>
                    <th>STAFF</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($con, "SELECT * from center_kosong ck 
                 join center c on ck.no_center=c.no_center
                 join karyawan k on k.id_karyawan=ck.id_karyawan
                 where ck.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' and c.id_cabang='$id_cabang'
                 and (ck.tgl_transaksi between '$tglawal' and '$tglakhir')  group by ck.no_center order by k.nama_karyawan asc, c.hari, tgl_transaksi desc
                 ");
                while ($r = mysqli_fetch_assoc($q)) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $r['no_center'] ?></td>
                        <td><?= $r['hari'] ?></td>
                        <td><?= $r['tgl_transaksi'] ?></td>
                        <td><?= $r['status_center'] ?></td>
                        <td></td>
                        <td><?= $r['nama_karyawan'] ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <!-- <h2 style='text-align:center'>CENTER PENDAPATAN NOLL BERTAMBAH<br> <?= format_hari_tanggal($tglawal) ?> s/d <?= format_hari_tanggal($tglakhir) ?></h2>
         <br>
         <table class='table table-bordered'>
             <thead>
                 <tr>
                     <th>NO</th>
                     <th>CENTER</th>
                     <th>HARI</th>
                     <th>TANGGAL</th>
                     <th>WARNA CENTER</th>
                     <th>KETERANGAN</th>
                     <th>STAFF</th>
                 </tr>
             </thead>
             <tbody>
                 <?php
                    $qq = "SELECT * from center_kosong ck 
                 join center c on ck.no_center=c.no_center
                 join karyawan k on k.id_karyawan=ck.id_karyawan
                 where ck.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' and c.id_cabang='$id_cabang'
                 and (ck.tgl_transaksi = '$tglawal') and ck.no_center in(select no_center from center_kosong where id_cabang='$id_cabang' and tgl_transaksi='$tglbanding_awal' group by no_center)  group by ck.no_center order by k.nama_karyawan asc, c.hari, tgl_transaksi desc
                 ";
                    echo $qq;
                    $q = mysqli_query($con, "$qq");
                    echo mysqli_error($con);
                    $no = 1;
                    while ($r = mysqli_fetch_assoc($q)) {
                    ?>
                     <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $r['no_center'] ?></td>
                        <td><?= $r['hari'] ?></td>
                        <td><?= $r['tgl_transaksi'] ?></td>
                        <td><?= $r['status_center'] ?></td>
                        <td></td>
                        <td><?= $r['nama_karyawan'] ?></td>
                    </tr>
                     <?php
                    }
                        ?>
             </tbody>
         </table> -->

    </div>

</div>
<!-- Button trigger modal -->