<?php 
$tgl_awal  = $_GET['sebelum'];
$tgl_banding = $_GET['minggu_ini']; 

// Escape variables untuk mencegah SQL injection
$tgl_awal = mysqli_real_escape_string($con, $tgl_awal);
$tgl_banding = mysqli_real_escape_string($con, $tgl_banding);
$id_cabang = mysqli_real_escape_string($con, $id_cabang);

// Initialize variable for angka() function
$sepat = '';
?>

<a href="#rekap" onclick="printPageArea('rekap_tambah_par')" class="btn btn-success"> PRINT PENAMBAHAN <i class="fa fa-print"></i></a>
<a href="#rekap" onclick="printPageArea('rekap_penurunan_par')" class="btn btn-success"> PRINT PENURUNAN <i class="fa fa-print"></i></a>
<h2>REKAP DELIQUENCY</h2>
<hr />
<div class='content table-responsive'>
    <div class='col-lg-6' id='rekap_penurunan_par'>
        <table class="table table-bordered">
            <tr>
                <th colspan="4">
                    <h3>REKAP PENURUNAN PAR</h3>
                </th>
            </tr>
            <tr>
                <th>NO</th>
                <th>STAFF</th>
                <th>TOTAL </th>
                <th>OUTSTANDING </th>
            </tr>
            <?php
            $no = 1;
            $total_minus = 0;
            $total_minus_agt = 0;
            
            // OPTIMASI: Gunakan LEFT JOIN untuk menghindari subquery NOT IN
            $query = mysqli_query($con, "
                SELECT 
                    COUNT(d.id) as total, 
                    SUM(d.sisa_saldo) as balance,
                    k.nama_karyawan 
                FROM deliquency d 
                INNER JOIN center c ON c.no_center = d.no_center AND c.id_cabang = '$id_cabang'
                INNER JOIN karyawan k ON k.id_karyawan = c.id_karyawan
                LEFT JOIN deliquency d2 ON d2.loan = d.loan 
                    AND d2.tgl_input = '$tgl_banding' 
                    AND d2.id_cabang = '$id_cabang'
                WHERE d.tgl_input = '$tgl_awal' 
                    AND d.id_cabang = '$id_cabang'
                    AND d2.loan IS NULL
                GROUP BY k.id_karyawan, k.nama_karyawan 
                ORDER BY k.nama_karyawan ASC
            ");
            
            while ($minus = mysqli_fetch_assoc($query)) {
                $total_minus += $minus['balance'];
                $total_minus_agt += $minus['total'];
            ?>
                <tr>
                    <th><?= $no++ ?></th>
                    <th><?= $minus['nama_karyawan'] ?></th>
                    <th><?= $minus['total'] ?> </th>
                    <th><?= angka($minus['balance'], $sepat) ?> </th>
                </tr>
            <?php
            }
            ?>
            <tr>
                <th colspan="2"></th>
                <th><?= $total_minus_agt ?> </th>
                <th><?= angka($total_minus, $sepat) ?></th>
            </tr>
        </table>
    </div>
    
    <div class='col-lg-6' id='rekap_tambah_par'>
        <table class="table table-bordered">
            <tr>
                <th colspan="4">
                    <h3>REKAP PENAMBAHAN PAR</h3>
                </th>
            </tr>
            <tr>
                <th>NO</th>
                <th>STAFF</th>
                <th>TOTAL </th>
                <th>OUTSTANDING </th>
            </tr>
            <?php
            $no = 1;
            $total_minus = 0;
            $total_minus_agt = 0;
            
            // OPTIMASI: Gunakan LEFT JOIN untuk menghindari subquery NOT IN
            $query = mysqli_query($con, "
                SELECT 
                    COUNT(d.id) as total, 
                    SUM(d.sisa_saldo) as balance,
                    k.nama_karyawan 
                FROM deliquency d 
                INNER JOIN center c ON c.no_center = d.no_center AND c.id_cabang = '$id_cabang'
                INNER JOIN karyawan k ON k.id_karyawan = c.id_karyawan
                LEFT JOIN deliquency d2 ON d2.loan = d.loan 
                    AND d2.tgl_input = '$tgl_awal' 
                    AND d2.id_cabang = '$id_cabang'
                WHERE d.tgl_input = '$tgl_banding' 
                    AND d.id_cabang = '$id_cabang'
                    AND d2.loan IS NULL
                GROUP BY k.id_karyawan, k.nama_karyawan 
                ORDER BY k.nama_karyawan ASC
            ");
            
            while ($minus = mysqli_fetch_assoc($query)) {
                $total_minus += $minus['balance'];
                $total_minus_agt += $minus['total'];
            ?>
                <tr>
                    <th><?= $no++ ?></th>
                    <th><?= $minus['nama_karyawan'] ?></th>
                    <th><?= $minus['total'] ?> </th>
                    <th><?= angka($minus['balance'], $sepat) ?> </th>
                </tr>
            <?php
            }
            ?>
            <tr>
                <th colspan="2"></th>
                <th><?= $total_minus_agt ?> </th>
                <th><?= angka($total_minus, $sepat) ?></th>
            </tr>
        </table>
    </div>

    <div class='col-lg-6' id='rekap_turun_os'>
        <a href="#rekap" onclick="printPageArea('rekap_turun_os')" class="btn btn-success">print <i class="fa fa-print"></i></a>
        <table class="table table-bordered">
            <tr>
                <th colspan="5">
                    <h3>REKAP PENURUNAN OUTSTANDING PAR</h3>
                </th>
            </tr>
            <tr>
                <th>NO</th>
                <th>STAFF</th>
                <th>OUTSTANDING <br /> <?= $tgl_awal ?></th>
                <th>OUTSTANDING PAR <br /> <?= $tgl_banding ?> </th>
                <th>OUTSTANDING PAR <br /> BERKURANG </th>
            </tr>
            <?php
            $no = 1;
            $total_minus_os = 0;
            $total_minus_os_agt = 0;
            $total_pengurangan = 0;
            
            // OPTIMASI: Gabungkan query dengan INNER JOIN untuk menghindari subquery
            $query = mysqli_query($con, "
                SELECT 
                    k.id_karyawan,
                    k.nama_karyawan,
                    SUM(d1.sisa_saldo) as total_turun_awal,
                    SUM(d2.sisa_saldo) as total_turun_banding,
                    COUNT(DISTINCT d1.id) as hitung
                FROM deliquency d1
                INNER JOIN deliquency d2 ON d2.loan = d1.loan 
                    AND d2.tgl_input = '$tgl_banding' 
                    AND d2.id_cabang = '$id_cabang'
                INNER JOIN center c ON c.no_center = d1.no_center AND c.id_cabang = '$id_cabang'
                INNER JOIN karyawan k ON k.id_karyawan = c.id_karyawan
                WHERE d1.tgl_input = '$tgl_awal' 
                    AND d1.id_cabang = '$id_cabang'
                GROUP BY k.id_karyawan, k.nama_karyawan 
                ORDER BY k.nama_karyawan ASC
            ");

            while ($minus = mysqli_fetch_assoc($query)) {
                $mingguini = $minus['total_turun_banding'];
                $total_turun_awal = $minus['total_turun_awal'];
                $pengurangan = $total_turun_awal - $mingguini;
                
                $total_minus_os += $total_turun_awal;
                $total_pengurangan += $pengurangan;
                $total_minus_os_agt += $minus['hitung'];
            ?>
                <tr>
                    <th><?= $no++ ?></th>
                    <th><?= $minus['nama_karyawan'] ?></th>
                    <th><?= angka($total_turun_awal, $sepat) ?> </th>
                    <th><?= angka($mingguini, $sepat) ?> </th>
                    <th><?= angka($pengurangan, $sepat) ?> </th>
                </tr>
            <?php
            }
            ?>
            <tr>
                <th colspan="2"></th>
                <th><?= angka($total_minus_os, $sepat) ?></th>
                <th> </th>
                <th><?= angka($total_pengurangan, $sepat) ?></th>
            </tr>
        </table>
    </div>

    <div class='col-lg-6' id='rekap_total_os'>
        <a href="#rekap" onclick="printPageArea('rekap_total_os')" class="btn btn-success">print <i class="fa fa-print"></i></a>
        <table class="table table-bordered">
            <tr>
                <th colspan="4">
                    <h3>REKAP TOTAL OUTSTANDING PAR</h3>
                </th>
            </tr>
            <tr>
                <th>NO</th>
                <th>STAFF</th>
                <th>TOTAL </th>
                <th>OUTSTANDING </th>
            </tr>
            <?php
            $no = 1;
            $total_minus = 0;
            $total_minus_agt = 0;
            
            $query = mysqli_query($con, "
                SELECT 
                    COUNT(d.id) as total, 
                    SUM(d.sisa_saldo) as balance,
                    k.nama_karyawan 
                FROM deliquency d 
                INNER JOIN center c ON c.no_center = d.no_center AND c.id_cabang = '$id_cabang'
                INNER JOIN karyawan k ON k.id_karyawan = c.id_karyawan
                WHERE d.tgl_input = '$tgl_banding' 
                    AND d.id_cabang = '$id_cabang'
                GROUP BY k.id_karyawan, k.nama_karyawan 
                ORDER BY k.nama_karyawan ASC
            ");
            
            while ($minus = mysqli_fetch_assoc($query)) {
                $total_minus += $minus['balance'];
                $total_minus_agt += $minus['total'];
            ?>
                <tr>
                    <th><?= $no++ ?></th>
                    <th><?= $minus['nama_karyawan'] ?></th>
                    <th><?= $minus['total'] ?> </th>
                    <th><?= angka($minus['balance'], $sepat) ?> </th>
                </tr>
            <?php
            }
            ?>
            <tr>
                <th colspan="2"></th>
                <th><?= $total_minus_agt ?> </th>
                <th><?= angka($total_minus, $sepat) ?></th>
            </tr>
        </table>
    </div>

    <div class='col-lg-12' id='rekap'>
        <a href="#rekap" onclick="printPageArea('rekap')" class="btn btn-success">print <i class="fa fa-print"></i></a>
        <table class="table table-bordered" style="border: 1px;">
            <tr>
                <th colspan="9" style="text-align: center;font-weight:bold">
                    <h3>REKAP SEMUA PAR</h3>
                </th>
            </tr>
            <tr>
                <th>NO</th>
                <th>STAFF</th>
                <th>TOTAL O.S PAR <br /> <?= $tgl_awal ?> </th>
                <th>PENURUNAN AGT </th>
                <th>PENGURANGAN O.S </th>
                <th>TOTAL PENURUNAN O.S</th>
                <th>PENAMBAHAN AGT </th>
                <th>PERUBAHAN </th>
                <th>TOTAL O.S PAR <br /> <?= $tgl_banding ?></th>
            </tr>
            <?php
            $no = 1;
            $total_minggu_sebelumnya = 0;
            $total_turun = 0;
            $total_turun_os = 0;
            $total_tambah = 0;
            $total_balance = 0;
            $total_perubahan = 0;
            
            // OPTIMASI: Gunakan satu query dengan subquery yang efisien
            $query = mysqli_query($con, "
                SELECT 
                    k.id_karyawan,
                    k.nama_karyawan,
                    -- Total minggu banding
                    SUM(CASE WHEN d.tgl_input = '$tgl_banding' THEN d.sisa_saldo ELSE 0 END) as balance_banding,
                    COUNT(CASE WHEN d.tgl_input = '$tgl_banding' THEN d.id ELSE NULL END) as total_banding,
                    -- Total minggu awal (kemarin)
                    SUM(CASE WHEN d.tgl_input = '$tgl_awal' THEN d.sisa_saldo ELSE 0 END) as balance_awal
                FROM karyawan k
                INNER JOIN center c ON c.id_karyawan = k.id_karyawan AND c.id_cabang = '$id_cabang'
                INNER JOIN deliquency d ON d.no_center = c.no_center 
                    AND d.id_cabang = '$id_cabang'
                    AND d.tgl_input IN ('$tgl_awal', '$tgl_banding')
                GROUP BY k.id_karyawan, k.nama_karyawan
                HAVING balance_banding > 0
                ORDER BY k.nama_karyawan ASC
            ");
            
            while ($staff = mysqli_fetch_assoc($query)) {
                $id_karyawan = $staff['id_karyawan'];
                $balance = $staff['balance_banding'];
                $minggu_kemarin = $staff['balance_awal'];
                
                // Query untuk PENURUNAN (ada di minggu lalu, tidak ada di minggu ini)
                $query1 = mysqli_query($con, "
                    SELECT SUM(d1.sisa_saldo) as balance 
                    FROM deliquency d1
                    INNER JOIN center c ON c.no_center = d1.no_center AND c.id_karyawan = '$id_karyawan'
                    LEFT JOIN deliquency d2 ON d2.loan = d1.loan 
                        AND d2.tgl_input = '$tgl_banding' 
                        AND d2.id_cabang = '$id_cabang'
                    WHERE d1.tgl_input = '$tgl_awal' 
                        AND d1.id_cabang = '$id_cabang'
                        AND d2.loan IS NULL
                ");
                $turun = mysqli_fetch_assoc($query1);
                $turun = $turun['balance'];
                
                // Query untuk PENAMBAHAN (ada di minggu ini, tidak ada di minggu lalu)
                $query2 = mysqli_query($con, "
                    SELECT SUM(d1.sisa_saldo) as balance 
                    FROM deliquency d1
                    INNER JOIN center c ON c.no_center = d1.no_center AND c.id_karyawan = '$id_karyawan'
                    LEFT JOIN deliquency d2 ON d2.loan = d1.loan 
                        AND d2.tgl_input = '$tgl_awal' 
                        AND d2.id_cabang = '$id_cabang'
                    WHERE d1.tgl_input = '$tgl_banding' 
                        AND d1.id_cabang = '$id_cabang'
                        AND d2.loan IS NULL
                ");
                $tambah_result = mysqli_fetch_assoc($query2);
                $tambah = $tambah_result['balance'];
                
                // Query untuk PENGURANGAN OS (loan yang ada di kedua periode)
                $query3 = mysqli_query($con, "
                    SELECT 
                        SUM(d1.sisa_saldo) as total_awal,
                        SUM(d2.sisa_saldo) as total_banding
                    FROM deliquency d1
                    INNER JOIN deliquency d2 ON d2.loan = d1.loan 
                        AND d2.tgl_input = '$tgl_banding' 
                        AND d2.id_cabang = '$id_cabang'
                    INNER JOIN center c ON c.no_center = d1.no_center AND c.id_karyawan = '$id_karyawan'
                    WHERE d1.tgl_input = '$tgl_awal' 
                        AND d1.id_cabang = '$id_cabang'
                ");
                $os_data = mysqli_fetch_assoc($query3);
                $minggu_sebelumnya = $os_data['total_awal'];
                $perubahan = $os_data['total_banding'];
                
                $turun_os = $minggu_sebelumnya - $perubahan;
                $perubahan_minggu_ini = $tambah - ($turun + $turun_os);
                
                $total_minggu_sebelumnya += $minggu_kemarin;
                $total_turun += $turun;
                $total_turun_os += $turun_os;
                $total_tambah += $tambah;
                $total_balance += $balance;
                $total_perubahan += $perubahan_minggu_ini;
                
                if ($perubahan_minggu_ini < 0) {
                    $warna = "green";
                    $icon = "<i class='fa fa-2 fa-sort-desc'></i> ";
                } elseif ($perubahan_minggu_ini == 0) {
                    $warna = "black";
                    $icon = "<i class='fa fa-2 fa-thumbs-up'></i> ";
                } else {
                    $warna = "red";
                    $icon = "<i class='fa fa-2 fa-sort-asc'></i> ";
                }
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $staff['nama_karyawan'] ?></td>
                    <td><?= ($minggu_kemarin == null ? "-" : rupiah($minggu_kemarin)) ?> </td>
                    <td><?= ($turun == null ? "-" : rupiah($turun)) ?> </td>
                    <td><?= ($turun_os == null ? "-" : rupiah($turun_os)) ?> </td>
                    <td><?= rupiah($turun + $turun_os) ?></td>
                    <td><?= ($tambah == null ? "-" : rupiah($tambah)) ?></td>
                    <td style="color:<?= $warna ?>"><?= $icon . rupiah($perubahan_minggu_ini) ?></td>
                    <td><?= ($balance == null ? "-" : rupiah($balance)) ?></td>
                </tr>
            <?php
            }
            
            if ($total_perubahan < 0) {
                $warna_total = "green";
                $icon_total = "<i class='fa fa-2 fa-sort-desc'></i> ";
            } else {
                $warna_total = "red";
                $icon_total = "<i class='fa fa-2 fa-sort-asc'></i> ";
            }
            ?>
            <tr>
                <th colspan="2">TOTAL SEMUA</th>
                <th><?= ($total_minggu_sebelumnya == null ? "-" : rupiah($total_minggu_sebelumnya)) ?> </th>
                <th><?= ($total_turun == null ? "-" : rupiah($total_turun)) ?> </th>
                <th><?= ($total_turun_os == null ? "-" : rupiah($total_turun_os)) ?> </th>
                <th><?= rupiah($total_turun_os + $total_turun) ?> </th>
                <th><?= ($total_tambah == null ? "-" : rupiah($total_tambah)) ?></th>
                <td style="color:<?= $warna_total ?>"><?= $icon_total . rupiah($total_perubahan) ?></td>
                <th><?= ($total_balance == null ? "-" : rupiah($total_balance)) ?></th>
            </tr>
        </table>
    </div>
</div>
<script>
function printPageArea(areaID) {
    var printContent = document.getElementById(areaID);
    var WinPrint = window.open('', '', 'width=900,height=650');
    WinPrint.document.write('<html><head><title>Print</title>');
    WinPrint.document.write('<style>');
    WinPrint.document.write('body { font-family: Arial, sans-serif; }');
    WinPrint.document.write('table { width: 100%; border-collapse: collapse; }');
    WinPrint.document.write('table, th, td { border: 1px solid black; }');
    WinPrint.document.write('th, td { padding: 8px; text-align: left; }');
    WinPrint.document.write('th { background-color: #f2f2f2; }');
    WinPrint.document.write('</style>');
    WinPrint.document.write('</head><body>');
    WinPrint.document.write(printContent.innerHTML);
    WinPrint.document.write('</body></html>');
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
</script>