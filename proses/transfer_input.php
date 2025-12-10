<div class='content table-responsive'>
    <h2 class='page-header'>INPUT ANGGOTA TRANSFER
    </h2>

    <?php
    if (!isset($_GET['id_tf'])) {
    ?>
        <div class="col-sm-5">
            <form action="" method="post" enctype="multipart/form-data">
                <table class="table">
                    <tr>
                        <td>INPUT BUKTI TF</td>
                        <td>
                            <input type="file" name="image" accept="image/*" class='form-control'>
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td><input type="date" name="tanggal" id="" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Total Transfer</td>
                        <td><input type="number" name="total_tf" min="0" id="" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td><textarea type="text" name="keterangan" id="" class="form-control"></textarea></td>
                    </tr>
                    <tr>
                        <th></th>
                        <th>
                            <input type="submit" name='upload' value="Upload Gambar" class='btn btn-info'>
                        </th>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-sm-12">
            <table class="table">
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>TRANSFER</th>
                    <th>KETERANGAN</th>
                    <th>DETAIL</th>
                    <th>STATUS</th>
                    <th>#</th>
                </tr>
                <?php
                $q = mysqli_query($con, "select * from bukti_tf where id_karyawan='$id_karyawan' order by tanggal desc");
                while ($r = mysqli_fetch_assoc($q)) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $r['tanggal'] ?></td>
                        <td><?= rupiah($r['total_nominal']) ?></td>
                        <td><?= $r['keterangan'] ?></td>
                        <td>
                            <table>

                                <?php
                                $q1 = mysqli_query($con, "SELECT * from detail_tf where id_bukti='$r[id_bukti]'");
                                while ($row = mysqli_fetch_assoc($q1)) {
                                ?>
                                    <tr>
                                        <td><?= $row['center'] ?>/<?= $row['kelompok'] ?>-</td>
                                        <td><?= $row['nama_nasabah'] ?>-</td>
                                        <td><?= rupiah($row['total']) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </td>
                        <td><?= strtoupper($r['status']) ?></td>
                        <td>
                            <?php
                            if ($r['status'] == 'pending') {
                            ?>
                                <a href="<?= $url . $menu . "transfer_input&id_tf=" . $r['id_bukti'] ?>" class="btn btn-danger">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            <?php
                            } else {
                            ?>
                                <a href="<?= $url . $menu . "transfer_input&id_tf=" . $r['id_bukti'] ?>" class="btn btn-success">Q</a>
                            <?php

                            }
                            ?>

                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    <?php
    } else {
        $id_tf = aman($con, $_GET['id_tf']);
        $cek = mysqli_query($con, "SELECT * from bukti_tf tf 
        join cabang c on c.id_cabang=tf.id_cabang
        join karyawan k on k.id_karyawan=k.id_karyawan
        where tf.id_bukti='$id_tf'");
        $bukti = mysqli_fetch_assoc($cek);

        if (!mysqli_num_rows($cek)) {
            alert("Tidak ditemukan!");
            pindah($url);
        }
    ?>
        <h1>Detail TF</h1>
        <div class="col-sm-12">
            Bukti <br>
            <table class="table table-bordered">
                <tr>
                    <td><img src="<?= $url ?>/assets/tf/<?= $bukti['nama_file'] ?>" alt="" class="img img-fluid"></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-12">
            <table class="table table-bordered">

                <tr>
                    <td>CABANG</td>
                    <td><?= ($bukti['nama_cabang']) ?></td>
                </tr>

                <tr>
                    <td>STAFF</td>
                    <td><?= ($bukti['nama_karyawan']) ?></td>
                </tr>

                <tr>
                    <td>TANGGAL</td>
                    <td><?= format_hari_tanggal($bukti['tanggal']) ?></td>
                </tr>
                <tr>
                    <td>STATUS</td>
                    <td><?= strtoupper($bukti['status']) ?></td>
                </tr>
                <tr>
                    <td>NOMINAL</td>
                    <td><?= rupiah($bukti['total_nominal']) ?></td>
                </tr>
                <tr>
                    <td>KETERANGAN</td>
                    <td><?= ($bukti['keterangan']) ?></td>
                </tr>

            </table>

            <table class="table">
                <tr>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>CTR/KLP</th>
                    <th>ID</th>
                    <th>NOMINAL</th>
                    <th>KET</th>
                </tr>
                <?php
                $q = mysqli_query($con, "SELECT * from detail_tf where id_bukti='$id_tf'");
                while ($r = mysqli_fetch_assoc($q)) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $r['nama_nasabah'] ?></td>
                        <td><?= $r['center'] ?>/<?= $r['kelompok'] ?></td>
                        <td><?= $r['id_detail_nasabah'] ?></td>
                        <td><?= rupiah($r['total']) ?></td>
                        <td><?= $r['keterangan'] ?></td>
                        <td>
                            <?php
                            if ($bukti['status'] == 'pending') {
                            ?>
                                <a href="<?= $url . $menu . "transfer_input&id_tf=" . $id_tf . "&hapus_detail&id_detail=" . $r['id_detail_tf'] ?>" onclick="return window.confirm('Apakah anda yakin akan dihapus?')" class="btn  btn-sm"> <i class="fa fa-times"></i></a>
                            <?php
                            }
                            ?>

                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>

            <?php

            $hitung = mysqli_query($con, "select sum(total) as total from detail_tf where id_bukti='$id_tf'");
            $hitung = mysqli_fetch_assoc($hitung);
            $total =  $hitung['total'];
            if ($total == $bukti['total_nominal']) {
                if ($bukti['status'] == 'simpan') {
                } else {


            ?>
                    <a href="<?= $url . $menu . "transfer_input&id_tf=" . $id_tf . "&simpan" ?>" class="btn btn-lg btn-danger">SIMPAN</a>
                <?php
                }
            } else {
                $kurang =  $bukti['total_nominal'] - $total;
                ?>
                <form action="" method="post">
                    <h3>Tambah</h3>
                    <table class="table">
                        <tr>
                            <td>ID NASABAH</td>
                            <td>
                                <input name="id_nsb" class='form-control' id="id_nasabah">
                            </td>

                        </tr>
                        <tr>
                            <td>
                                Detail
                            </td>
                            <td>
                                <div id="detail_nasabah"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>Nominal</td>
                            <td>
                                <input type="number" required max='<?= $kurang ?>' required name="nominal" class='form-control' id="">
                            </td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>
                                <input type="text" name="keterangan" class='form-control' id="">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" value="SIMPAN" name='tambah_nsb' class="btn btn-info">
                            </td>
                        </tr>
                    </table>
                </form>

            <?php
            }
            if (isset($_POST['tambah_nsb'])) {
                $table = "daftar_nasabah";
                $id_nsb = $_POST['id_nsb'];
                $query = mysqli_query($con, "SELECT * from $table left JOIN   karyawan on karyawan.id_karyawan=$table.id_karyawan where 
            $table.id_nasabah = '$id_nsb'  

            and $table.id_cabang='$id_cabang' 
            ");
                $cek = mysqli_fetch_assoc($query);
                $nominal = $_POST['nominal'];
                $ket = $_POST['keterangan'];
                $q = "INSERT INTO detail_tf 
            (id_bukti, id_nasabah, id_detail_nasabah, center, kelompok, nama_nasabah, total, keterangan)
            VALUES 
            ($id_tf, '$id_nsb', '$cek[id_detail_nasabah]', '$cek[no_center]', '$cek[kelompok]', '$cek[nama_nasabah]', '$nominal', '$ket')
            ";
                mysqli_query($con, $q);
                pindah($url . $menu . "transfer_input&id_tf=" . $id_tf);
            }
            ?>
        </div>

    <?php
    }
    ?>

</div>
<script>
    var url = "<?= $url ?>";
    var cabang = "<?= $id_cabang ?>";
    $(document).ready(function() {
        $("#id_nasabah").on('change', function() {
            var id = this.value
            $.get(url + "api/detail_nsb.php?cab=" + cabang + "&id_nsb=" + id, function(data, status) {
                $("#detail_nasabah").html(data);

            });
        });
    });
</script>
<?php
if (isset($_POST['upload'])) {
    $uploadDir = "assets/tf/"; // Folder penyimpanan gambar
    $newFileName = "tf-" . $id_cabang . "-" . date("Ymdhis"); // Nama baru untuk gambar
    $uploadFile = $uploadDir . $newFileName . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    // Periksa apakah file adalah gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        // Cek jenis file
        if ($imageFileType == "jpg" || $imageFileType == "jpeg" || $imageFileType == "png") {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
                //KETIKA BERHASIL UPLOAD GAMBAR
                $nama_file = $newFileName . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $tanggal = $_POST['tanggal'];
                $total_tf = $_POST['total_tf'];

                $keterangan = aman($con, $_POST['keterangan']);
                $q = mysqli_query($con, "INSERT INTO bukti_tf (`nama_file`, `tanggal`, `id_cabang`, `id_karyawan`, `status`, `keterangan`, `total_nominal`)
                VALUES 
                ('$nama_file', '$tanggal', '$id_cabang', '$id_karyawan', 'pending', '$keterangan', $total_tf)
                ");
                $id_tf = mysqli_insert_id($con);
                if ($q) {
                    pindah($url . $menu . "transfer_input&id_tf=" . $id_tf);
                } else {
                    pindah($url . $menu . "transfer_input");
                }
            } else {
                echo "Maaf, terjadi kesalahan saat mengunggah gambar.";
            }
        } else {
            echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
        }
    } else {
        echo "File bukan gambar.";
    }
}

if (isset($_GET['hapus_detail'])) {
    $id_detail = aman($con, $_GET['id_detail']);
    $id_tf = aman($con, $_GET['id_tf']);
    $q = mysqli_query($con, "DELETE from detail_tf where id_detail_tf='$id_detail'");

    pindah($url . $menu . "transfer_input&id_tf=" . $id_tf);
}

if (isset($_GET['simpan'])) {
    $id_tf = aman($con, $_GET['id_tf']);
    $q = mysqli_query($con, "update bukti_tf set status='simpan' where id_bukti ='$id_tf' ");
    alert("Berhasil disimpan");
    pindah($url);
}
