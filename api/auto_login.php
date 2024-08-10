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
@$token  = aman($con, $_GET['token']);

@$id  = aman($con, $_GET['id']);
$text = null;
if ($id == "") {
    $pesan = "ID KOSONG";
} else {
    if ($token == "") {
        $kode = "402";
        $pesan = "TOKEN KOSONG!";
    } else {

        if ($token == $TOKEN) {

            $q = mysqli_query($con, "select * from karyawan where id_karyawan='$id'  ");
            if (mysqli_num_rows($q)) {
                $cek = mysqli_fetch_array($q);
                if ($cek['status_karyawan'] == 'aktif') {
                    $_SESSION['id'] = $cek['id_karyawan'];
                    $_SESSION['nama_karyawan'] = $cek['nama_karyawan'];
                    $_SESSION['id_cabang'] = $cek['id_cabang'];
                    $_SESSION['cabang'] = $cek['id_cabang'];
                    $_SESSION['su'] = $cek['super_user'];
                    $_SESSION['informasi'] = 1;
                    $menu_asal = $_GET['url'];
                    $menu_asal1 = explode("=", $menu_asal)[1];
                    // echo $menu_asal;
                    $d = detail_karyawan($con, $cek['id_karyawan']);
                    if (isset($_GET['menu']) && $_GET['menu'] == 'monitoring') {
                        pindah("$url" . "app");
                        $text = "login ke menu monitoring @user  : $user  $cek[nama_karyawan] cabang : $d[nama_cabang]";
                    } else {
                        pindah("$url");
                        $text = "login @user  : $user  $cek[nama_karyawan] cabang : $d[nama_cabang]";
                    }
                } else {
                    $text = "@user $user tidak aktif mencoba login ";
                    pesan("STATUS ANDA DINONAKTIKAN, SILAHKAN HUBUNGI ATASAN ANDA", 'danger');
                }
            } else {
                pesan("USER/NIK TIDAK DITEMUKAN", 'danger');
                $text = "Percobaan login @user $user tidak ditemukan";
            }

            $url = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&reply_message_id=214&force_reply=true";
            file_get_contents($url);
        }
    }
}