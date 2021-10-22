<?php
$id_pinjaman = aman($con, $_GET['idpinjaman']);
// echo $id;
$query = mysqli_query($con, "select * from pinjaman join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan where pinjaman.id_pinjaman='$id_pinjaman' and pinjaman.id_cabang='$id_cabang'");
$pinjaman = mysqli_fetch_array($query);

?>
<form action="" method="post">

    <table class='table'>
        <tr>
            <td>Staff Sebelumnya</td>
            <td>
                Pindah ke

            </td>
        </tr>
        <tr>
            <td>
                <?=$pinjaman['nama_karyawan']?>
            </td>
            <td>
            <select name="karyawan" id="" class='form-control'>
                    <option value="">Pilih Staff</option>
                    <?php $data_karyawan  = (karyawan($con, $id_cabang)['data']);
                    for ($i = 0; $i < count($data_karyawan); $i++) {
                        $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                        if ($data_karyawan[$i]['id_karyawan'] == ($pinjaman['id_karyawan'])."s") {
                            echo "<option selected value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                        } else {
                            echo "<option value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>NO PINJAMAN</td>
            <td><?=$pinjaman['id_detail_pinjaman']?></td>
        </tr>
        <tr>
            <td>NO NASABAH</td>
            <td><?=$pinjaman['id_detail_nasabah']?></td>
        </tr>
        <tr>
            <td>NAMA NASABAH</td>
            <td><?=$pinjaman['nama_nasabah']?></td>
        </tr>
        <tr>
            <td>CENTER</td>
            <td><?=$pinjaman['center']?></td>
        </tr>
            <td></td>
            <td>
                <input type="submit" value="SIMPAN" name='pindah' class='btn btn-danger'>
            </td>
        </tr>
    </table>
</form>

<?php
if(isset($_POST['pindah'])){
    $id_staff = $_POST['karyawan'];
    $update =mysqli_query($con,"UPDATE `pinjaman` SET `id_karyawan` = '$id_staff' WHERE `id_pinjaman` = '$id_pinjaman'  ");
    if($update){
        alert("Berhasil dirubah");
        pindah($url.$menu."monitoring");
    }
    else{
        alert("gagal");
    }
}