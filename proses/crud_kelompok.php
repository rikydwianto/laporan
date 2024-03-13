<div class='content table-responsive'>
    <h2 class='page-header' style="font-style: italic;">DAFTAR KELOMPOK </h2>
    <a href="<?php echo "$url$menu" . "setting_kelompok" ?>" class='btn btn-danger'><i class='fa fa-eyes'></i> Atur Kelompok / tim</a>
    <form method="post" action="">
        <h3>Tambah Kelompok</h3>
        <table>
            <?php $nama_group = $_GET['namagroup']; ?>
            <?php $nett = $_GET['cash']; ?>
            <tr>
                <td>Nama Group</td>
                <td><input type="text" value="<?= $nama_group ?>" class='form-control' name="nama_group" id="">
                    <br> <small>Tambahkan Urutan Kelompok misal 01 - senin</small>
                </td>
            </tr>
            <tr>
                <td>CashFlow Kelompok</td>
                <td><input type="number" value="<?= $nett ?>" class='form-control' name="cashflow" id="">
                    <br> <small>Untuk target per kelompok</small>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="simpan_kelompok" value='SIMPAN' class='btn btn-info' id=""></td>
            </tr>
        </table>
        <table class='table'>
            <tr>
                <td>No.</td>
                <td>Nama Kelompok</td>
                <td>Chasflow Kelompok</td>
                <td>#</td>
            </tr>
            <?php
            $no = 1;
            $cekgroup = mysqli_query($con, "select * from `group` where id_cabang='$id_cabang'");
            while ($tampilGroup = mysqli_fetch_assoc($cekgroup)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $tampilGroup['nama_group'] ?></td>
                    <td><?= $tampilGroup['nett_cashflow'] ?></td>
                    <td>
                        <a href="<?php echo "$url$menu" . "crud_kelompok&edit&idgroup=$tampilGroup[id_group]&namagroup=$tampilGroup[nama_group]&cash=$tampilGroup[nett_cashflow]" ?>">Edit</a>
                        <a href="<?php echo "$url$menu" . "crud_kelompok&hapus&idgroup=$tampilGroup[id_group]" ?>" onclick="window.confirm('Apakah yakin akan menghapus ini?')">Hapus</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </form>
</div>
<?php
if (isset($_POST['simpan_kelompok']) && isset($_GET['edit'])) {
    $nama_group = $_POST['nama_group'];
    $id_group = aman($con, $_GET['idgroup']);
    $cash = $_POST['cashflow'];
    mysqli_query($con, "UPDATE `group` SET nama_group='$nama_group',nett_cashflow='$cash' WHERE `id_group` = '$id_group'");
    pindah("$url$menu" . "crud_kelompok");
} else if (isset($_POST['simpan_kelompok'])) {
    $nama_group = $_POST['nama_group'];
    $cash = $_POST['cashflow'];
    mysqli_query($con, "INSERT INTO `group` (`nama_group`,nett_cashflow,id_cabang) VALUES ('$nama_group','$cash','$id_cabang'); ");
    pindah("$url$menu" . "crud_kelompok");
}
if (isset($_GET['hapus'])) {
    $id_group = aman($con, $_GET['idgroup']);
    mysqli_query($con, "DELETE FROM `group` WHERE `id_group` ='$id_group'");
    $q = mysqli_query($con, "DELETE FROM `group_user` WHERE `id_group` ='$id_group'");
    pindah("$url$menu" . "crud_kelompok");
}


?>