<div class='content table-responsive'>
    <h2 class='page-header'>SELURUH CABANG</h2>
    <hr />
    <!-- Button to Open the Modal -->
    <a href='<?= $url . $menu ?>cabang&tambah' class="btn btn-success">
        <i class="fa fa-plus"></i> Tambah Cabang
    </a>
    <a href='<?= $url . $menu ?>cabang&tambah_wilayah' class="btn btn-info">
        <i class="fa fa-plus"></i> Tambah Wilayah
    </a>
    <br>
    <?php
    if (isset($_GET['backup'])) {
        // Dapatkan daftar tabel dalam database
        $tables = array();
        $result = mysqli_query($con, "SHOW TABLES");
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
        $exclude_table = 'deliquency';
        $tables = array_diff($tables, array($exclude_table));

        // Buat file SQL
        $file_name = "backup_id_cabang_$id_cabang.sql";
        $sql_content = "-- Backup untuk semua tabel terkait dengan id_cabang $id_cabang dalam database \n\n";

        // Loop melalui setiap tabel
        foreach ($tables as $table) {
            // Periksa apakah tabel memiliki kolom id_cabang
            $check_query = "SHOW COLUMNS FROM $table LIKE 'id_cabang'";
            $check_result = mysqli_query($con, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                // Dapatkan data dari tabel yang sesuai dengan id_cabang
                $data_query = "SELECT * FROM $table WHERE id_cabang = ?";
                $stmt = mysqli_prepare($con, $data_query);
                mysqli_stmt_bind_param($stmt, 'i', $id_cabang);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

                if (!empty($data)) {
                    // Dapatkan struktur tabel
                    $result = mysqli_query($con, "SHOW CREATE TABLE $table");
                    $row = mysqli_fetch_assoc($result);
                    $table_structure = $row['Create Table'];

                    // Tambahkan struktur dan data ke dalam file SQL
                    $sql_content .= "-- Struktur Tabel $table\n\n$table_structure;\n\n-- Data Tabel $table\n";
                    foreach ($data as $row) {
                        $values = "'" . implode("', '", $row) . "'";
                        $sql_content .= "INSERT INTO $table VALUES ($values);\n";
                    }

                    $sql_content .= "\n";
                }
            } else {
                echo $table . "<br/>";
            }
        }
        // echo $sql_content;
    }
    if (isset($_GET['del'])) {
        $iddet = $_GET['idcab'];
        $cek_table = mysqli_query($con, "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE '$db_name';");
        $del = mysqli_query($con, "delete from cabang where id_cabang='$iddet'");
        while ($r = mysqli_fetch_assoc($cek_table)) {
            if ($r['TABLE_TYPE'] != 'VIEW') {
                mysqli_query($con, "delete from " . $r['TABLE_NAME'] . " where id_cabang='$iddet'");
            }
        }
        if ($del) {
            pesan("Cabang Berhasil dihapus", 'success');
        }
    }
    if (isset($_GET['tambah'])) {
    ?>
        <div class="col-md-6">
            <form method="post">
                <h3>Tambah Cabang</h3>
                <table class='table'>

                    <tr>
                        <td>Kode Cabang</td>
                        <td>
                            <input name='kode_cabang' class="form-control"></input>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama Cabang</td>
                        <td>
                            <input name='nama_cabang' class="form-control"></input>
                        </td>
                    </tr>
                    <tr>
                        <td>SINGKATAN</td>
                        <td>
                            <input name='singkatan_cabang' class="form-control"></input>
                        </td>
                    </tr>
                    <tr>
                        <td>Latitude</td>
                        <td>
                            <input name='lat' value="" class="form-control"></input>
                        </td>
                    </tr>

                    <tr>
                        <td>Longitude</td>
                        <td>
                            <input name='lng' value="" class="form-control"></input>
                        </td>
                    </tr>

                    <tr>
                        <td>Wilayah</td>
                        <td>
                            <select name='wilayah' required class="form-control" aria-label="Default select example " id='wilayah'>
                                <option value=''> -- Silahkan Pilih Cabang --</option>
                                <?php
                                $jab = mysqli_query($con, "select * from wilayah ");
                                while ($wil = mysqli_fetch_assoc($jab)) {
                                    echo "<option value='$wil[id_wilayah]' ><b>$wil[wilayah]</b></option>";
                                }
                                ?>
                            </select>

                        </td>

                    </tr>

                    <tr>
                        <td> </td>
                        <td>
                            <input type='submit' name='tambah_cabang' class="btn btn-success" value='TAMBAH CABANG'></input>
                        </td>
                    </tr>

                </table>

            </form>

        </div>
    <?php
    }



    if (isset($_GET['edit'])) {
        $idcab = $_GET['idcab'];
        $kode = $_GET['kode_cabang'];
        $nama = $_GET['nama_cabang'];
        $wilayah = $_GET['wilayah'];
        $singkatan = $_GET['singkatan_cabang'];
        $lat = $_GET['lat'];
        $lng = $_GET['lng'];
    ?>
        <div class="col-md-6">
            <form method="post">
                <h3>EDIT Cabang</h3>
                <table class='table'>

                    <tr>
                        <td>Kode Cabang</td>
                        <td>
                            <input type="hidden" name='idcab' value="<?= $idcab ?>" class="form-control"></input>
                            <input name='kode_cabang' value="<?= $kode ?>" class="form-control"></input>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama Cabang</td>
                        <td>
                            <input name='nama_cabang' value="<?= $nama ?>" class="form-control"></input>
                        </td>
                    </tr>
                    <tr>
                        <td>Singkatan Cabang</td>
                        <td>
                            <input name='singkatan_cabang' value="<?= $singkatan ?>" class="form-control"></input>
                        </td>
                    </tr>

                    <tr>
                        <td>Latitude</td>
                        <td>
                            <input name='lat' value="<?= $lat ?>" class="form-control"></input>
                        </td>
                    </tr>

                    <tr>
                        <td>Longitude</td>
                        <td>
                            <input name='lng' value="<?= $lng ?>" class="form-control"></input>
                        </td>
                    </tr>

                    <tr>
                        <td>Wilayah</td>
                        <td>
                            <select name='wilayah' required class="form-control" aria-label="Default select example " id='wilayah'>
                                <option value=''> -- Silahkan Pilih Wilayah --</option>
                                <?php
                                $jab = mysqli_query($con, "select * from wilayah ");
                                while ($wil = mysqli_fetch_assoc($jab)) {
                                    if ($wilayah == $wil['id_wilayah'])
                                        echo "<option value='$wil[id_wilayah]' selected><b>$wil[wilayah]</b></option>";
                                    else
                                        echo "<option value='$wil[id_wilayah]' ><b>$wil[wilayah]</b></option>";
                                }
                                ?>
                            </select>

                        </td>

                    </tr>

                    <tr>
                        <td> </td>
                        <td>
                            <input type='submit' name='edit_cabang' class="btn btn-success" value='SIMPAN CABANG'></input>
                        </td>
                    </tr>

                </table>

            </form>

        </div>
    <?php
    }


    if (isset($_GET['tambah_wilayah'])) {
    ?>
        <div class="col-md-6">
            <form method="post">
                <h3>Tambah Wilayah</h3>
                <table class='table'>

                    <tr>
                        <td>Nama Wilayah</td>
                        <td>
                            <input name='nama_wilayah' class="form-control"></input>
                        </td>
                    </tr>

                    <tr>
                        <td>REGIONAL</td>
                        <td>
                            <!-- 	<select name='wilayah' required class="form-control" aria-label="Default select example "id='wilayah'>
								<option value=''> -- Silahkan Pilih Cabang --</option>
								<?php
                                $jab = mysqli_query($con, "select * from wilayah ");
                                while ($wil = mysqli_fetch_assoc($jab)) {
                                    echo "<option value='$wil[id_wilayah]' ><b>$wil[wilayah]</b></option>";
                                }
                                ?>
							  </select> -->

                        </td>

                    </tr>

                    <tr>
                        <td> </td>
                        <td>
                            <input type='submit' name='tambah_wilayah' class="btn btn-success" value='TAMBAH WILAYAH'></input>
                        </td>
                    </tr>

                </table>

            </form>

        </div>
    <?php
    }






    //TAMBAH CABANG
    if (isset($_POST['tambah_cabang'])) {
        $kode = $_POST['kode_cabang'];
        $nama = $_POST['nama_cabang'];
        $wilayah = $_POST['wilayah'];
        $singkatan = $_POST['singkatan_cabang'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $qtambah = mysqli_query($con, "
			INSERT INTO `cabang` (`id_cabang`, `kode_cabang`, `nama_cabang`, `latitude`, `longitude`, `id_wilayah`,`singkatan_cabang`) VALUES (NULL, '$kode', '$nama', '$lat','$lng','$wilayah','$singkatan'); 
	  		");
        if ($qtambah) {
            pesan("Cabang Berhasil Ditambahkan");
        }
    }
    //EDIT CABANG
    if (isset($_POST['edit_cabang'])) {
        $idcab = $_POST['idcab'];
        $kode = $_POST['kode_cabang'];
        $nama = $_POST['nama_cabang'];
        $wilayah = $_POST['wilayah'];
        $singkatan = $_POST['singkatan_cabang'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $qtambah = mysqli_query($con, "
			UPDATE `cabang` SET `kode_cabang` = '$kode', singkatan_cabang='$singkatan',`nama_cabang` = '$nama', `id_wilayah` = '$wilayah', latitude='$lat', longitude='$lng' WHERE `cabang`.`id_cabang` = $idcab; 
	  		");
        if ($qtambah) {
            pesan("Cabang Berhasil DISIMPAN", "success");
            pindah("$url$menu" . "cabang");
        }
    }
    //TAMBAH Wilayah
    if (isset($_POST['tambah_wilayah'])) {

        $kode = $_POST['kode_cabang'];
        $nama = $_POST['nama_wilayah'];
        $wilayah = $_POST['wilayah'];
        $qtambah = mysqli_query($con, "
			INSERT INTO `wilayah` (`id_wilayah`, `wilayah`) VALUES (NULL, '$nama'); 
	  		");
        if ($qtambah) {
            pesan("WILAYAH Berhasil Ditambahkan");
        }
    }
    if (isset($_GET['aktifkan'])) {
        $idcab = $_GET['idcab'];
        $q = mysqli_query($con, "update cabang set status_cabang='aktif' where id_cabang='$idcab'");


        if ($q) {
            pesan("Cabang Berhasil diaktifkan", 'success');
        }
    }
    if (isset($_GET['matikan'])) {
        $idcab = $_GET['idcab'];
        $q = mysqli_query($con, "update cabang set status_cabang='nonaktif' where id_cabang='$idcab'");


        if ($q) {
            pesan("Cabang Berhasil di Non-Aktifkan", 'success');
        }
    }

    ?>



    <br>
    <br>
    <br>
    <table id='data_karyawan'>
        <thead>
            <tr>
                <th>NO</th>
                <th>WILAYAH</th>
                <th>CABANG</th>
                <th>KODE</th>
                <th>STATUS</th>

                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no_cabang = 0;
            $wil = mysqli_query($con, "select * from wilayah");
            while ($wilayah = mysqli_fetch_assoc($wil)) {
            ?>
                <tr>

                    <th><?= $no++ ?></th>
                    <th colspan="1"><?= $wilayah['wilayah'] ?></th>
                    <th>
                        <hr>
                    </th>
                    <th>
                        <hr>
                    </th>
                    <th>
                        <hr>
                    </th>
                    <th>
                        <hr>
                    </th>

                </tr>
                <?php
                $q = mysqli_query($con, "select * from cabang  where id_wilayah ='$wilayah[id_wilayah]' order by nama_cabang asc");
                while ($center = mysqli_fetch_assoc($q)) {
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $wilayah['wilayah'] ?></td>
                        <td><?= strtoupper($center['nama_cabang']); ?></td>
                        <td><?= $center['kode_cabang']; ?></td>
                        <td><?= $center['status_cabang'] == 'aktif' ? $center['status_cabang'] : "Non-Aktif"; ?></td>

                        <td>
                            <a class="btn btn-danger" href="<?= $url . $menu ?>cabang&del&idcab=<?= $center['id_cabang'] ?>" onclick="return window.confirm('Menghapus cabang dapat mempengaruhi SEMUA')"> <i class='fa fa-times'></i> Hapus</a>
                            <a class="btn btn-warning" href="<?= $url . $menu ?>cabang&edit&idcab=<?= $center['id_cabang'] ?>&kode_cabang=<?= $center['kode_cabang'] ?>&nama_cabang=<?= $center['nama_cabang'] ?>&wilayah=<?= $center['id_wilayah'] ?>&lat=<?= $center['latitude'] ?>&lng=<?= $center['longitude'] ?>&singkatan_cabang=<?= $center['singkatan_cabang'] ?>">
                                <i class='fa fa-edit'></i> EDIT</a>
                            <!-- <a class="btn btn-success" href="<?= $url . $menu ?>cabang&backup&idcab=<?= $center['id_cabang'] ?>&kode_cabang=<?= $center['kode_cabang'] ?>&nama_cabang=<?= $center['nama_cabang'] ?>&wilayah=<?= $center['id_wilayah'] ?>&lat=<?= $center['latitude'] ?>&lng=<?= $center['longitude'] ?>&singkatan_cabang=<?= $center['singkatan_cabang'] ?>">
								<i class='fa fa-database'></i> Backup</a> -->
                            <?php
                            if ($center['status_cabang'] != 'aktif') {
                            ?>
                                <a class="btn btn-success" href="<?= $url . $menu ?>cabang&aktifkan&idcab=<?= $center['id_cabang'] ?>" onclick="return window.confirm('Aktifkan Cabang')"> <i class='fa fa-eye'></i> Aktifkan</a>
                            <?php
                            } else {
                            ?>
                                <a class="btn btn-primary" href="<?= $url . $menu ?>cabang&matikan&idcab=<?= $center['id_cabang'] ?>" onclick="return window.confirm('Non-Aktifkan Cabang')"> <i class='fa fa-eye-slash'></i>
                                    Non-Aktif</a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            <?php
            }


            ?>
        </tbody>
    </table>
</div>
<!-- Button trigger modal -->