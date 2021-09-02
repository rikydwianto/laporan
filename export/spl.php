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
    <title>GAMBAR</title>
    <style>
        @page {
            margin: 0;
        }

        * {
            margin: 0;
        }
        .tengah{
            text-align: center;
        }
        .nama{
            width: 27cm;
            font-size: 2.1cm;
        }
        .kotak{
            width: 8.5cm;
            font-size: 2.5cm;
        }
        .panjang{
            width: 15cm;
            font-size: 1.8cm;
        }
      
        td {
            border: 3px solid black;
            padding: 1cm;
            height: 4cm;
        }

        table {
            vertical-align: middle;
            /* width:1200cm; */
            font-weight: bolder;
            font-size: 1.5cm;

        }

    </style>
</head>

<body>
    <?php
    $sql = "select * from spl where tgl_spl='$qtgl'";
    $query = mysqli_query($con, $sql);

    //data array
    $array = array();
    while ($data = mysqli_fetch_assoc($query)) $array[] = $data;

    //mengubah data array menjadi json
    ?>
    <table style="float:right">
        <tr >
            <td>NAMA</td>
        </tr>
        <?php
        foreach ($array as $val) {
        ?>
            <tr>
                <td class='nama'><?= str_replace("Sub. Tot ","",$val['staff']) ?></td>
            </tr>
        <?php
        
        }
        ?>

    </table>
    <br>
    <table >
        <tr >
            <td> Center</td>
            <td>Member</td>
            <td>CLIENT</td>
        </tr>
        <?php
        foreach ($array as $val) {
        ?>
            <tr class='tengah'>
                <td class='kotak'><?= $val['jumlah_center'] ?></td>
                <td class='kotak'><?= $val['member'] ?></td>
                <td class='kotak'><?= $val['client'] ?></td>
            </tr>
        <?php
        }
        ?>
    </table>

    <br>
    <table >
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
                <td class='panjang' ><?= angka($disburse); ?></td>
                <td class='panjang' ><?= angka($outstanding) ?> </td>
            </tr>
        <?php
        }
        ?>
        <tr class='tengah'>
            <td class='panjang'  ><?=angka($total_disburse)?></td>
            <td class='panjang' ><?=angka($total_outstanding)?></td>
        </tr>
    </table>
    <br>
    <table >
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
                <td class='panjang' ><?= angka($masalah) ?> </td>
                <td class='kotak'><?= $val['par'] ?></td>
                <!-- <td class='kotak'><?= $new_member?></td> -->
            </tr>
        <?php
        }
        ?>
        <tr class='tengah'>
            <td class='panjang'><?=angka($total_masalah)?></td>
            <td class='kotak'><?=round(($total_masalah/$total_outstanding)*100,2)?></td>
            <!-- <td class='kotak'><?=$total_new_member?></td> -->
        </tr>
    </table>
</body>


</html>