<div class='content table-responsive'>
    <h2 class='page-header'>LIST TRANSFER
    </h2>
    <a href="<?= $url . $menu ?>transfer_input" class="btn btn-danger">Tambah</a>
    <?php
    if (isset($_GET['tglawal']) || isset($_GET['tglakhir'])) {
        $tglawal = $_GET['tglawal'];
        $tglakhir = $_GET['tglakhir'];
    } else {
        $tglawal = date("Y-m-d", strtotime('-4 day', strtotime(date("Y-m-d"))));
        $tglakhir = date("Y-m-d");
    }
    ?>
    <form action="">
        <input type="hidden" name='menu' value='transfer_list' />
        <input type="date" name='tglawal' value="<?= (isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d")) ?>" class="" />
        <input type="date" name='tglakhir' value="<?= (isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d")) ?>" class="" />
        <input type='submit' class="btn btn-info" name='cari' value='FILTER' />
        <a href="<?= $url . $menu ?>transfer_list" class="btn btn-info">Tampilkan semua</a>
        <a href="<?= $url ?>print_tf.php?tgl_awal=<?= $tglawal ?>&tgl_akhir=<?= $tglakhir ?>" target='_BLANK' class="btn btn-success">Print</a>

    </form>


    <form action="" method="post">


        <table class="table">
            <tr>
                <th>NO</th>
                <th>PHOTO</th>
                <th>TANGGAL</th>
                <th>TRANSFER</th>
                <th>KETERANGAN</th>
                <th>DETAIL</th>
                <th>STAFF</th>
                <th>STATUS</th>
                <th>#</th>
                <th>#</th>
            </tr>
            <?php
            $qtambah = "";
            if (isset($_GET['cari'])) {
                $qtambah = "and tanggal between '$tglawal' and '$tglakhir'";
            }
            $q = mysqli_query($con, "select * from bukti_tf join karyawan on karyawan.id_karyawan=bukti_tf.id_karyawan where bukti_tf.id_cabang='$id_cabang' $qtambah order by tanggal desc");
            while ($r = mysqli_fetch_assoc($q)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <a href="<?= $url . "assets/tf/" . $r['nama_file'] ?>" target='_blank'>
                            <img src="<?= $url . "assets/tf/" . $r['nama_file'] ?>" width='100px' class='img img-fluid' />
                        </a>
                    </td>
                    <td><?= $r['tanggal'] ?></td>
                    <td><?= rupiah($r['total_nominal']) ?></td>
                    <td><?= $r['keterangan'] ?></td>
                    <td>
                        <table>

                            <?php
                            $q1 = mysqli_query($con, "SELECT * from detail_tf where id_bukti='$r[id_bukti]'");
                            while ($row = mysqli_fetch_assoc($q1)) {
                            ?>
                                <tr>
                                    <td><?= $row['center'] ?>/<?= $row['kelompok'] ?>-</td>
                                    <td><?= $row['nama_nasabah'] ?>-</td>
                                    <td><?= rupiah($row['total']) ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <?= $r['nama_karyawan'] ?>
                    </td>
                    <td><?= strtoupper($r['status']) ?></td>
                    <td>
                        <input type="checkbox" name="id_tf[]" value='<?= $r['id_bukti'] ?>' class='btn btn-lg form-control' id="">

                    </td>
                    <td>
                        <?php
                        if ($r['status'] == 'pending') {
                        ?>
                            <a href="<?= $url . $menu . "transfer_input&id_tf=" . $r['id_bukti'] ?>" class="btn btn-danger">
                                <i class="fa fa-pencil"></i>
                            </a>
                        <?php
                        } else {
                        ?>
                            <a href="<?= $url . $menu . "transfer_input&id_tf=" . $r['id_bukti'] ?>" class="btn btn-success">Q</a>
                        <?php

                        }
                        ?>

                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </form>

</div>