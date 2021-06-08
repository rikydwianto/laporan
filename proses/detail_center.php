<div class='content table-responsive'>
    <h3 class='page-header'>SELURUH DATA KARYAWAN</h3>
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
        } else {
        ?>
            <br>
            <table class='table table-bordered table-hovered'>
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>KECAMATAN</th>
                        <th>DESA</th>
                        <th>RT/RW</th>
                        <th>ALAMAT</th>
                        <th>KETERANGAN</th>
                        <th>CENTER</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $kec = mysqli_query($con, "select * from data_center where id_cabang='$id_cabang' group by kecamatan order by kecamatan asc ");
                    while ($kecamatan = mysqli_fetch_array($kec)) {
                    ?>
                        <tr>
                            <th><?= $no++ ?></th>
                            <th>
                                <a href="<?= $url . $menu ?>detail_center&kecamatan=<?= strtolower($kecamatan['kecamatan']) ?>">
                                    <?= strtoupper($kecamatan['kecamatan']) ?>
                                </a>

                            </th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php
                        $keca = strtolower($_GET['kecamatan']);
                        if (strtolower($kecamatan['kecamatan']) == $keca) {
                            $qdet = mysqli_query($con, "select * from data_center where kecamatan='$keca' and id_cabang='$id_cabang' group by desa ");
                            $no1 = 1;
                            while ($detailCenter = mysqli_fetch_array($qdet)) {
                        ?>
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?= $no1++ ?>.</i></td>
                                    <td><?= strtoupper($detailCenter['kecamatan']) ?></td>
                                    <td>
                                        <a href="<?= $url . $menu ?>detail_center&kecamatan=<?= strtolower($kecamatan['kecamatan']) ?>&desa=<?= strtolower($detailCenter['desa']) ?>">
                                            <?= strtoupper($detailCenter['desa']) ?>
                                        </a>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>

                                    </td>
                                </tr>
                                <?php
                                $desa = $_GET['desa'];
                                if (strtolower($detailCenter['desa']) == $desa) {
                                    $qdetail = mysqli_query($con, "select * from data_center where kecamatan='$keca' and id_cabang='$id_cabang' and desa='$desa'  ");
                                    $no2=1;
                                    while ($detail = mysqli_fetch_array($qdetail)) {
                                ?>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;</i></td>
                                            <td></td>
                                            <td><?=$no2++?>. <?= strtoupper($detailCenter['desa']) ?></td>
                                            <td><?= $detail['rt'] ?>/<?= $detail['rw'] ?></td>
                                            <td><?= $detail['alamat'] ?></td>
                                            <td><?= $detail['keterangan'] ?></td>
                                            <td>
                                                <?php $cent = cek_center($con, $detail['no_center']) ?>
                                            </td>
                                        </tr>
                                    <?php
                                   if($detail['no_center']==""){
                                       $hitung_center[]=$no++;
                                   }
                                
                                }
                                    ?>
                                    <tr>
                                        <td colspan="7">
                                        <center>
                                        <b >
                                            Total Center di Desa : <?=$desa?> <?=count($hitung_center)?></td>
                                        </b>
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