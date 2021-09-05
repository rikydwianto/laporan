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
            margin: 0px;
        }

        .grid-container {
            display: grid;
            /* grid-template-columns: auto auto auto; */
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

        .lebar {
            width: 20cm;
        }

        .text_nama {

            padding-top: 1.5cm;
            padding-bottom: 1cm;
            padding-left: 1cm;
            font-size: 3.8rem;
            font-weight: bolder;
            display: block;


        }

        table {
            width: auto;
            font-size: 4em;
            display: block;
            font-weight: bolder;
        }
        table tr td {
            padding: 1cm;
            /* text-align: center; */
        }
        .kotak{
            text-align: center;
        }
        .nama{
            width: 20cm;
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
    <!-- <div class="grid-container">
        <?php
        foreach ($array as $val) {
        ?>
            <div class="grid-item lebar"><span class="text_nama"><?= str_replace("Sub. Tot ", "", $val['staff']) ?></span></div>

        <?php

        }
        ?>


    </div> -->

    <table border="1" >
        <tr>
            <td>NAMA</td>
            <td> Center</td>
            <td>Member</td>
            <td>CLIENT</td>
            <td>disburse</td>
            <td>outstanding</td>
            <td>masalah <br>outstanding</td>
            <td>par</td>
        </tr>
        <?php
        $total_disburse = 0;
        $total_outstanding = 0;
        $total_disburse = 0;
        $total_masalah = 0;
        $total_new_member = 0;
        foreach ($array as $val) {
            
        ?>
          <?php
        
            $disburse =  angka_mentah($val['disburse']);
            $outstanding = angka_mentah($val['outstanding']);
            $total_disburse = $total_disburse + $disburse;
            $total_outstanding = $total_outstanding + $outstanding;
        ?>
         <?php
       
            $disburse =  angka_mentah($val['disburse']);
            $masalah = angka_mentah($val['masalah']);
            $total_disburse = $total_disburse + $disburse;
            $total_masalah = $total_masalah + $masalah;
            $new_member = $val['new_member'];
            $total_new_member = $total_new_member + $new_member;
        ?>
            <tr>
                <td class='nama'><?= str_replace("Sub. Tot ", "", $val['staff']) ?></td>
                <td class='kotak'><?= $val['jumlah_center'] ?></td>
                <td class='kotak'><?= $val['member'] ?></td>
                <td class='kotak'><?= $val['client'] ?></td>
                <td class='kotak'><?= angka($disburse); ?></td>
                <td class='kotak'><?= angka($outstanding) ?> </td>
                <td class='kotak'><?= angka($masalah) ?> </td>
                <td class='kotak'><?= $val['par'] ?></td>
            </tr>

        <?php

        }
        ?>
        <tr class='tengah'>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ><?= angka($total_disburse) ?></td>
            <td ><?= angka($total_outstanding) ?></td>
            <td ><?= angka($total_masalah) ?></td>
            <td class='kotak'><?= round(($total_masalah / $total_outstanding) * 100, 2) ?></td>
        </tr>


    </table>
</body>


</html>