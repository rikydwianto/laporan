<?php
//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

if (isset($_SESSION)) {
    if (isset($_GET['mtr']) && isset($_GET['mtr'])) {

        $id  = aman($con, $_GET['id']);
        $mtr  = aman($con, $_GET['mtr']);
        $detail  = aman($con, $_GET['detail']);

        $edit  = mysqli_query($con, "update pinjaman set monitoring='$mtr' where id_pinjaman='$id'");
        $keluan  = mysqli_query($con, "update banding_monitoring set status='selesai' where id_detail_pinjaman='$detail'");
        if ($mtr == 'sudah') {
            if (isset($_GET['newapp'])) $text = "New App monitoring : $_SESSION[nama_karyawan] sedang input monitoring $detail";
            else $text = "$_SESSION[nama_karyawan] sedang input monitoring";
            $input = mysqli_query($con, "INSERT INTO `monitoring` (`id_pinjaman`,`id_detail_pinjaman`, `tgl_monitoring`,waktu) VALUES ('$id','$detail', curdate(),current_time()); ");
            $url_tele = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&reply_message_id=214&force_reply=true";
            file_get_contents($url_tele);
        } else {
            $input = mysqli_query($con, "DELETE FROM `monitoring` WHERE `id_pinjaman` = '$id'; ");
        }



        echo mysqli_error($con);
        // echo json_encode($_GET);
        // exit;
        if ($edit) {
            echo "berhasil";
        } else {
            echo "gagal";
        }
    } else if (isset($_GET['refresh'])) {
    }
}