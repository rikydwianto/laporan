<div class='content table-responsive'>
    <h2 class='page-header'>PERBAIKAN DATA </h2>
    <i></i>
    <a href="<?= $url . $menu ?>perbaikan" class="btn btn-info"> Lihat Data</a>
    <a href="<?= $url . $menu ?>perbaikan&tambah" class="btn btn-danger"> TAMBAH Data</a>
    <a href="<?= $url . $menu ?>perbaikan&belum_input" class="btn btn-warning"> Lihat Belum Input SL</a>
    <a href="<?= $url . $menu ?>perbaikan&statistik" class="btn btn-danger"> Statistik</a>
    <a href="<?= $url . "export/" ?>perbaikan.php" class="btn btn-success"> EXPORT DATA</a>
    <hr />
    <!-- Button to Open the Modal -->


    <?php
    @$id_perbaikan = aman($con, $_GET['id_perbaikan']);
    if (isset($_GET['belum'])) {
        mysqli_query($con, "UPDATE perbaikan set status='belum' where id_perbaikan='$id_perbaikan'");
        pindah($url . $menu . "perbaikan");
    } else if (isset($_GET['belum_input'])) {
        include("./proses/perbaikan_belum_input.php");
    } else if (isset($_GET['tambah'])) {
        include("./proses/tambah_perbaikan.php");
    } else if (isset($_GET['statistik'])) {
    ?>
        <h3>DATA BELUM DIKERJAKAN</h3>
        <table class='table'>
            <tr>
                <th>KESALAHAN</th>
                <th>TOTAL</th>
            </tr>
            <?php
            $keseluruhan = 0;
            $qs = mysqli_query($con, "SELECT kesalahan,COUNT(kesalahan) AS total_kesalahan FROM perbaikan JOIN karyawan ON karyawan.id_karyawan=perbaikan.id_karyawan WHERE karyawan.id_cabang='$id_cabang' and status='belum' and status_input is null GROUP BY perbaikan.kesalahan");
            while ($kesalahan  = mysqli_fetch_assoc($qs)) {
                $keseluruhan = $keseluruhan + $kesalahan['total_kesalahan'];
            ?>
                <tr>
                    <td>
                        <?= $kesalahan['kesalahan'] ?>
                    </td>
                    <td>
                        <a href="<?= $url . $menu ?>perbaikan&belum_input&fil=<?= $kesalahan['kesalahan'] ?>" class=""> <?= $kesalahan['total_kesalahan'] ?></a>
                    </td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td>
                    TOTAL
                </td>
                <td>
                    <h2><?= $keseluruhan ?></h2>
                </td>
            </tr>
        </table>
        <table class='table'>
            <tr>
                <th>NO</th>
                <th>STAFF</th>
                <th>TOTAL</th>
            </tr>
            <?php
            $total_semua = 0;
            $qq = mysqli_query($con, "SELECT karyawan.`nama_karyawan`,COUNT(kesalahan) AS total_kesalahan,karyawan.id_karyawan FROM perbaikan JOIN karyawan ON karyawan.id_karyawan=perbaikan.id_karyawan WHERE perbaikan.status_input is null and karyawan.id_cabang='$id_cabang' GROUP BY perbaikan.id_karyawan order by  total_kesalahan desc");
            while ($staff = mysqli_fetch_assoc($qq)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $staff['nama_karyawan'] ?></td>
                    <td>
                        <a href="<?= $url . $menu ?>perbaikan&belum_input&id_staff=<?= $staff['id_karyawan'] ?>"> <?= $staff['total_kesalahan'] ?></a>
                    </td>
                </tr>
            <?php
                $total_semua  = $total_semua + $staff['total_kesalahan'];
            }
            ?>
            <tr>
                <td>
                    TOTAL SEMUA STAFF
                </td>
                <td></td>
                <td><?= $total_semua ?></td>
            </tr>
        </table>
    <?php

    } else {
    ?>
        klik id anggota untuk menyalin id anggota
        <table id='data_center' class='table-bordered'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAMA</th>
                    <th>KESALAHAN</th>
                    <th>NO HP</th>
                    <th>KETERANGAN</th>
                    <th>KETERANGAN LAIN</th>
                    <th>STAFF</th>

                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $q = mysqli_query($con, "SELECT * from perbaikan 
        JOIN karyawan on perbaikan.id_karyawan=karyawan.id_karyawan
        JOIN center on perbaikan.no_center=center.no_center where perbaikan.status='sudah' and karyawan.id_cabang='$id_cabang' and status_input is NULL ");
                while ($kes = mysqli_fetch_assoc($q)) {
                    $hp = $kes['no_hp'];
                ?>
                    <tr id='ganti-<?= $kes['id_perbaikan'] ?>'>
                        <td><a class="btn" id='detail<?= $kes['id_perbaikan'] ?>' onclick="salin_id('<?= $kes['id_detail_nasabah'] ?>','<?= $kes['id_perbaikan'] ?>')" title='Klik untuk salin'><?= $kes['id_detail_nasabah'] ?></a></td>
                        <td><?= $kes['nama_nasabah'] ?></td>
                        <td><?= $kes['kesalahan'] ?></td>
                        <td><?= preg_replace('/[^0-9]/', '', str_replace("+62", '0', $hp)) ?></td>
                        <td><b><?php
                                echo ($kes['nama_ibu_kandung'] === null ? "" : "Ibu " . '<a class="btn" onclick="salin(' . "'" . strtoupper($kes['nama_ibu_kandung']) . "'" . ')">' . strtoupper($kes['nama_ibu_kandung']) . "</a><br/>");
                                echo ($kes['nik_ktp'] === null ? "" : "ktp : $kes[nik_ktp]<br/>");
                                echo ($kes['status_pernikahan'] === null ? "" : "status : $kes[status_pernikahan]<br/>");
                                echo ($kes['tgl_lahir'] === null ? "" : "lahir : $kes[tgl_lahir]<br/>");
                                echo ($kes['alamat'] === null ? "" : "alamat : $kes[alamat]<br/>");
                                if ($kes['status_input'] == 'sudah') {
                                    $tmb = "btn-success";
                                } else $tmb = 'btn-danger';
                                ?></b>
                        </td>
                        <td><?= ($kes['keterangan_lain']) ?></td>
                        <td><?= ($kes['nama_karyawan']) ?></td>

                        <td>
                            <a href="<?= $url . $menu ?>perbaikan&belum&id_perbaikan=<?= $kes['id_perbaikan'] ?>" class="btn btn-danger">balikan</a>
                            <a href="#" id='simpan_mdis-<?= $kes['id_perbaikan'] ?>' onclick="sudah(<?= $kes['id_perbaikan'] ?>)" data-id="<?= $kes['id_perbaikan'] ?>" class="btn <?= $tmb ?>">Sudah</a>
                        </td>
                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>

    <?php
    }
    ?>
</div>
<script>
    var url = "<?= $url ?>";

    function sudah(id) {
        var id = $("#simpan_mdis-" + id).data("id");
        // alert(id);
        $(document).ready(function() {


            $.get(url + "api/simpan_mdis.php?id=" + id, function(data, status) {

                $("#ganti-" + id).attr("style", "background:#5bc0de");
                $("#ganti-" + id).hide(1000);
                // alert(id)
            });
        });
    }

    function salin_id(detail, no) {
        salin(detail);
        $("#detail" + no).html("tersalin");
        setTimeout(function() {
            $("#detail" + no).html(detail);

        }, 3000);
    }
</script>