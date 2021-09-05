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
        *{
            margin: 0px;
        }
        .grid-container {
            display: grid;
            /* grid-template-columns: auto auto auto; */
            /* background-; */
            width: 33cm;
            padding: 0px;
            
        }
        }
        .grid-container1 {
            display: grid;
            grid-template-columns: auto auto auto;
            /* background-; */
            width: 33cm;
            padding: 0px;
            
        }

        .grid-item {
            background-color: rgba(255, 255, 255, 0.8);
            border: 2px solid rgba(0, 0, 0, 0.8);
            /* padding: 1cm; */
            /* padding-top: 1.4cm; */
            height: 5cm;
            font-size: 30px;
            /* text-align: center; */
            
            display: block;
            
        }
        .lebar{
            width:20cm;
        }
        .text_nama{
            
            padding-top: 1.5cm;
            padding-bottom: 1cm;
            padding-left: 1cm;
            font-size: 3.8rem;
            font-weight: bolder;
            display: block;
            

        }
        .kotak{
            width:8cm;
            /* border; */
        }
        .text_kotak{
            padding-top: 1.5cm;
            padding-bottom: 1cm;
            padding-left: 1cm;
            font-size: 4rem;
            font-weight: bolder;
            display: block;
        }
    </style>
</head>

<body>
    <?php
    $sql = "select * from spl ";
    $query = mysqli_query($con, $sql);

    //data array
    $array = array();
    while ($data = mysqli_fetch_assoc($query)) $array[] = $data;

    //mengubah data array menjadi json
    ?>
    <div class="grid-container">
        <?php
        foreach ($array as $val) {
        ?>
                <div class="grid-item lebar"><span class="text_nama"><?= str_replace("Sub. Tot ", "", $val['staff']) ?></span></div>

        <?php

        }
        ?>
        

    </div>

    <div class="grid-container1">
        <div class="grid-item kotak"> <span class="text_kotak">center</span></div>
        <div class="grid-item kotak"> <span class="text_kotak">center</span></div>
        </tr>
        <?php
        foreach ($array as $val) {
        ?>
        <div class="grid-item kotak"> <span class="text_kotak"><?= $val['jumlah_center'] ?></span></div>
        <div class="grid-item kotak"> <span class="text_kotak"><?= $val['member'] ?></span></div>
        <div class="grid-item kotak"> <span class="text_kotak"><?= $val['client'] ?></span></div>
        <?php
        }
        ?>
    </div>
    <br>
    <table>
        <tr>
            <td>disburse</td>
            <td>outstanding</td>
        </tr>
        <?php
        $total_disburse = 0;
        $total_outstanding = 0;
        foreach ($array as $val) {
            $disburse =  angka_mentah($val['disburse']);
            $outstanding = angka_mentah($val['outstanding']);
            $total_disburse = $total_disburse + $disburse;
            $total_outstanding = $total_outstanding + $outstanding;
        ?>
            <tr class='tengah'>
                <td class='panjang'><?= angka($disburse); ?></td>
                <td class='panjang'><?= angka($outstanding) ?> </td>
            </tr>
        <?php
        }
        ?>
        <tr class='tengah'>
            <td class='panjang'><?= angka($total_disburse) ?></td>
            <td class='panjang'><?= angka($total_outstanding) ?></td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td>masalah <br>outstanding</td>
            <td>par</td>
            <!-- <td>member</td> -->
        </tr>
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
            <tr class='tengah'>
                <td class='panjang'><?= angka($masalah) ?> </td>
                <td class='kotak'><?= $val['par'] ?></td>
                <!-- <td class='kotak'><?= $new_member ?></td> -->
            </tr>
        <?php
        }
        ?>
        <tr class='tengah'>
            <td class='panjang'><?= angka($total_masalah) ?></td>
            <td class='kotak'><?= round(($total_masalah / $total_outstanding) * 100, 2) ?></td>
            <!-- <td class='kotak'><?= $total_new_member ?></td> -->
        </tr>
    </table>
</body>


</html>