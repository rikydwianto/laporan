<?php
$id = aman($con, $_GET['staff']);
$staff = detail_karyawan($con, $id);
if (isset($_GET['filter'])) $q_staff = "and p.id_karyawan='$id'";
else $q_staff = '';
if (isset($_GET['filter_bulan'])) $q_bulan = " and tgl_cair like '$_GET[filter_bulan]-%' ";
else $q_bulan = '';
if (isset($_GET['filter_minggu'])) $q_minggu = " and tgl_cair between '$_GET[tgl_1]' and '$_GET[tgl_2]' ";
else $q_minggu = '';

$q = mysqli_query($con, "select *,DATEDIFF(CURDATE(), tgl_cair) as total_hari from pinjaman p left 
join karyawan k on k.id_karyawan=p.id_karyawan 

where p.id_cabang='$id_cabang' and p.monitoring ='belum' $q_staff $q_bulan $q_minggu and input_mtr='sudah' order by k.nama_karyawan asc");
?>
<div class="row">
    <div class="col-md-12">
        <a href="javascript:kembali()" class="btn btn-success">
            <i class="nc-icon nc-minimal-left text-success"></i> Kembali</a>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"> Monitoring
                    <?= isset($_GET['filter']) ? "an " . $staff['nama_karyawan'] : "" ?>
                    <?= isset($_GET['filter_bulan']) ? " Bulan " . $_GET['filter_bulan'] : "" ?>
                    <?= isset($_GET['filter_minggu']) ? " Dari " . $_GET['tgl_1'] . ' s/d ' . $_GET['tgl_2'] : "" ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hovered" id='list_monitoring'>
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Staff</th>
                                <th>CTR</th>
                                <th>Anggota</th>
                                <th>Loan no</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($pinjaman = mysqli_fetch_assoc($q)) {
                                $center = sprintf("%03d", trim(explode("-", $pinjaman['center'])[0]));
                                $json = array(
                                    'id_pinjaman' => $pinjaman['id_pinjaman'],
                                    'loanno' => ($pinjaman['id_detail_pinjaman']),
                                    'id_anggota' => ($pinjaman['id_detail_nasabah']),
                                    'disburse' => angka($pinjaman['jumlah_pinjaman']),
                                    'produk' => $pinjaman['produk'],
                                    'tujuan_pinjaman' => $pinjaman['tujuan_pinjaman'],
                                    'pinjaman_ke' => $pinjaman['pinjaman_ke'],
                                    'tgl_cair' => hari_biasa($pinjaman['tgl_cair']),
                                    'no_hp' => $pinjaman['no_hp'],
                                    'jk_waktu' => $pinjaman['jk_waktu'],
                                    'status' => $pinjaman['monitoring'],
                                );

                                $json = json_encode($json);
                            ?>
                            <tr data-detail='<?= $json ?>'>
                                <td><?= $no++ ?></td>
                                <td>

                                    <a href="index.php?menu=pinjaman&filter&staff=<?= $pinjaman['id_karyawan'] ?>">
                                        <?= $pinjaman['nama_karyawan'] ?>
                                </td>
                                <td><?= $center ?></td>
                                <th>
                                    <a href="cari_nasabah.php?id_detail=<?= $pinjaman['id_detail_nasabah'] ?>">

                                        <?= $pinjaman['nama_nasabah'] ?>
                                </th>
                                </a>
                                <td><button
                                        class="btn btn-info detail-btn"><?= $pinjaman['id_detail_pinjaman'] ?></button>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>


                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>