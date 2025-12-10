<?php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_karyawan = $_SESSION['id'];
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan = $_SESSION['jabatan'];
$cabang = $_SESSION['cabang'];
$id_cabang = $_SESSION['cabang'];
$su = $_SESSION['su'];
$d = detail_karyawan($con, $id_karyawan);
$nama_jabatan = $d['singkatan_jabatan']; ?>

<?php
if (isset($_GET['tgl'])) {
    $qtgl = $_GET['tgl'];
} else {
    $qtgl = date("Y-m-d");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPL</title>
    <style>
        * {
            margin-left: 0.6px;
        }

        .grid-container {
            display: grid;
            /* grid-template-columns: auto auto auto; */
            /* background-; */
            width: 33cm;
            padding: 0px;

        }

        .grid-container1 {
            display: grid;
            grid-template-columns: 8cm 8.1cm auto;
            /* background-; */
            width: 33cm;
            padding: 0px;

        }

        .grid-container2 {
            display: grid;
            grid-template-columns: auto auto;
            /* background-; */
            width: 22cm;
            padding: 0px;

        }

        .grid-item {
            background-color: rgba(255, 255, 255, 0.8);
            border: 4px solid rgba(0, 0, 0, 0.8);
            /* padding: 1cm; */
            /* padding-top: 1.4cm; */
            height: 4cm;
            font-size: 30px;
            /* text-align: center; */

            display: block;

        }

        .lebar {
            width: 20cm;
        }

        .text_nama {

            padding-top: 0.7cm;
            padding-bottom: 1cm;
            padding-left: 1cm;
            font-size: 5rem;
            font-weight: bolder;
            display: block;


        }

        .kotak {
            width: 8cm;
            /* border; */
        }

        .panjang {
            width: 17CM;
        }

        .text_kotak {
            padding-top: 0.7cm;
            font-size: 6rem;
            font-weight: bolder;
            display: block;
            text-align: center;
        }

        .text_panjang {
            padding: 1cm;
            font-size: 4.5rem;
            font-weight: bolder;
            display: block;

        }
    </style>
</head>

<body>
    <?php
    $id = aman($con,$_GET['id']);
    $sql = "select * from spl join karyawan on karyawan.id_karyawan=spl.id_karyawan where id_statistik='$id' ";
    $query = mysqli_query($con, $sql);

    //data array
    $array = array();
    while ($data = mysqli_fetch_assoc($query)) $array[] = $data;

    //mengubah data array menjadi json
    ?>
    <div class="grid-container" >
        <?php
        foreach ($array as $val) {
        ?>
            <div class="grid-item lebar"><span class="text_nama"><?= hapus_nama_belakang($val['nama_karyawan']) ?></span></div>

        <?php

        }
        ?>


    </div>

    <div class="grid-container1" >
        <div class="grid-item kotak"> <span class="text_kotak">center</span></div>
        <div class="grid-item kotak"> <span class="text_kotak">member</span></div>
        <div class="grid-item kotak"> <span class="text_kotak">client</span></div>
        </tr>
        <?php

        $jumlah_center = 0;
        $jumlah_member = 0;
        $jumlah_client = 0;
        foreach ($array as $val) {
        ?>
            <div class="grid-item kotak"> <span class="text_kotak"><?= $val['jumlah_center'] ?></span></div>
            <div class="grid-item kotak"> <span class="text_kotak"><?= $val['member'] ?></span></div>
            <div class="grid-item kotak"> <span class="text_kotak"><?= $val['client'] ?></span></div>
        <?php
            $jumlah_center = $jumlah_center + $val['jumlah_center'];
            $jumlah_member = $jumlah_member + $val['member'];
            $jumlah_client = $jumlah_client + $val['client'];
        }

        ?>
        <div class="grid-item kotak"> <span class="text_kotak"><?= $jumlah_center ?></span></div>
        <div class="grid-item kotak"> <span class="text_kotak"><?= $jumlah_member ?></span></div>
        <div class="grid-item kotak"> <span class="text_kotak"><?= $jumlah_client ?></span></div>
    </div>
    <br>

    <div class="grid-container2">
        <div class="grid-item panjang"> <span class="text_panjang">disburse</span></div>
        <div class="grid-item panjang"> <span class="text_panjang">outstanding</span></div>
        <?php
        $total_disburse = 0;
        $total_outstanding = 0;
        foreach ($array as $val) {
            $disburse =  angka_mentah($val['disburse']);
            $outstanding = angka_mentah($val['outstanding']);
            $total_disburse = $total_disburse + $disburse;
            $total_outstanding = $total_outstanding + $outstanding;
        ?>
            <div class="grid-item panjang"> <span class="text_panjang"><?= angka($disburse); ?></span></div>
            <div class="grid-item panjang"> <span class="text_panjang"><?= angka($outstanding) ?></span></div>
        <?php
        }
        ?>
        <div class="grid-item panjang"> <span class="text_panjang"><?= angka($total_disburse); ?></span></div>
        <div class="grid-item panjang"> <span class="text_panjang"><?= angka($total_outstanding) ?></span></div>
    </div>

    <br>
    <div class="grid-container2">
        <div class="grid-item panjang"> <span class="text_panjang">masalah</span></div>
        <div class="grid-item kotak"> <span class="text_kotak">par</span></div>
        <?php
        $total_disburse = 0;
        $total_masalah = 0;
        $total_new_member = 0;
        foreach ($array as $val) {
            $disburse =  angka_mentah($val['disburse']);
            $masalah = angka_mentah($val['masalah']);
            $total_disburse = $total_disburse + $disburse;
            $total_masalah = $total_masalah + $masalah;
            $new_member = $val['new_member'];
            $total_new_member = $total_new_member + $new_member;
        ?>
            <div class="grid-item panjang"> <span class="text_panjang"><?= angka($masalah) ?></span></div>
            <div class="grid-item kotak"> <span class="text_kotak"><?= round($val['par'],2) ?></span></div>
        <?php
        }
        ?>
        <div class="grid-item panjang"> <span class="text_panjang"><?= angka($total_masalah) ?></span></div>
        <div class="grid-item kotak"> <span class="text_kotak"><?= round(($total_masalah / $total_outstanding) * 100, 2) ?></span></div>
    </div>
    <br>
</body>


</html>