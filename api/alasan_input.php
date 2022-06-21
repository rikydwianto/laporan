<?php 
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_cabang=$_GET['cab'];
$id_anggota = $_GET['id'];
$urut = $_GET['urut'];
$cek = mysqli_query($con,"select * from daftar_nasabah where id_cabang='$id_cabang' and id_nasabah='$id_anggota'");
$cek_par = mysqli_query($con,"select * from deliquency where id_cabang='$id_cabang' and substring_index(id_detail_nasabah,'-',-1)='".sprintf("%06d",$id_anggota)."'");
if(mysqli_num_rows($cek)){
$ang = mysqli_fetch_array($cek);
    $q = mysqli_query($con,"select * from alasan_par where id_cabang='$id_cabang' and substring_index(id_detail_nasabah,'-',-1)='".sprintf("%06d",$id_anggota)."'");
    if(mysqli_num_rows($q)){
        $r = mysqli_fetch_array($q);
        $alasan=$r['alasan'];
    }
    else $alasan='';

    $i=$urut;

    if(mysqli_num_rows($cek_par)){
        $par='agt par';
    }
    else $par='';
    ?>
    <tr>
        <td><?=$ang['nama_nasabah'] ?>/<?=$ang['no_center'] ?>/<?=$ang['kelompok'] ?>(<?=$par?>)</td>

    </tr>
    <tr>
        <th>WAJIB</th>
        <td><input type="number" class='form-control' value='0' min="0" onchange="ganti_total(<?=$i?>)" name='wajib[]' id='wajib-<?=$i?>' style="width: 100px;" pattern="\d+"/></td>
        <th>PENSIUN</th>
        <td><input type="number" class='form-control' value='0' min="0" onchange="ganti_total(<?=$i?>)" name='pensiun[]' id='pensiun-<?=$i?>' style="width: 100px;" pattern="\d+"/></td>
    </tr>
    <tr>
    <th>
        SUKARELA
    </th>
    <td>
        <input type="number" class='form-control' value='0' min="0" onchange="ganti_total(<?=$i?>)" name='sukarela[]' id='sukarela-<?=$i?>' style="width: 100px;" pattern="\d+"/>
    </td>
    <th>
        HARI RAYA
    </td>
    <td>
        <input type="number" class='form-control' value='0' min="0" onchange="ganti_total(<?=$i?>)" name='hariraya[]' id='hariraya-<?=$i?>' style="width: 100px;" pattern="\d+"/>
        <input type="hidden" class='form-control' value='0' min="0" onchange="ganti_total(<?=$i?>)" name='nominal[]' id='nominal-<?=$i?>' style="width: 100px;" pattern="\d+"/>
    <small><p id='total-<?=$i?>'>total : </p></small>
    </td>
    </tr>
    <tr>
        <th>ANGSURAN MASUK</th>
        <td>
        <input type="number" class='form-control' value='0' min="0" max="100" name='masuk[]' id='masuk-<?=$i?>' style="width: 100px;" pattern="\d+"/>

        </td>
        <th>ALASAN</th>
        <td> <input type="text" name='alasan[]' class='form-control' value='<?=$alasan?>' ></td>
    </tr>
    

<?php
}
else{
    echo "anggota tidak ditemukan";
}