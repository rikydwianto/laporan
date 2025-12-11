<style>
    .tengah {
        text-align: center;
        font-weight: bold;
    }

    .kotak {
        width: 50px;
    }
</style>
<div class='content table-responsive'>
    <h2 class='page-header'>MONITORING </h2>
    <i>Tambah, kumpulkan monitoring, </i> <br />
    <a href="<?= $url . $menu ?>monitoring" class='btn btn-success'> <i class="fa fa-eye"></i> Lihat</a>
    <a href="<?= $url . $menu ?>monitoring&tambah" class='btn btn-info'> <i class="fa fa-plus"></i> Tambah</a>
    <a href="<?= $url . $menu ?>monitoring&nasabah_staff" class='btn btn-info'> <i class="fa fa-users"></i> Nasabah
        Staff</a>
    <a href="<?= $url . $menu ?>monitoring&rekap_monitoring_bulan" class='btn btn-success'> <i class="fa fa-list"></i>
        Rekap Bulan</a>
    <!-- <a href="<?= $url . $menu ?>monitoring&kosong" class='btn btn-danger' onclick="return window.confirm('Apakah anda yakin untuk hapus semuadata monitoring??')"> <i class="fa fa-trash"></i> Kosongkan</a> -->
    <a href="<?= $url . $menu ?>monitoring&ganti" class='btn btn-success'> <i class="fa fa-wrench"></i> Synchron
        Data</a>
    <a href="<?= $url . $menu ?>monitoring&staff" class='btn btn-danger'> <i class="fa fa-users"></i> Total
        Monitoring</a>
    <a href="<?= $url . $menu ?>monitoring&pu" class='btn btn-danger'> <i class="fa fa-users"></i> Detail Pinjaman
        umum</a>
    <a href="<?= $url . $menu ?>monitoring&daftar_pinjaman" class='btn btn-success'> <i class="fa fa-list"></i> Daftar
        Pinjaman</a>
    <a href="<?= $url . $menu ?>monitoring&input_tpk" class='btn btn-danger'> <i class="fa fa-plus"></i> Input TPK</a>
    <a href="<?= $url . $menu ?>monitoring" onclick="buka()" class='btn btn-info'> <i class="fa fa-file-excel-o"></i>
        Export</a>
    <a href="<?= $url . $menu ?>monitoring&importan" class='btn btn-danger'> <i class="fa fa-gear"></i> IMPORTANT</a>
    <?php
    if (isset($_SESSION['nama_file'])) {
    ?>
        <a href="<?= $url ?>export/excel/<?= $_SESSION['nama_file'] ?>.xlsx" class='btn btn-info'> <i
                class="fa fa-download"></i> UNDUH</a>
    <?php
    }
    ?>
    <hr />



    <?php




    if (isset($_GET['tambah'])) {
        include("./proses/monitoring_tambah.php");
    } elseif (isset($_GET['kosong'])) {
        mysqli_query($con, "DELETE FROM `pinjaman` WHERE `id_cabang` = '$id_cabang'");
        pindah("$url$menu" . 'monitoring');
    } else if (isset($_GET['hapus'])) {
        $id = aman($con, $_GET['id']);
        $detail = aman($con, $_GET['detail']);

        $q = mysqli_query($con, "DELETE FROM `pinjaman` WHERE `id_pinjaman` = '$id' ; ");
        if (isset($_GET['ref'])) {
            $ref = $_GET['ref'];
            pindah("$url$menu" . "monitoring&" . $ref);
        } else {
            pindah("$url$menu" . 'monitoring&banding');
        }
    } else if (isset($_GET['tutupbanding'])) {
        $id = aman($con, $_GET['id']);
        $detail = aman($con, $_GET['detail']);
        if (isset($_POST['kirim'])) {
            $pesan = aman($con, $_POST['pesan_balik']);


            $q = mysqli_query($con, "UPDATE `banding_monitoring` SET `status` = 'selesai', pesan='$pesan' WHERE `id_banding_monitoring` = '$id'; ");
            pindah("$url$menu" . 'monitoring&banding');
        }
        $qdetail = mysqli_query($con, "select * from pinjaman join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan join banding_monitoring on banding_monitoring.id_detail_pinjaman=pinjaman.id_detail_pinjaman where pinjaman.id_detail_pinjaman='$detail' ");
        $detail_pinjaman = mysqli_fetch_assoc($qdetail);
    ?>
        <form action="" method="post">
            <table class='table'>
                <tr>
                    <td>ID PINJAMAN</td>
                    <td><?= $detail ?></td>
                </tr>
                <tr>
                    <td>NAMA</td>
                    <td><?= $detail_pinjaman['nama_nasabah'] ?></td>
                </tr>
                <tr>
                    <td>STAFF</td>
                    <td><?= $detail_pinjaman['nama_karyawan'] ?></td>
                </tr>
                <tr>
                    <td>KELUHAN</td>
                    <td><?= $detail_pinjaman['keterangan_banding'] ?></td>
                </tr>
                <tr>
                    <td>PESAN BALIK</td>
                    <td>
                        <textarea name="pesan_balik" id="" cols="30" rows="10" class='form-control'
                            placeholder="Kirim pesan keterangan pada Staff"></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" class='btn btn-danger' name="kirim" value='KIRIM dan dan tutup laporan?'>
                    </td>
                </tr>
            </table>
        </form>
    <?php
    } elseif (isset($_GET['staff'])) {
        include "proses/total_monitoring_staff.php";
    } elseif (isset($_GET['pu'])) {
        $tgl = isset($_GET['tgl']) ? mysqli_real_escape_string($con, $_GET['tgl']) : date("Y-m-d");
    ?>
        <form method='get' action='<?php echo $url . $menu ?>monitoring'>
            <input type=hidden name='menu' value="monitoring" />
            <input type=hidden name='pu' />
            Sampai Dengan <input type=date name='tgl' value='<?= $tgl ?>' />
            <input type=submit name='cari' value='CARI' />
        </form>
        <br>
        <table class="table table-bordered">
            <tr style='background:#c8c9cc'>
                <th>NO</th>
                <th>STAFF</th>
                <?php
                $col  = 0;
                $data_pu = array();
                $pin = mysqli_query($con, "SELECT pinjaman_ke FROM pinjaman WHERE produk='PINJAMAN UMUM' and id_cabang='$id_cabang'  and input_mtr='sudah' GROUP BY pinjaman_ke ");
                while ($ke = mysqli_fetch_assoc($pin)) {
                    $col++;
                ?>
                    <th class='tengah'><?= $ke['pinjaman_ke'] ?></th>
                <?php
                }
                ?>

                <th class='tengah'>TOTAL </th>
            </tr>
            <?php
            $total_monitoring = 0;

            // OPTIMASI: Gunakan LEFT JOIN dan GROUP BY untuk menghitung semua sekaligus
            $cek_ka = mysqli_query($con, "
                SELECT 
                    k.id_karyawan,
                    k.nama_karyawan,
                    p.pinjaman_ke,
                    COUNT(p.id_pinjaman) as total_monitoring
                FROM karyawan k
                INNER JOIN jabatan j ON j.id_jabatan = k.id_jabatan
                LEFT JOIN pinjaman p ON p.id_karyawan = k.id_karyawan 
                    AND p.produk = 'PINJAMAN UMUM' 
                    AND p.tgl_cair <= '$tgl'
                    AND p.monitoring = 'belum'
                    AND p.input_mtr = 'sudah'
                    AND p.id_cabang = '$id_cabang'
                WHERE k.id_cabang = '$id_cabang' 
                    AND j.singkatan_jabatan = 'SL' 
                    AND k.status_karyawan = 'aktif'
                GROUP BY k.id_karyawan, k.nama_karyawan, p.pinjaman_ke
                ORDER BY k.nama_karyawan ASC
            ");

            // Reorganize data by karyawan
            $data_karyawan = [];
            while ($row = mysqli_fetch_assoc($cek_ka)) {
                $id_karyawan = $row['id_karyawan'];
                if (!isset($data_karyawan[$id_karyawan])) {
                    $data_karyawan[$id_karyawan] = [
                        'nama_karyawan' => $row['nama_karyawan'],
                        'pinjaman' => []
                    ];
                }
                if ($row['pinjaman_ke'] !== null) {
                    $data_karyawan[$id_karyawan]['pinjaman'][$row['pinjaman_ke']] = $row['total_monitoring'];
                }
            }

            $no = 1;
            foreach ($data_karyawan as $id_karyawan => $karyawan) {
            ?>
                <tr>
                    <th><?= $no ?></th>
                    <th><?= $karyawan['nama_karyawan'] ?></th>
                    <?php
                    $total_hitung = 0;
                    mysqli_data_seek($pin, 0);
                    while ($ke = mysqli_fetch_assoc($pin)) {
                        $hitung = isset($karyawan['pinjaman'][$ke['pinjaman_ke']]) ? $karyawan['pinjaman'][$ke['pinjaman_ke']] : 0;
                        $total_hitung += $hitung;
                    ?>
                        <th class='tengah kotak'><?= $hitung ?></th>
                    <?php
                    }
                    $total_monitoring += $total_hitung;
                    ?>
                    <th class='tengah'><?= $total_hitung ?></th>
                </tr>
            <?php
                $no++;
            }
            ?>
            <tr style='background:#c8c9cc'>
                <td class='tengah' colspan='<?= 2 ?>'>TOTAL</td>
                <?php
                mysqli_data_seek($pin, 0);
                while ($ke = mysqli_fetch_assoc($pin)) {
                    $hitung_ = mysqli_query($con, "
                        SELECT COUNT(monitoring) AS monitoring 
                        FROM pinjaman 
                        WHERE produk='PINJAMAN UMUM' 
                            and pinjaman_ke='$ke[pinjaman_ke]' 
                            and monitoring='belum'  
                            and input_mtr='sudah' 
                            and tgl_cair <='$tgl' 
                            and id_cabang='$id_cabang'
                    ");
                    $hitung_ = mysqli_fetch_assoc($hitung_);
                ?>
                    <td class='tengah'><?= $hitung_['monitoring'] ?></td>
                <?php
                }
                ?>
                <td class='tengah'><?= $total_monitoring ?></td>
            </tr>

        </table>
    <?php

    } else if (isset($_GET['ganti'])) {

        // --- BAGIKAN DATA MONITORING ---
        if (isset($_GET['bagikan']) && isset($_GET['tgl_cair'])) {
            $tgl_baru = mysqli_real_escape_string($con, $_GET['tgl_cair']);
            mysqli_query($con, "
            UPDATE pinjaman 
            SET input_mtr = 'sudah' 
            WHERE tgl_cair = '$tgl_baru' 
            AND id_cabang = '$id_cabang'
        ");
        }

        // --- HAPUS DATA MONITORING ---
        if (isset($_GET['delete']) && isset($_GET['tgl_cair'])) {
            $tgl_baru = mysqli_real_escape_string($con, $_GET['tgl_cair']);
            mysqli_query($con, "
            DELETE FROM pinjaman 
            WHERE tgl_cair = '$tgl_baru' 
            AND id_cabang = '$id_cabang'
        ");
        }
    ?>
        <form action="" method="post">

            <h3>SINKRON NAMA</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA MDIS</th>
                        <th>NAMA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;

                    // --- PROSES PERBAIKAN DATA ---
                    if (isset($_POST['ganti'])) {

                        alert("Sedang memproses... Harap tunggu");

                        $karyawan = $_POST['karyawan'];
                        $mdis     = $_POST['nama_mdis'];
                        var_dump($mdis);

                        // Perbaikan tanggal untuk data yang belum punya id_karyawan
                        $mon = mysqli_query($con, "
                    SELECT * FROM pinjaman 
                    WHERE id_karyawan IS NULL 
                    AND id_cabang = '$id_cabang'
                ");

                        alert("Memperbaiki tanggal pencairan... Harap tunggu");
                        while ($moni = mysqli_fetch_assoc($mon)) {
                            $tgl = explode("-", $moni['tgl_pencairan']);
                            $new_tgl = "{$tgl[2]}-{$tgl[1]}-{$tgl[0]}";

                            mysqli_query($con, "
                        UPDATE pinjaman 
                        SET tgl_cair = '$new_tgl'  
                        WHERE id_pinjaman = '{$moni['id_pinjaman']}'
                    ");
                        }



                        // for ($i = 0; $i < count($mdis); $i++) {
                        //     $qr = "UPDATE pinjaman 
                        //                             SET staff = NULL, id_karyawan = '{$karyawan[$i]}' 
                        //                             WHERE staff = '{$mdis[$i]}' 
                        //                             AND id_cabang = '$id_cabang'";
                        //     mysqli_query($con, $qr);
                        //     alert("query: $qr");
                        // }
                        foreach ($mdis as $index => $nama_mdis) {
                            $id_karyawan = $karyawan[$index];
                            if (!empty($id_karyawan)) {
                                $qr = "UPDATE pinjaman 
                                        SET staff = NULL, id_karyawan = '$id_karyawan' 
                                        WHERE staff = '$nama_mdis' 
                                        AND id_cabang = '$id_cabang'";
                                mysqli_query($con, $qr);
                                // alert("Memperbaiki nama staff $nama_mdis... Harap tunggu queri: $qr");
                                // echo "query: $qr<br/>";
                            }

                        }

                        // Input ke tabel disburse
                        $update = mysqli_query($con, "
                    SELECT * FROM pinjaman 
                    WHERE input_disburse = 'belum' 
                    AND id_cabang = '$id_cabang'
                ");

                        while ($upd = mysqli_fetch_assoc($update)) {

                            mysqli_query($con, "
                        INSERT INTO disburse (disburse, tgl_disburse, id_karyawan, id_cabang)
                        VALUES ('{$upd['jumlah_pinjaman']}', '{$upd['tgl_cair']}', '{$upd['id_karyawan']}', '$id_cabang')
                    ");

                            mysqli_query($con, "
                        UPDATE pinjaman 
                        SET input_disburse = 'sudah'  
                        WHERE id_pinjaman = '{$upd['id_pinjaman']}' 
                        AND id_cabang = '$id_cabang'
                    ");
                        }

                        // Input ke tabel anggota
                        $qagt = mysqli_query($con, "
                    SELECT * FROM pinjaman 
                    WHERE id_cabang = '$id_cabang' 
                    AND input_agt = 'belum' 
                    AND produk != 'Pinjaman Umum'
                ");

                        while ($p = mysqli_fetch_assoc($qagt)) {

                            $produk = strtolower($p['produk']);
                            $field = "";

                            if ($produk == "pinjaman sanitasi") $field = "psa";
                            else if ($produk == "pinjaman mikrobisnis") $field = "pmb";
                            else if ($produk == "pinjaman arta") $field = "arta";
                            else if ($produk == "pinjaman dt. pendidikan") $field = "ppd";
                            else if ($produk == "pinjaman renovasirumah") $field = "prr";

                            if ($field != "") {

                                // Cek apakah anggota sudah ada
                                $qcari = mysqli_query($con, "
                            SELECT * FROM anggota 
                            WHERE id_cabang = '$id_cabang' 
                            AND id_karyawan = '{$p['id_karyawan']}' 
                            AND tgl_anggota = '{$p['tgl_cair']}'
                        ");

                                if (mysqli_num_rows($qcari)) {
                                    $cari = mysqli_fetch_assoc($qcari);
                                    mysqli_query($con, "
                                UPDATE anggota 
                                SET $field = $field + 1 
                                WHERE id_anggota = '{$cari['id_anggota']}' 
                                AND id_cabang = '$id_cabang'
                            ");
                                } else {
                                    mysqli_query($con, "
                                INSERT INTO anggota ($field, id_karyawan, id_cabang, tgl_anggota)
                                VALUES (1, '{$p['id_karyawan']}', '$id_cabang', '{$p['tgl_cair']}')
                            ");
                                }
                            }

                            mysqli_query($con, "
                        UPDATE pinjaman 
                        SET input_agt = 'sudah' 
                        WHERE id_pinjaman = '{$p['id_pinjaman']}'
                    ");
                        }
                    }

                    // --- LIST STAFF YANG BELUM SINKRON ---
                    $q = mysqli_query($con, "
                SELECT staff 
                FROM pinjaman 
                WHERE id_karyawan IS NULL 
                AND id_cabang = '$id_cabang' 
                GROUP BY staff 
                ORDER BY staff ASC
            ");

                    while ($pinj = mysqli_fetch_assoc($q)) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?= $pinj['staff'] ?>
                                <input type="hidden" name="nama_mdis[]" value="<?= $pinj['staff'] ?>">
                            </td>
                            <td>
                                <select name="karyawan[]" required class="form-control">
                                    <option value="">Pilih Staff</option>
                                    <?php
                                    $data_karyawan = karyawan($con, $id_cabang)['data'];
                                    foreach ($data_karyawan as $k) {
                                        $selected = (strtolower($k['nama_karyawan']) == strtolower($pinj['staff'])) ? "selected" : "";
                                        echo "<option value='{$k['id_karyawan']}' $selected>{$k['nama_karyawan']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="2"></td>
                        <td>
                            <input type="submit" class="btn btn-success" value="KONFIRMASI" name="ganti">
                        </td>
                    </tr>
                </tbody>
            </table>

            <h3>BAGIKAN KE STAFF</h3>
            <table class="table">
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>HARI</th>
                    <th>TOTAL MONITORING</th>
                    <th>SINKRON</th>
                </tr>

                <?php
                $no = 1;
                $qsin = mysqli_query($con, "
            SELECT COUNT(*) AS total, tgl_cair 
            FROM pinjaman 
            WHERE id_cabang = '$id_cabang' 
            AND input_mtr = 'belum'
            GROUP BY tgl_cair 
            ORDER BY tgl_cair DESC
        ");

                while ($rsin = mysqli_fetch_assoc($qsin)) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $rsin['tgl_cair'] ?></td>
                        <td><?= format_hari_tanggal($rsin['tgl_cair']) ?></td>
                        <td><?= $rsin['total'] ?></td>
                        <td>
                            <a href="<?= $url . $menu ?>monitoring&ganti&tgl_cair=<?= $rsin['tgl_cair'] ?>&bagikan"
                                class="btn btn-success">BAGIKAN</a>

                            <a href="<?= $url . $menu ?>monitoring&list_bagi&tgl_cair=<?= $rsin['tgl_cair'] ?>&data"
                                class="btn btn-primary">LIST</a>

                            <a href="<?= $url . $menu ?>monitoring&ganti&tgl_cair=<?= $rsin['tgl_cair'] ?>&delete"
                                class="btn btn-danger">HAPUS</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>

        </form>

    <?php } else if (isset($_GET['rekap_monitoring_bulan'])) {
        include("proses/rekap_monitoring_bulan.php");
    } else if (isset($_GET['riwayat'])) {
        include("proses/riwayat_monitoring.php");
    } else if (isset($_GET['pindahstaff'])) {
        include("proses/pindahstaff.php");
    } else if (isset($_GET['list_bagi'])) {
        include("proses/monitoring_list_bagi.php");
    } else if (isset($_GET['pengumpulan_mtr'])) {
        include("proses/kumpul_monitoring.php");
    } else if (isset($_GET['nasabah_staff'])) {
        include("proses/nasabah_staff.php");
    } else if (isset($_GET['duplikat'])) {
        include("proses/monitoring_duplikat.php");
    } else if (isset($_GET['daftar_pinjaman'])) {
        include("proses/daftar_pinjaman.php");
    } else if (isset($_GET['input_tpk'])) {
        include("proses/input_tpk.php");
    } else if (isset($_GET['importan'])) {
        include("proses/monitoring_setting.php");
    } else if (isset($_GET['proses_importan'])) {
        include("proses/proses_importan.php");
    } else if (isset($_GET['hapus_tpk'])) {
        $id = aman($con, $_GET['id']);
        $detail = aman($con, $_GET['detail']);

        $q = mysqli_query($con, "DELETE FROM tpk WHERE `id_detail_nasabah` = '$id' and id_cabang='$id_cabang' ; ");
        $q = mysqli_query($con, "DELETE FROM keterangan_topup WHERE `id_detail_nasabah` = '$id' and id_cabang='$id_cabang' ; ");
        if (isset($_GET['ref'])) {
            $ref = $_GET['ref'];
            pindah("$url$menu" . "monitoring&" . $ref);
        } else {
            pindah("$url$menu" . 'monitoring&banding');
        }
    } else {

    ?>

        <form action="" method="post">
            <a href="<?= $url . $menu ?>monitoring" class='btn btn-success'> <i class="fa fa-list-ol"></i> Lihat yang
                belum</a>
            <a href="<?= $url . $menu ?>monitoring&filter" class='btn btn-info'> <i class="fa fa-book"></i> Lihat Semua
                Data</a>
            <a href="<?= $url . $menu ?>monitoring&banding" class='btn btn-warning'> <i class="fa fa-bell"></i>
                KELUHAN(<?= $hitung_banding ?>)</a>
            <a href="<?= $url . $menu ?>monitoring&duplikat" class='btn btn-danger'> <i class="fa fa-users"></i>
                DUPLIKAT</a>
            <a href="<?= $url . $menu ?>monitoring&riwayat" class='btn btn-info'> <i class="fa fa-check"></i> Riwayat
                Monitoring</a>
            <a href="<?= $url . $menu ?>monitoring&pengumpulan_mtr" class='btn btn-success'> <i class="fa fa-report"></i>
                Rekap Pengumpulan</a> <br /><br />
            <a href="<?= $url . $menu ?>monitoring&tgl=14" class='btn btn-danger'> <i class="fa fa-angle-right"></i> Lebih
                14 hari</a>
            <a href="<?= $url . $menu ?>monitoring&tgl=21" class='btn btn-danger'> <i class="fa fa-angle-right"></i> Lebih
                21 hari</a>
            <a href="<?= $url . $menu ?>monitoring&tgl=30" class='btn btn-danger'> <i class="fa fa-angle-right"></i> Lebih
                30 hari</a> <br /><br />

            <table class="table table-bordered table-striped" id="data_karyawan">
                <thead>
                    <tr>
                        <th>STAFF</th>
                        <th>TOPUP</th>
                        <th>NO Pinjaman</th>
                        <th>NASABAH</th>
                        <th>CTR</th>
                        <th>HARI<br>staff baru</th>
                        <th>PINJAMAN</th>
                        <th>PRODUK</th>
                        <th>Cair</th>
                        <th>KE</th>
                        <th>#</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_GET['filter'])) {
                        $q_tambah = "";
                    } else {
                        $q_tambah = "and pinjaman.monitoring ='belum'";
                    }
                    if (isset($_GET['filter_bulan'])) {
                        $filter_bulan = mysqli_real_escape_string($con, $_GET['filter_bulan']);
                        $q_bulan = "and pinjaman.tgl_cair like '$filter_bulan-%%'";
                    } else {
                        $q_bulan = "";
                    }

                    if (isset($_GET['id'])) {
                        $id = aman($con, $_GET['id']);
                        $q_id = "and pinjaman.id_karyawan = '$id'";
                    } else {
                        $q_id = "";
                    }

                    if (isset($_GET['tgl'])) {
                        $id = aman($con, $_GET['tgl']);
                        $q_hari = "and DATEDIFF(CURDATE(), tgl_cair) >$id";
                    } else {
                        $q_hari = "";
                    }

                    if (isset($_GET['banding'])) {

                        $q_banding = "and pinjaman.id_detail_pinjaman IN(SELECT id_detail_pinjaman FROM banding_monitoring where status='belum' and id_cabang='$id_cabang')";
                    } else {
                        $q_banding = "";
                    }
                    
                    // OPTIMASI: Ambil data utama dengan JOIN
                    $q = mysqli_query($con, "SELECT
                                            pinjaman.*,
                                            DATEDIFF(CURDATE(), tgl_cair) AS total_hari,
                                            karyawan.`nama_karyawan`
                                            FROM
                                            pinjaman
                                            LEFT JOIN karyawan
                                                ON karyawan.id_karyawan = pinjaman.id_karyawan 
                        
                        where pinjaman.id_cabang='$id_cabang' $q_tambah $q_id $q_hari $q_banding $q_bulan and input_mtr='sudah' order by karyawan.nama_karyawan asc");
                    
                    // OPTIMASI: Ambil semua data sekali untuk menghindari query dalam loop
                    $temp_data = [];
                    $nasabah_ids_empty_topup = []; // Hanya untuk yang jenis_topup kosong
                    $center_ids = [];
                    while ($pinj = mysqli_fetch_assoc($q)) {
                        $temp_data[] = $pinj;
                        
                        // Hanya kumpulkan ID nasabah jika jenis_topup kosong/null
                        if (empty($pinj['jenis_topup'])) {
                            $nasabah_ids_empty_topup[] = "'" . mysqli_real_escape_string($con, $pinj['id_detail_nasabah']) . "'";
                        }
                        
                        $cen = $pinj['center'];
                        $center = (explode(" ", $cen)[0]);
                        $center_ids[] = "'" . mysqli_real_escape_string($con, $center) . "'";
                    }
                    
                    // Ambil TPK hanya untuk nasabah yang jenis_topup kosong
                    $tpk_data = [];
                    if (!empty($nasabah_ids_empty_topup)) {
                        $nasabah_in = implode(',', array_unique($nasabah_ids_empty_topup));
                        $qtpk_all = mysqli_query($con, "
                            SELECT id_detail_nasabah 
                            FROM tpk 
                            WHERE id_detail_nasabah IN ($nasabah_in) AND id_cabang='$id_cabang'
                        ");
                        while ($row = mysqli_fetch_assoc($qtpk_all)) {
                            $tpk_data[$row['id_detail_nasabah']] = true;
                        }
                    }
                    
                    // Ambil topup hanya untuk nasabah yang jenis_topup kosong
                    $topup_data = [];
                    if (!empty($nasabah_ids_empty_topup)) {
                        $nasabah_in = implode(',', array_unique($nasabah_ids_empty_topup));
                        $qtopup_all = mysqli_query($con, "
                            SELECT id_detail_nasabah, topup 
                            FROM keterangan_topup 
                            WHERE id_detail_nasabah IN ($nasabah_in) AND id_cabang='$id_cabang'
                        ");
                        while ($row = mysqli_fetch_assoc($qtopup_all)) {
                            $topup_data[$row['id_detail_nasabah']] = $row['topup'];
                        }
                    }
                    
                    // OPTIMASI: Ambil semua center sekali dengan LEFT JOIN untuk handle center tanpa staff
                    $center_data = [];
                    if (!empty($center_ids)) {
                        $center_in = implode(',', array_unique($center_ids));
                        $qcenter_all = mysqli_query($con, "
                            SELECT 
                                c.no_center, 
                                c.hari, 
                                IFNULL(c.staff, 'Belum ada staff') as nama_karyawan,
                                c.id_karyawan
                            FROM center c
                            LEFT JOIN karyawan k ON k.id_karyawan = c.id_karyawan AND k.id_cabang='$id_cabang'
                            WHERE c.no_center IN ($center_in) AND c.id_cabang='$id_cabang'
                        ");
                        while ($row = mysqli_fetch_assoc($qcenter_all)) {
                            $center_data[$row['no_center']] = $row;
                        }
                    }
                    
                    // Ambil semua keluhan sekali (jika banding)
                    $keluhan_data = [];
                    if (isset($_GET['banding']) && !empty($temp_data)) {
                        $detail_pinjaman_ids = [];
                        foreach ($temp_data as $p) {
                            $detail_pinjaman_ids[] = "'" . mysqli_real_escape_string($con, $p['id_detail_pinjaman']) . "'";
                        }
                        $detail_in = implode(',', array_unique($detail_pinjaman_ids));
                        $qkeluh_all = mysqli_query($con, "
                            SELECT id_detail_pinjaman, id_banding_monitoring, keterangan_banding 
                            FROM banding_monitoring 
                            WHERE id_detail_pinjaman IN ($detail_in)
                        ");
                        while ($row = mysqli_fetch_assoc($qkeluh_all)) {
                            $keluhan_data[$row['id_detail_pinjaman']] = $row;
                        }
                    }

                    foreach ($temp_data as $pinj) {

                        if ($pinj['total_hari'] > 30) $tr = "#adacaa";
                        else if ($pinj['total_hari'] > 14) $tr = "#ffd4d4";
                        else if ($pinj['total_hari'] >= 0 && $pinj['total_hari'] <= 2) $tr = "#42f554";
                        else $tr = "#ffffff";

                        if (!empty($pinj['jenis_topup'])) {
                            $tpk = $pinj['jenis_topup'];
                        } else {
                            if (isset($tpk_data[$pinj['id_detail_nasabah']])) {
                                $tpk = "TPK";
                            } else if (isset($topup_data[$pinj['id_detail_nasabah']])) {
                                $tpk = $topup_data[$pinj['id_detail_nasabah']];
                            } else {
                                $tpk = "";
                            }
                        }
                    ?>
                        <tr style="background:<?= $tr ?>;">
                            <td>
                                <a href="<?= $url . $menu ?>monitoring&pindahstaff&idpinjaman=<?= $pinj['id_pinjaman'] ?>">
                                    <i class="fa fa-gears"></i>
                                </a>
                                <?= $nama_staff = $pinj['nama_karyawan'] ?>
                            </td>

                            <td><?= $tpk ?></td>
                            <td><?= ganti_karakter($pinj['id_detail_pinjaman']) ?></td>

                            <td><?= $pinj['nama_nasabah'] ?></td>

                            <td>
                                <?php
                                $center = explode(" ", $pinj['center'])[0];
                                echo $center;
                                ?>
                            </td>

                            <td>
                                <small>
                                    <?php
                                    // OPTIMASI: Cek center dari data cache dengan fallback jika staff kosong
                                    if (isset($center_data[$center])) {
                                        $c = $center_data[$center];
                                        
                                        // Jika center belum ada staff atau id_karyawan null
                                        if (empty($c['id_karyawan']) || $c['nama_karyawan'] == 'Belum ada staff') {
                                            $color = "orange";
                                            $ket = "center belum punya staff";
                                        }
                                        // Jika staff center sama dengan staff pinjaman
                                        else if ($nama_staff == $c['nama_karyawan']) {
                                            $color = "black";
                                            $ket = "";
                                        }
                                        // Jika staff center berbeda dengan staff pinjaman
                                        else {
                                            $color = "red";
                                            $ket = "ganti - " . strtolower($c['nama_karyawan']);
                                        }

                                        echo $c['hari'] . "<br>";
                                        echo "<i style='color:$color'>$ket</i>";
                                    } else {
                                        echo "<i style='color:gray'>Center tidak ditemukan</i>";
                                    }
                                    ?>
                                </small>
                            </td>

                            <td><?= angka($pinj['jumlah_pinjaman']) ?></td>

                            <td>
                                <?php
                                $produk = strtolower($pinj['produk']);
                                if ($produk == "pinjaman umum") $kode = "P.U";
                                else if ($produk == "pinjaman sanitasi") $kode = "PSA";
                                else if ($produk == "pinjaman mikrobisnis" || $produk == "pinjaman mikro bisnis") $kode = "PMB";
                                else if ($produk == "pinjaman arta") $kode = "ARTA";
                                else if ($produk == "pinjaman dt. pendidikan") $kode = "PPD";
                                else if ($produk == "pinjaman renovasirumah") $kode = "PRR";
                                else $kode = "LL";

                                echo $kode;
                                ?>
                            </td>

                            <td><?= $pinj['tgl_cair'] ?></td>
                            <td><?= $pinj['pinjaman_ke'] ?></td>

                            <td>
                                <?php
                                if ($pinj['monitoring'] == 'belum') {
                                    $tombol = "btn-danger";
                                    $icon = "";
                                } else {
                                    $tombol = "btn-info";
                                    $icon = ' Sudah <i class="fa fa-check"></i>';
                                }
                                ?>
                                <span id='loading_<?= $pinj['id_pinjaman'] ?>'></span>
                                <?= $icon ?>
                                <a href="#modalku1" data-toggle="modal" data-id="<?= $pinj['id_pinjaman'] ?>">Detail</a>
                            </td>

                            <td>
                                <?php if ($pinj['id_karyawan'] != null): ?>
                                    <input type="button"
                                        id="cek_<?= $pinj['id_pinjaman'] ?>"
                                        class="btn <?= $tombol ?>"
                                        value="<?= $pinj['monitoring'] ?>"
                                        onclick="monitoring('<?= $pinj['id_pinjaman'] ?>','<?= $pinj['id_detail_pinjaman'] ?>')">

                                    <?php if (isset($_GET['banding']) && isset($keluhan_data[$pinj['id_detail_pinjaman']])):
                                        $keluh1 = $keluhan_data[$pinj['id_detail_pinjaman']];
                                    ?>
                                        <a class="btn"
                                            href="<?= $url . $menu . 'monitoring&tutupbanding&id=' . $keluh1['id_banding_monitoring'] . '&detail=' . $pinj['id_detail_pinjaman'] ?>">
                                            Kirim Pesan?
                                        </a>
                                        <a class="btn"
                                            onclick="return confirm('Yakin hapus data ini?')"
                                            href="<?= $url . $menu . 'monitoring&hapus&id=' . $pinj['id_pinjaman'] . '&detail=' . $pinj['id_detail_pinjaman'] ?>">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    <?php endif ?>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </form>
    <?php

    }
    ?>

</div>
<div class="modal fade" id="modalku1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Ini adalah Bagian Header Modal -->
            <div class="modal-header">
                <h4 class="modal-title">DETAIL MONITORING</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Ini adalah Bagian Body Modal -->
            <div class="modal-body">

                <div id="isi_detail"></div>
                <br><br>

            </div>

            <!-- Ini adalah Bagian Footer Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">close</button>
            </div>

        </div>
    </div>
</div>


<script>
    var url = "<?= $url ?>";
    var cabang = "<?= $id_cabang ?>";


    function buka() {
        window.open(url + 'export/monitoring.php', 'popup', 'width=10,height=10');
        window.location.assign(url + "index.php?menu=monitoring")

        location.reload();

    }

    function monitoring(id, detail) {
        var cek = $("#cek_" + id).val();
        if (cek == 'belum') {


            $.get(url + "api/monitoring.php?mtr=sudah&id=" + id + "&detail=" + detail, function(data, status) {
                $("#loading_" + id).html("Proses");
                setTimeout(function() {
                    $("#loading_" + id).html("<i class='fa fa-check'></i>");
                    $("#cek_" + id).val('sudah');
                    $("#cek_" + id).removeClass("btn-danger");
                    $("#cek_" + id).addClass("btn-info");
                }, 1000);

            });

        } else {
            $.get(url + "api/monitoring.php?mtr=belum&id=" + id, function(data, status) {

                setTimeout(function() {
                    $("#cek_" + id).val('belum');
                    $("#cek_" + id).removeClass("btn-info");
                    $("#cek_" + id).addClass("btn-danger");
                    $("#loading_" + id).html("<i class='fa fa-times'></i>");
                }, 500);

            });
        }
    }
</script>