<form method="post" action="" enctype="multipart/form-data">
    <div class="col-md-4">
        <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
        <input class="form-control" type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
        <input type="submit" value="Proses" class='btn btn-danger' name='ekse'>
    </div>
</form>
<form method="post" enctype="multipart/form-data">
    <div class="col-md-4">
        <label for="formFile" class="form-label">FILE PERMINTAAN DISBURSE SUM XML</label>
        <input class="form-control" type="file" name='file' accept=".xml" id="formFile">
        <input class="form-control" type="date" name='tgl' value="<?= date("Y-m-d") ?>">
        <input type="submit" value="UPLOAD TOPUP" class='btn btn-danger' name='topup'>
    </div>
</form>

<?php
if (isset($_POST['topup'])) {
    $file = $_FILES['file']['tmp_name'];
    $date = mysqli_real_escape_string($con, $_POST['tgl']);
    $xml = simplexml_load_file($file);
    $xml = $xml->Tablix1->Details_Collection;
    
    mysqli_query($con, "DELETE FROM topup where id_cabang='$id_cabang' and tgl_topup='$date'");
    mysqli_query($con, "DELETE FROM keterangan_topup where id_cabang='$id_cabang' and tgl_topup='$date'");
    
    // OPTIMASI: Kumpulkan data untuk batch insert
    $topup_values = [];
    $keterangan_topup_values = [];
    $tpk_values = [];
    
    foreach ($xml->Details as $sum) {
        $os_topup = mysqli_real_escape_string($con, $sum['OsPokokTopUP']);
        $net_disburse = mysqli_real_escape_string($con, $sum['NetDisburse']);
        $officer_name = mysqli_real_escape_string($con, $sum['OfficerName']);
        
        $topup_values[] = "('$os_topup', '$net_disburse', '$date', '$officer_name', '$id_cabang')";
        
        $jenis_topup = $sum['JenisTopUp'];
        if ($jenis_topup != "") {
            $client_id = mysqli_real_escape_string($con, $sum['ClientID']);
            $jenis_topup_escaped = mysqli_real_escape_string($con, $jenis_topup);
            
            $keterangan_topup_values[] = "('$client_id', '$jenis_topup_escaped', '$date', '$id_cabang')";
            
            if ($jenis_topup == "KHUSUS") {
                $id_nasabah = explode("-", $client_id)[1] + 0;
                $tpk_values[] = "('$id_nasabah', '$client_id', '$id_cabang')";
            }
            
            // OPTIMASI: Update jenis_topup di tabel pinjaman untuk performa di masa depan
            mysqli_query($con, "UPDATE pinjaman SET jenis_topup='$jenis_topup_escaped' 
                               WHERE id_detail_nasabah='$client_id' AND id_cabang='$id_cabang'");
        }
    }
    
    // OPTIMASI: Batch insert untuk topup
    if (!empty($topup_values)) {
        $values_string = implode(',', $topup_values);
        mysqli_query($con, "INSERT INTO `topup` (`os_topup`, `sisa_topup`, `tgl_topup`, `nama_karyawan`, `id_cabang`) 
                           VALUES $values_string");
    }
    
    // OPTIMASI: Batch insert untuk keterangan_topup
    if (!empty($keterangan_topup_values)) {
        $values_string = implode(',', $keterangan_topup_values);
        mysqli_query($con, "INSERT INTO `keterangan_topup` (`id_detail_nasabah`, `topup`, `tgl_topup`, `id_cabang`) 
                           VALUES $values_string");
    }
    
    // OPTIMASI: Batch insert untuk tpk
    if (!empty($tpk_values)) {
        $values_string = implode(',', $tpk_values);
        mysqli_query($con, "INSERT INTO `tpk` (`id_nasabah`, `id_detail_nasabah`, `id_cabang`) 
                           VALUES $values_string");
    }
}
?>

<?php
if (isset($_POST['ekse'])) {
?>
    <table class='table'>
        <tr>
            <td>no</td>
            <td>ID NASABAH</td>
            <td>LOAN</td>
            <td>NASABAH</td>
            <td>NO HP</td>
            <td>CENTER</td>
            <td>KELOMPOK</td>
            <td>PRODUK</td>
            <td>PINJAMAN</td>
            <td>OUTSTANDING</td>
            <td>J. WAKTU</td>
            <td>ANGSURAN</td>
            <td>TUJUAN</td>
            <td>PIN. KE</td>
            <td>STAFF</td>
            <td>TGL PENGAJUAN</td>
            <td>TGL PENCAIRAN</td>
            <td>TGL ANGSURAN</td>
        </tr>


        <?php
        $no_input = 0;
        $no = 1;
        $file = $_FILES['file']['tmp_name'];
        $path = $file;
        $reader = PHPExcel_IOFactory::createReaderForFile($path);
        $objek = $reader->load($path);
        $ws = $objek->getActiveSheet();
        $last_row = $ws->getHighestDataRow();

        $cells_to_check = ["A1", "A2", "A3", "A4", "A5"];
        $found = false;

        foreach ($cells_to_check as $cell) {
            $value = $ws->getCell($cell)->getValue();
            if (preg_match('/DAFTAR\s*PINJAMAN/i', $value)) {
                $found = true;
                break;
            }
        }
        
        if ($found) {
            // OPTIMASI: Ambil semua loan yang sudah ada sekali saja
            $existing_loans = [];
            $qloans = mysqli_query($con, "SELECT id_detail_pinjaman FROM pinjaman WHERE id_cabang='$id_cabang'");
            while ($rloan = mysqli_fetch_assoc($qloans)) {
                $existing_loans[$rloan['id_detail_pinjaman']] = true;
            }
            
            // OPTIMASI: Kumpulkan data untuk batch insert
            $insert_values = [];

            for ($row = 7; $row <= $last_row; $row++) {
                $id_nasabah =  $ws->getCell("B" . $row)->getValue();
                if ($id_nasabah == null) {
                    continue;
                }
                
                $agt = (substr(ganti_karakter($id_nasabah), 0, 3));

                if ($agt == "AGT" || $agt == "NSB") {
                    $nasabah =  aman($con, ganti_karakter($ws->getCell("D" . $row)->getValue()));
                    $loan = ganti_karakter($ws->getCell("C" . $row)->getValue());
                    $no_center = aman($con, ganti_karakter($ws->getCell("F" . $row)->getValue()));
                    $id_nasabah = aman($con, ganti_karakter1($ws->getCell("B" . $row)->getValue()));
                    $kelompok = ganti_karakter1($ws->getCell("G" . $row)->getValue());
                    $hp = ganti_karakter1($ws->getCell("E" . $row)->getValue());
                    $produk = ganti_karakter1($ws->getCell("H" . $row)->getValue());
                    $tujuan = ganti_karakter1($ws->getCell("O" . $row)->getValue());
                    $pinj_ke = ganti_karakter1($ws->getCell("P" . $row)->getValue());
                    $staff = ganti_karakter1($ws->getCell("R" . $row)->getValue());

                    $tgl_pengajuan = str_replace("/", "-", ganti_karakter1($ws->getCell("S" . $row)->getValue()));
                    $tgl_pencairan = str_replace("/", "-", ganti_karakter1($ws->getCell("T" . $row)->getValue()));
                    $tgl_angsuran = str_replace("/", "-", ganti_karakter1($ws->getCell("U" . $row)->getValue()));
                    
                    $margin = ganti_karakter1($ws->getCell("M" . $row)->getValue());
                    $pinjaman = (int)ganti_karakter(str_replace(",", "", $ws->getCell("I" . $row)->getValue()));
                    $outstanding = (int)ganti_karakter(str_replace(",", "", $ws->getCell("K" . $row)->getValue()));
                    $jk = (int)ganti_karakter(str_replace(",", "", $ws->getCell("L" . $row)->getValue()));
                    $angsuran = (int)ganti_karakter(str_replace(",", "", $ws->getCell("N" . $row)->getValue()));
                    $tunggakan = (int)ganti_karakter(str_replace(",", "", $ws->getCell("M" . $row)->getValue()));
                    $minggu = (int)ganti_karakter(str_replace(",", "", $ws->getCell("N" . $row)->getValue()));

                    $jenis_topup =  ganti_karakter1($ws->getCell("Q" . $row)->getValue());

        ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $id_nasabah ?></td>
                        <td><?= $loan ?></td>
                        <td><?= $nasabah ?></td>
                        <td><?= $hp ?></td>
                        <td><?= $no_center ?></td>
                        <td><?= $kelompok ?></td>
                        <td><?= $produk ?></td>
                        <td><?= $pinjaman ?></td>
                        <td><?= $outstanding ?></td>
                        <td><?= $jk ?></td>
                        <td><?= $angsuran ?></td>
                        <td><?= $tujuan ?></td>
                        <td><?= $pinj_ke ?></td>
                        <td><?= $staff ?></td>
                        <td><?= $tgl_pengajuan ?></td>
                        <td><?= $tgl_pencairan ?></td>
                        <td><?= $tgl_angsuran ?></td>
                    </tr>
        <?php
                    // OPTIMASI: Cek dari array yang sudah di-cache, bukan query
                    if (!isset($existing_loans[$loan])) {
                        $no_input++;
                        
                        // Escape semua data untuk keamanan
                        $id_nasabah_esc = mysqli_real_escape_string($con, $id_nasabah);
                        $loan_esc = mysqli_real_escape_string($con, $loan);
                        $nasabah_esc = mysqli_real_escape_string($con, $nasabah);
                        $hp_esc = mysqli_real_escape_string($con, $hp);
                        $no_center_esc = mysqli_real_escape_string($con, $no_center);
                        $kelompok_esc = mysqli_real_escape_string($con, $kelompok);
                        $produk_esc = mysqli_real_escape_string($con, $produk);
                        $tujuan_esc = mysqli_real_escape_string($con, $tujuan);
                        $pinj_ke_esc = mysqli_real_escape_string($con, $pinj_ke);
                        $staff_esc = mysqli_real_escape_string($con, $staff);
                        $tgl_pengajuan_esc = mysqli_real_escape_string($con, $tgl_pengajuan);
                        $tgl_pencairan_esc = mysqli_real_escape_string($con, $tgl_pencairan);
                        $tgl_angsuran_esc = mysqli_real_escape_string($con, $tgl_angsuran);
                        $margin_esc = mysqli_real_escape_string($con, $margin);
                        $jenis_topup_esc = mysqli_real_escape_string($con, $jenis_topup);
                        
                        $insert_values[] = "('$id_nasabah_esc', '$loan_esc', '$nasabah_esc', '$hp_esc', '$no_center_esc', '$kelompok_esc', '$produk_esc', '$pinjaman', '$outstanding', '$jk', '$margin_esc', '$angsuran', '$tujuan_esc', '$pinj_ke_esc', '$staff_esc', '$tgl_pengajuan_esc', '$tgl_pencairan_esc', '$tgl_angsuran_esc', '$id_cabang', '$jenis_topup_esc')";
                        
                        // Tambahkan ke cache agar tidak diinsert lagi
                        $existing_loans[$loan] = true;
                    }
                }
            }
            
            // OPTIMASI: Batch insert semua data sekaligus
            if (!empty($insert_values)) {
                // Split menjadi chunk jika terlalu besar (misalnya 100 rows per query)
                $chunks = array_chunk($insert_values, 100);
                foreach ($chunks as $chunk) {
                    $values_string = implode(',', $chunk);
                    mysqli_query($con, "INSERT INTO `pinjaman` 
                        (`id_detail_nasabah`, `id_detail_pinjaman`, `nama_nasabah`, `no_hp`, `center`, `kelompok`, `produk`, `jumlah_pinjaman`, `outstanding`, `jk_waktu`, `margin`, `angsuran`, `tujuan_pinjaman`, `pinjaman_ke`, `staff`, `tgl_pengajuan`, `tgl_pencairan`, `tgl_angsuran`, `id_cabang`, `jenis_topup`)
                        VALUES $values_string");
                }
            }
            
        } else {
            alert('DITOLAK, BUKAN FILE DAFTAR PINJAMAN');
        }
        
        alert("Sebanyak " . ($no_input) . " telah diinput, silahkan sinkron");
        pindah($url . $menu . "monitoring&ganti");
        ?>
    </table>
<?php
}
?>
