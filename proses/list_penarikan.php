<?php
if (isset($_GET['tgl'])) {
    $qtgl = $_GET['tgl'];
} else {
    $qtgl = date("Y-m-d");
}

if (isset($_GET['del'])) {
    $id = aman($con, $_GET['id']);
    $q = mysqli_query($con, "DELETE FROM `penarikan_simpanan` WHERE `id_penarikan` = '$id'; ");
    if ($q) {
        alert("Berhasil dihapus");
    } else {
        alert("Gagal disimpan");
    }
    pindah("$url" . "$menu" . "list_penarikan");
} else if (isset($_GET['edit'])) {
    $id = aman($con, $_GET['id']);
    $q1= mysqli_query($con,"select * from penarikan_simpanan where id_penarikan='$id'");
    $pen = mysqli_fetch_array($q1);

    if(isset($_POST['edit'])){
        $id_anggota = $_POST['id_nasabah'];
        $nominal = $_POST['nominal'];
        $qEdit = mysqli_query($con," UPDATE `penarikan_simpanan` SET `nominal_penarikan` = '$nominal',id_anggota='$id_anggota' WHERE `id_penarikan` = '$id'; 
        ");
        if($qEdit){
            alert("Berhasil");
        }
        else{
            pesan("gagal disimpan");
        }
        pindah("$url" . "$menu" . "list_penarikan");
    }

?>

    <form action="" method="post" class="form-horizontal">
        <fieldset>

            <!-- Form Name -->
            <legend>EDIT PENARIKAN</legend>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="id">ID ANGGOTA</label>
                <div class="col-md-4">
                    <input id="id" name="id_nasabah" type="text" value="<?=$pen['id_anggota']?>" placeholder="ID NASABAH" class="form-control input-md">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">NOMINAL</label>
                <div class="col-md-4">
                    <input id="textinput" name="nominal" value="<?=$pen['nominal_penarikan']?>"  type="text" placeholder="placeholder" class="form-control input-md">

                </div>
            </div>

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="edit"></label>
                <div class="col-md-4">
                    <button id="edit" name="edit" class="btn btn-primary">SIMPAN</button>
                </div>
            </div>

        </fieldset>

    </form>
<?php
}
?>
<div class='content table-responsive'>
    <h2 class='page-header'>Penarikan Simpanan oleh Manager</h2>
    <a href="<?= $url . $menu ?>penarikan_simpanan&list=cari&cari=FILTER" class='btn btn-success'>
        <i class="fa fa-plus"></i> Tambah
    </a>
    <a href="<?= $url . "export/penarikan_simpanan.php?" ?>upk&tgl=<?= $qtgl ?>" class='btn btn-danger'>
        <i class="fa fa-file-excel-o"></i> EXPORT
    </a>
    <br>
    <form method='get' action='<?php echo $url . $menu ?>list_penarikan'>
        <input type=hidden name='menu' value='list_penarikan' />
        <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
        <input type=submit name='cari' value='CARI' />
    </form>
    <table class='table table-bordered'>
        <tr>
            <th>No</th>
            <th>STAFF</th>
            <th>ID Nasabah</th>
            <th>ID</th>
            <th>Group</th>
            <th>Center</th>
            <th>Nasabah</th>
            <th>Nominal</th>
            <th>#</th>
        </tr>

        <?php
        $tgl = date("Y-m-d");
        $total_penarikan = 0;
        $penarikan = mysqli_query($con, "SELECT * FROM penarikan_simpanan left JOIN daftar_nasabah ON daftar_nasabah.`id_nasabah`=penarikan_simpanan.`id_anggota` join karyawan on karyawan.id_karyawan=penarikan_simpanan.id_karyawan where penarikan_simpanan.tgl_penarikan='$qtgl' and penarikan_simpanan.id_cabang='$id_cabang'  order by karyawan.nama_karyawan asc");
        while ($simp = mysqli_fetch_array($penarikan)) {
            $total_penarikan = $total_penarikan + $simp['nominal_penarikan'];
            $kel = $simp['id_detail_nasabah'];
            $kel = explode("/", $kel);
            $kel = $kel[2];
        ?>
            <tr>
                <td><?= $no++ ?>.</td>
                <td><?= $simp['nama_karyawan'] ?></td>
                <td><?= $simp['id_detail_nasabah'] ?></td>
                <td><?= $simp['id_anggota'] ?></td>

                <td> <?= $kel ?></td>
                <td><?= $simp['no_center'] ?></td>

                <td><?= $simp['nama_nasabah'] ?></td>
                <td><?= rupiah($simp['nominal_penarikan']) ?></td>
                <td>
                    <a href="<?= $url . $menu ?>list_penarikan&del&id=<?= $simp['id_penarikan'] ?>" class="btn btn-danger"><i class='fa fa-times'></i></a>
                    <a href="<?= $url . $menu ?>list_penarikan&edit&id=<?= $simp['id_penarikan'] ?>" class="btn btn-info"><i class='fa fa-edit'></i></a>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="7">Total Penarikan</th>
            <th><?= rupiah($total_penarikan) ?></th>
        </tr>
    </table>


</div>