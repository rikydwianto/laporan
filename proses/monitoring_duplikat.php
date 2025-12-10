<h2>DUPLIKAT MONITORING</h2>

<TABLE class='table' id='riwayat_monitoring'>
    <thead>
        <tr>
            <th>Staff</th>
            <th>NO Pinjaman</th>
            <th>Nama</th>
            <th>Jumlah Pinjaman</th>
            <th>Produk</th>
            <th>Cair</th>

            <th>#</th>

        </tr>
    </thead>
    <tbody>

        <?php
        @$tgl = $_GET['tgl'];

        if (empty($tgl)) {
            $tgl = date("Y-m-d");
        } else {
            $tgl = $tgl;
        }
        $q = mysqli_query($con, "SELECT *,DATEDIFF(CURDATE(), tgl_cair) AS total_hari FROM pinjaman 
        LEFT JOIN karyawan ON karyawan.id_karyawan=pinjaman.id_karyawan 
 WHERE pinjaman.id_cabang='$id_cabang' AND 
       pinjaman.id_detail_pinjaman IN (SELECT pinjaman.id_detail_pinjaman FROM `pinjaman`
             GROUP BY  pinjaman.id_detail_pinjaman HAVING COUNT(*) > 1) ORDER BY pinjaman.nama_nasabah
");
        while ($pinj = mysqli_fetch_assoc($q)) {
            if ($pinj['total_hari'] > 14) {
                $tr = "#ffd4d4";
            } else $tr = "#fffff";
        ?>
            <tr style="background:<?= $tr ?>">
                <td><?= $pinj['nama_karyawan'] ?></td>
                <td><?= ganti_karakter($pinj['id_detail_pinjaman']) ?></td>
                <td>
                    <?= $pinj['nama_nasabah'] ?>


                </td>
                <td><?= $pinj['jumlah_pinjaman'] ?></td>
                <td>


                    <?php

                    $produk = strtolower($pinj['produk']);
                    if ($produk == "pinjaman umum") $kode = "P.U";
                    else if ($produk == "pinjaman sanitasi") $kode = "PSA";
                    else if ($produk == "pinjaman mikrobisnis") $kode = "PMB";
                    else if ($produk == "pinjaman arta") $kode = "ARTA";
                    else if ($produk == "pinjaman dt. pendidikan") $kode = "PPD";
                    else if ($produk == "pinjaman renovasirumah") $kode = "PRR";
                    else $kode = "LL";

                    echo $kode; ?>(<?= $pinj['pinjaman_ke'] ?>)
                </td>
                <td><?= $pinj['tgl_cair'] ?></td>

                <td>


                    <?php
                    if ($pinj['monitoring'] == 'belum') {
                        $tombol = "btn-danger";
                    } elseif ($pinj['monitoring'] == 'sudah') {

                        $tombol = "btn-info";
                    } else $tombol = "btn-danger";
                    ?>
                    <span class="pull-right" id='loading_<?= $pinj['id_pinjaman'] ?>' class="badge rounded-pill bg-danger"></span>
                    <a href="#modalku1" id="custId" class="btn btn-warning" data-toggle="modal" data-id="<?= $pinj['id_pinjaman'] ?>">Detail</a>
                    <a href="<?= $url . $menu . "monitoring&ref=duplikat&hapus&id=" . $pinj['id_pinjaman'] ?>" class="btn btn-danger"> <i class="fa fa-times"></i> </a>
                    <input type="button" id="cek_<?= $pinj['id_pinjaman'] ?>" class='btn <?= $tombol ?>' value='<?= $pinj['monitoring'] ?>' onclick="monitoring('<?= $pinj['id_pinjaman'] ?>','<?= $pinj['id_detail_pinjaman'] ?>')" id="">

                </td>
            </tr>

        <?php
        }
        ?>
    </tbody>
    </form>
</TABLE>