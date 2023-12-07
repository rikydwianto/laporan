<?php 
$q = "SELECT * FROM pinjaman  WHERE pinjaman.id_cabang='$id_cabang'AND input_mtr='sudah' AND `monitoring`='belum' ";
$q= mysqli_query($con,$q);
while($r = mysqli_fetch_array($q)){
    $no_center = trim(explode(" -",$r['center'])[0]);
    $id_karyawan_mtr = $r['id_karyawan'];
    $cari_center = "select * from center where no_center ='$no_center' and id_cabang='$id_cabang'";
    $q_center = mysqli_fetch_array(mysqli_query($con,$cari_center));
    $id_karyawan_center= $q_center['id_karyawan'];
    $id_pin = $r['id_pinjaman'];
    // echo "$no_center | ". $id_karyawan_center." = ". $id_karyawan_mtr." | ". (($id_karyawan_center == $id_karyawan_mtr) ? "sama" : "tidak sama") ."<br>";
    $q_update = "update pinjaman set id_karyawan='$id_karyawan_center' where id_pinjaman='$id_pin'";
    $udpate = mysqli_query($con,$q_update);

}
if($udpate){
    echo "berhasil diupdate";
    pindah($url.$menu."monitoring");
}
else echo "gagal";