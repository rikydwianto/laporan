<div class="col-md-12">
    <div class="col-md-12">

        <h3>DATA PAR</h3>
        <table class='table table-bordered'>
            <tr>
                <th>NO</th>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>TOTAL AGT PAR</th>
                <th>CTR PAR</th>
                <th>OS PAR</th>
                <th>#</th>
            </tr>
            <?php
            // OPTIMASI: Gabungkan COUNT DISTINCT center dalam satu query menggunakan subquery
            $q_tgl1 = mysqli_query($con, "
                SELECT  
                    d.tgl_input,
                    COUNT(*) as hitung, 
                    SUM(d.sisa_saldo) as total_par,
                    (SELECT COUNT(DISTINCT no_center) 
                     FROM deliquency 
                     WHERE id_cabang = '$id_cabang' 
                     AND tgl_input = d.tgl_input) as total_center
                FROM deliquency d 
                WHERE d.id_cabang='$id_cabang' 
                GROUP BY d.tgl_input 
                ORDER BY d.tgl_input DESC
            ");
            
            while ($cari = mysqli_fetch_assoc($q_tgl1)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $cari['tgl_input'] ?></td>
                    <td><?= format_hari_tanggal($cari['tgl_input']) ?></td>
                    <td><?= $cari['hitung'] ?></td>
                    <td><?= $cari['total_center'] ?></td>
                    <td><?= rupiah($cari['total_par']) ?></td>
                    <td>
                        <a href="<?= $url . $menu ?>par&list&munculkan&tgl=<?= $cari['tgl_input'] ?>" class="btn btn-success"> <i class="fa fa-eye"></i> Tampilkan Detail</a>
                        <a href="#" onclick="buka('popup/par.php?tgl=<?= $cari['tgl_input'] ?>')" class="btn btn-primary"> <i class="fa fa-list"></i> Tampilkan Semua</a>
                        <a href="<?= $url . $menu ?>par&delete&tgl=<?= $cari['tgl_input'] ?>" onclick="return window.confirm('apakah yakin menghapus semua data <?= format_hari_tanggal($cari['tgl_input']) ?>')" class="btn btn-danger"> <i class="fa fa-times"></i> </a>
                    </td>
                </tr>

                <?php
                // OPTIMASI: Ambil data minggu hanya sekali dan simpan dalam array
                $minggu_list = [];
                $qminggu = mysqli_query($con, "
                    SELECT DISTINCT minggu as minggu1 
                    FROM deliquency 
                    WHERE tgl_input='$cari[tgl_input]' 
                    AND id_cabang='$id_cabang' 
                    ORDER BY minggu ASC
                ");
                while ($rminggu = mysqli_fetch_assoc($qminggu)) {
                    $minggu_list[] = $rminggu['minggu1'];
                }

                if (isset($_GET['munculkan']) && isset($_GET['tgl']) && $_GET['tgl'] == $cari['tgl_input']) {
                ?>
                        <tr>
                            <td colspan="7">
                                <table class='table table-bordered'>
                                    <tr>
                                        <th>MINGGU</th>
                                        <?php foreach ($minggu_list as $minggu_val) { ?>
                                            <th>
                                                <a href="#" onclick="buka('popup/par.php?tgl=<?= $cari['tgl_input'] ?>&minggu=<?= $minggu_val ?>')" target="_blank">
                                                    <?= $minggu_val ?>
                                                </a>
                                            </th>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>JML</td>
                                        <?php
                                        // OPTIMASI: Ambil semua total minggu dalam satu query menggunakan GROUP BY
                                        $minggu_totals = [];
                                        $query_minggu_total = mysqli_query($con, "
                                            SELECT minggu, COUNT(id) as total 
                                            FROM deliquency 
                                            WHERE id_cabang='$id_cabang' 
                                            AND tgl_input='$cari[tgl_input]' 
                                            GROUP BY minggu
                                            ORDER BY minggu ASC
                                        ");
                                        while ($row = mysqli_fetch_assoc($query_minggu_total)) {
                                            $minggu_totals[$row['minggu']] = $row['total'];
                                        }
                                        
                                        foreach ($minggu_list as $minggu_val) {
                                            $total_minggu = isset($minggu_totals[$minggu_val]) ? $minggu_totals[$minggu_val] : 0;
                                        ?>
                                            <td><?= $total_minggu ?></td>
                                        <?php } ?>
                                    </tr>
                                </table>

                                <table class='table table-bordered'>
                                    <tr>
                                        <th>NO</th>
                                        <th>TAHUN</th>
                                        <th>BULAN</th>
                                        <th>TOTAL</th>
                                        <th>TOTAL OS</th>
                                        <th>#</th>
                                    </tr>
                                    <?php
                                    $no = 1;
                                    $qt = mysqli_query($con, "
                                        SELECT 
                                            YEAR(tgl_disburse) as tahun,
                                            MONTH(tgl_disburse) as bulan,
                                            SUM(sisa_saldo) as total_os, 
                                            COUNT(*) as total
                                        FROM deliquency 
                                        WHERE id_cabang='$id_cabang' 
                                        AND tgl_input='$cari[tgl_input]' 
                                        GROUP BY YEAR(tgl_disburse), MONTH(tgl_disburse) 
                                        ORDER BY tahun DESC, bulan DESC
                                    ");
                                    while ($rtgl_dis = mysqli_fetch_assoc($qt)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $rtgl_dis['tahun'] ?></td>
                                            <td><?= bulan_indo($rtgl_dis['bulan']) ?></td>
                                            <td><?= $rtgl_dis['total'] ?></td>
                                            <td><?= rupiah($rtgl_dis['total_os']) ?></td>
                                            <td>
                                                <a href="#" onclick="buka('popup/par.php?tgl=<?= $cari['tgl_input'] ?>&bulan=<?= $rtgl_dis['tahun'] . '-' . sprintf('%02d', $rtgl_dis['bulan']) ?>')" class="btn btn-success"> Detail </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>

                                <br>
                            </td>
                        </tr>
                <?php
                }
                ?>
            <?php
            }
            ?>
        </table>
    </div>

</div>