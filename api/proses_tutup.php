<?php
// header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$TOKEN_TELE = $token;
$baca = file_get_contents('buka.txt');
if ($baca == 'belum') {
    $text = "IMPORTANT MESSAGE, SHE IS READ THE MESSAGE!!";
    $url = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&reply_message_id=214&force_reply=true";
    file_get_contents($url);
    file_put_contents("buka.txt", "sudah");
}