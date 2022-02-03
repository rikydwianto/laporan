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
<h1 style='text-align:center'>JADWAL CENTER MEETING <br/> CABANG <?= strtoupper($d['nama_cabang']) ?> <br/> DENGAN JAM DAN DESA<br/></h1>
	<table border="1">
        
        

    <thead>
        <?php 
        $qhari = mysqli_query($con,"SELECT distinct hari from center where id_cabang='$id_cabang' order by FIELD(hari,'senin','selasa','rabu','kamis','jumat') asc ");
        while($hari = mysqli_fetch_array($qhari)){
            $qjam = (mysqli_query($con,"SELECT jam_center from center where id_cabang='$id_cabang' and hari='$hari[hari]' group by jam_center order by jam_center"));
            ?>
            <tr class='hari'>
                <td rowspan="2" style=";font-weight:bold;font-size:10px"><!-- <?=strtoupper($hari['hari'])?>--></td>
                <!-- <th>JAM</th> -->
                <?php $qkar = mysqli_query($con,"SELECT distinct k.nama_karyawan from center c join karyawan k on k.id_karyawan=c.id_karyawan where c.id_cabang='$id_cabang' order by k.nama_karyawan asc ");
                while($kar=mysqli_fetch_array($qkar)){
                    ?>
                    <th  style="font-size: 10px;"> <b style=""><?=explode(" ",strtoupper( $kar['nama_karyawan']))[0]?></b> </th>
                    <?php

                    $center_hari = mysqli_query($con,"SELECT count(hari) as hitung_hari from center where id_cabang='$id_cabang' and hari='$hari[hari]'");
                }
                ?>
                <td rowspan="1" style="padding: 15px;vertical-align: top;"></style>KETERANGAN</td>
            </tr>
            
    </thead>
    <tbody>

            <tr id='staff'>
               <td><?=strtoupper($hari['hari'])?></td>
           <?php $qkar = mysqli_query($con,"SELECT distinct k.nama_karyawan,k.id_karyawan from center c join karyawan k on k.id_karyawan=c.id_karyawan where c.id_cabang='$id_cabang' order by k.nama_karyawan asc ");
                while($kar=mysqli_fetch_array($qkar)){
                    $qcenter = mysqli_query($con,"SELECT no_center,status_center,member_center,jam_center,desa,kecamatan from center where id_cabang='$id_cabang' and hari='$hari[hari]' and id_karyawan='$kar[id_karyawan]' order by jam_center asc");
                    ?>
                    <td style="vertical-align: top; font-size:10px; ">
                       <?php
                       while($center=mysqli_fetch_array($qcenter)){
                           $jam = explode(":",$center['jam_center']);
                           $jam = $jam[0].":".$jam[1];

                        $status = $center['status_center'];
                        if($status=='hijau') $warna='#025e1e';
                        else if($status=='kuning') $warna='#b59d02';
                        else if($status=='merah') $warna='#eb4034';
                        else if($status=='hitam') $warna='black';
                        else $warna='black';
                            echo "<b style='color:$warna; padding-top:0px;'>$jam | ".sprintf("%03d", $center['no_center'])."  ".'<br/>'.'  '.strtoupper(str_replace(" ","",$center['desa']))."<br/> client : $center[member_center] </b>"."<hr/>";
                   
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
    </tbody>

         <?php   
         }
         $hitung_semua=0;
         $total_member=0;
         ?>
         <tr >
                 <th colspan="1">TOTAL SEMUA STAFF<hr>MEMBER</th>
                 <?php 
                 $qkar = mysqli_query($con,"SELECT distinct k.nama_karyawan,k.id_karyawan from center c join karyawan k on k.id_karyawan=c.id_karyawan where c.id_cabang='$id_cabang' order by k.nama_karyawan asc ");
                 while($kar=mysqli_fetch_array($qkar)){
                     $qcenter = mysqli_query($con,"SELECT count(no_center) as hitung_center,sum(member_center) as member from center where id_cabang='$id_cabang'  and id_karyawan='$kar[id_karyawan]' order by jam_center asc");
                     $semua = mysqli_fetch_array($qcenter);
                     ?>
                     <th style="vertical-align: middle;text-align:center;font-weight: bold;background-color: #e86143;">
                         <?=$total = $semua['hitung_center']?><hr/>
                         <?=$total_member = $semua['member']?><br/>
                         <?php $hitung_semua +=$total ?>
                     </th>
                     <?php

                     $total_member += $total_member;
                 }
                 ?>
                 <td rowspan="0" style="padding: 10px;font-weight: bold;">
                 <?=$hitung_semua?><hr>
                 <?php
                 $total_member = mysqli_query($con,"SELECT sum(member_center) as member from center where id_cabang='$id_cabang' group by id_cabang  ");
                 $total_member = mysqli_fetch_array($total_member);
                 echo $total_member['member'];
                 ?>
                 </td>
             </tr>
    
    </table>
	
</body>
<style>
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    /* tfoot { display:table-footer-group } */
</style>
</html>
	
