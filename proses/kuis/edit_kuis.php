<h2 style="text-align:center ;">TAMBAH KUIS</h2>
<?php
$idkuis = aman(null, $_GET['idkuis']);
if (isset($_POST['simpan_kuis'])) {
    $nama_kuis = aman($con, $_POST['nama_kuis']);
    $pembuat = aman($con, $_POST['pembuat']);
    $tgl = aman($con, $_POST['tgl']);
    $lama = aman($con, $_POST['lama']);
    $status = aman($con, $_POST['status']);
    $insert = mysqli_query($con, "
    UPDATE `kuis` SET `status` = '$status',nama_kuis='$nama_kuis',tgl_kuis='$tgl',waktu='$lama' WHERE `id_kuis` = '$idkuis'; 
    ");
    if ($insert) {
        pesan("Berhasil menambahkan soal", 'success');
        pindah("$url$menu" . 'kuis');
    } else {
        pesan("Gagal menghapus : " . mysqli_error($con), 'danger');
    }
}

$kuis = mysqli_query($con, "SELECT * FROM kuis where id_kuis='$idkuis' ");
$kuis = mysqli_fetch_assoc($kuis);
?>
<form method="post">

    <table class='table table-bordered'>
        <tr>
            <td>NAMA KUIS</td>
            <td><input type="text" value="<?= $kuis['nama_kuis'] ?>" name="nama_kuis" class="form-control"></td>
        </tr>
        <tr>
            <td>PEMBUAT</td>
            <td><input readonly type="text" value='<?= $kuis['nama_karyawan'] ?>' name="pembuat" class="form-control"></td>
        </tr>
        <tr>
            <td>TANGGAL KUIS</td>
            <td><input type="date" value='<?= date('Y-m-d') ?>' value="<?= $kuis['tgl_kuis'] ?>" name="tgl" class="form-control"></td>
        </tr>
        <tr>
            <td>LAMA(MENIT)</td>
            <td><input type="number" name="lama" value="<?= $kuis['waktu'] ?>" class="form-control"></td>
        </tr>
        <tr>
            <td>STATUS</td>
            <td>
                <select name="status" required id="" class='form-control'>
                    <?php
                    if ($kuis['status'] == 'aktif') {
                    ?>
                        <option value="aktif" selected>AKTIF</option>
                        <option value="tidakaktif">TIDAK AKTIF</option>
                    <?php
                    } else {
                    ?>
                        <option value="aktif">AKTIF</option>
                        <option value="tidakaktif" selected>TIDAK AKTIF</option>
                    <?php
                    }


                    ?>

                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="submit" value="BUAT" name="simpan_kuis" class='btn btn-danger'>
            </td>
        </tr>
    </table>
</form>