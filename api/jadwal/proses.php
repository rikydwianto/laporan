<?php
$token_fcm = "fUT08tOsSUy-ohmgJkOguq:APA91bETW3CrClK33zZXRnN453Od8Kklhw2HEz7n3SNYOaK_WMW2avLvp-TIw-BdpgjYj_3FjM0rMWtEGb4zVhY4-OsbwNrnsvwniI3lNlr5zQNtBDDfoYn3UlHb-1X4XIyhH_Ekky3a";
require_once "../../config/seting.php";
require_once "../../config/koneksi.php";
require_once("../../proses/fungsi.php");
require_once("../../model/model.php");
$id_karyawan=1;
send_notif($token_fcm,"HAI2","ISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIF",$id_karyawan,"admin");
?>
