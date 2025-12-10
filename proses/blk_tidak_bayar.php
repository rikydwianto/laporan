<a href="<?= $url . $menu ?>blk_input" class="btn btn-success"> Kembali</a>
<?php


$file = $_FILES['file']['tmp_name'];
$path = $file;
$reader = PHPExcel_IOFactory::createReaderForFile($path);
$objek = $reader->load($path);
$ws = $objek->getActiveSheet();
$last_row = $ws->getHighestDataRow();
require './vendor/autoload.php';

?>
<h2>BLK </h2>

<?php
for ($row1 = 1; $row1 <= 10; $row1++) {
    $TANGGAL = $ws->getCell("E" . $row1)->getValue();
    // echo $TANGGAL;
    if ($TANGGAL == "Tanggal") {
        $tanggal = $ws->getCell("G" . $row1)->getValue();
    }
}
$tanggal =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tanggal));;
$hari = strtolower(explode(",", format_hari_tanggal($tanggal))[0]);


for ($row = 1; $row <= $last_row; $row++) {
    $kode_pemb =  $ws->getCell("C" . $row)->getValue();


    if ($kode_pemb == null) {
    } else {
        $agt = (substr($kode_pemb, 0, 3));
        // echo $agt;
        $ket1 = "";

        $kode_pemb = ganti_karakter($kode_pemb);
        if ($kode_pemb == 'PU' || $kode_pemb == 'PMB' || $kode_pemb == 'PSA' || $kode_pemb == 'PRR' || $kode_pemb == 'PPD') {
            $id_nasabah =  ganti_karakter($ws->getCell("A" . $row)->getValue());
            if ($id_nasabah != null) {
                $nasabah =  ganti_karakter($ws->getCell("B" . $row)->getValue());
                $pensiun =  (int)ganti_karakter(str_replace(",", "", $ws->getCell("S" . $row)->getValue()));
                $sukarela = (int)ganti_karakter(str_replace(",", "", $ws->getCell("P" . $row)->getValue()));
                $wajib = (int)ganti_karakter(str_replace(",", "", $ws->getCell("M" . $row)->getValue()));
            } else {


                $baris_baru = $row - 1;
                $nasabah =  ganti_karakter($ws->getCell("B" . $baris_baru)->getValue());
                // $pensiun =  (int)ganti_karakter(str_replace(",","",$ws->getCell("S".$baris_baru)->getValue()));
                $id_nasabah =  ganti_karakter($ws->getCell("A" . $baris_baru)->getValue());
                // $sukarela = (int)ganti_karakter(str_replace(",","",$ws->getCell("P".$baris_baru)->getValue()));
                // $wajib = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$baris_baru)->getValue()));
                if ($nasabah == null || $nasabah == " ") {
                    $baris_baru = $row - 1;
                    $id_nasabah =  ganti_karakter($ws->getCell("A" . $baris_baru)->getValue());
                }
            }

            $ID = sprintf("%0d", $id_nasabah);

            $pokok =    (int)ganti_karakter(str_replace(",", "", $ws->getCell("I" . $row)->getValue()));
            $margin =   (int)ganti_karakter(str_replace(",", "", $ws->getCell("J" . $row)->getValue()));
            $amount =   (int)ganti_karakter(str_replace(",", "", $ws->getCell("F" . $row)->getValue()));
            $os =       (int)ganti_karakter(str_replace(",", "", $ws->getCell("G" . $row)->getValue()));
            $ke =       (int)ganti_karakter(str_replace(",", "", $ws->getCell("D" . $row)->getValue()));
            $rill =     (int)ganti_karakter(str_replace(",", "", $ws->getCell("E" . $row)->getValue()));

            $wajib_minggu = 0;
            if ($kode_pemb == 'PU' || $kode_pemb == 'PMB') {
                $wajib_minggu = $amount / 1000;
                if (is_float($wajib_minggu)) {
                    $pecah = explode(".", $wajib_minggu);
                    $awal = $pecah[0];
                    $wajib_minggu = ($awal + 1) * 1000;
                } else {
                    $wajib_minggu = $wajib_minggu * 1000;
                }
            }


            // $tgl = $ws->getCell("I".$row)->getValue();
            // $tgl =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl));
            $q = mysqli_query($con, "SELECT a.`status_center`,b.`nama_karyawan`,b.id_karyawan,a.`no_center`
            , YEAR(CURDATE()) - YEAR(c.`tgl_bergabung`) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(c.`tgl_bergabung`, '%m%d')) AS lama
            FROM center a 
            JOIN karyawan b ON b.id_karyawan=a.id_karyawan
            JOIN daftar_nasabah c ON c.no_center=a.`no_center` 
            WHERE c.`id_nasabah`='$ID' AND a.`id_cabang`='$id_cabang'");
            $nama = mysqli_fetch_assoc($q);
            $warna = "";
            $cicilan = $pokok + $margin + $wajib_minggu;
            $selisih = $ke - $rill;
            $ket = '';
            $satu_angsuran = 0;
            $warna_baris = "";
            $pensiun_tiga = 0;
            $tanpa_margin = 0;
            if ($selisih == 0) {
                // echo 'double 1';
            } elseif ($selisih > 1) {
                if ($selisih > 1 && $selisih < 4) {

                    $ket =  $selisih - 1 . " tunggakan";
                    if ($nama['status_center'] == 'hijau') {
                        $warna_baris = "#79ff54";
                        $warna = "hijau";
                    } elseif ($nama['status_center'] == 'kuning') {
                        $warna_baris = "yellow";
                        $warna = "kuning";
                    }

                    if ($nama['lama'] >= 3) {
                        if ($pensiun < $pensiun_tiga + 10000) {
                            $pensiun_tiga  = 0;
                        } else {
                            $pensiun_tiga  = ($amount * 1 / 100) * 1000;
                        }

                        $satu_angsuran = (($sukarela - 2000) + ($pensiun - $pensiun_tiga)) - $cicilan;
                    } else {
                        $satu_angsuran = ($sukarela - 2000) - $cicilan;
                    }
                    $tanpa_margin = $os - (($wajib - 2000) + ($pensiun - 2000) + ($sukarela - 2000));
                    // $satu_angsuran = ($sukarela - 2000) -$cicilan;
                } else {
                    $ket = 'par ' . ($selisih - 1);
                    $tanpa_margin = $os - (($wajib - 2000) + ($pensiun - 2000) + ($sukarela - 2000));
                }
            } elseif ($selisih < 0) {
                // $ket = "double ".$selisih;
            }
?>
            <?php $center = $nama['no_center'] ?>


<?php
            $id_nasabah = sprintf("%0d", $id_nasabah);
            if ($ket) {
                $par = 'ya';
            } else $par = 'tidak';
            $cek_id = mysqli_num_rows(mysqli_query($con, "select id from blk where id_anggota='$id_nasabah' and id_cabang='$id_cabang' and hari='$hari' and tanggal_blk='$tanggal' "));
            if ($cek_id < 1) {
                $ins = "INSERT INTO blk(id_anggota,center,nama_nasabah,ke,rill,amount,outstanding,angsuran,wajib,sukarela,pensiun,par,id_karyawan,id_cabang,hari,tanggal_blk) 
                VALUES ('$id_nasabah','$center','$nasabah','$ke','$rill','$amount','$os','$cicilan','$wajib','$sukarela','$pensiun','$par','$nama[id_karyawan]','$id_cabang','$hari','$tanggal');
                ";
                mysqli_query($con, $ins);
            }
        }
    }
}
pindah($url . $menu . "anal_bayar&tgl=" . $tanggal);
?>