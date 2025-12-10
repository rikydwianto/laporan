<?php
$tgl = $_GET['tgl'];
if (isset($_GET['id'])) {
    $id  = aman($con, $_GET['id']);
    $q_tambah['karyawan'] = "and p.id_karyawan='$id'";
} else $q_tambah['karyawan'] = "";
$qcek = mysqli_query($con, "SELECT * FROM pengembalian p JOIN detail_pengembalian d ON p.id=d.id_pengembalian where p.tgl_pengembalian='$tgl' and p.id_cabang='$id_cabang'  $q_tambah[karyawan]");
echo mysqli_error($con);
?>
<div class='content table-responsive'>
    <a href="<?= $url . $menu ?>rekap_setoran&tgl=<?= $tgl ?>" class="btn btn-success">kembali</a>

    <h2 class='page-header'>Detail Setoran <?= format_hari_tanggal($tgl) ?>
        <hr>
    </h2>

    <div class='col-md-8'>
        <a href='<?= $url . $menu . 'detail_setoran&tgl=' . $tgl ?>' class='btn btn-success'>Lihat Semua Center</a>
        <form method='get'>
            <input type=hidden name='menu' value='detail_setoran' />
            <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
            <div class='col-md-5'>
                <select name="id" required id="karyawan" class='form-control'>
                    <option value="">PILIH SEMUA STAFF</option>
                    <?php
                    $id = aman($con, $_GET['id']);
                    $data_karyawan  = (karyawan($con, $id_cabang)['data']);

                    for ($i = 0; $i < count($data_karyawan); $i++) {
                        $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                        $idk = $data_karyawan[$i]['id_karyawan'];
                        if ($idk == $id) {
                            echo "<option selected value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                        } else {
                            echo "<option value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <input type=submit name='cari' value='CARI' />
        </form>
        <table class='table  table-bordered'>
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>STAFF</th>
                    <th>CENTER</th>
                    <th>POKOK</th>
                    <th>MARGIN</th>
                    <th>POKOK + MARGIN</th>
                    <th>SIMPANAN DEBIT</th>
                    <th>SIMPANAN KREDIT</th>
                    <th>TOTAL</th>
                    <th>TOTAL UANG</th>
                    <th>MEMBER</th>
                    <th>BAYAR</th>
                    <th>HADIR</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_margin = 0;
                $total_pokok = 0;
                $total_total = 0;
                $total_sd = 0;
                $total_kd = 0;
                $total_uang = 0;
                $total_semua = 0;
                while ($r = mysqli_fetch_assoc($qcek)) {
                    $detil = json_decode($r['json_detail_pengembalian']);
                    $detil = $detil->attribute;
                    $pokok = int_xml($detil->Pokok);
                    $sukareladebet = int_xml($detil->SukarelaDebet) +  int_xml($detil->SiharaDebet) +  int_xml($detil->WajibDebet) +  int_xml($detil->PokokDebet) +  int_xml($detil->PensiunDebet);
                    $sukarelakredit = int_xml($detil->SukarelaKredit) +  int_xml($detil->SiharaKredit) +  int_xml($detil->WajibKredit) +  int_xml($detil->PokokKredit) +  int_xml($detil->PensiunKredit) - (int_xml($detil->RupaPendapatan) + int_xml($detil->RupaPendapatan2) + int_xml($detil->RupaPendapatan3));
                    $center = $r['no_center'];
                    $margin = int_xml($detil->NISBAH);
                    $total = $pokok + $margin;
                    $total_margin += $margin;
                    $total_pokok += $pokok;
                    $total_total += $total;
                    $total_sd += $sukareladebet;
                    $total_kd += $sukarelakredit;
                    $total_uang = (($total + $sukareladebet) - $sukarelakredit);
                    $total_semua += $total_uang;
                    //{"attribute":{"CenterID1":"133","Disbursed":"0.0000","DanaResiko":"0.0000","Pokok":"1426100.0000","NISBAH":"396700.0000","WajibDebet":"74000.0000","WajibKredit":"0.0000","SukarelaDebet":"26600.0000","SukarelaKredit":"128400.0000","PokokDebet":"0.0000","PokokKredit":"0.0000","PensiunDebet":"0.0000","PensiunKredit":"0.0000","SiharaDebet":"395000.0000","SiharaKredit":"0.0000","RupaPendapatan":"0.0000","RupaPendapatan2":"0.0000","QurbanDebet":"0.0000","QurbanKredit":"0.0000","RupaPendapatan3":"0.0000","TOTAL":"2190000.0000"}}
                    $de_laporan = mysqli_query($con, "SELECT
                dl.*
              FROM
                detail_laporan dl
                JOIN laporan l
                  ON l.`id_laporan` = dl.`id_laporan`
                JOIN karyawan k
                  ON k.`id_karyawan` = l.`id_karyawan`
              WHERE no_center = '$center'
                AND tgl_laporan = '$tgl'
                AND k.`id_cabang`='$id_cabang'
                AND dl.`status_detail_laporan`='sukses'");
                    $del_laporan = mysqli_fetch_assoc($de_laporan);
                    error_reporting(0)
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $r['nama_karyawan'] ?></td>
                        <td style='text-align:center'><?= $center ?></td>
                        <td align='right'><?= angka($pokok) ?></td>
                        <td align='right'><?= angka($margin) ?></td>
                        <td align='right'><?= angka($total) ?></td>
                        <td align='right'><?= angka($sukareladebet) ?></td>
                        <td align='right'><?= angka($sukarelakredit) ?></td>
                        <td align='right'><?= angka($sukareladebet - $sukarelakredit) ?></td>
                        <td align='right'><?= angka($total_uang) ?></td>
                        <td align='right'><?= $del_laporan['member'] ?></td>
                        <td align='right'><?= $del_laporan['total_bayar'] ?></td>
                        <td align='right'><?= $del_laporan['anggota_hadir'] ?></td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">TOTAL</th>
                    <th style="text-align:right"><?= angka($total_pokok) ?></th>
                    <th style="text-align:right"><?= angka($total_margin) ?></th>
                    <th style="text-align:right"><?= angka($total_total) ?></th>
                    <th style="text-align:right"><?= angka($total_sd) ?></th>
                    <th style="text-align:right"><?= angka($total_kd) ?></th>
                    <th style="text-align:right"><?= angka($total_sd - $total_kd) ?></th>
                    <th style="text-align:right"><?= angka($total_semua) ?></th>
                </tr>

            </tfoot>


        </table>
    </div>

</div>