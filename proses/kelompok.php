<div class='content table-responsive'>
    <?php
    if (isset($_GET['tglawal']) || isset($_GET['tglakhir'])) {
        $tglawal = $_GET['tglawal'];
        $tglakhir = $_GET['tglakhir'];
    } else {
        $tglawal = date("2021-01-01");
        $tglakhir = date("2021-12-31");
        // dd
    }
    ?>
    <h2 class='page-header'> KELOMPOK </h2>
    <i>Kelompok/Groups melihat capaian per groups</i>
    <hr />
    <?php
    $qGrup = mysqli_query($con, "select * from `group`   where id_cabang='$cabang' order by nama_group asc");
    while ($tampilGrup = mysqli_fetch_array($qGrup)) {

    ?>
        <div class="col-md-4 ">
            <div class="card">
                <div class="card-header">
                    Kelompok : <b> <?php echo strtoupper($tampilGrup['nama_group']) ?> </b>
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                        <p>
                        <table class='table ' style="font-size: 12px;">
                            <tr>
                                <td>Nama</td>
                                <td>Masuk</td>
                                <td>Keluar</td>
                                <td>Nett</td>
                                <td>Target</td>
                            </tr>
                            <?php
                            $total_masuk = 0;
                            $total_keluar = 0;
                            $total_nett = 0;
                            $total_target = 0;
                            $staff = mysqli_query($con, "SELECT * FROM `group_user` LEFT JOIN karyawan ON `group_user`.`id_karyawan`=karyawan.`id_karyawan` WHERE `group_user`.`id_group`='$tampilGrup[id_group]' order by karyawan.nama_karyawan");
                            while ($tampilStaff = mysqli_fetch_array($staff)) {

                                $q = "SELECT sum(anggota.anggota_masuk) as masuk,
                        sum(anggota.anggota_keluar) as keluar,
                        sum(anggota.net_anggota) as nett,
                        sum(anggota.psa) as psa,
                        sum(anggota.ppd) as ppd,
                        sum(anggota.prr) as prr,
                        sum(anggota.arta) as arta,
                        sum(anggota.pmb) as pmb,
                        karyawan.nama_karyawan FROM `anggota`,karyawan 
                        where anggota.id_karyawan=karyawan.id_karyawan and karyawan.id_cabang=$cabang 
                        and anggota.tgl_anggota >= '$tglawal' and anggota.tgl_anggota <= '$tglakhir' and
                        karyawan.id_karyawan='$tampilStaff[id_karyawan]'
                        GROUP by anggota.id_karyawan order by karyawan.nama_karyawan asc";
                                $tampilData = mysqli_query($con, $q);
                                $tampilData = mysqli_fetch_array($tampilData);

                                $q2 = "SELECT sum(cashflow.cashflow_masuk) as masuk,
                        sum(cashflow.cashflow_keluar) as keluar,
                        sum(cashflow.net_cashflow) as nett,
                        sum(cashflow.psa) as psa,
                        sum(cashflow.ppd) as ppd,
                        sum(cashflow.prr) as prr,
                        sum(cashflow.arta) as arta,
                        sum(cashflow.pmb) as pmb,
                        karyawan.nama_karyawan FROM `cashflow`,karyawan 
                        where cashflow.id_karyawan=karyawan.id_karyawan and karyawan.id_cabang=$cabang 
                        and cashflow.tahun_cashflow >= '$tglawal' and cashflow.tahun_cashflow <= '$tglakhir' and
                        karyawan.id_karyawan='$tampilStaff[id_karyawan]'
                        GROUP by cashflow.id_karyawan order by karyawan.nama_karyawan asc";
                                $cashflow = mysqli_query($con, $q2);
                                $cashflow = mysqli_fetch_array($cashflow);



                            ?>
                                <tr>
                                    <td><?= $tampilStaff['nama_karyawan'] ?></td>
                                    <td><?= $masuk = $tampilData['masuk'] ?></td>
                                    <td><?= $keluar = $tampilData['keluar'] ?></td>
                                    <td><?php echo $nett = $tampilData['nett'] ?></td>
                                    <td><?php echo $target = $cashflow['nett'];
                                        $persen = round(($nett / $target) * 100);
                                        echo "($persen)%";
                                        ?></td>
                                </tr>
                            <?php
                                $total_masuk = $masuk + $total_masuk;
                                $total_keluar = $keluar + $total_keluar;
                                $total_nett = $nett + $total_nett;
                                $total_target = $target + $total_target;
                            }
                            ?>
                            <tr>
                                <td colspan="">Total Kelompok</td>
                                <td><?php echo $total_masuk ?></td>
                                <td><?php echo $total_keluar ?></td>
                                <td><?php echo $total_nett ?></td>
                                <td><?php echo $total_target ?>(<?php echo $total_persen = round(($total_nett / $total_target) * 100) ?>%)</td>
                            </tr>
                        </table>
                        </p>
                        <?php
                        $persentasi = (int)$total_persen;
                        $kurang = $total_target - $total_nett;
                        if ($persentasi > 100) {
                            $keterangan = "Kelompok $tampilGrup[nama_group], telah melampaui target";
                        } else if ($persentasi >= 80 && $persentasi < 100) {
                            $keterangan = "Kelompok $tampilGrup[nama_group] hampir mencapai target, Semangat <cite title='Source Title'>Kurang " . $kurang . " anggota </cite>";
                        } else if ($persentasi >= 50 && $persentasi < 80) {
                            $keterangan = "Semangat terus, masih kurang $kurang anggota nih";
                        } else {
                            $keterangan = "Masih jauh nih, semangat akhir tahun 100%";
                        }

                        ?>
                        <footer class="blockquote-footer"><?= $keterangan ?></footer>
                    </blockquote>
                </div>
            </div>

        </div>
    <?php
    }
    ?>
</div>