<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$kode = '400';
@$username  = aman($con, $_POST['uname']);
@$password  = aman($con, $_POST['pw']);
@$token  = aman($con, $_POST['token']);
@$token_fcm  = aman($con, $_POST['token_fcm']);

$data = null;
$text = null;
if ($username == "" && $password == "") {

    // $detail  = aman($con,$_GET['detail']);
    $pesan = "Usename atau Password Tidak boleh kosong";
    $kode = '401';
} else {
    if ($token == "") {
        $kode = "402";
        $pesan = "TOKEN KOSONG!";
    } else {

        if ($token == $TOKEN) {

            $user = $username;
            $pass = md5($password);
            $q = mysqli_query($con, "select * from karyawan where nik_karyawan='$user'  ");
            if (mysqli_num_rows($q)) {
                $cek = mysqli_fetch_array($q);
                if ($cek['status_karyawan'] == 'aktif') {
                    if ($cek['password'] == $pass) {
                        $d = detail_karyawan($con, $cek['id_karyawan']);
                        $status_cabang = $d['status_cabang'];
                        $su = $d['super_user'];
                        if ($su != 'y') {
                            if ($status_cabang == 'nonaktif') {
                                $text = "$d[nama_cabang] dilock!";
                            } else {
                                $text = "Login Berhasil sebagai : $cek[nama_karyawan] cabang : $d[nama_cabang]";
                                mysqli_query($con, "update karyawan set token_fcm='$token_fcm' where id_karyawan='$d[id_karyawan]'");
                                $data = array("id" => $d['id_karyawan'], 'idc' => $d['id_cabang'], 'jabatan' => $d['singkatan_jabatan']);
                                $kode = '202';
                            }
                        } else {
                            $text = "Login Berhasil sebagai : $cek[nama_karyawan] cabang : $d[nama_cabang]";
                            mysqli_query($con, "update karyawan set token_fcm='$token_fcm' where id_karyawan='$d[id_karyawan]'");
                            $data = array("id" => $d['id_karyawan'], 'idc' => $d['id_cabang'], 'jabatan' => $d['singkatan_jabatan']);
                            $kode = '202';
                        }
                    } else {
                        $text = "Password yang anda masukan tidak sesuai";
                    }
                } else {
                    $text = "User Sudah tidak aktif ";
                }
            } else {
                $text = "Username tidak ditemukan!";
            }
            $pesan = $text;
        } else {
            $pesan = "TOKEN TIDAK SESUAI!";
        }
    }
}

echo json_encode(array("kode" => $kode, "pesan" => "$pesan", "data" => $data));