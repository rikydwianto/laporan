<div class='content table-responsive'>
    <h2 class='page-header'>KETERANGAN TURUN PAR </h2>
    <i>Anggota Turun PAR</i>
    <hr />
    <a href="<?= $url ?>" class="btn btn-danger">TIDAK ADA</a>
    <div class="col-md-12">
        <div class="row">

            <?php
            $id_karyawan = aman($con, $_GET['id']);
            if (isset($_POST['lanjut'])) {
                $ket = $_POST['ket'];
                for ($a = 0; $a < count($ket); $a++) {
                    $ket1 = $ket[$a];
                    $nasabah = $_POST['nama'][$a];
                    $center = $_POST['center'][$a];
                    if ($ket1 == 'agt_keluar' || $ket1 == 'pelunasan') {
                        $os_selesai = $_POST['sisa'][$a];
                        $keterangan = "Pelunasan/Keluar";
                    } else if ($ket1 == 'bayar_satu') {
                        $os_selesai = (int)ganti_karakter(str_replace(".", "", str_replace(",", "", $_POST['bayar'][$a])));
                        $keterangan = "Bayar 1 atau lebih";
                    } else {
                        $os_selesai = $_POST['tunggakan'][$a];
                        $keterangan = "Double/Tutup Par";
                    }
                    $os_selesai = angka($os_selesai);
                    $text[] = "nama agt : $nasabah
ctr: $center
total bayar: $os_selesai
keterangan: $keterangan";
                    // echo $nasabah. $os_selesai.' '.$center.'<br/>';
                }
                $text_join = implode("\n", $text);
            ?>

                <form action="" method="post">
                    <textarea name="keterangan_lain" class='form-control' id="" cols="30" rows="10">
<?= ($text_join) ?>
 </textarea>
                    <!-- <a href="<?= $url ?>" class="btn btn-danger">Tidak Ada</a> -->
                    <input type="submit" class='btn btn-success' value="SIMPAN" name='simpan_ket'>
                </form>

            <?php

            }
            ?>
            <?php
            if (isset($_POST['simpan_ket'])) {
                $id = aman($con, $_GET['id_laporan']);
                $ket =  $_POST['keterangan_lain'];
                $q = mysqli_query($con, "UPDATE laporan set keterangan_lain='$ket' where id_laporan='$id'");
                if ($q) {
                    alert("Terima Kasih...");
                    pindah($url);
                } else {
                    echo mysqli_error($con);
                }
            }
            ?>

            <?php
            if (isset($_POST['turun_par'])) {

                $cek_no = $_POST['cek_no'];

                //    echo var_dump($cek_no);
                $hitung_no = count($cek_no);
            ?>
                <form action="" id='bayar' method="post">
                    <table class='table'>
                        <tr>
                            <th>NO</th>
                            <th>NAMA</th>
                            <th>OS</th>
                            <th>TUNGGAKAN</th>
                            <th>KETERANGAN</th>
                        </tr>

                        <?php
                        $no = 1;
                        for ($i = 0; $i < $hitung_no; $i++) {
                            $cek_par = mysqli_query($con, "select * from deliquency where loan='$cek_no[$i]' and id_cabang='$id_cabang'");
                            $par = mysqli_fetch_assoc($cek_par);
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $par['nasabah'] ?></td>
                                <td><?= angka($par['sisa_saldo']) ?></td>
                                <td><?= angka($par['tunggakan']) ?></td>
                                <td>
                                    <input type="hidden" name="nama[]" value="<?= $par['nasabah'] ?>">
                                    <input type="hidden" name="loan[]" value="<?= $par['loan'] ?>">
                                    <input type="hidden" name="center[]" value="<?= $par['no_center'] ?>">
                                    <input type="hidden" name="sisa[]" value="<?= $par['sisa_saldo'] ?>">
                                    <input type="hidden" name="tunggakan[]" value="<?= $par['tunggakan'] ?>">
                                    <select id='ket-<?= $par['id'] ?>' onchange="bayar_satu(<?= $par['id'] ?>)" name='ket[]' required>
                                        <option value=''>Pilih</option>
                                        <option value='agt_keluar'>AGT Keluar</option>
                                        <option value='pelunasan'>PELUNASAN</option>
                                        <option value='double'>TUTUP PAR</option>
                                        <option value='bayar_satu'>BAYAR 1/lebih</option>
                                    </select>
                                    <br />
                                    <input type="hidden" class=' form-control' placeholder="outstanding turun(angka)" id='bayar-<?= $par['id'] ?>' name="bayar[]" value="">

                                </td>

                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="1">
                                <input class='btn btn-info btn-sm' type="submit" value="LANJUTKAN" name='lanjut'>

                            </td>
                        </tr>
                    </table>
                </form>
            <?php
            }
            ?>

            <form action="" method="post">


                <table class='table'>
                    <tr>
                        <th>NO</th>
                        <th>CENTER</th>
                        <th>PRODUK</th>
                        <th>LOAN</th>
                        <th>NAMA</th>
                        <th>OS PAR</th>
                        <th>TUNGGAKAN</th>
                        <th>WEEK PAS</th>
                    </tr>
                    <?php
                    $total_sisa_saldo = 0;
                    $no = 1;

                    $q_q['group'] = "group by d.loan";
                    $q_q['tgl'] = "tgl_input IN(SELECT MAX(tgl_input) FROM deliquency where id_cabang='$id_cabang') AND";
                    $q = mysqli_query($con, "SELECT *,c.`id_karyawan` FROM deliquency d JOIN center c ON c.`no_center`=d.`no_center`
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
            WHERE $q_q[tgl] k.`id_cabang`='$id_cabang' AND k.`id_karyawan`='$id_karyawan' and d.id_cabang='$id_cabang' $q_q[group] order by d.no_center,d.nasabah asc");
                    while ($row = mysqli_fetch_assoc($q)) {
                        $produk = $row['loan'];
                        $produk = explode("-", $produk)[0];
                        $par = mysqli_num_rows(mysqli_query($con, "select * from anggota_par where id_detail_nasabah='$row[id_detail_nasabah]' and id_cabang='$id_cabang'"));
                        $sisa_saldo = $row['sisa_saldo'];
                        $total_sisa_saldo += $sisa_saldo;
                        if ($par) {
                            $baris['baris'] = "#c9c7c1";
                            $baris['text'] = "red";
                        } else {
                            $baris['baris'] = "#ffff";
                            $baris['text'] = "#black";
                        }
                    ?>
                        <tr style="background-color:<?= $baris['baris'] ?>;color:<?= $baris['text'] ?>">
                            <td><?= $no++ ?>.
                                <input type="checkbox" class='btn lg form-control' name="cek_no[]" value="<?= $row['loan'] ?>">
                                <input type="hidden" name="nasabah[]" value="<?= $row['id_detail_nasabah'] ?>">
                                <input type="hidden" name="loan[]" value="<?= $row['loan'] ?>">

                            </td>
                            <td><?= $row['no_center'] ?></td>
                            <td><?= $produk ?></td>
                            <td><?= $row['loan'] ?></td>
                            <td><?= $row['nasabah'] ?></td>
                            <td><?= angka($sisa_saldo) ?></td>
                            <td><?= angka($row['tunggakan']) ?></td>
                            <td><?= $row['minggu'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th colspan="1">

                        </th>
                        <th colspan="4">TOTAL O.S PAR</th>
                        <th><?= angka($total_sisa_saldo) ?></th>
                    </tr>

                </table>
                <input type="submit" value="KONFIRMASI" name='turun_par' class='btn btn-danger'>
            </form>
        </div>

    </div>
</div>
<script>
    function bayar_satu(id) {
        $(document).ready(function() {
            const ket = $("#ket-" + id).val();
            const bayar = $("#bayar-" + id);
            // bayar.hide();
            //    alert(ket)
            if (ket == 'bayar_satu') {
                bayar.attr('type', 'number')
                bayar.attr('required', 'required')
            } else {
                bayar.attr('type', 'hidden')
                // bayar.hide();
            }
        })
    }
</script>