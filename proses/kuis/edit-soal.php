<?php
$id_soal = aman($con, $_GET['idsoal']);
$soal = mysqli_query($con, "SELECT * from kuis_bank_soal where id='$id_soal'");
$soal = mysqli_fetch_assoc($soal);
echo mysqli_error($con);
?>
<form method="post">
    <div class="col-lg-8">
        <h2>BUAT SOAL</h2>
        <hr>
        <table class='table'>
            <tr>
                <th>SOAL</th>
                <th><textarea name="soal" id="" class='form-control' required cols="30" rows="5"><?= $soal['soal'] ?></textarea></th>
            </tr>
            <tr>
                <th>PILIHAN</th>
                <th>
                    <div class="col-lg-8">
                        <table class='table'>
                            <?php
                            $pilihan = ['a', 'b', 'c', 'd'];
                            foreach ($pilihan as $pilih) {
                            ?>
                                <tr>
                                    <td>PILIHAN <?= strtoupper($pilih) ?> &nbsp;</td>
                                    <td> <input type="text" value='<?= $soal['pilihan_' . $pilih] ?>' required class='form-control' name='pilihan_<?= $pilih ?>' placeholder="PILIHAN <?= strtoupper($pilih) ?>"></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td>JAWABAH BENAR</td>
                                <td>
                                    <select name="jawaban" id="" class='form-control' required>
                                        <option value="">PILIHAN BENAR</option>
                                        <?php
                                        foreach ($pilihan as $pilih) {
                                            if ($pilih == $soal['pilihan_benar'])
                                                $sel = 'selected';
                                            else $sel = '';
                                        ?>
                                            <option value="<?= $pilih ?>" <?= $sel ?>> PILIHAN <?= strtoupper($pilih) ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>
                </th>
            </tr>
            <tr>
                <td>KATEGORI</td>
                <td>
                    <input type="text" value='<?= $soal['kategori'] ?>' name='kategori' class='form-control'>
                    pisahkan dengan koma jika ada beberapa kategori
                </td>
            </tr>
            <tr>
                <td>CREATED BY</td>
                <td>
                    <input type="text" readonly value='<?= $d['nama_karyawan'] ?>/<?= $d['nama_cabang'] ?>' name='pembuat' class='form-control'>

                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="submit" value='SIMPAN Soal' name='simpan' class='btn btn-danger'>

                </td>
            </tr>
        </table>
    </div>
</form>
<?php
if (isset($_POST['simpan'])) {
    $soal = aman($con, $_POST['soal']);
    $kategori = aman($con, $_POST['kategori']);
    $jawaban = aman($con, $_POST['jawaban']);
    $pilihan_a = aman($con, $_POST['pilihan_a']);
    $pilihan_b = aman($con, $_POST['pilihan_b']);
    $pilihan_c = aman($con, $_POST['pilihan_c']);
    $pilihan_d = aman($con, $_POST['pilihan_d']);
    $pembuat = aman($con, $_POST['pembuat']);

    $insert = mysqli_query($con, "
   UPDATE `kuis_bank_soal` SET `soal` = '$soal' , `pilihan_a` = '$pilihan_a' , `pilihan_b` = '$pilihan_b' , 
   `pilihan_c` = '$pilihan_c'
    , `pilihan_d` = '$pilihan_d' 
    , `pilihan_benar` = '$jawaban' 
    , `kategori` = '$kategori' 
   WHERE `id` = '$id_soal'; 

   ");
    if ($insert) {
        pesan("Berhasil Menghaspus soal", 'success');
        // pindah("$url$menu".'kuis&act=bank-soal');
    } else {
        pesan("Gagal menghapus : " . mysqli_error($con), 'danger');
    }
}
?>