<?php 
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$cari = aman($con,$_GET['cari']);
$id_cabang = aman($con,$_GET['id']);
$kategori = aman($con,$_GET['kategori']);
$berdasarkan = aman($con,$_GET['berdasarkan']);
if($cari!=null){
if($kategori=='aktif') $table='daftar_nasabah';
else $table='daftar_nasabah_mantan';

// echo $berdasarkan.$cari;
?>
<table class='table'>
    <thead>
        <tr>
            <th>NO</th>
            <th>ID</th>
            <th>ID Detail</th>
            <th>NAMA</th>
            <th>SUAMI</th>
            <th>KTP</th>
            <th>TGL BERGABUNG</th>
            <?php 
            if($kategori=='mantan') {
                ?>
            <th>TGL KELUAR</th>
            <th>ALASAN KELUAR</th>
                <?php
            }
            
            ?>
            <th>HARI</th>
            <th>STAFF</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = mysqli_query($con, "SELECT * from $table left JOIN   karyawan on karyawan.id_karyawan=$table.id_karyawan where 
        $berdasarkan like '%$cari%'  
        
         and $table.id_cabang='$id_cabang' group by id_detail_nasabah order by nama_nasabah asc limit 0,100
        ");
        echo mysqli_error($con);
        while ($dup = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$dup['id_nasabah']?></td>
                <td><?=$dup['id_detail_nasabah']?></td>
                <td><?=$dup['nama_nasabah']?></td>
                <td><?=$dup['suami_nasabah']?></td>
                <td><?=$dup['no_ktp']?></tdd>
                <td><?=$dup['tgl_bergabung']?></td>
                <?php 
                if($kategori=='mantan') {
                    $text ="select * from temp_anggota_keluar d where d.id_cabang='$id_cabang' and d.id_nasabah='$dup[id_nasabah]'";
                    $query1  = mysqli_query($con,$text);
                    $cek  = mysqli_fetch_array($query1);
                    ?>
                <td><?=$cek['tgl_keluar']?></td>
                <td><?=$cek['alasan']?></td>
                    <?php
                }
                
                ?>
                <td><?=$dup['hari']?></td>
                <td><?=($dup['nama_karyawan']==null?$dup['staff']:$dup['nama_karyawan'])?></td>
            </tr>
        <?php

        }
        ?>


    </tbody>
</table>
<?php 
}
?>