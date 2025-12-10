<table id='data_center'>
    <thead>
        <tr>
            <th>NO</th>
            <th>Nama Kuis</th>
            <th>Penerbit</th>
            <th>Tanggal</th>
            <th>Lama Pengerjaan</th>
            <th>Jumlah</th>
            <th>status</th>
            <th>Mengisi</th>
            <th>#</th>

        </tr>
    </thead>

    <tbody>
        <?php
        $q = mysqli_query($con, "select * from kuis where kuis.id_cabang='$id_cabang' order by tgl_kuis desc, id_kuis desc");
        while ($kuis = mysqli_fetch_assoc($q)) {
            $total_soal = mysqli_query($con, "select count(*) as total from kuis_soal where id_kuis='$kuis[id_kuis]'");
            $total_soal = mysqli_fetch_assoc($total_soal)['total'];
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $kuis['nama_kuis'] ?></td>
                <td><?= $kuis['nama_karyawan'] ?></td>
                <td><?= $kuis['tgl_kuis'] ?></td>
                <td><?= $kuis['waktu'] ?> menit</td>
                <td><?= $total_soal ?> soal</td>
                <td><?= $kuis['status'] ?></td>
                <td>
                    <?php
                    $qhitung = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(*) as total from kuis_jawab where id_kuis='$kuis[id_kuis]' and status='selesai'"))['total'];
                    echo $qhitung;
                    ?>

                </td>
                <td>

                    <a href="<?= $url . $menu ?>kuis&act=hasil&idkuis=<?= $kuis['id_kuis'] ?>" class="btn btn-success"> Hasil </a>
                    <a href="<?= $url . $menu ?>kuis&act=tambah-soal&idkuis=<?= $kuis['id_kuis'] ?>" class="btn btn-danger"> kelola soal </a>
                    <a href="<?= $url . $menu ?>kuis&act=kuis&edit&idkuis=<?= $kuis['id_kuis'] ?>" class="btn btn-primary"> <i class="fa fa-gears"></i> </a>
                    <a href="<?= $url . $menu ?>kuis&act=kuis&hapus&idkuis=<?= $kuis['id_kuis'] ?>" class="btn btn-danger" onclick="return window.confirm('yakin untuk menghapus kuis ini??')"> <i class="fa fa-times"></i> </a>

                </td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>