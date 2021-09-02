<div class='content table-responsive'>
    <h2 class='page-header'>KONFIRMASI CENTER</h2>

    <?php
    if (isset($_POST['simpan'])) {
        $idcenter = $_POST['id_center'];
        $hari = $_POST['hari'];
        $jam = $_POST['jam'];
        for ($i = 0; $i < count($idcenter); $i++) {
            $del = mysqli_query($con, "UPDATE `center` SET hari='$hari[$i]',jam_center='$jam[$i]', `konfirmasi` = 'y' WHERE `id_center` = '$idcenter[$i]'; ");
        }
        if ($del) {
            pesan("Center Berhasil dikonfirmasi", 'success');
            pindah($url);
        }
    }




    ?>
    <h3>Silahkan di cek dan diganti jika tidak sesuai, setelah konfirmasi akan muncul menu laporan</h3>
    <form action="" method="post">



        <table id='data_center1' class='table table-bordered'>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>CENTER</th>
                    <th>HARI</th>
                    <th>JAM</th>
                    <th>STAFF</th>


                </tr>
            </thead>
            <tbody>

                <?php
                $q = mysqli_query($con, "select * from center where id_cabang='$id_cabang' and id_karyawan='$id_karyawan'  order by FIELD(hari,'senin','selasa','rabu','kamis') asc");
                while ($center = mysqli_fetch_assoc($q)) {

                ?>
                    <tr>
                        <td>
                            <?= $no++; ?>
                            <input type="hidden" name="id_center[]" value="<?= $center['id_center'] ?>">
                        </td>
                        <td><?= $center['no_center']; ?></td>
                        <td>
                            <select name='hari[]' width="30px">
                                <?php $hari = hari();
                                for ($i = 0; $i < count($hari); $i++) {
                                    if (strtolower($hari[$i]) == $center['hari']) {
                                        echo "<option value='" . strtolower($hari[$i]) . "' selected >$hari[$i]</option>";
                                    } else {
                                        echo "<option value='" . strtolower($hari[$i]) . "' >$hari[$i]</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="time" name="jam[]" value='<?= $center['jam_center']; ?>' id=""></td>
                        <td>
                            <select name="karyawan[]" id="" class='form-control'>
                                <option value="">Pilih Staff</option>
                                <?php $data_karyawan  = (karyawan($con, $id_cabang)['data']);
                                for ($i = 0; $i < count($data_karyawan); $i++) {
                                    $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                                    if ( $data_karyawan[$i]['id_karyawan'] == strtolower($center['id_karyawan'])) {
                                        echo "<option selected value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                                    } else {
                                        echo "<option value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>

                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><input type="submit" class='btn btn-info' value="KONFIMASI" name='simpan' /></td>

                </tr>
            </tfoot>
        </table>
    </form>
</div>