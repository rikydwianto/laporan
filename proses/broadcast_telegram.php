<div class='content table-responsive'>
    <h2 class='page-header'>Broadcast Telegram Bot </h2>
    <div class="col-md-6">

        <form action="" method="post">
            <table class='table'>
                <tr>
                    <td>TANGGAL</td>
                    <td><input type="date" class='' value='<?= date("Y-m-d") ?>' name='tanggal'><input required type="time" class='' value="<?= date("H:i") ?>" name='waktu'></td>
                </tr>
                <tr>
                    <td>JUDUL</td>
                    <td><input type="text" class='form-control' name='judul'></td>
                </tr>
                <tr>
                    <td>DESKRIPSI</td>
                    <td>
                        <textarea name="deskripsi" id="" cols="30" rows="10" class='form-control'></textarea>
                    </td>
                </tr>

                <tr>
                    <td>PENGULANGAN HARI</td>
                    <td>
                        <input type="number" name='ulang' value='1' min='1' class='form-control'>
                    </td>
                </tr>
                <tr>
                    <td>ID TELE</td>
                    <td>
                        <input type="text" readonly name='tele' value='' class='form-control'>
                    </td>
                </tr>
                <tr>
                    <td> </td>
                    <td>
                        <input type="submit" value='TAMBAH' class='btn btn-success' name='tambah'>
                    </td>
                </tr>

            </table>
        </form>
        <?php
        if (isset($_GET['hapus'])) {
            $id = aman($con, $_GET['id']);
            mysqli_query($con, "delete from tasklist where id='$id'");
            alert("Berhasil dihapus");

            pindah("$url$menu" . "broadcast_telegram");
        }

        if (isset($_POST['kirim'])) {
            //  $judul = $_POST['judul'];
            $tele = $_POST['tele'];
            $deskripsi = $_POST['deskripsi'];
            $pesan = urlencode("$deskripsi");
            $url_pesan = "https://api.telegram.org/$token1/sendMessage?parse_mode=html&chat_id=$tele&text=$pesan";
            file_get_contents($url_pesan);
            pindah("$url$menu" . "broadcast_telegram");
            exit;
        }
        if (isset($_POST['tambah'])) {
            $ulang  = $_POST['ulang'];
            for ($i = 1; $i <= $ulang; $i++) {
                $judul = $_POST['judul'];
                $deskripsi = $_POST['deskripsi'];
                $tgl = $_POST['tanggal'];
                $kurang = $i - 1;
                if ($ulang > 1) {
                    $tgl =  date("Y-m-d", (strtotime("+$kurang day", strtotime(date("Y-m-d")))));
                }
                $waktu = $_POST['waktu'];
                $tele = $_POST['tele'];

                $q = mysqli_query($con, "INSERT INTO `tasklist` (`id`, `tasklist`, `deskripsi_tasklist`, `waktu`, `tgl_tasklist`, `status`, `id_karyawan`, `telegram`) 
                VALUES (NULL, '$judul', '$deskripsi', '$waktu', '$tgl', 'belum', '$id_karyawan', '$tele'); 
                ");
            }
            if ($q) {
                pesan("Berhasil disimpan, sampai dengan $tgl", 'success');
                pindah("$url$menu" . "broadcast_telegram");
            }
        }

        ?>
    </div>
    <div class='col-md-6'>
        <h2>KIIRIM PESAN LANGSUNG KIRIM</h2>
        <form action="" method="post">
            <table class='table'>


                <tr>
                    <td>DESKRIPSI</td>
                    <td>
                        <textarea name="deskripsi" id="" cols="30" rows="10" class='form-control'></textarea>
                    </td>
                </tr>


                <tr>
                    <td>ID TELE</td>
                    <td>
                        <select name="tele" id="" class='form-control'>
                            <option value="">PILIH</option>
                            <?php
                            $a = mysqli_query($con, "select * from karyawan join cabang on karyawan.id_cabang=cabang.id_cabang where status_karyawan='aktif'  order by nama_karyawan asc");
                            while ($rtele = mysqli_fetch_assoc($a)) {
                                if ($rtele['id_telegram'] > 0) {

                            ?>
                                    <option value="<?= $rtele['id_telegram']; ?>"><?= $rtele['nama_karyawan']; ?> - <?= $rtele['nama_cabang']; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td> </td>
                    <td>
                        <input type="submit" value='KIRIM' class='btn btn-danger' name='kirim'>
                    </td>
                </tr>

            </table>
        </form>
    </div>
    <table class='table'>
        <tr>
            <th>NO</th>
            <th>JUDUL</th>
            <th>DESKRIPSI</th>
            <th>TANGGAL JAM</th>
            <th>STATUS</th>
            <th></th>
        </tr>
        <?php
        $qlihat = mysqli_query($con, "select * from tasklist where id_karyawan='$id_karyawan'");
        while ($lihat = mysqli_fetch_assoc($qlihat)) {
        ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $lihat['tasklist'] ?></td>
                <td><?= $lihat['deskripsi_tasklist'] ?></td>
                <td><?= $lihat['tgl_tasklist'] ?> <?= $lihat['waktu'] ?></td>
                <td><?= $lihat['status'] ?></td>
                <td>
                    <a href="<?= $url . $menu ?>broadcast_telegram&hapus&id=<?= $lihat['id'] ?>" class="btn">
                        <i class="fa fa-times"></i>
                    </a>
                </td>
            </tr>
        <?php
        }

        ?>
    </table>
</div>