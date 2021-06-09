<div class='content table-responsive'>
    <h3 class='page-header'>DATA WILAYAH KOMIDA CAB . <?= strtoupper($d['nama_cabang']) ?></h3>
    <a href='<?= $url . $menu ?>detail_center&tambah' class='btn btn-info '><i class='fa fa-plus'></i> Detail Center</a>
    <a href='<?= $url . $menu ?>detail_center' class='btn btn-success '><i class='fa fa-eye'></i> Lihat</a>
    <a href='<?= $url ?>export/data_center.php' class='btn btn-info '><i class='fa fa-file-o'></i> Export</a>
    <br />
    <br />

    <div>
        <?php
        if (isset($_GET['tambah'])) {
        ?>
            <?php
            if (isset($_POST['tmb_detail'])) {
                $iddesa = $_POST['desa'];
                $desa1 = daftar_wilayah($con, $iddesa);
                $desa = $desa1['desa'];
                $keca = $desa1['kecamatan'];
                $alamat  = $_POST['alamat'];
                $rt  = sprintf("%03d", $_POST['rt']);
                $rw  = sprintf("%03d", $_POST['rw']);
                $keterangan  = $_POST['keterangan'];
                $center  = $_POST['center'];
                $q = mysqli_query($con, " INSERT INTO `data_center` (`kecamatan`, `desa`, `rw`, `rt`, `alamat`, `no_center`, `keterangan`, `id_cabang`,`id_karyawan`) VALUES ('$keca', '$desa', '$rw', '$rt', '$alamat', '$center', '$keterangan ', '$id_cabang','$id_karyawan'); ");
                if ($q) {
                    alert("Berhasil ditambahkan");
                    pindah("$url$menu" . "detail_center");
                } else {
                    alert("gagal Ditambahkan");
                }
            }
            ?>
            <br>
            <form action="" method="post">
                <table class="table">
                    <tr>
                        <td>Kecamatan - Desa</td>
                        <td>
                            <select name='desa' required class="form-control" aria-label="Default select example " id='detail_wilayah'>
                                <option value=''> -- Silahkan Pilih Kecamatan --</option>
                                <?php
                                $kec = mysqli_query($con, "select * from daftar_wilayah_cabang where id_cabang='$id_cabang' group by kecamatan ");
                                while ($kec1 = mysqli_fetch_assoc($kec)) {
                                    echo "<option  disabled>$kec1[kecamatan]</option>";
                                    $desa = mysqli_query($con, "select * from daftar_wilayah_cabang where id_cabang='$id_cabang' and kecamatan='$kec1[kecamatan]'");
                                    while ($TampilDesa = mysqli_fetch_array($desa)) {
                                        echo "<option value='$TampilDesa[id_daftar_wilayah]' > -   $TampilDesa[desa]</option>";
                                    }
                                }
                                ?>
                            </select>

                        </td>
                    </tr>
                    <tr>
                        <td>RT/RW</td>
                        <td>
                            <div class="col" style="float: left;">
                                <input type="text" name="rt" id="" style="width:150px" class='form-control' placeholder="RT (001)" />
                            </div>
                            <div class="col" style="float: left;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                            <div class="col">
                                <input type="text" name="rw" id="" style="width:150px" class='form-control' placeholder="RW (001)" />
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>Alamat lengkap</td>
                        <td>
                            <textarea name="alamat" id="" class='form-control' cols="10" rows="5" placeholder="Patokan Alamat"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan </td>
                        <td>
                            <textarea name="keterangan" id="" class='form-control' cols="10" rows="5" placeholder="Keterngan lain, contoh  no hp, keterangan center bermasalah atau tidak"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>NO CENTER
                            <br>
                            <i>
                                Diisi jika di RT/RW ini ada CENTER
                            </i>
                        </td>
                        <td>
                            <input type="text" name="center" placeholder="001" class='form-control' id="">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" value="SIMPAN" class='btn btn-success' name='tmb_detail' />
                        </td>
                    </tr>
                </table>

            </form>



        <?php
        } elseif (isset($_GET['edit'])) {
            //EDIT
            //PROSES EDIT
            $id = $_GET['id'];
            if (isset($_POST['edit_detail'])) {
                $iddesa = $_POST['desa'];
                $desa1 = daftar_wilayah($con, $iddesa);
                $desa = $desa1['desa'];
                $keca = $desa1['kecamatan'];
                $alamat  = $_POST['alamat'];
                $rt  = sprintf("%03d", $_POST['rt']);
                $rw  = sprintf("%03d", $_POST['rw']);
                $keterangan  = $_POST['keterangan'];
                $center  = $_POST['center'];
                $q = mysqli_query($con, "UPDATE `data_center` SET desa='$desa',kecamatan='$keca',`rw` = '$rw' , `rt` = '$rt' , `alamat` = '$alamat' , `no_center` = '$center' , `keterangan` = '$keterangan' WHERE `id_data_center` = '$id'; 
                ");
                if ($q) {
                    alert("Berhasil diedit");
                    pindah("$url$menu" . "detail_center&kecamatan=$keca&desa=$desa");
                } else {
                    alert("gagal Ditambahkan");
                }
            }

            //FORM EDIT

            $sq = mysqli_query($con, "select * from data_center where id_data_center='$id'");
            $editCenter = mysqli_fetch_array($sq);



        ?>
            <br>
            <form action="" method="post">
                <table class="table">
                    <tr>
                        <td>Kecamatan - Desa</td>
                        <td>
                            <select name='desa' required class="form-control" aria-label="Default select example " id='detail_wilayah'>
                                <option value=''> -- Silahkan Pilih Kecamatan --</option>
                                <?php
                                $kec = mysqli_query($con, "select * from daftar_wilayah_cabang where id_cabang='$id_cabang' group by kecamatan ");
                                while ($kec1 = mysqli_fetch_assoc($kec)) {
                                    echo "<option  disabled>$kec1[kecamatan]</option>";
                                    $desa = mysqli_query($con, "select * from daftar_wilayah_cabang where id_cabang='$id_cabang' and kecamatan='$kec1[kecamatan]'");
                                    while ($TampilDesa = mysqli_fetch_array($desa)) {
                                        if ($TampilDesa['desa'] == $editCenter['desa']) {

                                            echo "<option value='$TampilDesa[id_daftar_wilayah]' selected> -   $TampilDesa[desa]</option>";
                                        } else {
                                            echo "<option value='{$TampilDesa['id_daftar_wilayah']}'> -   {$TampilDesa['desa']}</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>

                        </td>
                    </tr>
                    <tr>
                        <td>RT/RW</td>
                        <td>
                            <div class="col" style="float: left;">
                                <input type="text" name="rt" id="" value="<?= $editCenter['rt'] ?>" style="width:150px" class='form-control' placeholder="RT (001)" />
                            </div>
                            <div class="col" style="float: left;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                            <div class="col">
                                <input type="text" name="rw" value="<?= $editCenter['rw'] ?>" id="" style="width:150px" class='form-control' placeholder="RW (001)" />
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>Alamat lengkap</td>
                        <td>
                            <textarea name="alamat" id="" class='form-control' cols="10" rows="5" placeholder="Patokan Alamat"><?= $editCenter['alamat'] ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan </td>
                        <td>
                            <textarea name="keterangan" id="" class='form-control' cols="10" rows="5" placeholder="Keterngan lain, contoh  no hp, keterangan center bermasalah atau tidak"><?= $editCenter['keterangan'] ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>NO CENTER
                            <br>
                            <i>
                                Diisi jika di RT/RW ini ada CENTER
                            </i>
                        </td>
                        <td>
                            <input type="text" value='<?= $editCenter['no_center'] ?>' name="center" placeholder="001" class='form-control' id="">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" value="SIMPAN" class='btn btn-success' name='edit_detail' />
                        </td>
                    </tr>
                </table>

            </form>
        <?php

            //END EDIT
        } elseif (isset($_GET['hapus'])) {
            $id = $_GET['id'];
            $q = mysqli_query($con, "delete from data_center  WHERE `id_data_center` = '$id'; 
                ");
            if ($q) {
                alert("Berhasil Dihapus");
                pindah("$url$menu" . "detail_center&kecamatan=$keca&desa=$desa");
            } else {
                alert("gagal Ditambahkan");
            }
        } elseif (isset($_GET['hapus_kecamatan'])) {
            $nama_kecamatan = $_GET['nama_kecamatan'];
            $q = mysqli_query($con, "delete from daftar_wilayah_cabang  WHERE kecamatan = '$nama_kecamatan'; 
                ");
            if ($q) {
                alert("Berhasil Dihapus");
                pindah("$url$menu" . "detail_center");
            } else {
                alert("gagal Ditambahkan");
            }
        } elseif (isset($_GET['hapus_desa'])) {
            $nama_desa = $_GET['nama_desa'];
            $q = mysqli_query($con, "delete from daftar_wilayah_cabang  WHERE desa = '$nama_desa'; 
                ");
            if ($q) {
                alert("Berhasil Dihapus");
                pindah("$url$menu" . "detail_center");
            } else {
                alert("gagal dihapus");
            }
        } else {
        ?>
            <br>
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>KECAMATAN</th>
                        <th>DESA</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $kec = mysqli_query($con, "select * from daftar_wilayah_cabang where id_cabang='$id_cabang' group by kecamatan order by kecamatan asc ");
                    while ($kecamatan = mysqli_fetch_array($kec)) {
                    ?>
                        <tr>
                            <th><?= $no++ ?></th>
                            <th>
                                <a href="<?= $url . $menu ?>detail_center&kecamatan=<?= strtolower($kecamatan['kecamatan']) ?>">
                                    <?= strtoupper($kecamatan['kecamatan']) ?>
                                </a>

                            </th>
                            <th colspan="5">
                                <?php
                                $hitung_kec = mysqli_query($con, "SELECT COUNT(kecamatan) AS kecamatan FROM daftar_wilayah_cabang where kecamatan='$kecamatan[kecamatan]' and id_cabang='$id_cabang' GROUP BY kecamatan ");
                                $hitung_kec = mysqli_fetch_array($hitung_kec);
                                $hitung_kec = $hitung_kec['kecamatan'];
                                echo "<b>" . $hitung_kec . " Desa</b> | ";

                                $hitung_alamat = mysqli_query($con, "SELECT COUNT(rt) AS rt FROM data_center WHERE kecamatan='$kecamatan[kecamatan]' AND id_cabang='$id_cabang' GROUP BY kecamatan  ");
                                $hitung_alamat = mysqli_fetch_array($hitung_alamat);
                                $hitung_alamat = $hitung_alamat['rt'];
                                echo "<b>" . (int)$hitung_alamat . " Alamat</b>";
                                ?>
                            </th>
                            <th>
                                <?php
                                if ($jabatan == 'BM' || $jabatan == 'ASM' || $su == 'y') {
                                ?>
                                    <a href="<?= $url . $menu ?>detail_center&hapus_kecamatan&nama_kecamatan=<?= $kecamatan['kecamatan'] ?>" onclick="return window.confirm('yakin akan menghapus ini Kecamatan : <?= $kecamatan['kecamatan'] ?>?')" class='btn'>
                                        <i class='fa fa-times'></i>
                                    </a>
                                <?php
                                }
                                ?>
                            </th>
                        </tr>
                        <?php
                        $keca = strtolower($_GET['kecamatan']);
                        $dess = strtolower($_GET['desa']);
                        if (strtolower($kecamatan['kecamatan']) == $keca) {
                            $qdet = mysqli_query($con, "select * from daftar_wilayah_cabang where kecamatan='$keca' and id_cabang='$id_cabang' group by desa ");
                            $no1 = 1;
                            while ($detailCenter = mysqli_fetch_array($qdet)) {
                        ?>
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?= $no1++ ?>.</i></td>
                                    <td><?= strtoupper($detailCenter['kecamatan']) ?></td>
                                    <td>
                                        <a href="<?= $url . $menu ?>detail_center&kecamatan=<?= strtolower($kecamatan['kecamatan']) ?>&desa=<?= strtolower($detailCenter['desa']) ?>">
                                            <?php echo strtoupper($detailCenter['desa']); 
                                            $hitung_detail_alamat = mysqli_query($con, "SELECT COUNT(desa) AS desa FROM data_center WHERE desa='$detailCenter[desa]' AND id_cabang='$id_cabang' GROUP BY kecamatan  ");
                                            $hitung_detail_alamat = mysqli_fetch_array($hitung_detail_alamat);
                                            $hitung_detail_alamat = $hitung_detail_alamat['desa'];
                                            echo "<b>(" . (int)$hitung_detail_alamat . ")</b>";
                                            ?>

                                        </a>
                                    </td>
                                    <?php
                                    if (strtolower($detailCenter['desa']) == $dess) {
                                    ?>
                                        <td>RT/RW</td>
                                        <td>ALAMAT</td>
                                        <td>KETERANGAN</td>
                                        <td>CENTER</td>
                                    <?php
                                    } else {
                                        ?>
                                        <th colspan="4">&nbsp</th>
                                        <?php
                                    }
                                    ?>
                                    <td>
                                        <?php
                                        if ($jabatan == 'BM' || $jabatan == 'ASM' || $su == 'y') {
                                        ?>
                                            <a href="<?= $url . $menu ?>detail_center&hapus_desa&nama_desa=<?= $detailCenter['desa'] ?>" onclick="return window.confirm('yakin akan menghapus ini desa : <?= $detailCenter['desa'] ?>?')" class='btn'>
                                                <i class='fa fa-times'></i>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                $desa = $_GET['desa'];
                                if (strtolower($detailCenter['desa']) == $desa) {
                                    $qdetail = mysqli_query($con, "select * from data_center where kecamatan='$keca' and id_cabang='$id_cabang' and desa='$desa'  ");
                                    $no2 = 1;
                                    while ($detail = mysqli_fetch_array($qdetail)) {
                                ?>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;</i></td>
                                            <td></td>
                                            <td><?= $no2++ ?>. <?= strtoupper($detailCenter['desa']) ?></td>
                                            <td><?= $detail['rt'] ?>/<?= $detail['rw'] ?></td>
                                            <td><?= $detail['alamat'] ?></td>
                                            <td><?= $detail['keterangan'] ?></td>
                                            <td>
                                                <?php $cent = cek_center($con, $detail['no_center']) ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($jabatan == 'BM' || $jabatan == 'ASM' || $su == 'y') {
                                                ?>
                                                    <a href="<?= $url . $menu ?>detail_center&edit&id=<?= $detail['id_data_center'] ?>" class='btn'>
                                                        <i class='fa fa-edit'></i>
                                                    </a>
                                                    <a href="<?= $url . $menu ?>detail_center&hapus&id=<?= $detail['id_data_center'] ?>" onclick="return window.confirm('yakin akan menghapus ini?')" class='btn'>
                                                        <i class='fa fa-times'></i>
                                                    </a>
                                                    <?php
                                                } else {
                                                    if ($id_karyawan == $detail['id_karyawan']) {
                                                    ?>
                                                        <a href="<?= $url . $menu ?>detail_center&edit&id=<?= $detail['id_data_center'] ?>" onclick="return window.confirm('yakin akan menghapus ini?')" class='btn'>
                                                            <i class='fa fa-edit'></i>
                                                        </a>
                                                        <a href="<?= $url . $menu ?>detail_center&hapus&id=<?= $detail['id_data_center'] ?>" class='btn'>
                                                            <i class='fa fa-times'></i>
                                                        </a>
                                                <?php
                                                    }
                                                }
                                                ?>

                                            </td>
                                        </tr>
                                    <?php
                                        if ($detail['no_center'] != "") {
                                            $hitung_center[] = $no++;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="8">
                                            <center>
                                                <b>
                                                    Total Center di Desa <?= $desa ?> : <?= count($hitung_center) ?>
                                                </b>
                                        </td>
                                        </center>

                                    </tr>
                    <?php
                                }
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>

        <?php
        }
        ?>
    </div>
    <br />

</div>