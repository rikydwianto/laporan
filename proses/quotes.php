<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Qoutes</h1>
            <br>
        </div>
    </div>


    <form method='post'>
        <table class='table'>
            <tr>
                <td>NAMA</td>
                <td><?php echo strtoupper($d['nama_karyawan']) ?></td>
            </tr>
            <tr>
                <td>QOUTES</td>
                <td>
                    <textarea name="quotes" class='form-control' id="" cols="30" rows="5"></textarea>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type='submit' value='TAMBAH' name='tambah' class='btn btn-danger'></td>
            </tr>
        </table>
    </form>
    <table class='table'>
        <thead>
            <tr>
                <th>No</th>
                <th>Qoutes</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqou = mysqli_query($con, "select * from quotes order by id_quotes desc");
            while ($qoutes = mysqli_fetch_assoc($sqou)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $qoutes['quotes'] ?></td>
                    <td>
                        <?php
                        $aktif = $qoutes['prioritas'];
                        if ($aktif == 'y') {
                        ?>
                            <a href="<?= $url . $menu ?>quotes&tidak-aktif&id=<?= $qoutes['id_quotes'] ?>">Tidak Aktif</a> | <br>
                        <?php
                        } else {
                        ?>
                            <a href="<?= $url . $menu ?>quotes&aktif&id=<?= $qoutes['id_quotes'] ?>">Set Aktif</a> | <br>
                        <?php
                        }
                        ?>

                        <a href="<?= $url . $menu ?>quotes&hapus&id=<?= $qoutes['id_quotes'] ?>" onclick="return window.confirm('Apakah anda yakin menghapus ini?')"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

</div>
<?php
if (isset($_GET['hapus']) && !empty($_GET['id'])) {
    $id = aman($con, $_GET['id']);
    mysqli_query($con, "delete from quotes where id_quotes='$id'");
    alert("berhasil dihapus");
    pindah($url . $menu . "quotes");
}
if (isset($_GET['aktif']) && !empty($_GET['id'])) {
    $id = aman($con, $_GET['id']);
    mysqli_query($con, "UPDATE `quotes` SET `prioritas` = 'y' WHERE  id_quotes='$id'");
    alert("berhasil di aktifkan");
    pindah($url . $menu . "quotes");
}
if (isset($_GET['tidak-aktif']) && !empty($_GET['id'])) {
    $id = aman($con, $_GET['id']);
    mysqli_query($con, "UPDATE `quotes` SET `prioritas` = null WHERE  id_quotes='$id'");
    alert("berhasil di aktifkan");
    pindah($url . $menu . "quotes");
}

if (isset($_POST['tambah'])) {

    $quotes = aman($con, $_POST['quotes']);
    $insert = mysqli_query($con, "INSERT INTO `quotes` (`quotes`,`prioritas`) VALUES ('$quotes ','y'); ");
    alert("berhasil ditambahkan");
    pindah($url . $menu . "quotes");
}
?>