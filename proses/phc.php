<div class='content table-responsive'>
    <h2 class='page-header'>Progres Harian Cabang</h2>

    <?php
    if (!isset($_GET['tgl'])) {
        pindah($url . $menu . "phc&tgl=" . date("Y-m-d"));
    }

    $tgl = $_GET['tgl'];
    if (isset($_GET['tgl'])) {

        $qphc = mysqli_query($con, "select * from phc where id_cabang='$id_cabang' and tgl_phc='$tgl'");
        if (mysqli_num_rows($qphc)) {
            $readonly = 'readonly';
            $phc = mysqli_fetch_assoc($qphc);
            $d = detail_karyawan($con, $phc['id_karyawan']);
            if ($phc['status'] == 'pending' || $phc['status'] == 'belum') {
                $ket = "<span class='btn-danger'>SUDAH MEMBUAT PHC NAMUN BELUM ISI DETAIL PHC</span>";
            } elseif ($phc['status'] == 'konfirmasi') {
                $ket = "<strong>TERIMA KASIH TELAH ISI PHC</strong>";
            } elseif ($phc['status'] == 'belum') {
                // $ket="<strong>TERIMA KASIH TELAH ISI PHC</strong>";
                $ket = "<span class='btn-danger'>BELUM MENGISI PHC</span>";
            }
        } else {
            $readonly = '';
            $ket = "<span class='btn-danger'>BELUM MEMBUAT PHC TANGGAL $tgl</span>";
        }
    ?>
    <div class='col-md-6'>
        <form method="post">
            <table class='table'>
                <tr>
                    <td>TGL PHC</td>
                    <td><input type="date" class='form-control' id='tgl' onchange="tgl_ganti()" value='<?= $tgl ?>'
                            name='tgl'></td>
                </tr>
                <tr>
                    <th>CABANG</th>
                    <td>
                        <input type="text"
                            value="<?= strtoupper($d['kode_cabang']) ?>/<?= strtoupper($d['nama_cabang']) ?>" readonly
                            name="cabang" id="" class='form-control'>
                    </td>
                </tr>
                <tr>
                    <th>PIC</th>
                    <td>
                        <input type="text" value="<?= $d['nama_karyawan'] ?>/<?= $d['nama_jabatan'] ?>" readonly
                            name="pic" id="" class='form-control'>
                    </td>
                </tr>
                <tr>
                    <th>STATUS</th>
                    <td>
                        <?= $ket ?>
                    </td>
                </tr>
                <tr>
                    <th>KETERANGAN</th>
                    <td>
                        <textarea name="keterangan" <?= $readonly ?> id="" cols="30" class='form-control'
                            rows="10"><?= @$phc['keterangan_phc'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <td>
                        <?php
                            if (mysqli_num_rows($qphc)) {

                                $detail = mysqli_query($con, "SELECT * from phc join detail_phc on phc.id=detail_phc.id where phc.id_cabang='$id_cabang' and phc.tgl_phc='$tgl'");
                                $detaill = mysqli_fetch_assoc($detail)['id_detail'];
                            ?>
                        <a href="<?= $url . $menu . "phc&detail&id=$phc[id]&tgl=$tgl&iddetail=$detaill" ?>"
                            class="btn btn-primary">ISI PHC</a>
                        <a href="<?= $url . $menu . "phc&hapus&id=$phc[id]&tgl=$tgl" ?>" class="btn btn-danger"
                            onclick="return window.confirm('Jika anda menghapus PHC ini, maka ISI PHC juga akan terhapus, anda yakin??')">HAPUS</a>
                        <?php
                            } else {
                            ?>
                        <input type="submit" name="konfirm_phc" value='SIMPAN' id="" class='btn btn-danger'>
                        <?php
                            }
                            ?>
                    </td>
                </tr>
            </table>
            <br>
        </form>
    </div>
    <div class='col-md-6'>

    </div>
    <?php
        if (isset($_POST['konfirm_phc'])) {
            $tgl = $_POST['tgl'];
            $ket = $_POST['keterangan'];
            $q = mysqli_query($con, "INSERT INTO phc(`id`,`tgl_phc`,`id_karyawan`,`id_cabang`,`id_wilayah`,`keterangan_phc`,`created_at`)
            VALUES(null,'$tgl','$id_karyawan','$id_cabang','$d[id_wilayah]','$ket',CURRENT_TIMESTAMP);
            ");
            if ($q) {
                $id = mysqli_insert_id($con);
                mysqli_query($con, "INSERT INTO detail_phc(id,id_cabang,id_wilayah)
                VALUES($id,'$id_cabang','$d[id_wilayah]')");
                $id_detail = mysqli_insert_id($con);
                pindah($url . $menu . "phc&tgl=$tgl&id=$id&iddetail=$id_detail&detail");
            } else {
                echo mysqli_error($con);
            }
        }
    }

    if (isset($_GET['hapus'])) {
        $id = aman($con, $_GET['id']);
        $tgl = $_GET['tgl'];
        mysqli_query($con, "DELETE from phc where id_cabang='$id_cabang' and id='$id'");
        mysqli_query($con, "DELETE from detail_phc where id_cabang='$id_cabang' and id='$id'");
        pindah($url . $menu . 'phc');
    }


    if (isset($_GET['detail']) && isset($_GET['id'])) {
        $iddetail = aman($con, $_GET['iddetail']);
        $idphc = aman($con, $_GET['id']);

        ?>
    <hr>
    <form method="post">
        <div class='col-md-10'>
            <?php
                if (isset($_POST['konfirmasi_phc'])) {
                    $qupdate = mysqli_query($con, "UPDATE phc set edited_at=CURRENT_TIMESTAMP , status='konfirmasi' where id='$idphc' and id_cabang='$id_cabang'");
                    if ($qupdate) {
                        pesan("BERHASIL DIKONFIRMASI, TERIMAKASIH", 'success');
                        pindah($url . $menu . "phc&tgl=$tgl&id=$idphc&iddetail=$iddetail&readonly=yes&detail");
                    } else {
                        pesan('GAGAL : ' . mysqli_error($con), 'danger');
                    }
                }
                if (isset($_POST['unkonfirmasi_phc'])) {
                    $qupdate = mysqli_query($con, "UPDATE phc set edited_at=CURRENT_TIMESTAMP , status='belum' where id='$idphc' and id_cabang='$id_cabang'");
                    if ($qupdate) {
                        pesan("PHC BERHASIL DIUBAH MENJADI PENDING", 'success');
                        pindah($url . $menu . "phc&tgl=$tgl&id=$idphc&iddetail=$iddetail&readonly=no&detail");
                    } else {
                        pesan('GAGAL : ' . mysqli_error($con), 'danger');
                    }
                }
                if (isset($_POST['simpan_phc'])) {
                    $kas = $_POST['kas'];
                    $bank = $_POST['bank'];
                    $ppi = $_POST['ppi'];
                    $ktp = $_POST['ktp'];
                    $total_center = $_POST['total_center'];
                    $center_dtd = $_POST['center_dtd'];
                    $total_agt_bayar = $_POST['total_agt_bayar'];
                    $total_agt_tidak_bayar = $_POST['total_agt_tidak_bayar'];
                    $total_par = $_POST['total_par'];
                    $monitoring = $_POST['monitoring'];
                    $pu = $_POST['pu'];
                    $pk = $_POST['pk'];
                    $lwk = $_POST['lwk'];
                    $upk = $_POST['upk'];
                    $agt_masuk = $_POST['anggota_masuk'];
                    $agt_keluar = $_POST['anggota_keluar'];
                    $cek_buku = $_POST['cek_buku'];
                    $cek_disburse = $_POST['cek_disburse'];
                    $cek_saving = $_POST['cek_saving'];
                    $qsimpan  = "UPDATE `detail_phc` SET 
                   `saldo_kas` = '$kas',
                    `saldo_bank` = '$bank' ,
                     `ppi_not_entry` = '$ppi' ,
                      `ktp_not_entry` = '$ktp' ,
                       `ctr_input` = '$total_center' ,
                    `ctr_dtd` = '$center_dtd' ,
                     `agt_bayar` = '$total_agt_bayar' ,
                      `agt_tidak_bayar` = '$total_agt_tidak_bayar' ,
                       `total_agt_par` = '$total_par' ,
                        `sisa_monitoring` = '$monitoring' ,
                         `pu` = '$pu' ,
                          `pk` = '$pk' , 
                          `lwk` = '$lwk' , `upk` = '$upk' , `agt_keluar` = '$agt_keluar' , `agt_masuk` = '$agt_masuk' , 
                          `cek_buku` = '$cek_buku' , `cek_disburse` = '$cek_disburse' , `cek_simpanan` = '$cek_saving' WHERE `id_detail` = '$iddetail' and id_cabang='$id_cabang';";
                    //  echo $qsimpan;
                    if (mysqli_query($con, $qsimpan)) {
                        $qupdate = mysqli_query($con, "UPDATE phc set edited_at=CURRENT_TIMESTAMP , status='pending' where id='$idphc' and id_cabang='$id_cabang'");
                        pesan("Berhasil disimpan", 'success');
                        pindah($url . $menu . "phc&tgl=$tgl&id=$idphc&iddetail=$iddetail&readonly=yes&detail");
                    } else {
                        echo mysqli_error($con);
                    }
                }


                $cek_detail = mysqli_query($con, "select * from detail_phc where id_detail='$iddetail' and id_cabang='$id_cabang'");
                $cek_detail = mysqli_fetch_assoc($cek_detail);

                if ($_GET['readonly'] == 'yes') {
                    $readonly = 'readonly';
                } else
                    $readonly = '';
                ?>

            <h4>DIISI TANPA TITIK, KOMA</h4>
            <div class='col-md-6'>
                <table class='table'>
                    <tr>
                        <td>SALDO KAS</td>
                        <td><input type="number" <?= $readonly ?> name="kas" value="<?= $cek_detail['saldo_kas'] ?>"
                                class='form-control' id=""></td>
                    </tr>
                    <tr>
                        <td>SALDO BANK</td>
                        <td><input type="number" <?= $readonly ?> name="bank" value="<?= $cek_detail['saldo_bank'] ?>"
                                class='form-control' id=""></td>
                    </tr>
                    <tr>
                        <td>PPI NOT ENTRY</td>
                        <td><input type="number" <?= $readonly ?> name="ppi" value="<?= $cek_detail['ppi_not_entry'] ?>"
                                class='form-control' id=""></td>
                    </tr>
                    <tr>
                        <td>KTP NO ENTRY</td>
                        <td><input type="number" <?= $readonly ?> name="ktp" value="<?= $cek_detail['ktp_not_entry'] ?>"
                                class='form-control' id=""></td>
                    </tr>
                    <tr>
                        <td>TOTAL CENTER </td>
                        <td><input type="number" <?= $readonly ?> name="total_center"
                                value="<?= $cek_detail['ctr_input'] ?>" class='form-control' id=""></td>
                    </tr>
                    <tr>
                        <td>TOTAL CENTER DTD </td>
                        <td><input type="number" <?= $readonly ?> name="center_dtd"
                                value="<?= $cek_detail['ctr_dtd'] ?>" class='form-control' id=""></td>
                    </tr>
                    <!-- <tr>
                            <td>TOTAL ANGGOTA </td>
                            <td><input type="number" <?= $readonly ?> name="total_agt" class='form-control' id=""></td>
                        </tr> -->
                    <tr>
                        <td>TOTAL ANGGOTA BAYAR </td>
                        <td><input type="number" <?= $readonly ?> name="total_agt_bayar"
                                value="<?= $cek_detail['agt_bayar'] ?>" class='form-control' id=""></td>
                    </tr>
                    <tr>
                        <td>TOTAL ANGGOTA TIDAK BAYAR </td>
                        <td><input type="number" <?= $readonly ?> name="total_agt_tidak_bayar"
                                value="<?= $cek_detail['agt_tidak_bayar'] ?>" class='form-control' id=""></td>
                    </tr>
                    <tr>
                        <td>TOTAL AGT PAR</td>
                        <td><input type="number" <?= $readonly ?> name="total_par"
                                value="<?= $cek_detail['total_agt_par'] ?>" class='form-control' id=""></td>
                    </tr>
                    <tr>
                        <td>SISA MONITORIN H-3</td>
                        <td><input type="number" <?= $readonly ?> name="monitoring"
                                value="<?= $cek_detail['sisa_monitoring'] ?>" class='form-control' id=""></td>
                    </tr>

                </table>
            </div>
            <div class='col-md-6'>
                <table class='table'>

                    <tr>
                        <td>PERTEMUAN UMUM</td>
                        <td><input type="number" <?= $readonly ?> name="pu" class='form-control'
                                value="<?= $cek_detail['pu'] ?>" id=""></td>
                    </tr>
                    <tr>
                        <td>PK</td>
                        <td><input type="number" <?= $readonly ?> name="pk" class='form-control'
                                value="<?= $cek_detail['pk'] ?>" id=""></td>
                    </tr>
                    <tr>
                        <td>LWK </td>
                        <td><input type="number" <?= $readonly ?> name="lwk" class='form-control'
                                value="<?= $cek_detail['lwk'] ?>" id=""></td>
                    </tr>
                    <tr>
                        <td>UPK </td>
                        <td><input type="number" <?= $readonly ?> name="upk" class='form-control'
                                value="<?= $cek_detail['upk'] ?>" id=""></td>
                    </tr>

                    <tr>
                        <td>ANGGOTA MASUK </td>
                        <td><input type="number" <?= $readonly ?> name="anggota_masuk" class='form-control'
                                value="<?= $cek_detail['agt_masuk'] ?>" id=""></td>
                    </tr>
                    <tr>
                        <td>ANGGOTA KELUAR </td>
                        <td><input type="number" <?= $readonly ?> name="anggota_keluar" class='form-control'
                                value="<?= $cek_detail['agt_keluar'] ?>" id=""></td>
                    </tr>
                    <tr>
                        <td>PENGECEKAN BUKU </td>
                        <td><input type="number" <?= $readonly ?> name="cek_buku" class='form-control'
                                value="<?= $cek_detail['cek_buku'] ?>" id=""></td>
                    </tr>
                    <tr>
                        <td>PENGECEKAN DISBURSE </td>
                        <td><input type="number" <?= $readonly ?> name="cek_disburse" class='form-control'
                                value="<?= $cek_detail['cek_disburse'] ?>" id=""></td>
                    </tr>
                    <tr>
                        <td>PENGECEKAN SIMPANAN </td>
                        <td><input type="number" <?= $readonly ?> name="cek_saving" class='form-control'
                                value="<?= $cek_detail['cek_simpanan'] ?>" id=""></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td>
                            <?php
                                if ($phc['status'] == 'konfirmasi') {
                                ?>
                            <input type="submit" name="unkonfirmasi_phc" class='btn btn-danger' value="UN UPPROVE"
                                onclick="" id="">

                            <?php
                                } elseif ($phc['status'] == 'pending') {
                                ?>
                            <input type="submit" name="konfirmasi_phc" class='btn btn-primary' value="KONFIRMASI"
                                onclick="return window.confirm('Apakah semua sudah benar?')" id="">
                            <input type="submit" name="unkonfirmasi_phc" class='btn btn-danger' value="EDIT" onclick=""
                                id="">
                            <?php
                                } elseif ($phc['status'] == 'belum') {
                                ?>
                            <input type="submit" name="simpan_phc" class='btn btn-success' value="SIMPAN" id="">
                            <input type="reset" name="reset" class='btn btn-danger' value="RESET" id="">
                            <?php
                                }
                                ?>
                        </td>
                    </tr>

                </table>
            </div>

        </div>
    </form>
    <?php
    }

    ?>
</div>
<script>
function tgl_ganti() {
    var tgl = $("#tgl").val();
    const url = "<?= $url . $menu ?>phc&tgl=" + tgl;
    location.href = url;
    // alert(a)
}
</script>