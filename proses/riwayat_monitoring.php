<h2>RIWAYAT MONITORING</h2>
<form method='get' action='<?php echo $url . $menu ?>monitoring'>
            <input type=hidden name='menu' value="monitoring" />
            <input type=hidden name='riwayat' />
            Tanggal <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' />
            <input type=submit name='cari' value='CARI' />
        </form>
    

<TABLE class='table' id='riwayat_monitoring'>
    <thead>
        <tr>
            <th>waktu</th>
            <th>Staff</th>
            <th>NO Pinjaman</th>
            <th>Nama</th>
            <th>Jumlah Pinjaman</th>
            <th>Produk</th>
            <th>Cair</th>

            <th>TGL MTR</th>
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
        $q = mysqli_query($con, "select *,DATEDIFF(CURDATE(), tgl_cair) as total_hari from monitoring 
            left join pinjaman on pinjaman.id_pinjaman=monitoring.id_pinjaman
            left join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan 
                            
                            where pinjaman.id_cabang='$id_cabang' and monitoring.tgl_monitoring='$tgl' order by monitoring.waktu  desc");
        while ($pinj = mysqli_fetch_array($q)) {
            if ($pinj['total_hari'] > 14) {
                $tr = "#ffd4d4";
            } else $tr = "#fffff";
        ?>
            <tr style="background:<?= $tr ?>">
                <td><?= $pinj['waktu'] ?></td>
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
                        $icon = "";
                    } elseif ($pinj['monitoring'] == 'sudah') {

                        $tombol = "btn-info";
                        $icon = $pinj['tgl_monitoring'];
                    } else $tombol = "btn-danger";
                    ?>
                    <span class="pull-right" id='loading_<?= $pinj['id_pinjaman'] ?>' class="badge rounded-pill bg-danger"></span>
                    <?= $icon ?>

                </td>
                <td>
                    <a href="#modalku1" id="custId" class="btn btn-warning" data-toggle="modal" data-id="<?= $pinj['id_pinjaman'] ?>">Detail</a>
                    <input type="button" id="cek_<?= $pinj['id_pinjaman'] ?>" class='btn <?= $tombol ?>' value='<?= $pinj['monitoring'] ?>' onclick="monitoring('<?= $pinj['id_pinjaman'] ?>','<?= $pinj['id_detail_pinjaman'] ?>')" id="">

                </td>
            </tr>

        <?php
        }
        ?>
    </tbody>
    </form>
</TABLE>