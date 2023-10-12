<!DOCTYPE html>
<html lang="en">
<?php require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");

require_once("model/model.php");
require("vendor/PHPExcel/Classes/PHPExcel.php");
$id_karyawan = $_SESSION['id'];
$tgl_awal = aman($con,$_GET['tgl_awal']);
$tgl_akhir = aman($con,$_GET['tgl_akhir']);

$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan = $_SESSION['jabatan'];
$cabang = $_SESSION['cabang'];
$id_cabang = $_SESSION['cabang'];
$su = $_SESSION['su'];
$d = detail_karyawan($con, $id_karyawan);
$nama_jabatan = $d['singkatan_jabatan'];
$_SESSION['kode_cabang']=$d['kode_cabang'];?>

<head>
    <style>
    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        /* font-size: 11px; */

    }

    table tr th {
        height: 0.7cm;
        padding: 3px;
        font-weight: bold;
        /* font-size: 11px; */
    }

    .kertas table {
        font-size: 8px;
        margin-left: 0cm;
        margin-right: 0.2cm;
    }

    .kertas {
        /* background-color: red; */
        width: 100%;
        /* height: 21cm; */
    }

    .kertas .tengah {
        padding-top: 0.5cm;
        text-align: center;
        line-height: 0cm
    }

    table tr td {
        vertical-align: middle;

    }

    .isi_tengah {
        text-align: center;
    }

    .kecil {
        font-size: 10px;
    }

    /* .image-container {
        position: relative;
    }

    .draggable {
        position: absolute;
        cursor: grab;
    } */
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Transfer</title>
</head>

<body>
    <div class="table-container" class='draggable'>
        <table>
            <tr>
                <th rowspan=1>Total Transfer</th>
                <th>Nama Anggota</th>
                <th>ID</th>
                <th>CTR/KLP</th>
                <th>RINCIAN</th>
                <th>KETERANGAN</th>
            </tr>
            <?php 
        $q="SELECT
        COUNT(`detail_tf`.`id_bukti`) AS total_tf,
        detail_tf.id_bukti,
        bukti_tf.`total_nominal`
      FROM
        detail_tf
        LEFT JOIN bukti_tf
          ON bukti_tf.`id_bukti` = detail_tf.`id_bukti`
          WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
      GROUP BY detail_tf.id_bukti";
        $q_ = mysqli_query($con,$q);
        while($r = mysqli_fetch_array($q_)){
            $baris=1;
            ?>
            <tr>
                <td rowspan="<?=$r['total_tf']?>"><?=rupiah($r['total_nominal'])?></td>
                <?php 
                $q2= mysqli_query($con,"select * from detail_tf where id_bukti='$r[id_bukti]'");
               while($detail = mysqli_fetch_array($q2)){
                if($baris==1){
                    ?>
                <td><?=$detail['nama_nasabah']?></td>
                <td><?=$detail['id_nasabah']?></td>
                <td><?=$detail['center']?>/<?=$detail['kelompok']?></td>
                <td><?=rupiah($detail['total'])?></td>
                <td><?=$detail['keterangan']?></td>
            </tr>
            <?php
                }
                else{
                    ?>
            <tr>
                <td><?=$detail['nama_nasabah']?></td>
                <td><?=$detail['id_nasabah']?></td>
                <td><?=$detail['center']?>/<?=$detail['kelompok']?></td>
                <td><?=rupiah($detail['total'])?></td>
                <td><?=$detail['keterangan']?></td>
            </tr>
            <?php
                }
                $baris++;
               } 
                ?>
            </tr>
            <?php
        }
        ?>
        </table>
        <?php 
     $q_gambar="SELECT
     COUNT(`detail_tf`.`id_bukti`) AS total_tf,
     detail_tf.*,
     bukti_tf.*
   FROM
     detail_tf
     LEFT JOIN bukti_tf
       ON bukti_tf.`id_bukti` = detail_tf.`id_bukti`
       WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
   GROUP BY detail_tf.id_bukti";
   $q_gambar = mysqli_query($con,$q_gambar);
   $counter = 0;

   $left =0;
   $top = 0;
   while($gambar = mysqli_fetch_array($q_gambar)){
   
    ?>
        <img src="<?=$url?>assets/tf/<?=$gambar['nama_file']?>" class="draggable" style="max-width:280px;" alt="">

        <?php
    $counter++;
   }
    ?>

    </div>
</body>

</html>