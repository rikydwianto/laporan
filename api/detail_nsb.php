<?php 
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_nasabah = aman($con,$_GET['id_nsb']);
$id_cabang = aman($con,$_GET['cab']);
$table="daftar_nasabah";
$query = mysqli_query($con, "SELECT * from $table left JOIN   karyawan on karyawan.id_karyawan=$table.id_karyawan where 
$table.id_nasabah = '$id_nasabah'  

 and $table.id_cabang='$id_cabang' 
");
if(!mysqli_num_rows($query)){
    ?>
    <h2>TIDAK DITEMUKAN</h2>
    <?php

}
else{
    $cek = mysqli_fetch_array($query);
    ?>
<table border=1>
    <tr>
        <td>Nama</td>
        <td><?=$cek['nama_nasabah']?></td>
    </tr>
    <tr>
        <td>ID</td>
        <td><?=$cek['id_detail_nasabah']?></td>
    </tr>
    <tr>
        <td>CTR/KLP</td>
        <td><?=$cek['no_center']?>/<?=$cek['kelompok']?></td>
    </tr>
    <tr>
        <td>STAFF</td>
        <td><?=$cek['nama_karyawan']?></td>
    </tr>
    <tr>
        <td>HARI</td>
        <td><?=$cek['hari']?></td>
    </tr>
</table>
<?php   
}