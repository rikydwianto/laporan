<?php 
$tgl_awal  = $_GET['sebelum'] ?? '';
$tgl_banding = $_GET['minggu_ini'] ?? '';
$id = isset($_GET['id']) ? aman($con, $_GET['id']) : '';
$q_tambah = ($id != null && $id != '') ? "AND k.id_karyawan='$id'" : "";

?>
<h3> ANGGOTA PAR <?= $tgl_banding ?></h3>
<div class='content table-responsive'>
    <form action="" method="post">
        <select name="id" id="karyawan" class='form-control'>
            <option value="">PILIH SEMUA STAFF</option>
            <?php
            $data_karyawan = (karyawan($con, $id_cabang)['data']);

            foreach ($data_karyawan as $karyawan_item) {
                $selected = ($karyawan_item['id_karyawan'] == $id) ? 'selected' : '';
                echo "<option $selected value='{$karyawan_item['id_karyawan']}'>{$karyawan_item['nama_karyawan']}</option>";
            }
            ?>
        </select>

    </form>
    <?php
    $jk_default = array(
        "25" => 0.06,
        "50" => 0.06,
        "75" => 0.06,
        "100" => 0.06,
    );
    ?>

    <table class='table table-bordered'>
        <tr>
            <td>NO</td>
            <td>LOAN</td>
            <td>CENTER</td>
            <td>ID AGT</td>
            <td>ANGGOTA</td>
            <td>JW</td>
            <td>RIll</td>
            <td>DISBURSE</td>
            <td>BALANCE</td>
            <td>TOPUP</td>
            <?php
            foreach ($jk_default as $j => $v) {
                echo "<td>ANGSURAN<br />$j</td>";
            }
            ?>
            <td>HARI</td>
            <td>STAFF</td>
            <td>JENIS TOPUP</td>
            <td>KETERANGAN</td>
        </tr>

        <?php
        $no = 1;
        $total_bermasalah = 0;

        // OPTIMASI: Ambil max tgl_input sekali saja di luar query utama
        $max_tgl_result = mysqli_query($con, "SELECT MAX(tgl_input) as max_tgl FROM deliquency WHERE id_cabang='$id_cabang'");
        $max_tgl = mysqli_fetch_assoc($max_tgl_result)['max_tgl'] ?? '';
        
        // OPTIMASI: Gabungkan check anggota_par dalam LEFT JOIN untuk menghindari N+1 query
        $query = mysqli_query($con, "
            SELECT 
                d.*,
                k.nama_karyawan, 
                c.hari as hari_center,
                IF(ap.id_detail_nasabah IS NOT NULL, 1, 0) as is_par
            FROM deliquency d 
            JOIN center c ON c.no_center = d.no_center 
            JOIN karyawan k ON k.id_karyawan = c.id_karyawan 
            LEFT JOIN anggota_par ap ON ap.id_detail_nasabah = d.id_detail_nasabah
            WHERE d.tgl_input = '$max_tgl'
                AND c.id_cabang = '$id_cabang' 
                AND d.id_cabang = '$id_cabang' 
                AND k.id_cabang = '$id_cabang'
                $q_tambah
            ORDER BY k.nama_karyawan, d.sisa_saldo ASC
        ");

        if (mysqli_error($con)) {
            echo "<tr><td colspan='15' style='color:red'>Error: " . mysqli_error($con) . "</td></tr>";
        }
        
        while ($data = mysqli_fetch_assoc($query)) {
            $total_bermasalah += $data['sisa_saldo'];
            
            // OPTIMASI: Gunakan hasil dari LEFT JOIN, tidak perlu query lagi
            if ($data['is_par']) {
                $baris_bg = "#c9c7c1";
                $baris_text = "red";
                $baris_ket = 'RE/DTD';
            } else {
                $baris_bg = "#ffff";
                $baris_text = "#black";
                $baris_ket = '';
            }
            
            $hari = $data['hari_center'] ?? '';
            
            // OPTIMASI: Hitung persentase sekali
            $setengah = ($data['priode'] > 0) ? ($data['minggu_rill'] / $data['priode']) * 100 : 0;
            
            // Tentukan jk berdasarkan setengah
            if ($setengah >= 50) {
                $jk = array(
                    "25" => 0.06,
                    "50" => 0.06,
                    "75" => 0.06,
                    "100" => 0.06,
                );
            } else {
                $jk = array(
                    "25" => 0.12,
                    "50" => 0.12,
                    "75" => 0.12,
                    "100" => 0.12,
                );
            }

            // Hitung saldo topup
            // $bagi = 1000000;
            // $saldo = $data['sisa_saldo'] / $bagi;
            $saldo = $data['sisa_saldo'] ;
            // $saldo = round(round($saldo, 2, PHP_ROUND_HALF_UP) * $bagi, 2);
            // $saldo = $saldo + ($saldo * 0);
            // $saldo = ceil($saldo / 100000) * 100000;
            $saldo = $saldo  + 10000;
            
            $ket_tpk = ($setengah < 50 && $data['jenis_topup'] == 'KHUSUS') ? "TIDAK BISA TPK" : "";
        ?>
        <tr style="background-color:<?= $baris_bg ?>;color:<?= $baris_text ?>">
            <td><?= $no++ ?></td>
            <td><?= $data['loan'] ?></td>
            <td><?= $data['no_center'] ?></td>
            <td><?= $data['id_detail_nasabah'] ?></td>
            <td><?= $data['nasabah'] ?></td>
            <td><?= $data['priode'] ?></td>
            <td><?= $data['minggu_rill'] ?></td>
            <td><?= angka($data['amount']) ?></td>
            <td><?= angka(round($data['sisa_saldo'])) ?></td>
            <td><?= angka($saldo) ?></td>
            <?php
            foreach ($jk as $j => $v) {
                $angsuran = ($j > 0) ? angka(($saldo + ($saldo * $v)) / $j) : 0;
                echo "<td>$angsuran</td>";
            }
            ?>
            <td><?= strtoupper($hari) ?></td>
            <td><?= $data['nama_karyawan'] ?></td>
            <td><?= $data['jenis_topup'] ?></td>
            <td><?= $ket_tpk ?></td>
        </tr>
        <?php
        } ?>
        <tr>
            <th colspan="8">TOTAL OUTSTANDING BERMASALAH</th>
            <th><?= angka($total_bermasalah) ?></th>
            <th colspan="6"></th>
        </tr>
    </table>
</div>
<script>
$("#karyawan").on("change", function() {
    var url = "<?= $url . $menu ?>par&anal_topup=ANALISA+TOPUP";
    let id = $(this).val();
    if (id == "" || id == null) {
        window.location.replace(url);
    } else {
        window.location.replace(url + "&id=" + id);
    }
});
</script>