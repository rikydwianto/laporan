<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">CEK PAR HARI INI</h1>
            <a href="<?= $url . $menu ?>cek_par_hari_ini" class="btn btn-success">Input</a>
            <a href="<?= $url . $menu ?>cek_par_hari_ini&act=banding" class="btn btn-primary">Banding</a>
            <br>
        </div>
    </div>

    <?php

    if (isset($_GET['act']) && $_GET['act'] == 'banding') {
    ?>
    <div class="row">
        <form method="post" enctype="multipart/form-data">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="delinSelect">Select Delin</label>
                    <select class="form-control" id="delinSelect" name="tgl_delin">
                        <option value="" selected>Pilih Delin</option>
                        <?php
                            $q_delin = mysqli_query($con, "SELECT DISTINCT tgl_input FROM deliquency where id_cabang='$id_cabang'");
                            while ($tgl_delin = mysqli_fetch_assoc($q_delin)) {
                            ?>
                        <option value="<?= $tgl_delin['tgl_input'] ?>"><?= $tgl_delin['tgl_input'] ?></option>
                        <?php
                            }

                            ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fileSelect">Select File Pembanding</label>
                    <select class="form-control" id="fileSelect" name="tgl_bayar">
                        <option value="" selected>Temp Bayar</option>
                        <?php
                            $q_delin = mysqli_query($con, "SELECT DISTINCT tgl_bayar FROM temp_bayar where id_cabang='$id_cabang'");
                            while ($tgl_bayar = mysqli_fetch_assoc($q_delin)) {
                            ?>
                        <option value="<?= $tgl_bayar['tgl_bayar'] ?>"><?= $tgl_bayar['tgl_bayar'] ?></option>
                        <?php
                            }

                            ?>
                    </select>
                </div>

                <button type="submit" name='cek_banding' class="btn btn-primary">Submit</button>
            </div>

        </form>
    </div>

    <?php
    } else {
    ?>
    <div class="row">
        <form method="post" enctype="multipart/form-data">
            <div class="col-md-4">
                <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
                <input class="form-control" required type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
                <input type="date" required name="tgl" class='form-control' id="">
                <input type="submit" onclick="return confirm('yakin sudah benar?/')" value="KONFIRMASI"
                    class='btn btn-danger' name='preview'>
            </div>
        </form>
    </div>
    <?php
    }
    ?>




    <?php
    if (isset($_POST['preview'])) {
        try {
            $tgl = $_POST['tgl'];
            $file = $_FILES['file']['tmp_name'];
            $path = $file;
            // $reader = PHPExcel_IOFactory::createReaderForFile($path);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
            mysqli_query($con, "DELETE from temp_bayar where id_cabang='$id_cabang' and tgl_bayar='$tgl'");
            $objek = $reader->load($path);
            $ws = $objek->getActiveSheet();
            $last_row = $ws->getHighestDataRow();
            // $last_row = 10;
            for ($row = 2; $row <= $last_row; $row++) {
                $id_nasabah =  $ws->getCell("B" . $row)->getValue();
                if ($id_nasabah == null) {
                } else {
                    $agt = (substr(ganti_karakter($id_nasabah), 0, 3));

                    if ($agt == "AGT" || $agt == "NSB") {
                        $nasabah =  aman($con, ganti_karakter($ws->getCell("C" . $row)->getValue()));
                        $loan_number['pu'] = aman($con, ganti_karakter($ws->getCell("F" . $row)->getValue()));
                        $loan_number['pmb'] = aman($con, ganti_karakter($ws->getCell("L" . $row)->getValue()));
                        $loan_number['ppd'] = aman($con, ganti_karakter($ws->getCell("R" . $row)->getValue()));
                        $loan_number['pertanian'] = aman($con, ganti_karakter($ws->getCell("X" . $row)->getValue()));
                        $loan_number['psa'] = aman($con, ganti_karakter($ws->getCell("AD" . $row)->getValue()));
                        $loan_number['arta'] = aman($con, ganti_karakter($ws->getCell("AJ" . $row)->getValue()));
                        $loan_number['prr'] = aman($con, ganti_karakter($ws->getCell("AP" . $row)->getValue()));


                        $pinjaman = []; // Inisialisasi array untuk menyimpan pinjaman


                        if (($loan_number['pmb']        == null || empty($loan_number['pmb'])) ||
                            ($loan_number['ppd']        == null || empty($loan_number['ppd'])) ||
                            ($loan_number['pertanian']  == null || empty($loan_number['pertanian'])) ||
                            ($loan_number['psa']        == null || empty($loan_number['psa'])) ||
                            ($loan_number['arta']       == null || empty($loan_number['arta'])) ||
                            ($loan_number['prr']        == null || empty($loan_number['prr']))
                        ) {
                            $pinjaman['loan_no'][] = "tidak ada pinjaman";
                        }

                        if ($loan_number['pu'] != null || !empty($loan_number['pu'])) {
                            $pinjaman['loan_no'][] = $loan_number['pu'];
                        }

                        if ($loan_number['pmb'] != null || !empty($loan_number['pmb'])) {
                            $pinjaman['loan_no'][] = $loan_number['pmb'];
                        }
                        if ($loan_number['ppd'] != null || !empty($loan_number['ppd'])) {
                            $pinjaman['loan_no'][] = $loan_number['ppd'];
                        }
                        if ($loan_number['pertanian'] != null || !empty($loan_number['pertanian'])) {
                            $pinjaman['loan_no'][] = $loan_number['pertanian'];
                        }
                        if ($loan_number['psa'] != null || !empty($loan_number['psa'])) {
                            $pinjaman['loan_no'][] = $loan_number['psa'];
                        }
                        if ($loan_number['arta'] != null || !empty($loan_number['arta'])) {
                            $pinjaman['loan_no'][] = $loan_number['arta'];
                        }
                        if ($loan_number['prr'] != null || !empty($loan_number['prr'])) {
                            $pinjaman['loan_no'][] = $loan_number['prr'];
                        }
                        $loan_types = ['pmb', 'ppd', 'pertanian', 'psa', 'arta', 'prr'];





                        foreach ($pinjaman as $pinj) {
                            if (count($pinj) == 1) {
                                // echo $id_nasabah . '. ' . $nasabah . ' cuti :' . '<br/>';
                            } else {
                                foreach ($pinj as $pin_akhir) {
                                    if ($pin_akhir != "tidak ada pinjaman") {
                                        $kode = explode("-", $pin_akhir)[0];
                                        $bayar = 0;
                                        $saldo = 0;
                                        if ($kode == "PU") {
                                            $bayar = aman($con, ganti_karakter($ws->getCell("K" . $row)->getValue()));
                                            $saldo = aman($con, ganti_karakter($ws->getCell("I" . $row)->getValue()));
                                        } else if ($kode == "PMB") {
                                            $saldo = aman($con, ganti_karakter($ws->getCell("O" . $row)->getValue()));
                                            $bayar = aman($con, ganti_karakter($ws->getCell("Q" . $row)->getValue()));
                                        } else if ($kode == "PPD") {
                                            $saldo = aman($con, ganti_karakter($ws->getCell("U" . $row)->getValue()));
                                            $bayar = aman($con, ganti_karakter($ws->getCell("W" . $row)->getValue()));
                                        } else if ($kode == "PSA") {
                                            $saldo = aman($con, ganti_karakter($ws->getCell("AG" . $row)->getValue()));
                                            $bayar = aman($con, ganti_karakter($ws->getCell("AI" . $row)->getValue()));
                                        } else if ($kode == "PRT") {
                                            $saldo = aman($con, ganti_karakter($ws->getCell("AM" . $row)->getValue()));
                                            $bayar = aman($con, ganti_karakter($ws->getCell("AO" . $row)->getValue()));
                                        } else if ($kode == "PRR") {
                                            $saldo = aman($con, ganti_karakter($ws->getCell("AS" . $row)->getValue()));
                                            $bayar = aman($con, ganti_karakter($ws->getCell("AU" . $row)->getValue()));
                                        }
                                        if (true) {
                                            $staff = aman($con, ganti_karakter($ws->getCell("E" . $row)->getValue()));
                                            $center = aman($con, ganti_karakter($ws->getCell("D" . $row)->getValue()));
                                            $q = "INSERT INTO temp_bayar (
                                                no_center,
                                                loan_no,
                                                id_detail_anggota,
                                                bayar,
                                                tgl_bayar,
                                                nik,
                                                jenis,
                                                id_cabang,
                                                nama_nasabah,
                                                balance
                                            )
                                            VALUES
                                            (
                                                '$center',
                                                '$pin_akhir',
                                                '$id_nasabah',
                                                '$bayar',
                                                '$tgl',
                                                '$staff',
                                                'LOAN',
                                                 '$id_cabang',
                                                 '$nasabah',
                                                 '$saldo'
                                            )";
                                            mysqli_query($con, $q);
                                            echo mysqli_error($con);
                                            // echo $id_nasabah . '. ' . $nasabah . ' pinjaman :' . $pin_akhir . ' - saldo : ' . $saldo . ' | bayar : ' . $bayar . '<br/>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            alert("berhasil ditambahkan");
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }


    if (isset($_POST['cek_banding'])) {
        $tgl_delin = $_POST['tgl_delin'];
        $tgl_bayar = $_POST['tgl_bayar'];
    ?>
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>CTR</th>
                    <th>ID AGT</th>
                    <th>PINJAMAN</th>
                    <th>NAMA</th>
                    <th>BALANCE</th>
                    <th>STAFF</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q_cari = "SELECT * FROM temp_bayar WHERE id_cabang='$id_cabang' and tgl_bayar='$tgl_bayar' and `bayar`<=0 AND loan_no NOT IN(SELECT loan FROM deliquency where id_cabang='$id_cabang' and tgl_input='$tgl_delin') ";
                    $q_cari = mysqli_query($con, $q_cari);
                    while ($belum_bayar = mysqli_fetch_assoc($q_cari)) {
                    ?>
                <tr>
                    <th><?= $no++ ?></th>
                    <td><?= $belum_bayar['no_center'] ?></td>
                    <td><?= $belum_bayar['id_detail_anggota'] ?></td>
                    <td><?= $belum_bayar['loan_no'] ?></td>
                    <th><?= $belum_bayar['nama_nasabah'] ?></th>
                    <td><?= angka($belum_bayar['balance']) ?></td>
                    <td><?= ($belum_bayar['nik']) ?></td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>

        </table>
    </div>


</div>