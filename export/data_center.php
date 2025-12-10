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
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Daftar wilayah .xls");
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
	th, td {
	  padding: 5px;
	  text-align: left;
	}
	</style>
	<title></title>
</head>
<body>
<table border="1">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>KECAMATAN</th>
                        <th>DESA</th>
                        <th>RT/RW</th>
                        <th>ALAMAT</th>
                        <th>KETERANGAN</th>
                        <th>CENTER</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $kec = mysqli_query($con, "select * from data_center where id_cabang='$id_cabang' group by kecamatan order by kecamatan asc ");
                    while ($kecamatan = mysqli_fetch_array($kec)) {
                    ?>
                        <tr>
                            <th><?= $no++ ?></th>
                            <th><?= strtoupper($kecamatan['kecamatan']) ?></th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php
                        $qdet = mysqli_query($con, "select * from data_center where kecamatan='$kecamatan[kecamatan]' and id_cabang='$id_cabang' order by desa,rt,rw asc");
                        $no1=1;
                        while ($detailCenter = mysqli_fetch_array($qdet)) {
                        ?>
                            <tr>
                                <td >&nbsp;&nbsp;&nbsp;&nbsp;<i><?= $no1++ ?>.</i></td>
                                <td><?= strtoupper($detailCenter['kecamatan']) ?></td>
                                <td><?= strtoupper($detailCenter['desa']) ?></td>
                                <td><?=$detailCenter['rt']?>/<?=$detailCenter['rw']?></td>
                                <td><?=$detailCenter['alamat']?></td>
                                <td>'<?=$detailCenter['keterangan']?></td>
                                <td>
                                    <?php cek_center($con,$detailCenter['no_center'])?>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>

</body>
</html>
	

