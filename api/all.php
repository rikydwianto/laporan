<?php
// header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$kode = '400';
$TOKEN_TELE = $token;
@$token  = aman($con, $_POST['token']);

@$id  = aman($con, $_POST['id']);
@$id_cabang  = aman($con, $_POST['id_cabang']);
@$menu  = aman($con, $_POST['menu']);
@$token_fcm  = aman($con, $_POST['token_fcm']);
$data = null;
$text = null;
if ($id == "") {
    $pesan = "ID KOSONG";
} else {
    if ($token == "") {
        $kode = "402";
        $pesan = "TOKEN KOSONG!";
    } else {

        if ($token == $TOKEN) {

            if ($menu == "detail_login") {
                $kode = "200";
                $pesan = "Detail";
                $query = mysqli_query($con, "SELECT cabang.*,kode_cabang,nama_karyawan,nama_jabatan,nik_karyawan,nama_cabang,singkatan_jabatan,singkatan_cabang,status_karyawan FROM karyawan,jabatan,cabang,wilayah where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and cabang.id_wilayah=wilayah.id_wilayah and karyawan.id_karyawan='$id' ");
                $data = mysqli_fetch_assoc($query);


                $url_p = "https://images.soco.id/589-5-fakta-menarik-film-avatar-yang-kembali-tayang-5.jpg.jpg";
                $data['photo'] = "$url_p";

                $pesan_tele = "Login android sebagai : $data[nama_karyawan] \ncabang $data[nama_cabang]\njabatan : $data[singkatan_jabatan]";
                $url_tele = "https://api.telegram.org/$TOKEN_TELE/sendMessage?parse_mode=html&chat_id=1185334687&text=$pesan_tele&force_reply=true";
                file_get_contents($url_tele);
            } else if ($menu == 'update_fcm_token') {
                mysqli_query($con, "update karyawan set token_fcm='$token_fcm' where id_karyawan='$id'");
            } else if ($menu == "cari_nasabah") {
                $table = "daftar_nasabah";
                $cari = aman($con, $_POST['cari']);
                if ($cari == null || $cari == "") {
                    $kode = "200";
                    $pesan = "tidak ditemukan";
                    $data = "[]";
                } else {
                    $kode = "200";
                    $pesan = "ditemukan";
                    @$berdasarkan  = aman($con, $_POST['kategori']);

                    $q = "SELECT $table.nama_nasabah,$table.suami_nasabah,$table.no_center,$table.kelompok,$table.id,$table.id_detail_nasabah,$table.no_ktp,$table.hp_nasabah from $table  where $berdasarkan like '$cari%'and $table.id_cabang='$id_cabang' group by id_detail_nasabah order by nama_nasabah asc limit 0,50";
                    $query = mysqli_query($con, "$q");
                    $array = array();
                    while ($data = mysqli_fetch_assoc($query)) {
                        $array[] = $data;
                    }


                    $data = $array;
                }
            } else if ($menu == "statistik") {
                $kode = "200";
                $pesan = "Statistik Cabbang";
                //INFORMASI PAR
                $q = "SELECT COUNT(*) AS total_par,tgl_input, SUM(`sisa_saldo`) + 0 AS total_balance  FROM `deliquency` WHERE id_cabang=$id_cabang AND tgl_input=(SELECT MAX(`tgl_input`) FROM deliquency WHERE id_cabang=$id_cabang)";
                $q = mysqli_fetch_assoc((mysqli_query($con, $q)));
                //MEMBER
                $q1 = mysqli_query($con, "SELECT COUNT(id) AS member FROM daftar_nasabah WHERE id_cabang='$id_cabang' GROUP BY id_cabang");
                $hit1 = mysqli_fetch_array($q1);

                //INFORMASI CENTER
                $q2 = mysqli_query($con, "select count(no_center) as center from center where id_cabang=$id_cabang");
                $hit2 = mysqli_fetch_array($q2);

                $qpin = mysqli_query($con, "SELECT id_karyawan,COUNT(*) as total
                    FROM pinjaman WHERE monitoring='belum' and id_cabang='$id_cabang' and input_mtr='sudah' GROUP BY id_cabang ");
                $mon = mysqli_fetch_array($qpin);
                $mon1 = $mon['total'];


                //AM AK DAN NETT
                $anggota = "SELECT sum(anggota.anggota_masuk) as masuk,sum(anggota.anggota_keluar) as keluar,sum(anggota.net_anggota) as nett,sum(anggota.psa) as psa,sum(anggota.ppd) as ppd,sum(anggota.prr) as prr,sum(anggota.arta) as arta,sum(anggota.pmb) as pmb,karyawan.nama_karyawan FROM `anggota`,karyawan where anggota.id_karyawan=karyawan.id_karyawan and karyawan.id_cabang=$id_cabang and anggota.tgl_anggota >= '" . date("Y-m-01") . "' and anggota.tgl_anggota <= '" . date("Y-m-31") . "' ";

                $stak = mysqli_query($con, $anggota);
                $stak = mysqli_fetch_assoc($stak);


                $urut = mysqli_fetch_array(mysqli_query($con, "select max(no_urut) as no_urut from surat where id_cabang='$id_cabang' AND YEAR(tgl_surat) = YEAR(curdate()) "));
                $urut = ($urut['no_urut'] == NULL ? 0 : $urut['no_urut']);

                //NOTIFIKASI
                $qnot = mysqli_query($con, "SELECT * from notifikasi where id_karyawan='$id' and dibaca='belum' order by waktu desc ");
                if (mysqli_num_rows($qnot) > 0) {
                    $notif_status = "ada";
                } else {
                    $notif_status = 'tidakada';
                }

                $notif = mysqli_fetch_array($qnot);
                $total_notif = mysqli_num_rows($qnot);

                $data = $q;
                $data['total_member'] = $hit1['member'];
                $data['total_center'] = $hit2['center'];
                $data['total_monitoring'] = sprintf("%0d", $mon1);
                $data['total_am'] = $stak['masuk'] + 0;
                $data['total_ak'] = $stak['keluar'] + 0;
                $data['total_nett'] = $stak['nett'] + 0;
                $data['surat_terakhir'] = sprintf("%03d", $urut);
                $data['surat_sekarang'] = sprintf("%03d", $urut + 1);

                $data['status_notif'] = $notif_status;
                $data['judul_notif'] = $notif['judul'];
                $data['isi_notif'] = $notif['isi_notifikasi'];
                $data['total_notif'] = $total_notif;
            } else if ($menu == "detail_nasabah") {
                $pesan = "detail nasabah";
                $kode = 200;
                $table = "daftar_nasabah";
                $id_nsb = aman($con, $_POST['id_nsb']);
                $id_detail = aman($con, $_POST['id_detail_nasabah']);
                if ($id_detail != "kosong") {
                    // $id_detail = explode("-",$id_detail)[1]+0;
                    $qcek_id = "select id from $table where id_detail_nasabah='$id_detail' and id_cabang='$id_cabang'";
                    $cek_id = mysqli_query($con, $qcek_id);
                    $cek_id = mysqli_fetch_assoc($cek_id)['id'];
                    $id_nsb = $cek_id;
                }
                $pesan = "Detail Nasabah";

                $q = "SELECT * from $table  where id = '$id_nsb' and id_cabang='$id_cabang' ";
                $query = mysqli_query($con, "$q");
                if (mysqli_num_rows($query)) {
                    $status_ada = "ada";
                } else {
                    $status_ada = "tidak ada";
                }
                $array = array();
                $data = mysqli_fetch_assoc($query);
                //https://cdn1.iconfinder.com/data/icons/user-pictures/100/female1-512.png
                $url_photo = "https://cdn1.iconfinder.com/data/icons/user-pictures/100/female1-512.png";
                $array = $data;

                //PAR

                $qpar = "SELECT * FROM `deliquency` WHERE id_cabang='$id_cabang' AND tgl_input=(SELECT MAX(`tgl_input`) FROM deliquency WHERE id_cabang='$id_cabang') and id_detail_nasabah='$data[id_detail_nasabah]'";
                $qpar = mysqli_query($con, $qpar);
                $par = mysqli_fetch_assoc($qpar);
                $tgl_update_par = $par['tgl_input'];

                if (mysqli_num_rows($qpar) > 0) {
                    $status_par = "ya";
                } else {
                    $status_par = "tidak";
                }
                $qlihatpar = mysqli_query($con, "select * from deliquency where id_cabang='$id_cabang' and id_detail_nasabah='$data[id_detail_nasabah]' and tgl_input='$tgl_update_par'");
                while ($data_par = mysqli_fetch_array($qlihatpar)) {
                    $pemb = $data_par['loan'];
                    $pemb = explode("-", $pemb)[0];
                    $cair = rupiah($data_par['amount']);
                    $saldo = rupiah($data_par['sisa_saldo']);
                    $cicilan = rupiah($data_par['cicilan']);
                    $isi_par .= "Pemb : $pemb\n  - Cair : $cair | Balance : $saldo\n  - Angsuran : $cicilan\n\n";
                }
                $detail_par = $isi_par;


                //STATUS TPK


                $qtab = "select * from detail_simpanan where id_cabang='$id_cabang' and id_nasabah='$data[id_nasabah]' AND `update_simpanan`=(SELECT MAX(`update_simpanan`) FROM detail_simpanan WHERE id_cabang=$id_cabang)";
                $cek_detail = mysqli_query($con, $qtab);

                if (mysqli_num_rows($cek_detail)) {
                    $status_tabungan = "ada";
                } else {
                    $status_tabungan = "tidakada";
                }

                $simpanan = mysqli_fetch_assoc($cek_detail);
                $data = $array;
                $tgl_simp = $simpanan['update_simpanan'];
                $simpanan = json_decode($simpanan['detail_simpanan'], true);
                $data['tgl_update_par'] = $tgl_update_par;
                $data['detail_par'] = $detail_par;

                $data['photo_nasabah'] = $url_photo;
                $data['text'] = $text;
                $data['status_par'] = $status_par;
                $data['update_simpanan'] = format_hari_tanggal($tgl_simp);
                $data['status_tabungan'] = $status_tabungan;
                $data['wajib'] = $simpanan['wajib'] + 0;
                $data['sukarela'] = $simpanan['sukarela'] + 0;
                $data['pensiun'] = $simpanan['pensiun'] + 0;
                $data['hariraya'] = $simpanan['hari_raya'] + 0;
                $data['total'] = $simpanan['wajib'] + $simpanan['sukarela'] + $simpanan['pensiun'] + $simpanan['hari_raya'];
                $data['ada'] = $status_ada;
            } else if ($menu == "tampil_monitoring") {
                $cari = aman($con, $_POST['cari']);
                $berdasarkan = aman($con, $_POST['berdasarkan']);
                $filter_kosong = "";
                if ($cari == "") {
                    $filter_kosong = "";
                } else {
                    $filter_kosong = "and p.$berdasarkan like '%$cari%'";
                }
                $qpin = mysqli_query($con, "SELECT * FROM pinjaman p 
                join karyawan k on k.id_karyawan=p.id_karyawan 
                WHERE monitoring='belum' and p.id_cabang='$id_cabang' and input_mtr='sudah' $filter_kosong order by p.nama_nasabah asc");
                $array = array();
                while ($data = mysqli_fetch_assoc($qpin)) {
                    $array[] = $data;
                }

                $data = $array;

                //LIST MONITORING

            } else if ($menu == "monitoring") {
                $kode = "200";
                $pesan = "iinput mtr";
                $id  = aman($con, $_POST['id_pin']);
                $mtr  = aman($con, $_POST['mtr']);
                $detail  = aman($con, $_POST['detail']);

                $edit  = mysqli_query($con, "update pinjaman set monitoring='$mtr' where id_pinjaman='$id'");
                $keluan  = mysqli_query($con, "update banding_monitoring set status='selesai' where id_detail_pinjaman='$detail'");
                if ($mtr == 'sudah') {
                    $input = mysqli_query($con, "INSERT INTO `monitoring` (`id_pinjaman`,`id_detail_pinjaman`, `tgl_monitoring`,waktu) VALUES ('$id','$detail', curdate(),current_time()); ");
                    $text = "$id_cabang sedang input monitoring pake android ";
                    $url_tele = "https://api.telegram.org/$TOKEN_TELE/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&force_reply=true";
                    file_get_contents($url_tele);
                } else {
                    $input = mysqli_query($con, "DELETE FROM `monitoring` WHERE `id_pinjaman` = '$id'; ");
                }
            } else if ($menu == "detail_pinjaman") {
                $pesan = "berhasil";
                $id_pinjaman = aman($con, $_POST['id_pinjaman']);
                $id_nasabah = aman($con, $_POST['id_nasabah']);
                $qpin = mysqli_query($con, "SELECT * from pinjaman where id_cabang='$id_cabang' and id_detail_nasabah='$id_nasabah' and id_detail_pinjaman='$id_pinjaman'");
                $hasil = mysqli_fetch_assoc($qpin);


                $ctr = explode(" -", $hasil['center'])[0];


                $qstaf = mysqli_query($con, "SELECT * from center ctr join karyawan k on k.id_karyawan=ctr.id_karyawan where ctr.no_center='$ctr' and ctr.id_cabang='$id_cabang'");
                $staff = mysqli_fetch_assoc($qstaf);
                $staff = $staff['nama_karyawan'];

                $qtpk = mysqli_query($con, "select * from tpk where id_cabang='$id_cabang' and id_detail_nasabah='$id_nasabah'");
                if (mysqli_num_rows($qtpk)) {
                    $tpk = "TPK";
                } else {
                    $tpk = "BUKAN TPK";
                }
                $data = $hasil;



                $data['no_center'] = sprintf("%03d", $ctr);
                $data['no_kelompok'] = sprintf("%03d", $hasil['kelompok']);
                $data['status_tpk'] = $tpk;
                $data['nama_karyawan'] = $staff;
            } else if ($menu == "notif") {
                $q = mysqli_query($con, "select * from notifikasi where id_karyawan='$id' order by waktu desc");
                $array = array();
                while ($r = mysqli_fetch_assoc($q)) {
                    $array[] = $r;
                }
                $data = $array;
            } else if ($menu == 'notif_baca') {
                $id_notif = aman($con, $_POST['id_notif']);
                mysqli_query($con, "update notifikasi set dibaca='dibaca' where id='$id_notif'");
            } else if ($menu == 'bersihkan_notif') {
                $id_notif = aman($con, $_POST['id_notif']);
                mysqli_query($con, "delete from notifikasi where id_karyawan='$id' ");
            } else if ($menu == 'cek_no_surat') {

                $kategori = aman($con, $_POST['kategori']);
                $tgl = aman($con, $_POST['tgl_surat']);
                if (!empty($kategori)) {
                    $kategori = strtoupper("$kategori/");
                } else {
                    $kategori = "";
                }


                $query = mysqli_query($con, "SELECT singkatan_cabang,kode_cabang FROM karyawan,jabatan,cabang,wilayah where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and cabang.id_wilayah=wilayah.id_wilayah and karyawan.id_karyawan='$id' ");
                $data = mysqli_fetch_assoc($query);
                $singkatan_cabang = $data['singkatan_cabang'];
                $kode_cabang = "KMD";
                $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', ''];
                $urut = mysqli_fetch_array(mysqli_query($con, "select max(no_urut) as no_urut from surat where id_cabang='$id_cabang' AND YEAR(tgl_surat) = YEAR('$tgl') "));
                $urut = ($urut['no_urut'] == NULL ? 0 : $urut['no_urut']);

                $mon = date('n', strtotime($tgl));
                $bulan = $mon;

                $no_surat = sprintf("%03d", $urut + 1) . "/$kode_cabang-$singkatan_cabang/$kategori" . $romawi[$bulan] . "/" . date("Y", strtotime($tgl));
                $data['no_surat_lengkap'] = $no_surat;
                $data['no_surat'] = sprintf("%03d", $urut + 1);
                $data['tgl_surat'] = $tgl;
            } else if ($menu == 'tambah_surat_keluar') {

                $no_urut = aman($con, $_POST['no_urut']);
                $no_surat = aman($con, $_POST['no_surat']);
                $tgl_surat = aman($con, $_POST['tgl_surat']);
                $perihal = aman($con, $_POST['perihal']);
                $kategori = aman($con, $_POST['kategori']);
                $tmb = mysqli_query($con, "INSERT INTO `surat` (`no_urut`, `no_surat`, `perihal_surat`, `kategori_surat`, `tgl_surat`, `isi_surat`, `type_surat`, `id_cabang`, `id_karyawan`)
                VALUES ('$no_urut', '$no_surat', '$perihal', '$kategori', '$tgl_surat', '', 'keluar', '$id_cabang', '$id');");
                if ($tmb) {
                    $pesan = "berhasil";
                } else {
                    $pesan = "gagal input";
                }
            } else if ($menu == "list_surat_keluar") {
                $pesan = "list surat keluar";
                $qsa = mysqli_query($con, "select s.*,k.nama_karyawan from surat s join karyawan k on s.id_karyawan=k.id_karyawan where s.id_cabang='$id_cabang' order by s.id_surat desc limit 0,30");
                $array = array();
                while ($r = mysqli_fetch_assoc($qsa)) {
                    $array[] = $r;
                }
                $data = $array;
            } else if ($menu == 'hapus_surat') {
                $id_surat = aman($con, $_POST['id_surat']);
                mysqli_query($con, "delete from surat where id_surat='$id_surat' and id_cabang='$id_cabang'");
            } else if ($menu == 'list_center') {
                $no_center = aman($con, $_POST['no_center']);
                if ($no_center != "") {
                    $filter_center = " and no_center='$no_center'";
                } else {
                    $filter_center = "";
                }
                $pesan = "List Center ";
                $q = mysqli_query($con, "select * from center where id_cabang='$id_cabang' $filter_center order by no_center asc ");
                $array = array();
                while ($r = mysqli_fetch_assoc($q)) {
                    $array[] = $r;
                }
                $data = $array;
            } else if ($menu == "lokasi_center") {
                $a = "SELECT
                c.*,
                k.nama_karyawan,
                cb.nama_cabang
              FROM
                center c
                JOIN karyawan k
                  ON c.id_karyawan = k.`id_karyawan`
                JOIN cabang cb
                  ON cb.id_cabang = c.id_cabang
              WHERE c.latitude IS NOT NULL
                AND c.latitude <> ''
                AND c.latitude <> 'null' AND c.`id_cabang`=$id_cabang
                GROUP BY c.`id_cabang`,c.`no_center` ,c.`latitude`";
                $q = mysqli_query($con, $a);
                $array = array();
                while ($r = mysqli_fetch_assoc($q)) {
                    $array[] = $r;
                }
                $pesan = "Center";
                $data = $array;
            } else if ($menu == 'detail_center') {
                $pesan = "Detail center";
                $no_center = aman($con, $_POST['no_center']);
                $q = mysqli_query($con, "select * from center ctr join cabang cb on ctr.id_cabang=cb.id_cabang where ctr.id_cabang='$id_cabang' and ctr.no_center='$no_center' order by no_center asc ");
                $data = mysqli_fetch_assoc($q);
            } else if ($menu == 'set_lokasi') {
                $pesan = "Lokasi";
                $no_center = aman($con, $_POST['no_center']);
                $lat = aman($con, $_POST['lat']);
                $long = aman($con, $_POST['long']);
                $q = mysqli_query($con, "update center set latitude='$lat',longitude='$long' where no_center='$no_center' and id_cabang='$id_cabang'");
                if ($q) {
                    $pesan = "Lokasi Berhasil diubah";
                } else {
                    $pesan = "gagal diubah";
                }
            } else if ($menu == "all_staff") {
                $cek = mysqli_query($con, "SELECT k.`id_karyawan`,k.`nik_karyawan`,k.`nama_karyawan`,j.* FROM karyawan k JOIN jabatan j ON k.`id_jabatan`=j.`id_jabatan`  WHERE id_cabang='$id_cabang' AND k.`status_karyawan`='aktif' ORDER BY nama_karyawan ASC");
                $array = array();
                while ($data = mysqli_fetch_assoc($cek)) {
                    $array[] = $data;
                }


                $data = $array;
            } else if ($menu == 'angsuran') {
                $cair = aman($con, $_POST['cair']);
                $produk = array(
                    'UMUM' => array(
                        'max' => '12000000',
                        'min' => '100000',
                        'jk' => array(
                            25 => 12,
                            50 => 24,
                            75 => 36,
                            100 => 48,
                        ),
                    ),
                    'PMB' => array(
                        'max' => '25000000',
                        'min' => '5000000',
                        'jk' => array(
                            25 => 12,
                            50 => 24,
                            75 => 36,
                            100 => 48,
                        ),
                    ),
                    'DTP' => array(
                        'max' => '10000000',
                        'min' => '500000',
                        'jk' => array(
                            50 => 12,
                            75 => 18,
                            100 => 24,
                            125 => 30,
                            150 => 36,
                        ),
                    ),
                    'PSA' => array(
                        'max' => '7000000',
                        'min' => '1000000',
                        'jk' => array(
                            26 => 11,
                            51 => 22,
                            76 => 33,
                            101 => 44,
                        ),
                    ),
                );
                $hasil_angsuran = array();
                $data_angsuran = [];
                foreach ($produk as $pr => $data) {

                    $max = $data['max'];
                    if ($max >= $cair) {
                        foreach ($data['jk'] as $jk => $mrg) {

                            $total_margin =  $cair * ($mrg / 100);
                            $pokok_margin = $cair + $total_margin;
                            $angsuran = $pokok_margin / $jk;
                            $hasil_angsuran['pokok_margin'] = rupiah($pokok_margin);
                            $hasil_angsuran['total_margin'] = rupiah($total_margin);
                            $hasil_angsuran['produk'] = $pr;
                            $hasil_angsuran['pokok'] = rupiah($cair);
                            $hasil_angsuran['angsuran'] = rupiah($angsuran);
                            $hasil_angsuran['jk'] = $jk;
                            $hasil_angsuran['margin'] = $mrg;
                            array_push($data_angsuran, $hasil_angsuran);
                        }
                    } else {
                        $hasil_angsuran['pokok_margin'] =            "";
                        $hasil_angsuran['total_margin'] =            "";
                        $hasil_angsuran['produk'] =          "";
                        $hasil_angsuran['pokok'] =           "";
                        $hasil_angsuran['angsuran'] =            "";
                        $hasil_angsuran['jk'] =          "";
                        $hasil_angsuran['margin'] =          "";
                    }
                }

                $data = ($data_angsuran);
            } else {
                $kode = '404';
                $pesan = "Permintaan tidak jelas";
            }
        } else {
            $pesan = "TOKEN TIDAK SESUAI!";
        }
    }
}


echo json_encode(array("kode" => $kode, "pesan" => "$pesan", "data" => $data));