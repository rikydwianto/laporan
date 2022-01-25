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
 if(isset($_GET['tgl']))
	{
		$qtgl=$_GET['tgl'];
	}
	else{
		$qtgl=date("Y-m-d");
	}
	$hari = hari_biasa($qtgl);
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=laporan harian $hari .xls");
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>table {
  border-collapse: collapse;
}
</style>
	
</head>
<body>

	<table border="1">
        <tr>
            <th colspan="20"> <br/>
                JADWAL CENTER MEETING <br/> CABANG <?= strtoupper($d['nama_cabang']) ?> <br/><br/>
            </th>
        </tr>
        <?php 
        $qhari = mysqli_query($con,"SELECT distinct hari from center where id_cabang='$id_cabang' order by FIELD(hari,'senin','selasa','rabu','kamis','jumat') asc");
        while($hari = mysqli_fetch_array($qhari)){
            ?>
            <tr >
                <td rowspan="2" style="padding: 10px;font-weight: bold;" ><?=strtoupper($hari['hari'])?></td>
                <?php $qkar = mysqli_query($con,"SELECT distinct k.nama_karyawan from center c join karyawan k on k.id_karyawan=c.id_karyawan where c.id_cabang='$id_cabang' order by k.nama_karyawan asc ");
                while($kar=mysqli_fetch_array($qkar)){
                    ?>
                    <th colspan="1" style="font-size: 12px;min-width: 60px;"> &nbsp;&nbsp;<?=explode(" ",strtoupper( $kar['nama_karyawan']))[0]?>&nbsp;&nbsp;</th>
                    <?php

                    $center_hari = mysqli_query($con,"SELECT count(hari) as hitung_hari from center where id_cabang='$id_cabang' and hari='$hari[hari]'");
                }
                ?>
                <td rowspan="2">
                
                
                </td>
            </tr>
            <tr >
                
                <?php $qkar = mysqli_query($con,"SELECT distinct k.nama_karyawan,k.id_karyawan from center c join karyawan k on k.id_karyawan=c.id_karyawan where c.id_cabang='$id_cabang' order by k.nama_karyawan asc ");
                while($kar=mysqli_fetch_array($qkar)){
                    $qcenter = mysqli_query($con,"SELECT no_center,status_center,member_center from center where id_cabang='$id_cabang' and hari='$hari[hari]' and id_karyawan='$kar[id_karyawan]' order by jam_center asc");
                    ?>
                    <td style="vertical-align: top;text-align:center">
                        <?php 
                        while($center = mysqli_fetch_array($qcenter)){
                            $status = $center['status_center'];
                            if($status=='hijau') $warna='#025e1e';
                            else if($status=='kuning') $warna='#b59d02';
                            else if($status=='merah') $warna='#eb4034';
                            else if($status=='hitam') $warna='black';
                            else $warna='black';
                            echo "<b style='color:$warna'>".sprintf("%03d", $center['no_center'])."</b> | <b>$center[member_center]</b>"."<br/>";
                        }
                        ?>    
                    </td>
                    <?php
                }
                ?>
                
            </tr>
            <tr >
                <th>TOTAL</th>
                <?php $qkar = mysqli_query($con,"SELECT distinct k.nama_karyawan,k.id_karyawan from center c join karyawan k on k.id_karyawan=c.id_karyawan where c.id_cabang='$id_cabang' order by k.nama_karyawan asc ");
                while($kar=mysqli_fetch_array($qkar)){
                    $qcenter = mysqli_query($con,"SELECT count(no_center) as hitung_center from center where id_cabang='$id_cabang' and hari='$hari[hari]' and id_karyawan='$kar[id_karyawan]' order by jam_center asc");
                    ?>
                    <td style="vertical-align: top;text-align:center;font-weight: bold;background-color: #dcdedc;">
                        <?=mysqli_fetch_array($qcenter)['hitung_center']?>
                    </td>
                    <?php
                }
                ?>
                <th><?=mysqli_fetch_array($center_hari)['hitung_hari']?></th>
            </tr>

            <?php
        }
    $hitung_semua=0;
        ?>
        <tr >
                <th>TOTAL SEMUA STAFF</th>
                <?php $qkar = mysqli_query($con,"SELECT distinct k.nama_karyawan,k.id_karyawan from center c join karyawan k on k.id_karyawan=c.id_karyawan where c.id_cabang='$id_cabang' order by k.nama_karyawan asc ");
                while($kar=mysqli_fetch_array($qkar)){
                    $qcenter = mysqli_query($con,"SELECT count(no_center) as hitung_center from center where id_cabang='$id_cabang'  and id_karyawan='$kar[id_karyawan]' order by jam_center asc");
                    ?>
                    <th style="vertical-align: middle;text-align:center;font-weight: bold;background-color: #e86143;">
                        <?=$total = mysqli_fetch_array($qcenter)['hitung_center']?>
                        <?php $hitung_semua +=$total ?>
                    </th>
                    <?php
                }
                ?>
                <td rowspan="0" style="padding: 10px;font-weight: bold;">
                <?=$hitung_semua?>
                </td>
            </tr>
    
    </table>
	
</body>
</html>
	

