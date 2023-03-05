<?php
$token_fcm = "d1fLll2zRq6Ff1v95Dr0I2:APA91bF-7-sfirT8e6gCIXwM8sVj85B34kjnNVb-jT1cqRtvgDcSJS2izg0tEWZd9C8rFuVcD7M7dS23WvgRk362UkpLN7FC6r39D2vcIvo4k1pPAv1lkzbQ9lrb4iuF9AA-iT9iH392";
require_once "../../config/seting.php";
require_once "../../config/koneksi.php";
require_once("../../proses/fungsi.php");
require_once("../../model/model.php");
$id_karyawan=1;
send_notif($token_fcm,"HAI2","ISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIFISI PUSH NOTIF",$id_karyawan,"admin");
?>
