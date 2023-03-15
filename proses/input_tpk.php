<h3>INPUT ID TOPUP KHUSUS</h3>
<form action="" method="post">
    <textarea name="tpk" id="" cols="28" placeholder="AGT/051/03/367-009570
AGT/051/03/367-009569
9570
9569
" class='form-control' rows="10"></textarea>
    <input type="submit" value="SIMPAN" name='input_tpk' class='btn btn-danger btn-lg'>
</form>
<br>
<br>
<br>

<?php
if (isset($_POST['input_tpk'])) {
    $text = $_POST['tpk'];
    $q = "";
    $pecah = explode(PHP_EOL, $text);
    foreach ($pecah as $i) {
        $id_nasabah = trim($i);
        if (substr($id_nasabah, 0, 3) == 'AGT') {
            if ($id_nasabah != "") {
                $ID = (int)explode("-", $id_nasabah)[1];
                $cek = mysqli_query($con, "select id from tpk where id_cabang='$id_cabang' and id_nasabah='$ID' and id_detail_nasabah='$id_nasabah'");
                if (!mysqli_num_rows($cek)) {
                    $q = mysqli_query($con, "INSERT INTO `tpk` (`id_nasabah`, `id_detail_nasabah`, `id_cabang`) VALUES ('$ID', '$id_nasabah', '$id_cabang'); ");
                }
            }
        } else {
            if ($id_nasabah != "") {
                $cek = mysqli_query($con, "select id_detail_nasabah from daftar_nasabah where id_cabang='$id_cabang' and id_nasabah='$id_nasabah' ");
                $cek1 = mysqli_fetch_array($cek);
                $ID = $cek1['id_detail_nasabah'];
                $cek_tpk = mysqli_query($con, "SELECT id from tpk where id_cabang='$id_cabang' and id_nasabah='$id_nasabah'");
                if (!mysqli_num_rows($cek_tpk)) {
                    $q = mysqli_query($con, "INSERT INTO `tpk` (`id_nasabah`, `id_detail_nasabah`, `id_cabang`) VALUES ('$id_nasabah', '$ID', '$id_cabang'); ");
                }
            }
        }
    }

    if ($q) {
        pesan("Berhasil disimpan", 'success');
    }
}
