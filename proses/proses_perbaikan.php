<?php
$id_perbaikan = aman($con, $_GET['id_perbaikan']);
$qper = mysqli_query($con, "SELECT * from perbaikan 
JOIN karyawan on perbaikan.id_karyawan=karyawan.id_karyawan
JOIN center on perbaikan.no_center=center.no_center where perbaikan.id_perbaikan='$id_perbaikan'");
$per = mysqli_fetch_array($qper);
?>
<form method="post">

    <h3> Nama : <?= $per['nama_nasabah'] ?></h3>
    <table class='table'>
        <tr>
            <td>ID</td>
            <td><?= $per['id_detail_nasabah'] ?></td>
        </tr>
        <tr>
            <td>NAMA</td>
            <td><?= $per['nama_nasabah'] ?></td>
        </tr>
        <tr>
            <td>CENTER</td>
            <td><?= $per['no_center'] ?></td>
        </tr>
        <tr>
            <td>
                KESALAHAN
            </td>
            <td>
                <code><?= $per['kesalahan'] ?></code>
            </td>
        </tr>
        <tr>
            <td>NO HP</td>
            <td><input type="text" required name='no_hp' class='form-control'></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>
                <textarea name="keterangan" id="" cols="30" rows="10" class='form-control'></textarea>

            </td>
        </tr>
        <?php
        if ($per['kesalahan'] == "~~~~~STATUS PERKAWINAN TIDAK DI ISI~~~~") {
        ?>
            <tr>
                <td>STATUS</td>
                <td>
                    <select name="status_nikah" id="" class='form-control'>
                        <option value="kawin">Kawin</option>
                        <option value="tidak_kawin">Tidak Kawin</option>
                        <option value="janda">Janda</option>
                        <option value="cerai">Cerai</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="SIMPAN" name='submit_kawin'></td>
            </tr>
        <?php
        } else if ($per['kesalahan'] == "~~~~~STATUS PERKAWINAN TIDAK DI ISI~~NAMA IBU KANDUNG TIDAK DI ISI~~") {
        ?>
            <tr>
                <td>STATUS</td>
                <td>
                    <select name="status_nikah" id="" class='form-control'>
                        <option value="kawin">Kawin</option>
                        <option value="tidak_kawin">Tidak Kawin</option>
                        <option value="janda">Janda</option>
                        <option value="cerai">Cerai</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>NAMA IBU KANDUNG</td>
                <td><input type="text" name="ibu_kandung" id="" class='form-control'></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="KONFIRMASI" class='btn btn-info' name='submit_kawin_ibu'></td>
            </tr>
        <?php
        } else if ($per['kesalahan'] == "~~~~~~~NAMA IBU KANDUNG TIDAK DI ISI~~") {
        ?>

            <tr>
                <td>NAMA IBU KANDUNG</td>
                <td><input type="text" name="ibu_kandung" id="" class='form-control'></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="KONFIRMASI" class='btn btn-info' name='submit_ibu'></td>
            </tr>
        <?php
        } else if ($per['kesalahan'] == "TGL. LAHIR TIDAK SAMA DENGAN TGL. LAHIR KTP~~~~~~~~~") {
        ?>

            <tr>
                <td>TANGGAL LAHIR</td>
                <td><input type="date" name="tgl_lahir" id="" class='form-control'></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="KONFIRMASI" class='btn btn-info' name='submit_tgl'></td>
            </tr>
        <?php
        } else if ($per['kesalahan'] == "~~~~~~ALAMAT RUMAH TIDAK DI ISI~~~") {
        ?>

            <tr>
                <td>ALAMAT</td>
                <td><input type="text" name="alamat" id="" class='form-control'></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="KONFIRMASI" class='btn btn-info' name='submit_alamat'></td>
            </tr>
        <?php
        } else if ($per['kesalahan'] == "~~~~~STATUS PERKAWINAN TIDAK DI ISI~~~~NOMOR NIK TERDAPA DUPLIKASI") {
        ?>
        
            <tr>
                <td>STATUS</td>
                <td>
                    <select name="status_nikah" id="" class='form-control'>
                        <option value="kawin">Kawin</option>
                        <option value="tidak_kawin">Tidak Kawin</option>
                        <option value="janda">Janda</option>
                        <option value="cerai">Cerai</option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td> NIK </td>
                <td><input type="text" name="nik" minlength="16" maxlength="16" id="" class='form-control'></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="KONFIRMASI" class='btn btn-info' name='submit_kawin_nik'></td>
            </tr>
        <?php
        }
    else {//if ($per['kesalahan'] == "~~~~~STATUS PERKAWINAN TIDAK DI ISI~~~~NOMOR NIK TERDAPA DUPLIKASI") {
        ?>
        <tr>
                <td>TANGGAL LAHIR</td>
                <td><input type="date" name="tgl_lahir" id="" class='form-control'></td>
            </tr>
            <tr>
                <td>STATUS</td>
                <td>
                    <select name="status_nikah" id="" class='form-control'>
                        <option value="kawin">Kawin</option>
                        <option value="tidak_kawin">Tidak Kawin</option>
                        <option value="janda">Janda</option>
                        <option value="cerai">Cerai</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>ALAMAT</td>
                <td><input type="text" name="alamat" id="" class='form-control'></td>
            </tr>
            <tr>
                <td> NIK </td>
                <td><input type="text" name="nik" minlength="16" maxlength="16" id="" class='form-control'></td>
            </tr>
            <tr>
                <td>NAMA IBU KANDUNG</td>
                <td><input type="text" name="ibu_kandung" id="" class='form-control'></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="KONFIRMASI" class='btn btn-info' name='submit_semua'></td>
            </tr>
        <?php
        }
        ?>
    </table>
</form>
<!-- PROSES -->
<?php
if (isset($_POST['submit_kawin'])) {
    $status_nikah = $_POST['status_nikah'];
    $no_hp = $_POST['no_hp'];
    $keterangan = $_POST['keterangan'];
    mysqli_query($con, "UPDATE `perbaikan` SET status_pernikahan='$status_nikah' , no_hp='$no_hp',keterangan_lain='$keterangan', status='sudah'  WHERE `id_perbaikan` = '$id_perbaikan'; ");
    pindah($url . $menu . "perbaikan_sl");
}
if (isset($_POST['submit_kawin_ibu'])) {
    $status_nikah = $_POST['status_nikah'];
    $no_hp = $_POST['no_hp'];
    $keterangan = $_POST['keterangan'];
    $ibu = $_POST['ibu_kandung'];
    mysqli_query($con, "UPDATE `perbaikan` SET nama_ibu_kandung='$ibu', status_pernikahan='$status_nikah' , no_hp='$no_hp',keterangan_lain='$keterangan', status='sudah'  WHERE `id_perbaikan` = '$id_perbaikan'; ");
    pindah($url . $menu . "perbaikan_sl");
}
if (isset($_POST['submit_ibu'])) {
    $status_nikah = $_POST['status_nikah'];
    $no_hp = $_POST['no_hp'];
    $keterangan = $_POST['keterangan'];
    $ibu = $_POST['ibu_kandung'];
    mysqli_query($con, "UPDATE `perbaikan` SET nama_ibu_kandung='$ibu', no_hp='$no_hp',keterangan_lain='$keterangan', status='sudah'  WHERE `id_perbaikan` = '$id_perbaikan'; ");
    pindah($url . $menu . "perbaikan_sl");
}
if (isset($_POST['submit_tgl'])) {
    $status_nikah = $_POST['status_nikah'];
    $no_hp = $_POST['no_hp'];
    $keterangan = $_POST['keterangan'];
    $tgl_lahir = $_POST['tgl_lahir'];
    mysqli_query($con, "UPDATE `perbaikan` SET tgl_lahir='$tgl_lahir', no_hp='$no_hp',keterangan_lain='$keterangan', status='sudah'  WHERE `id_perbaikan` = '$id_perbaikan'; ");
    pindah($url . $menu . "perbaikan_sl");
}
if (isset($_POST['submit_alamat'])) {
    $status_nikah = $_POST['status_nikah'];
    $no_hp = $_POST['no_hp'];
    $keterangan = $_POST['keterangan'];
    $alamat = $_POST['alamat'];
    mysqli_query($con, "UPDATE `perbaikan` SET alamat='$alamat', no_hp='$no_hp',keterangan_lain='$keterangan', status='sudah'  WHERE `id_perbaikan` = '$id_perbaikan'; ");
    pindah($url . $menu . "perbaikan_sl");
}
if (isset($_POST['submit_kawin_nik'])) {
    $status_nikah = $_POST['status_nikah'];
    $no_hp = $_POST['no_hp'];
    $keterangan = $_POST['keterangan'];
    $nik = $_POST['nik'];
    mysqli_query($con, "UPDATE `perbaikan` SET nik_ktp='$nik', status_pernikahan='$status_nikah', no_hp='$no_hp',keterangan_lain='$keterangan', status='sudah'  WHERE `id_perbaikan` = '$id_perbaikan'; ");
    pindah($url . $menu . "perbaikan_sl");
}
if (isset($_POST['submit_semua'])) {
    $status_nikah = $_POST['status_nikah'];
    $no_hp = $_POST['no_hp'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $ibu_kandung = $_POST['ibu_kandung'];
    $keterangan = $_POST['keterangan'];
    $nik = $_POST['nik'];
    mysqli_query($con, "UPDATE `perbaikan` SET
     nik_ktp='$nik',
     no_hp='$no_hp',
     tgl_lahir = '$tgl_lahir',
     keterangan_lain='$keterangan',
     ibu_kandung='$ibu_kandung',
     status_pernikahan='$status_nikan',

      status='sudah'  WHERE `id_perbaikan` = '$id_perbaikan'; ");
    pindah($url . $menu . "perbaikan_sl");
}
?>