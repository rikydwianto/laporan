<?php 
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_cabang=$_GET['cab'];
$id_anggota = $_GET['id'];
$urut = $_GET['urut'];
$cek = mysqli_query($con,"select * from daftar_nasabah where id_cabang='$id_cabang' and id_nasabah='$id_anggota'");
$cek_par = mysqli_query($con,"select * from deliquency where id_cabang='$id_cabang' and substring_index(id_detail_nasabah,'-',-1) like '".sprintf("%06d",$id_anggota)."' AND tgl_input IN (SELECT MAX(tgl_input) FROM deliquency WHERE id_cabang='$id_cabang') order by id desc ");
if(mysqli_num_rows($cek)){
$ang = mysqli_fetch_array($cek);
    $q = mysqli_query($con,"select * from alasan_par where id_cabang='$id_cabang' and substring_index(id_detail_nasabah,'-',-1) like '".sprintf("%06d",$id_anggota)."'");
    if(mysqli_num_rows($q)){
        $r = mysqli_fetch_array($q);
        $alasan=$r['alasan'];
    }
    else $alasan='';

    $i=$urut;
    echo mysqli_error($con);
    if(mysqli_num_rows($cek_par)>1){
        echo 2;
    }
    else $par='';
    
}
else{
    
    echo "anggota tidak ditemukan";
}

if(isset($_GET['CEKPEM'])){
    ?>
    <select class="pemb[]" required onchange="ganti_pem(<?=$urut?>)" id="pemb-<?=$urut?>">
        <option value="">Pilih</option>
        <?php 

        while($pe=mysqli_fetch_array($cek_par)){
            $loan = explode("-",$pe['loan'])[0];
            ?>
            <option value="<?=$loan?>"><?=$loan?> <?=$pe['loan']?></option>
            <?php
        }
        ?>
    </select>
    <?php
}