<div class='content table-responsive'>
    <h2 class='page-header'>Singkron Laporan dari DTC</h2>

    <div class='col-md-4'>
        <form action="" method="get">
            Pilih Tanggal :
            <input type="hidden" name="menu" value="laporan_dtc">
            <input type="date" value="<?= isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>" name="tanggal" id=""
                class='form-control'> <br>
            <input type="submit" value="PILIH" name='filter' class='btn btn-danger'>
        </form>
    </div>
    <div class="col-md-12">

        <?php
        if (isset($_GET['filter'])) {
            $tgl = $_GET['tanggal'];
        ?>

        <div class="table-responsive">

            <form action="" method="post">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>CABANG</th>
                            <th>NIK</th>
                            <th>NAMA</th>
                            <th>TGL TRANSAKSI</th>
                            <th>DETAIL</th>
                            <th>SINGKRON</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $cab  = getCabang($id_cabang);
                            $nama_cabang = strtoupper($cab['nama_cabang']);
                            $no_urut = 0;
                            $q_cek  = mysqli_query($con, "SELECT * from temp_laporan_dtc where cabang='$nama_cabang' and  tanggal='$tgl'  and singkron_laporan='belum' ");
                            if (mysqli_num_rows($q_cek)) {
                                while ($r = mysqli_fetch_assoc($q_cek)) {
                            ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $r['cabang'] ?></td>
                            <td><?= $r['nik'] ?></td>
                            <td>
                                <?= $r['nama_staff'] ?> <br>
                                <input type="hidden" name="id_temp[]" value="<?= $r['id'] ?>">
                                <select name="staff[]" class="form-control" id="">
                                    <option value="">Pilih Staff</option>
                                    <?php
                                                $cek_staff = mysqli_query($con, "select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=1 order by nama_karyawan asc");
                                                while ($staff = mysqli_fetch_assoc($cek_staff)) {
                                                    if ($staff['nik_karyawan'] == $r['nik'] || strtoupper($staff['nama_karyawan']) == strtoupper($r['nama_staff'])) {
                                                ?>
                                    <option selected value="<?= $staff['id_karyawan'] ?>"><?= $staff['nama_karyawan'] ?>
                                    </option>
                                    <?php
                                                    } else {
                                                    ?>
                                    <option value="<?= $staff['id_karyawan'] ?>"><?= $staff['nama_karyawan'] ?></option>

                                    <?php
                                                    }
                                                    ?>

                                    <?php
                                                }
                                                ?>
                                </select>
                            </td>
                            <td><?= $r['tanggal'] ?></td>
                            <td>


                            </td>
                            <td><?= $r['singkron_laporan'] ?></td>
                            <td>#</td>
                        </tr>

                        <?php
                                    $json = json_decode($r['json_laporan'], true);
                                    foreach ($json as $data) {
                                        if (is_numeric($data['bayar'])) {

                                    ?>
                        <tr>
                            <th>Center : <?= $data['center'] ?></th>
                            <td colspan="7">
                                Anggota : <?= $data['anggota'] ?> |
                                Bayar : <?= $data['bayar'] ?> |
                                Tidak Bayar : <?= $data['tidak_bayar'] ?> |
                                Hadir : <?= $data['hadir'] ?> |
                                Tidak Hadir : <?= $data['anggota'] - $data['hadir'] ?>

                                <input type="hidden" name="no_center[<?= $r['id'] ?>][]" value="<?= $data['center'] ?>"
                                    id="">
                                <input type="hidden" name="anggota[<?= $r['id'] ?>][]" value="<?= $data['anggota'] ?>"
                                    id="">
                                <input type="hidden" name="hadir[<?= $r['id'] ?>][]" value="<?= $data['hadir'] ?>"
                                    id="">
                                <input type="hidden" name="bayar[<?= $r['id'] ?>][]" value="<?= $data['bayar'] ?>"
                                    id="">
                                <input type="hidden" name="tidak_bayar[<?= $r['id'] ?>]"
                                    value="<?= $data['tidak_bayar'] ?>" id="">

                            </td>
                        </tr>
                        <?php
                                        }
                                    }
                                    ?>
                        <?php
                                    $no_urut++;
                                }
                            } else {
                                ?>
                        <tr>
                            <td colspan="8" align="center">Tidak ada Data!</td>
                        </tr>
                        <?php
                            }
                            ?>
                    </tbody>

                </table>
                <input type="submit" value="SUBMIT" class="btn btn-danger btn-lg" name='konfirmasi'>
            </form>
        </div>
        <?php
        }
        ?>
    </div>
</div>

<?php
function warna($persen)
{

    if ($persen < 1) {
        $warna = 'hitam'; // Hitam untuk 0
    } elseif ($persen >= 1 && $persen <= 30) {
        $warna = 'merah'; // Merah untuk di atas 0 dan kurang atau sama dengan 30
    } elseif ($persen > 30 && $persen < 80) {
        $warna = 'kuning'; // Kuning untuk di atas 30 dan kurang dari 80
    } elseif ($persen >= 80) {
        $warna = 'hijau'; // Hijau untuk 80 atau lebih
    }

    return $warna;
}
if (isset($_POST['konfirmasi'])) {
    $staff = $_POST['staff'];
    $id_temp = $_POST['id_temp'];
    $anggota = $_POST['anggota'];
    $no_center = $_POST['no_center'];

    for ($i = 0; $i <= count($staff); $i++) {
        $id_staff =  $staff[$i];
        if ($id_staff != null || $id_staff != "") {
            $id = $id_temp[$i];
            // echo $id_temp . '<br/>';

            $query = "INSERT INTO `laporan` 
            ( `id_karyawan`, `tgl_laporan`, `keterangan_laporan`, `status_laporan`, `keterangan_lain`) VALUES 
            ( '$id_staff', '$tgl', 'Laporan dari DTC', 'sukses', NULL); ";
            echo $query;
            $tmb_laporan = mysqli_query($con, "$query");
            $id_laporan = mysqli_insert_id($con);
            $q_cek  = mysqli_query($con, "SELECT * from temp_laporan_dtc where id='$id'");
            $data = mysqli_fetch_assoc($q_cek);
            $data = json_decode($data['json_laporan'], true);

            if ($tmb_laporan) {



                foreach ($data as $detail) {
                    if (is_numeric($detail['bayar'])) {

                        $persen_bayar = ($detail['bayar'] / $detail['anggota']) * 100;
                        $warna = warna($persen_bayar);
                        $q_center = "INSERT INTO `detail_laporan` ( `id_laporan`, `no_center`, `status`, `doa`, `member`, `total_agt`, `total_bayar`, `total_tidak_bayar`, `status_detail_laporan`, `doortodoor`,`anggota_hadir`) 
                    VALUES ('$id_laporan', '$detail[center]', '$warna', 'y', '$detail[anggota]', '$detail[anggota]', '$detail[bayar]', '$detail[tidak_bayar]', 'sukses', 'y','$detail[hadir]'); 
                    ";
                        // echo $q_center;
                        mysqli_query($con, $q_center);


                        $d = mysqli_query($con, "UPDATE center SET status_center = '$warna',
                        member_center = '$detail[anggota]' , anggota_center = '$detail[anggota]' , center_bayar = '$detail[bayar]' , anggota_hadir='$detail[hadir]' WHERE no_center = '$detail[center]' and id_cabang=$id_cabang; ");
                    }
                }
            }
            mysqli_query($con, "UPDATE temp_laporan_dtc set singkron_laporan='sudah' where id='$id'");
            alert("Berhasil");
            pindah("");
        }
    }
}
?>