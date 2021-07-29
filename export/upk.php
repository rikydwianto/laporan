<?php 
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_karyawan = $_SESSION['id'];
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan= $_SESSION['jabatan'];
$cabang= $_SESSION['cabang'];
$id_cabang= $_SESSION['cabang'];
$su= $_SESSION['su'];
$d = detail_karyawan($con,$id_karyawan);
$nama_jabatan=$d['singkatan_jabatan'];
if (isset($_GET['tglawal']) || isset($_GET['tglakhir'])) {
    $tglawal = $_GET['tglawal'];
    $tglakhir = $_GET['tglakhir'];
} else {
    $tglawal = date("Y-m-d");
    $tglakhir = date("Y-m-d", strtotime('+4 day', strtotime(date("Y-m-d"))));
}

 if(isset($_GET['tgl']))
	{
		$qtgl=$_GET['tgl'];
	}
	else{
		$qtgl=date("Y-m-d");
	}
	$hari = hari_biasa($qtgl);
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data UPK.xls");
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
    table{
        border-collapse: collapse;
    }
	th, td {
	  padding: 5px;
	  text-align: left;
	}
    table, thead, tr,th{
        text-align: center;
    }
    td {
        height: 30px;
        vertical-align:middle;
    }
	</style>
	<title></title>
</head>
<body>

<h2>UPK Tanggal <?=format_hari_tanggal($tglawal)?> s/d <?=format_hari_tanggal($tglakhir)?></h2>
<table border="1" class='border: 1px solid;width:100%'>
    <thead style="text-align: center;">
        <th >No</th>
        <th>Tanggal</th>
        <th>Staff</th>
        <th>Center</th>
        <th>Hari</th>
        <th>JAM</th>
        <th>Anggota</th>
        <th>Status</th>
        <th style="width:200px">keterangan</th>

    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM upk where id_cabang ='$id_cabang' and tgl_upk >= '$tglawal' and tgl_upk <= '$tglakhir' order by tgl_upk,id_karyawan asc ";
        $query  = mysqli_query($con,$sql);
        $total_anggota = 0;
        while ($upk = mysqli_fetch_array($query)) {
            $cari = mysqli_query($con, "select * from center where id_cabang='$id_cabang' AND no_center='" . $upk['no_center'] . "'");
            $cari = mysqli_fetch_array($cari);
        ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=format_hari_tanggal($upk['tgl_upk'])?></td>
                <td><?=detail_karyawan($con,$upk['id_karyawan'])['nama_karyawan']?></td>
                <td><?=$cari['no_center']?></td>
                <td><?=$cari['hari']?></td>
                <td><?=$cari['jam_center']?></td>
                <td><?=$total = $upk['anggota_upk']?></td>
                <td><?=$upk['status']?></td>
                <td>
                
                   
                </td>
                
            </tr>
        <?php
        $total_anggota = $total_anggota + $total;
        }
        for($i=1;$i<=15;$i++){
            ?>
            <tr>
                <td><?=$no++?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                
                   
                </td>
            </tr>
            <?php

        }
        ?>
    </tbody>
    <?php 
    if(mysqli_num_rows($query)){
        ?>
    <tfoot>
        <tr>
            <th colspan=6>Total Anggota UPK</th>
            <th align="center"><?=$total_anggota?></th>
            <th></th>

        </tr>

    </tfoot>
        <?php
    }
    else{
        ?>
        <tfoot>
            <tr>
                <th colspan=10><center>Tidak ada data!</center></th>
                

            </tr>

        </tfoot>
        <?php
    }
    ?>
</table>


</body>
</html>
	

