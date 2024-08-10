<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Daftar Staff</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hovered" id="daftar_staff">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th class="text-center">Total</th>
                                <th>#</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                            $cek_ka = mysqli_query($con, "SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
                            while ($karyawan = mysqli_fetch_assoc($cek_ka)) {


                                $q = mysqli_query($con, "
                            SELECT  id_karyawan,
                            SUM(CASE WHEN produk = 'PINJAMAN UMUM' THEN 1 ELSE 0 END) AS pu,
                            SUM(CASE WHEN produk = 'PINJAMAN MIKROBISNIS' OR produk = 'PINJAMAN MIKRO BISNIS' THEN 1 ELSE 0 END) AS pmb,
                            SUM(CASE WHEN produk = 'PINJAMAN SANITASI' THEN 1 ELSE 0 END) AS psa,
                            SUM(CASE WHEN produk = 'PINJAMAN DT. PENDIDIKAN' THEN 1 ELSE 0 END) AS ppd,
                            SUM(CASE WHEN produk = 'PINJAMAN ARTA' THEN 1 ELSE 0 END) AS arta,
                            SUM(CASE WHEN produk = 'PINJAMAN RENOVASIRUMAH' THEN 1 ELSE 0 END) AS prr,
                                SUM(CASE WHEN 
                            produk != 'PINJAMAN UMUM' AND  
                            produk != 'PINJAMAN SANITASI' AND
                            produk != 'PINJAMAN MIKROBISNIS' AND
                            produk != 'PINJAMAN MIKRO BISNIS' AND
                            produk != 'PINJAMAN DT. PENDIDIKAN' AND
                            produk != 'PINJAMAN ARTA' AND produk != 'PINJAMAN RENOVASIRUMAH'
                            
                            THEN 1 ELSE 0 END) AS lain_lain,
                            COUNT(*) AS total
                            
                        FROM pinjaman where id_karyawan=$karyawan[id_karyawan] and monitoring='belum'
                            and tgl_cair <= CURDATE() and input_mtr='sudah' and id_cabang='$id_cabang'
                        GROUP BY id_karyawan ");
                                $pemb = mysqli_fetch_assoc($q);
                                echo mysqli_error($con);
                                $total = $pemb['total'] ? $pemb['total'] : 0;
                                $pu = ($pemb['pu'] == null ? 0 : $pemb['pu']);
                                $pmb = ($pemb['pmb'] == null ? 0 : $pemb['pmb']);;
                                $psa = ($pemb['psa'] == null ? 0 : $pemb['psa']);;
                                $ppd = ($pemb['ppd'] == null ? 0 : $pemb['ppd']);;
                                $arta = ($pemb['arta'] == null ? 0 : $pemb['arta']);;
                                $lain = ($pemb['lain_lain'] == null ? 0 : $pemb['lain_lain']);
                                $prr = ($pemb['prr'] == null ? 0 : $pemb['prr']);

                                $hitung_agt = mysqli_query($con, "select total_nasabah as member from total_nasabah where id_cabang='$id_cabang' and id_karyawan='$karyawan[id_karyawan]'");
                                $hitung_agt = mysqli_fetch_assoc($hitung_agt);
                                $hitung_agt = $hitung_agt['member'];
                                $tiga_persen = ($hitung_agt == null ? 0 : round($hitung_agt * 3 / 100));

                                $kumpul = null;
                                if ($tiga_persen == $total) {
                                    $lebih = 'Pas';
                                    $warna = '';
                                } elseif ($tiga_persen >= $total) {

                                    $warna = '';
                                    $lebih = "Bagus";
                                } elseif ($total >= ($tiga_persen * 3)) {
                                    $lebih = "lebih dari 9%";
                                    $warna = "#b0b0b0";
                                    $kumpul =  ($total - $tiga_persen) + 1;
                                } else {
                                    $lebih = "Lebih";
                                    $kumpul =  $total - $tiga_persen;

                                    $warna = '#fcc0c0';
                                }
                                if ($total == 0) {
                                    $warna = '#50ff4a';
                                    $lebih = "NOL";
                                }
                                if ($hitung_agt == 0) {
                                    $kumpul = "";
                                    $lebih = "Anggota NOLL";
                                    $warna = '#e4e7ed';
                                }

                                $json = array(
                                    'total_monitoring' => $total,
                                    'keterangan' => $lebih,
                                    'pu' => $pu,
                                    'psa' => $psa,
                                    'pmb' => $pmb,
                                    'ppd' => $ppd,
                                    'arta' => $arta,
                                    'prr' => $prr,
                                    'total_anggota' => $hitung_agt

                                );
                                $json = json_encode($json);
                            ?>
                            <tr data-detail='<?= $json ?>'>
                                <th>
                                    <a href="index.php?menu=pinjaman&filter&staff=<?= $karyawan['id_karyawan'] ?>">
                                        <?= $karyawan['nama_karyawan'] ?>
                                </th>

                                </a>
                                <th style="text-align: center;vertical-align: middle;"><?= $total ?></th>
                                <th><button class="btn btn-primary detail-btn">Detail</button></th>
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