<?php
$q = mysqli_query($con, "select * from pengembalian");
while ($r = mysqli_fetch_assoc($q)) {
    $hari = format_hari_tanggal($r['tgl_pengembalian']);
    $hari = strtolower(explode(",", $hari)[0]);
    mysqli_query($con, "UPDATE pengembalian set hari_pengembalian='$hari' where id='$r[id]'");
}
