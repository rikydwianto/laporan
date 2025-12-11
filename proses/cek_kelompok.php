<?php
if (isset($_GET['lebih'])) {
    $lebih = $_GET['lebih'];
} else $lebih = 7; ?>
<div class='content table-responsive'>
    <h1>CEK KELOMPOK LEBIH DARI <?= $lebih ?></h1>
    <?php
    for ($i = 7; $i <= 15; $i++) {
    ?>
        <a href="<?= $url . $menu ?>cek_kelompok&lebih=<?= $i ?>" class="btn"><?= $i ?></a>
    <?php
    }
    ?>
    <br />
    <?php
    foreach (hari() as $hari) {
        $hari = strtolower($hari);
    ?>
        <a href="<?= $url . $menu ?>cek_kelompok&lebih=<?= $lebih ?>&hari=<?= $hari ?>" class="btn"><?= $hari ?></a>
    <?php
    }
    ?>
    <table id='' class='table'>
        <thead>
            <tr>
                <th>NO</th>
                <th>CENTER</th>
                <th>KELOMPOK</th>
                <th>ANGGOTA</th>
                <th>MERGER<br />HARUS DIPINDAH</th>
                <th>STAFF</th>
                <th>HARI</th>
            </tr>

        </thead>
        <tbody>

            <?php
            if (isset($_GET['hari'])) {
                $qtam = "and hari='$_GET[hari]'";
            } else {
                $qtam = "";
            }
            
            // OPTIMIZED: Single query dengan subquery untuk menghitung total
            $q = mysqli_query($con, "
                SELECT 
                    no_center, 
                    kelompok, 
                    COUNT(*) as total_anggota,
                    hari,
                    k.nama_karyawan,
                    GROUP_CONCAT(d.id_detail_nasabah ORDER BY d.id_nasabah DESC) as id_list,
                    GROUP_CONCAT(d.nama_nasabah ORDER BY d.id_nasabah DESC SEPARATOR '|||') as nama_list
                FROM daftar_nasabah d 
                JOIN karyawan k ON k.id_karyawan = d.id_karyawan 
                WHERE d.id_cabang = '$id_cabang' $qtam 
                GROUP BY no_center, kelompok, hari, k.nama_karyawan
                HAVING total_anggota >= $lebih
                ORDER BY nama_karyawan, no_center, kelompok, hari
            ");
            echo mysqli_error($con);
            
            // Cache kelompok data per center untuk menghindari query berulang
            $center_kelompok_cache = [];
            
            while ($r = mysqli_fetch_assoc($q)) {
                $hitung = $r['total_anggota'];
                $center_key = $r['no_center'];
                
                // Cache kelompok data untuk center ini jika belum ada
                if (!isset($center_kelompok_cache[$center_key])) {
                    $qkel = mysqli_query($con, "
                        SELECT kelompok, COUNT(*) as total_anggota 
                        FROM daftar_nasabah 
                        WHERE no_center = '$center_key' AND id_cabang = '$id_cabang' 
                        GROUP BY kelompok
                    ");
                    $center_kelompok_cache[$center_key] = [];
                    while ($kel = mysqli_fetch_assoc($qkel)) {
                        $center_kelompok_cache[$center_key][] = $kel;
                    }
                }
            ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $r['no_center'] ?></td>
                        <td><?= $r['kelompok'] ?></td>
                        <td><?= $hitung ?></td>
                        <td>
                            <?php
                            $limit = $hitung - 6;
                            
                            // Parse data dari GROUP_CONCAT
                            $id_array = explode(',', $r['id_list']);
                            $nama_array = explode('|||', $r['nama_list']);
                            
                            // Ambil hanya yang perlu dipindah (limit pertama)
                            for ($i = 0; $i < min($limit, count($id_array)); $i++) {
                            ?>
                                <?= htmlspecialchars($nama_array[$i]) ?> - <a onclick="salin('<?= htmlspecialchars($id_array[$i]) ?>')"><?= htmlspecialchars($id_array[$i]) ?></a><br />
                            <?php
                            }

                            // Tampilkan info kelompok dari cache
                            foreach ($center_kelompok_cache[$center_key] as $kel) {
                            ?>
                                kel : <?= $kel['kelompok'] ?>
                                agt : <?= $kel['total_anggota'] ?><br />
                            <?php
                            }
                            ?>
                        </td>
                        <td><?= $r['nama_karyawan'] ?></td>
                        <td><?= $r['hari'] ?></td>
                    </tr>
            <?php
            }
            ?>
        </tbody>
        <tbody>
            <tr>
                <th>NO</th>
                <th>CENTER</th>
                <th>KELOMPOK</th>
                <th>ANGGOTA</th>
                <th>MERGER</th>
                <th>STAFF</th>
                <th>HARI</th>
            </tr>

        </tbody>
    </table>
</div>



<script>
    $(document).ready(function() {
        $('#kelompok').DataTable({
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });
</script>