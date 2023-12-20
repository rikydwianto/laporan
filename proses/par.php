<div class="row">

    <h3 class="page-header">INPUT ANGGOTA PAR</h3>
    <a href="<?= $url . $menu ?>par&list" class="btn btn-success">Lihat Data Par</a>
    <a href="<?= $url . $menu ?>center_tidak_ada" class="btn btn-primary">Syncrone merger center</a>
    <form method="post" enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
            <input class="form-control" required type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
            <input type="date" required name="tgl" class='form-control' id="">
            <input type="submit" onclick="return confirm('yakin sudah benar?/')" value="KONFIRMASI" class='btn btn-danger' name='preview'>
        </div>
    </form>
    <div class="col-md-8">
        <form action="" method="get">
            <input type="hidden" name="menu" value="par">
            <div class="col-md-4">
                <h3>MINGGU SEBELUM NYA</h3>
                <select name='sebelum' class='form-control' required>

                    <option value="">PILIH MINGGU SEBELUM</option>
                    <?php
                    error_reporting(0);
                    $q_tgl = mysqli_query($con, "SELECT DISTINCT tgl_input FROM deliquency where id_cabang='$id_cabang'  order by tgl_input desc");
                    while ($tgl_ = mysqli_fetch_array($q_tgl)) {
                    ?>
                        <option value="<?= $tgl_['tgl_input'] ?>" <?= ($_GET['sebelum'] === $tgl_['tgl_input'] ? "selected" : "") ?>>
                            <?= format_hari_tanggal($tgl_['tgl_input']) ?></option>
                    <?php
                    }
                    ?>

                </select>
                <?= separator($_GET['tipe']) ?>


            </div>
            <div class="col-md-4">
                <h3>MINGGU INI</h3>
                <select name='minggu_ini' class='form-control' required>

                    <option value="">PILIH MINGGU INI</option>
                    <?php
                    $q_tgl = mysqli_query($con, "SELECT DISTINCT tgl_input FROM deliquency where id_cabang='$id_cabang' order by tgl_input desc");
                    while ($tgl_ = mysqli_fetch_array($q_tgl)) {
                    ?>
                        <option value="<?= $tgl_['tgl_input'] ?>" <?= ($_GET['minggu_ini'] === $tgl_['tgl_input'] ? "selected" : "") ?>>
                            <?= format_hari_tanggal($tgl_['tgl_input']) ?></option>
                    <?php
                    }
                    ?>

                </select>
                <?= kategori($_GET['kat']) ?>
                <input type="submit" value="BANDINGKAN" name='bandingkan' class='btn btn-md btn-danger'>
                <input type="submit" value="REKAP" name='rekap' class='btn btn-md btn-info'>
                <!-- <input type="submit" value="REKAP SEMUA" name='rekap_semua' class='btn btn-danger btn-md btn-info'> -->
                <!-- <input type="submit" value="ANALISA TOPUP" name='anal_topup' class='btn btn- btn-md btn-info'> -->

            </div>
        </form>
    </div>



    <?php
    $no = 1;
    if (isset($_POST['preview'])) {
    ?>
        <table class='table'>
            <tr>
                <td>no</td>
                <td>loan</td>
                <td>no_center</td>
                <td>id_nasabah</td>
                <td>nasabah</td>
                <td>amount</td>
                <td>balance</td>
                <td>tunggakan</td>
                <td>minggu</td>
                <td>tgl dis</td>
            </tr>
            <?php
            $file = $_FILES['file']['tmp_name'];
            $path = $file;
            $tgl = $_POST['tgl'];
            $reader = PHPExcel_IOFactory::createReaderForFile($path);
            $objek = $reader->load($path);
            $ws = $objek->getActiveSheet();
            $last_row = $ws->getHighestDataRow();

            for ($row = 2; $row <= $last_row; $row++) {
                $id_nasabah =  $ws->getCell("D" . $row)->getValue();
                if ($id_nasabah == null) {
                } else {
                    $agt = (substr(ganti_karakter($id_nasabah), 0, 3));

                    if ($agt == "AGT" || $agt == "NSB") {
                        $nasabah =  aman($con, ganti_karakter($ws->getCell("E" . $row)->getValue()));
                        $loan = ganti_karakter($ws->getCell("B" . $row)->getValue());
                        $no_center = ganti_karakter($ws->getCell("C" . $row)->getValue());
                        $id_nasabah = ganti_karakter1($ws->getCell("D" . $row)->getValue());
                        // $tgl_dis = ganti_karakter1($ws->getCell("H".$row)->getValue());

                        $bm = ($ws->getCell("C4")->getValue());
                        // echo "sssss".$bm;
                        if ($bm == "Ctr ID") {
                            $amount = (int)ganti_karakter(str_replace(",", "", $ws->getCell("H" . $row)->getValue()));
                            $balance = (int)ganti_karakter(str_replace(",", "", $ws->getCell("M" . $row)->getValue()));
                            $tunggakan = (int)ganti_karakter(str_replace(",", "", $ws->getCell("N" . $row)->getValue()));
                            $minggu = (int)ganti_karakter(str_replace(",", "", $ws->getCell("O" . $row)->getValue()));
                            $tgl_dis = ganti_karakter1($ws->getCell("J" . $row)->getValue());
                            $tgl_dis = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl_dis));


                            $wajib = (int)ganti_karakter(str_replace(",", "", $ws->getCell("U" . $row)->getValue()));
                            $sukarela = (int)ganti_karakter(str_replace(",", "", $ws->getCell("V" . $row)->getValue()));
                            $pensiun = (int)ganti_karakter(str_replace(",", "", $ws->getCell("W" . $row)->getValue()));
                            $hariraya = (int)ganti_karakter(str_replace(",", "", $ws->getCell("X" . $row)->getValue()));
                            $cicilan = (int)ganti_karakter(str_replace(",", "", $ws->getCell("AB" . $row)->getValue()));
                            $hari = ganti_karakter(str_replace(",", "", $ws->getCell("AF" . $row)->getValue()));
                            $nama_staff = ganti_karakter(str_replace(",", "", $ws->getCell("AG" . $row)->getValue()));
                            $minggu_ke = ganti_karakter(str_replace(",", "", $ws->getCell("AC" . $row)->getValue()));
                            $minggu_rill = ganti_karakter(str_replace(",", "", $ws->getCell("AD" . $row)->getValue()));
                            $priode = ganti_karakter(str_replace(",", "", $ws->getCell("I" . $row)->getValue()));
                            $kode = ganti_karakter(str_replace(",", "", $ws->getCell("G" . $row)->getValue()));
                        } else {
                            $amount = (int)ganti_karakter(str_replace(",", "", $ws->getCell("F" . $row)->getValue()));
                            $balance = (int)ganti_karakter(str_replace(",", "", $ws->getCell("K" . $row)->getValue()));
                            $tunggakan = (int)ganti_karakter(str_replace(",", "", $ws->getCell("L" . $row)->getValue()));
                            $minggu = (int)ganti_karakter(str_replace(",", "", $ws->getCell("M" . $row)->getValue()));
                            $tgl_dis = ganti_karakter1($ws->getCell("H" . $row)->getValue());
                            $tgl_dis = explode('/', $tgl_dis);
                            $tgl_dis = $tgl_dis[2] . '-' . $tgl_dis[1] . '-' . $tgl_dis[0];
                        }
                        /* 
            <!-- <tr>
                <td><?=$no++?></td>
        <td><?=$loan?></td>
        <td><?=$no_center?></td>
        <td><?=$id_nasabah?></td>
        <td><?=$nasabah?></td>
        <td><?=$amount?></td>
        <td><?=$balance?></td>
        <td><?=$tunggakan?></td>
        <td><?=$minggu?></td>
        <td><?=$tgl_dis?></td>
        <td><?=$wajib?></td>
        <td><?=$sukarela?></td>
        <td><?=$pensiun?></td>
        <td><?=$hariraya?></td>
        <td><?=$cicilan?></td>
        <td><?=$hari?></td>
        <td><?=$nama_staff?></td>
        </tr> -->
        */
                        // // INSERT INTO `deliquency` (`id`, `loan`, `no_center`, `id_detail_nasabah`, `nasabah`, `amount`,
                        // `sisa_saldo`,
                        // `tunggakan`, `minggu`, `tgl_input`, `id_cabang`) VALUES (NULL, 'PU-072-21-01-000216', '003',
                        // 'AGT/072/01/003-000034', 'RUMNASIH', '6', '2', '1', '8', NULL, NULL);
                        mysqli_query($con, "INSERT INTO `deliquency`
        ( id,`loan`, `no_center`, `id_detail_nasabah`, `nasabah`, `amount`, `sisa_saldo`, `tunggakan`, `minggu`,
        `tgl_input`,
        `id_cabang`,tgl_disburse,wajib,sukarela,pensiun,hariraya,cicilan,hari,staff,minggu_ke,minggu_rill,priode,kode_pemb)
        VALUES
        (NULL, '$loan', '$no_center', '$id_nasabah', '$nasabah', '$amount', '$balance', '$tunggakan', '$minggu', '$tgl',
        '$id_cabang','$tgl_dis','$wajib','$sukarela','$pensiun','$hariraya','$cicilan','$hari','$nama_staff','$minggu_ke','$minggu_rill','$priode','$kode');
        ");
                    }
                }
            }
            alert("Berhasil ditambahkan!");
            pindah($url . $menu . "par");
            ?>
        </table>
    <?php
    }
    $sepat = $_GET['tipe'];
    if (isset($_GET['bandingkan'])) {

        $tgl_awal  = $_GET['sebelum'];
        $tgl_banding = $_GET['minggu_ini'];
    ?>
        <a href="#" onclick="buka('popup/par.php?tgl=<?= $tgl_awal ?>')" class="btn btn-primary"> <i class="fa fa-list"></i>
            Tampilkan Semua AGT PAR TGL <?= $tgl_awal ?></a>
        <a href="#" onclick="buka('popup/par.php?tgl=<?= $tgl_banding ?>')" class="btn btn-danger"> <i class="fa fa-list"></i>
            Tampilkan Semua AGT PAR TGL <?= $tgl_banding ?></a>
        <?php
        $kat = $_GET['kat'];
        if ($kat == 'turun' || $kat == 'naik') {
        ?>
            <a href="#rekap" onclick="printPageArea('turun_par')" class="btn btn-success">print <i class="fa fa-print"></i></a>
            <div id='turun_par'>
                <h3>PENURUNAN ANGGOTA PAR</h3>
                <table class='table table-bordered'>
                    <tr>
                        <th>NO</th>
                        <th>LOAN</th>
                        <th>CENTER</th>
                        <th>ID AGT</th>
                        <th>ANGGOTA</th>
                        <th>TANGGAL</th>
                        <th>DISBURSE</th>
                        <th>BALANCE</th>
                        <th>ARREAS</th>
                        <th>WEEK PAS</th>
                        <th>STATUS</th>
                        <th>ALASAN</th>
                        <th>TARGET</th>
                        <th>STAFF</th>
                    </tr>

                    <?php
                    $total_os = 0;
                    $query = mysqli_query($con, " SELECT d.*,k.nama_karyawan FROM deliquency d 
            JOIN center c ON c.`no_center`=d.`no_center` 
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
            where d.loan not in (select loan from deliquency where tgl_input='$tgl_banding' and id_cabang='$id_cabang') and d.tgl_input='$tgl_awal' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
                    while ($data = mysqli_fetch_array($query)) {
                        $total_os += $data['sisa_saldo'];
                        $ID = (int)explode("-", $data['id_detail_nasabah'])[1];
                        $qreason = (mysqli_query($con, "select * from alasan_par where id_loan='$data[loan]'  and id_cabang='$id_cabang'"));
                        $qak = mysqli_num_rows(mysqli_query($con, "select * from temp_anggota_keluar where id_nasabah='$ID'  and id_cabang='$id_cabang'"));
                        $par = mysqli_num_rows(mysqli_query($con, "select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
                        if ($par) {
                            $baris['baris'] = "#c9c7c1";
                            $baris['text'] = "red";
                            $baris['ket'] = 'RE/DTD';
                        } else {
                            $baris['baris'] = "#ffff";
                            $baris['text'] = "#black";
                            $baris['ket'] = '';
                        }
                        if (($qak)) {
                            $baris['ket'] .= "Agt Keluar";
                        } else {
                            $baris['ket'] .= "";
                        }
                        if ((mysqli_num_rows($qreason))) {
                            $reason  = mysqli_fetch_array($qreason);
                            $baris['alasan'] = $reason['alasan'];
                        } else {
                            $baris['alasan'] = "";
                        }

                        $cek_tpk = mysqli_query($con, "select id from tpk where id_cabang='$id_cabang' and id_detail_nasabah='$data[id_detail_nasabah]'");
                        if (mysqli_num_rows($cek_tpk) > 0) {
                            $baris['ket'] .= "TPK ";
                        } else {
                            $baris['ket'] .= "";
                            $cek_topup = mysqli_query($con, "select * from keterangan_topup where id_cabang='$id_cabang' and id_detail_nasabah='$data[id_detail_nasabah]'");
                            echo mysqli_error($con);
                            if (mysqli_num_rows($cek_topup)) {
                                $top = mysqli_fetch_assoc($cek_topup);
                                $baris['ket'] .= $top['topup'];
                            } else {
                                $cek_topup = mysqli_query($con, "select * from tpk where id_cabang='$id_cabang' and id_detail_nasabah='$data[id_detail_nasabah]'");
                                if (mysqli_num_rows($cek_topup)) {
                                    $baris['ket'] .= "TPK";
                                }
                            }
                        }
                    ?>
                        <tr style="background-color:<?= $baris['baris'] ?>;color:<?= $baris['text'] ?>">
                            <td><?= $no++ ?></td>
                            <td><?= $data['loan'] ?></td>
                            <td><?= $data['no_center'] ?></td>
                            <td><?= $data['id_detail_nasabah'] ?></td>
                            <td><?= $data['nasabah'] ?></td>
                            <td><?= $data['tgl_disburse'] ?></td>
                            <td><?= angka($data['amount'], $sepat) ?></td>
                            <td><?= angka($data['sisa_saldo'], $sepat) ?></td>
                            <td><?= angka($data['tunggakan'], $sepat) ?></td>
                            <td><?= $data['minggu'] ?></td>
                            <td><?= $baris['ket'] ?></td>
                            <td><?= $baris['alasan'] ?></td>
                            <td><?= $baris['penyelesaian_par'] ?></td>
                            <td><?= $data['nama_karyawan'] ?></td>
                        </tr>
                    <?php
                    } ?>
                    <tr>
                        <th colspan="7">TOTAL OUTSTANDING BERKURANG</th>
                        <th>-<?= angka($total_os, $sepat) ?></th>
                    </tr>
                </table>
            </div>
            <?php
            // }

            // if($kat=='naik'){
            //     
            ?>
            <a href="#rekap" onclick="printPageArea('tambah_par')" class="btn btn-success">print <i class="fa fa-print"></i></a>

            <div id='tambah_par'>
                <h3>PENAMBAHAN ANGGOTA PAR</h3>
                <table class='table table-bordered'>
                    <tr>
                        <th>NO</th>
                        <th>LOAN</th>
                        <th>CENTER</th>
                        <th>ID AGT</th>
                        <th>ANGGOTA</th>
                        <th>TANGGAL</th>
                        <th>DISBURSE</th>
                        <th>BALANCE</th>
                        <th>ARREAS</th>
                        <th>WEEK PAS</th>
                        <th>STATUS</th>
                        <th>ALASAN</th>
                        <th>TARGET</th>
                        <th>STAFF</th>
                    </tr>

                    <?php
                    $no = 1;
                    $total_tambah = 0;
                    $query1 = mysqli_query($con, "
        SELECT d.*,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
        where d.loan not in (select loan from deliquency where tgl_input='$tgl_awal' and id_cabang='$id_cabang') and d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and  d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
                    while ($data = mysqli_fetch_array($query1)) {

                        $qreason = (mysqli_query($con, "select * from alasan_par where id_loan='$data[loan]'  and id_cabang='$id_cabang'"));

                        $par = mysqli_num_rows(mysqli_query($con, "select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
                        if ($par) {
                            $baris['baris'] = "#c9c7c1";
                            $baris['text'] = "red";
                            $baris['ket'] = 'RE/DTD';
                        } else {
                            $baris['baris'] = "#ffff";
                            $baris['text'] = "#black";
                            $baris['ket'] = '';
                        }
                        if ((mysqli_num_rows($qreason))) {
                            $reason  = mysqli_fetch_array($qreason);
                            $baris['alasan'] = $reason['alasan'];
                        } else {
                            $baris['alasan'] = "";
                        }
                        $cek_tpk = mysqli_query($con, "select id from tpk where id_cabang='$id_cabang' and id_detail_nasabah='$data[id_detail_nasabah]'");
                        if (mysqli_num_rows($cek_tpk) > 0) {
                            $baris['ket'] .= "TPK ";
                        } else {
                            $baris['ket'] .= "";
                            $cek_topup = mysqli_query($con, "select * from keterangan_topup where id_cabang='$id_cabang' and id_detail_nasabah='$data[id_detail_nasabah]'");
                            echo mysqli_error($con);
                            if (mysqli_num_rows($cek_topup)) {
                                $top = mysqli_fetch_assoc($cek_topup);
                                $baris['ket'] .= $top['topup'];
                            } else {
                                $cek_topup = mysqli_query($con, "select * from tpk where id_cabang='$id_cabang' and id_detail_nasabah='$data[id_detail_nasabah]'");
                                if (mysqli_num_rows($cek_topup)) {
                                    $baris['ket'] .= "TPK";
                                }
                            }
                        }
                        $total_tambah += $data['sisa_saldo'];
                    ?>
                        <tr style="background-color:<?= $baris['baris'] ?>;color:<?= $baris['text'] ?>">
                            <td><?= $no++ ?></td>
                            <td><?= $data['loan'] ?></td>
                            <td><?= $data['no_center'] ?></td>
                            <td><?= $data['id_detail_nasabah'] ?></td>
                            <td><?= $data['nasabah'] ?></td>
                            <td><?= $data['tgl_disburse'] ?></td>
                            <td><?= angka($data['amount'], $sepat) ?></td>
                            <td><?= angka($data['sisa_saldo'], $sepat) ?></td>
                            <td><?= angka($data['tunggakan'], $sepat) ?></td>
                            <td><?= $data['minggu'] ?></td>
                            <td><?= $baris['ket'] ?></td>
                            <td><?= $baris['alasan'] ?></td>
                            <td><?= $baris['penyelesaian_par'] ?></td>
                            <td><?= $data['nama_karyawan'] ?></td>
                        </tr>
                    <?php
                    } ?>
                    <tr>
                        <th colspan="7">TOTAL OUTSTANDING BERTAMBAH</th>
                        <th>+<?= angka($total_tambah, $sepat) ?></th>
                    </tr>
                </table>
            </div>
        <?php
        }
        ?>







        <!-- PENGURANGAN OUTSTANDING PAR -->
        <!-- PENGURANGAN OUTSTANDING PAR -->
        <!-- PENGURANGAN OUTSTANDING PAR -->
        <!-- PENGURANGAN OUTSTANDING PAR -->
        <?php
        if ($kat == 'berkurang') {
        ?>
            <a href="javascript:void(0)" onclick="printPageArea('turun_os')" class="btn btn-success">print <i class="fa fa-print"></i></a>
            <div id='turun_os'>
                <h3> PENGURANGAN OUTSTANDING PAR</h3>
                <table class='table'>
                    <tr>
                        <th>NO</th>
                        <th>LOAN</th>
                        <th>CENTER</th>
                        <th>ID AGT</th>
                        <th>ANGGOTA</th>
                        <th>DISBURSE</th>
                        <th>BALANCE</th>
                        <th>BALANCE </th>
                        <th>MINUS</th>
                        <th>WEEK</th>
                        <th>STATUS</th>
                        <th>STAFF</th>
                    </tr>

                    <?php
                    $no = 1;
                    $total_minus = 0;
                    $query_s = mysqli_query($con, "
        SELECT d.*,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
        where d.loan  in (select loan from deliquency where tgl_input='$tgl_banding' and id_cabang='$id_cabang') and d.tgl_input='$tgl_awal' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
                    while ($data = mysqli_fetch_array($query_s)) {

                        $loan = $data['loan'];
                        $banding = mysqli_query($con, "select sisa_saldo from deliquency where loan='$loan' and tgl_input='$tgl_banding' and id_cabang='$id_cabang'");
                        $banding = mysqli_fetch_array($banding);
                        $saldo_awal = $data['sisa_saldo'];
                        $saldo_akhir = $banding['sisa_saldo'];
                        $total = $saldo_awal - $saldo_akhir;

                        if ($total > 0) {
                            $total_minus +=  $total;

                            // $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
                            if ($par) {
                                $baris['baris'] = "#c9c7c1";
                                $baris['text'] = "red";
                                $baris['ket'] = 'RE/DTD';
                            } else {
                                $baris['baris'] = "#ffff";
                                $baris['text'] = "#black";
                                $baris['ket'] = '';
                            }
                    ?>
                            <tr style="background-color:<?= $baris['baris'] ?>;color:<?= $baris['text'] ?>">
                                <td><?= $no++ ?></td>
                                <td><?= $data['loan'] ?></td>
                                <td><?= $data['no_center'] ?></td>
                                <td><?= $data['id_detail_nasabah'] ?></td>
                                <td><?= $data['nasabah'] ?></td>
                                <td><?= angka($data['amount'], $sepat) ?></td>
                                <td><?= angka($saldo_awal, $sepat) ?></td>
                                <td><?= angka($saldo_akhir, $sepat) ?></td>
                                <td>-<?= angka($total, $sepat) ?></td>
                                <td><?= $data['minggu'] ?></td>
                                <td><?= $baris['ket'] ?></td>
                                <td><?= $data['nama_karyawan'] ?></td>
                            </tr>
                    <?php
                        }
                    } ?>
                    <tr>
                        <th colspan="8">TOTAL OUTSTANDING BERKURANG</th>
                        <th>-<?= angka($total_minus, $sepat) ?></th>
                    </tr>
                </table>
            </div>
        <?php
        }
        ?>










    <?php
    } elseif (isset($_GET['rekap'])) {
        include("./proses/rekap_par.php");
    } elseif (isset($_GET['rekap_semua'])) {
        include("./proses/rekap_semua.php");
    } elseif (isset($_GET['anal_topup'])) {
        include("./proses/anal_topup.php");
    } else if (isset($_GET['list'])) {
        include("./proses/list_par.php");
    } else {
    ?>
        <h2><a href="<?= $url . $menu ?>par&list" class="btn btn-success">Lihat Data Par</a></h2>
    <?php
    }

    if (isset($_GET['delete'])) {
        $tgl = $_GET['tgl'];
        $hapus = mysqli_query($con, "delete from deliquency where tgl_input='$tgl' and id_cabang='$id_cabang'");

        if ($hapus) {
            alert("Data Berhasil dihapus");
        } else alert("Data gagal dihapus");

        pindah($url . $menu . "par");
    }
    ?>

</div>

<style>
    /* style sheet for "A4" printing */
    @page {
        size: auto;
        size: F4;
        margin: 0mm;
    }
</style>
<script>
    function printPageArea(areaID) {
        var printContent = document.getElementById(areaID);
        var WinPrint = window.open('', '', 'width=900,height=650');
        WinPrint.document.write(printContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }

    function buka(url) {
        let l = "<?= $url ?>";
        var myWindow = window.open(url, "DATA PAR", "width=1000,height=1000");
        //   myWindow.document.write("<p>This is 'MsgWindow'. I am 200px wide and 100px tall!</p>");
    }
</script>