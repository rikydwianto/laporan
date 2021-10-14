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
    <a href="<?= $url . $menu ?>monitoring&kosong" class='btn btn-danger' onclick="return window.confirm('Apakah anda yakin untuk hapus semuadata monitoring??')"> <i class="fa fa-trash"></i> Kosongkan</a>
    <a href="<?= $url . $menu ?>monitoring&ganti" class='btn btn-success'> <i class="fa fa-wrench"></i> Synchron Data</a>
    <a href="<?= $url . $menu ?>monitoring&staff" class='btn btn-danger'> <i class="fa fa-users"></i> Total Monitoring</a>
    <a href="<?= $url . $menu ?>monitoring&pu" class='btn btn-danger'> <i class="fa fa-users"></i> Detail Pinjaman umum</a>
    <a href="<?= $url . $menu ?>monitoring" onclick="buka()" class='btn btn-info'> <i class="fa fa-file-excel-o"></i> Export</a>
    <?php
    if (isset($_SESSION['nama_file'])) {
    ?>
        <a href="<?= $url ?>export/excel/<?= $_SESSION['nama_file'] ?>.xlsx" class='btn btn-info'> <i class="fa fa-download"></i> UNDUH</a>
    <?php
    }
    ?>
    <hr />

    <?php
    if (isset($_GET['tambah'])) {
    ?>
        <form action="" method="post">
            <textarea name="query" class='form-control' id="" cols="50" rows="20"></textarea>
            <input type="submit" value="Execute" name='ekse' />
            <?php
            if (isset($_POST['ekse'])) {
                $text = ganti_karakter($_POST['query']);
                $text = str_replace("mytable", "pinjaman", $text);
                $query = mysqli_multi_query($con, $text);
                if ($query) {
                    sleep(10);
                    alert("Terima Kasih telah menunggu, Data berhasil input ...");
                } else {
                    pesan("Gagal <br/> $text", 'danger');
                }
            }
            ?>
        </form>
    <?php
    } elseif (isset($_GET['kosong'])) {
        mysqli_query($con, "DELETE FROM `pinjaman` WHERE `id_cabang` = '$id_cabang'");
        // echo "DELETE FROM `upk` WHERE `id_cabang` = '$id_cabang'";
        pindah("$url$menu" . 'monitoring');
    } else if (isset($_GET['hapus'])) {
        $id = aman($con, $_GET['id']);
        $detail = aman($con, $_GET['detail']);

        $q = mysqli_query($con, "DELETE FROM `pinjaman` WHERE `id_pinjaman` = '$id' ; ");
        // $q1 = mysqli_query($con, "UPDATE `banding_monitoring` SET `status` = 'selesai' WHERE `id_detail_pinjaman` = '$detail'; ");
        pindah("$url$menu" . 'monitoring&banding');
    } else if (isset($_GET['tutupbanding'])) {
        $id = aman($con, $_GET['id']);
        $detail = aman($con, $_GET['detail']);
        // $detail = aman($con, $_GET['detail']);   
        if (isset($_POST['kirim'])) {
            $pesan = aman($con, $_POST['pesan_balik']);


            $q = mysqli_query($con, "UPDATE `banding_monitoring` SET `status` = 'selesai', pesan='$pesan' WHERE `id_banding_monitoring` = '$id'; ");
            pindah("$url$menu" . 'monitoring&banding');
        }
        $qdetail = mysqli_query($con, "select * from pinjaman join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan join banding_monitoring on banding_monitoring.id_detail_pinjaman=pinjaman.id_detail_pinjaman where pinjaman.id_detail_pinjaman='$detail' ");
        $detail_pinjaman = mysqli_fetch_array($qdetail);
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
                        <textarea name="pesan_balik" id="" cols="30" rows="10" class='form-control' placeholder="Kirim pesan keterangan pada Staff"></textarea>
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
        @$tgl = $_GET['tgl'];
        if (empty($tgl)) {
            $tgl = date("Y-m-d");
        } else {
            $tgl = $tgl;
        }
    ?>
        <form method='get' action='<?php echo $url . $menu ?>monitoring'>
            <input type=hidden name='menu' value="monitoring" />
            <input type=hidden name='pu' />
            Sampai Dengan <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' />
            <input type=submit name='cari' value='CARI' />
        </form>
        <br>
        <table class="table table-bordered">
            <tr style='background:#c8c9cc'>
                <th>
                    NO
                </th>
                <!-- <th>NIK</th> -->
                <th>STAFF</th>
                <?php
                $col  = 0;
                $data_pu = array();
                $pin = mysqli_query($con, "SELECT pinjaman_ke FROM pinjaman WHERE produk='PINJAMAN UMUM' and id_cabang='$id_cabang' GROUP BY pinjaman_ke ");
                while ($ke = mysqli_fetch_array($pin)) {
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
            $cek_ka = mysqli_query($con, "SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
            while ($karyawan = mysqli_fetch_array($cek_ka)) {
            ?>
                <tr>
                    <th>
                        <?= $no ?>
                    </th>
                    <!-- <th>NIK</th> -->
                    <th><?= $karyawan['nama_karyawan'] ?></th>
                    <?php
                    $pin = mysqli_query($con, "SELECT pinjaman_ke FROM pinjaman WHERE produk='PINJAMAN UMUM' and id_cabang='$id_cabang' GROUP BY pinjaman_ke ");
                    $total_hitung = 0;
                    while ($ke = mysqli_fetch_array($pin)) {
                        $hitung = mysqli_query($con, "SELECT COUNT(monitoring) AS monitoring FROM pinjaman WHERE id_karyawan='$karyawan[id_karyawan]' AND produk='PINJAMAN UMUM' and pinjaman_ke='$ke[pinjaman_ke]' and tgl_cair <='$tgl' and monitoring='belum'");
                        $hitung = mysqli_fetch_array($hitung);
                        $hitung = $hitung['monitoring'];
                        $total_hitung = $hitung + $total_hitung;
                    ?>
                        <th class='tengah kotak'><?= $hitung ?></th>
                    <?php
                    }


                    $total_monitoring = $total_monitoring + $total_hitung;
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
                for ($c = 1; $c <= $col; $c++) {
                    $hitung_ = mysqli_query($con, "SELECT COUNT(monitoring) AS monitoring FROM pinjaman WHERE  produk='PINJAMAN UMUM' and pinjaman_ke='$c' and monitoring='belum' and tgl_cair <='$tgl' and pinjaman.id_cabang='$id_cabang'");
                    $hitung_ = mysqli_fetch_array($hitung_);
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
    ?>
        <form action="" method="post">
            <!-- <input type="submit" value="SIMPAN" name='mtr' class='btn btn-danger'> -->
            <TABLE class='table'>
                <thead>
                    <tr>
                        <!-- <th>no</th> -->
                        <th>NO </th>
                        <th>NAMA MDIS</th>
                        <th>NAMA </th>
                    </tr>
                </thead>
                <tbody>


                    <?php
                    if (isset($_POST['ganti'])) {
                        $karyawan = $_POST['karyawan'];
                        $mdis = $_POST['nama_mdis'];
                        $mon = mysqli_query($con, "select tgl_pencairan,id_pinjaman from pinjaman where id_karyawan is null and id_cabang='$id_cabang' ");
                        while ($moni = mysqli_fetch_array($mon)) {
                            $tgl = $moni['tgl_pencairan'];
                            $tgl = explode("-", $tgl);
                            $new_tgl = $tgl[2] . "-" . $tgl[1] . "-" . $tgl[0];
                            mysqli_query($con, "UPDATE `pinjaman` SET `tgl_cair` = '$new_tgl'  WHERE `id_pinjaman` = '$moni[id_pinjaman]';");
                        }
                        for ($i = 0; $i < count($mdis); $i++) {
                            if (!empty($karyawan[$i])) {
                                $text = " UPDATE `pinjaman` SET `staff` = null , id_karyawan='$karyawan[$i]' WHERE `staff` = '$mdis[$i]'; ";
                                $q = mysqli_query($con, "$text");
                            }
                        }
                    }


                    $q = mysqli_query($con, "select staff from pinjaman where id_karyawan is  null group by staff order by staff asc ");
                    while ($pinj = mysqli_fetch_array($q)) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $pinj['staff'] ?>
                                <input type="hidden" name="nama_mdis[]" value="<?= $pinj['staff'] ?>">
                            </td>
                            <td>

                                <select name="karyawan[]" id="" required class='form-control'>
                                    <option value="">Pilih Staff</option>
                                    <?php $data_karyawan  = (karyawan($con, $id_cabang)['data']);
                                    for ($i = 0; $i < count($data_karyawan); $i++) {
                                        $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                                        if (strtolower($nama_karyawan) == strtolower($pinj['staff'])) {
                                            echo "<option selected value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                                        } else {
                                            echo "<option value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2"></td>
                        <td>
                            <input type="submit" class='btn btn-success' value='KONFIRMASI' name='ganti' />
                        </td>
                    </tr>
                </tbody>
            </TABLE>
        </form>
    <?php
    } else if (isset($_GET['riwayat'])) {
        //RIWAYAT MONITORING
        include("proses/riwayat_monitoring.php");
    } else {






    ?>

        <form action="" method="post">
            <!-- <input type="submit" value="SIMPAN" name='mtr' class='btn btn-danger'> -->
            <a href="<?= $url . $menu ?>monitoring" class='btn btn-success'> <i class="fa fa-list-ol"></i> Lihat yang belum</a>
            <a href="<?= $url . $menu ?>monitoring&filter" class='btn btn-info'> <i class="fa fa-book"></i> Lihat Semua Data</a>
            <a href="<?= $url . $menu ?>monitoring&banding" class='btn btn-warning'> <i class="fa fa-bell"></i> KELUHAN(<?= $hitung_banding ?>)</a>
            <a href="<?= $url . $menu ?>monitoring&riwayat" class='btn btn-info'> <i class="fa fa-check"></i> Riwayat Monitoring</a> <br /><br />
            <a href="<?= $url . $menu ?>monitoring&tgl=14" class='btn btn-danger'> <i class="fa fa-angle-right"></i> Lebih 14 hari</a>
            <a href="<?= $url . $menu ?>monitoring&tgl=21" class='btn btn-danger'> <i class="fa fa-angle-right"></i> Lebih 21 hari</a>
            <a href="<?= $url . $menu ?>monitoring&tgl=30" class='btn btn-danger'> <i class="fa fa-angle-right"></i> Lebih 30 hari</a> <br /><br />

            <TABLE class='table' id='data_karyawan'>
                <thead>
                    <tr>
                        <!-- <th>no</th> -->
                        <th>Staff</th>
                        <th>NO Pinjaman</th>
                        <th>Nama</th>
                        <th>CTR</th>
                        <th>Jumlah Pinjaman</th>
                        <th>Produk</th>
                        <th>Cair</th>
                        <th>KE</th>
                        <?php
                        if (isset($_GET['banding'])) {
                        ?>
                            <th>Keluhan</th>
                        <?php
                        }
                        ?>
                        <th>#</th>
                        <th>#</th>

                    </tr>
                </thead>
                <tbody>


                    <?php
                    if (isset($_GET['filter'])) {
                        $q_tambah = "";
                    } else {
                        $q_tambah = "and pinjaman.monitoring ='belum'";
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
                    $q = mysqli_query($con, "select *,DATEDIFF(CURDATE(), tgl_cair) as total_hari from pinjaman left 
                        join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan 
                        
                        where pinjaman.id_cabang='$id_cabang' $q_tambah $q_id $q_hari $q_banding order by karyawan.nama_karyawan asc");
                    while ($pinj = mysqli_fetch_array($q)) {
                        if ($pinj['total_hari'] > 14) {
                            $tr = "#ffd4d4";
                        } else $tr = "#fffff";
                    ?>
                        <tr style="background:<?= $tr ?>">
                            <!-- <td><?= $no++ ?></td> -->
                            <td><?= $pinj['nama_karyawan'] ?></td>
                            <td><?= ganti_karakter($pinj['id_detail_pinjaman']) ?></td>
                            <td>
                                <?= $pinj['nama_nasabah'] ?>
                            </td>
                            <td>
                                <?php 
                                $cen = $pinj['center'];
                                $center = (explode(" ",$cen)[0]);
                                echo $center;
                                ?>
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

                                echo $kode; ?>
                            </td>
                            <td><?= $pinj['tgl_cair'] ?></td>
                            <td><?= $pinj['pinjaman_ke'] ?></td>
                            <?php
                            if (isset($_GET['banding'])) {
                                $keluh = mysqli_query($con, "select * from banding_monitoring where id_detail_pinjaman='$pinj[id_detail_pinjaman]'");
                                $keluh1 = mysqli_fetch_array($keluh);
                                $keluh = $keluh1['keterangan_banding'];
                            ?>
                                <td><?= $keluh ?></td>
                            <?php
                            }
                            ?>
                            <td>
                                <?php
                                if ($pinj['monitoring'] == 'belum') {
                                    $tombol = "btn-danger";
                                    $icon = "";
                                } elseif ($pinj['monitoring'] == 'sudah') {

                                    $tombol = "btn-info";
                                    $icon = ' Sudah <i class="fa fa-check"></i>';
                                } else $tombol = "btn-danger";
                                ?>
                                <span class="pull-right" id='loading_<?= $pinj['id_pinjaman'] ?>' class="badge rounded-pill bg-danger"></span>
                                <?= $icon ?>
                                <a href="#modalku1" id="custId" data-toggle="modal" data-id="<?= $pinj['id_pinjaman'] ?>">Detail</a>
                            </td>
                            <td>
                                <?php
                                if ($pinj['id_karyawan'] != null) {
                                ?>
                                    <input type="button" id="cek_<?= $pinj['id_pinjaman'] ?>" class='btn <?= $tombol ?>' value='<?= $pinj['monitoring'] ?>' onclick="monitoring('<?= $pinj['id_pinjaman'] ?>','<?= $pinj['id_detail_pinjaman'] ?>')" id="">
                                <?php
                                    if (isset($_GET['banding'])) {
                                        echo "<a href='$url$menu" . 'monitoring&tutupbanding&id=' . $keluh1['id_banding_monitoring'] . "&detail=" . $pinj['id_detail_pinjaman'] . "'  class='btn'>Kirim Pesan?  </a>";
                                        echo "<a href='$url$menu" . 'monitoring&hapus&id=' . $pinj['id_pinjaman'] . "&detail=" . $pinj['id_detail_pinjaman'] . "' onclick='return window.confirm(" . '"' . "Apakah anda yakin untuk menghapus data ini??" . '"' . ")' class='btn'><i class='fa fa-times'></i>  </a>";
                                    }
                                } ?>

                            </td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
        </form>
        </TABLE>
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
        // window.href.location = url + "index.php?menu=monitoring";
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