<div class='content table-responsive'>
    <h3 class='page-header'>LAPORAN KEGIATAN HARIAN</h3>
    <!-- <i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/> -->
    <!-- Button to Open the Modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalku">
    <i class="fa fa-plus"></i> Center
  </button> -->


    <?php
    if (isset($_GET['tglawal']) || isset($_GET['tglakhir'])) {
        $tglawal = $_GET['tglawal'];
        $tglakhir = $_GET['tglakhir'];
    } else {
        $tgl = date("Y-m-d");
        $tglawal = date("Y-m-d", strtotime('last monday', strtotime("$tgl")));
        $tglakhir = date("Y-m-d");
    }

    ?>
    <form action="">
        <input type="hidden" name='menu' value='rekap_kegiatan' />
        <input type="date" name='tglawal' value="<?= (isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d", (strtotime('-4 day', strtotime(date("Y-m-d")))))) ?>" class="" />
        <input type="date" name='tglakhir' value="<?= (isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d")) ?>" class="" />
        <input type='submit' class="btn btn-info" name='cari' value='FILTER' />
        <a href="<?= $url . $menu ?>rekap_kegiatan&tglawal=<?= date("Y-m-d", (strtotime('-1 day', strtotime(date("Y-m-d"))))) ?>&tglakhir=<?= date("Y-m-d", (strtotime('-1 day', strtotime(date("Y-m-d"))))) ?>&cari=FILTER" class="btn btn-danger">KEMARIN</a>
        <a href="<?= $url . $menu ?>rekap_kegiatan&tglawal=<?= date("Y-m-d") ?>&tglakhir=<?= date("Y-m-d") ?>&cari=FILTER" class="btn btn-danger">HARI INI</a>
    </form>
    <hr>
    <div class="col-md-8">
        <h2 style='text-align:center'>REKAP KEGIATAN HARIAN SL <br>
            <?= format_hari_tanggal($tglawal) ?> s/d <?= format_hari_tanggal($tglakhir) ?>
        </h2>
        <table class='table'>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>STAFF</th>
                    <th>KUNJUNGAN <br /> MASALAH</th>
                    <th>KUNJUNGAN <br /> BIASA</th>
                    <th>CAPRES</th>
                    <th>UK</th>
                    <th>MONITORING</th>
                    <th>KARTU KUNING</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_dtd = 0;
                $total_biasa = 0;
                $total_capres = 0;
                $total_uk = 0;
                $total_mtr = 0;
                $total_kk = 0;
                $qkar = mysqli_query($con, "SELECT distinct k.id_karyawan, k.nama_karyawan from laporan_harian l join karyawan k on k.id_karyawan=l.id_karyawan where l.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' and tgl_laporan between '$tglawal' and '$tglakhir' and status='sukses' order by k.nama_karyawan");
                while ($rkar = mysqli_fetch_assoc($qkar)) {
                    $qlapo = mysqli_query($con, "SELECT 
                sum(kunjungan_dtd) as dtd,
                sum(kunjungan_biasa) as biasa,
                sum(capres) as capres,
                sum(uji_kelayakan) as uk,
                sum(monitoring) as mtr,
                sum(kartu_kuning) as kk
                
                from laporan_harian l where 
                l.id_cabang='$id_cabang' and tgl_laporan between '$tglawal' and '$tglakhir' and status='sukses' and l.id_karyawan='$rkar[id_karyawan]'
                group by l.id_karyawan
                ");
                    $lap = mysqli_fetch_assoc($qlapo);
                    $total_dtd += $lap['dtd'];
                    $total_biasa += $lap['biasa'];
                    $total_capres += $lap['capres'];
                    $total_uk += $lap['uk'];
                    $total_mtr += $lap['mtr'];
                    $total_kk += $lap['kk'];
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $rkar['nama_karyawan'] ?></td>
                        <td style='text-align:center'><?= $lap['dtd'] ?></td>
                        <td style='text-align:center'><?= $lap['biasa'] ?></td>
                        <td style='text-align:center'><?= $lap['capres'] ?></td>
                        <td style='text-align:center'><?= $lap['uk'] ?></td>
                        <td style='text-align:center'><?= $lap['mtr'] ?></td>
                        <td style='text-align:center'><?= $lap['kk'] ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">TOTAL</th>
                    <th style='text-align:center'><?= $total_dtd ?></th>
                    <th style='text-align:center'><?= $total_biasa ?></th>
                    <th style='text-align:center'><?= $total_capres ?></th>
                    <th style='text-align:center'><?= $total_uk ?></th>
                    <th style='text-align:center'><?= $total_mtr ?></th>
                    <th style='text-align:center'><?= $total_kk ?></th>
                </tr>
            </tfoot>

        </table>
    </div>


</div>