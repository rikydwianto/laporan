<?php 
$bulan_kemarin = sprintf("%02d",$_GET['bln_kemarin']);
$tahun_kemarin = sprintf("%02d",$_GET['tahun_kemarin']);
$tahun_ini = $_GET['tahun'];
$bulan_ini = $_GET['bln'];

?>

<div class='content table-responsive'>
<form method='get' >
	<div class='col-md-6'>
        <div class='col-md-6'>
            <h3>BULAN INI</h3>
            <input type=hidden name='menu' value='rekap_kehadiran' />
                
                <select name='bln'>

                    <?php
                    $bulan_no=1;

                    foreach($bulan as $bln){
                        if($bulan_no==date("n") || sprintf("%01d",$bulan_no)==$bln1) $check="selected";
                        else $check="";
                        echo"<option value='".$bulan_no++."' $check > $bln</option>";
                    }
                    ?>
                </select>
                <input type=text name='tahun' value='<?php echo date("Y") ?>'/>
        </div>
        <div class='col-md-6'>

        <h3>BULAN LALU</h3>
                <select name='bln_kemarin'>

                    <?php
                    $bulan_no=1;

                    foreach($bulan as $bln){
                        if($bulan_no==date("n")-1 || sprintf("%01d",$bulan_no)==$bulan_kemarin) $check="selected";
                        else $check="";
                        echo"<option value='".$bulan_no++."' $check > $bln</option>";
                    }
                    ?>
                </select>
                <input type=text name='tahun_kemarin' value='<?php echo date("Y") ?>'/>
            <input type=submit name='cari' value='CARI' />
        </div>
    </div>
</form>
<br>
<br>
<br>
<br>
<br>
<br>
	<?php 
    if(isset($_GET['cari'])){
        ?>
        <h2 class='page-header'>REKAP KEHADIRAN DAN PERSEN ANGSURAN </h2>
            <table class='table table-bordered'>
                <thead>
                    <th>NO</th>
                    <th>STAFF</th>
                    <th>TOTAL <br> CTR</th>
                    <th>TOTAL <br> ANGGOTA</th>
                    <th>TOTAL <br> KEHADIRAN </th>
                    <th>TOTAL <br> TIDAK HADIR </th>
                    <th>TOTAL <br> BAYAR(client) </th>
                    <th>TOTAL <br> TIDAK BAYAR </th>
                </thead>
                <tbody>
                <?php
                $q = mysqli_query($con,"SELECT DISTINCT k.id_karyawan,k.nama_karyawan from laporan l join detail_laporan d on l.id_laporan=d.id_laporan join karyawan k on k.id_karyawan=l.id_karyawan where k.id_cabang='$id_cabang' order by k.nama_karyawan asc");
                echo mysqli_error($con); 
                while($r = mysqli_fetch_array($q)){
                    $cek_l=mysqli_query($con,"SELECT sum(detail_laporan.total_agt)as anggota,sum(detail_laporan.member)as member,sum(detail_laporan.anggota_hadir)as hadir, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, count(if(doortodoor='y',1,NULL) ) as hitung_dtd, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and 
                    laporan.id_karyawan='$r[id_karyawan]'  and laporan.tgl_laporan like '$tahun_ini-$bulan_kemarin-%%' and laporan.status_laporan='sukses'");
                    $lap = mysqli_fetch_array($cek_l);
                    ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=$r['nama_karyawan']?></td>
                        <td><?=$lap['hitung_center']?></td>
                        <td><?=$lap['member']?></td>
                        <td><?=$lap['hadir']?></td>
                        <td><?=$lap['member'] - $lap['hadir']  ?> </td>
                        <td><?=$lap['bayar']?></td>
                        <td><?=$lap['tidak_bayar']?> </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>

            </table>
            <div class='col-md-6'>
                <h2>REKAP CENTER</h2>
                <table  class='table table-bordered'>
                <thead>
                    <tr>
                            <th rowspan="2">NO</th>
                            <th rowspan="2"> STAFF</th>
                            <th colspan="5" style="text-align: center;">INFORMASI CENTER</th>
                        </tr>
                        <tr style="text-align: center;">
                            <th  >JUMLAH CENTER</th>
                            <th>HIJAU</th>
                            <th>KUNING</th>
                            <th>MERAH</th>
                            <th>% KEHADIRAN <br/> <?=$bulan[sprintf("%0d",$bulan_kemarin)]?></th>
                        </tr>
                </thead>
                <tbody>
                    <?php
                    $no=1;
                    $q=mysqli_query($con,"SELECT DISTINCT center.id_karyawan,karyawan.nama_karyawan from center join karyawan on karyawan.id_karyawan=center.id_karyawan where center.id_cabang='$id_cabang' order by karyawan.nama_karyawan asc"); 
                    while($r = mysqli_fetch_array($q)){
                        $qhasil = mysqli_query($con,"
                        select count(*) as total_center,
                        count(if(status_center='hijau',1,NULL) ) as hijau,
        count(if(status_center='kuning',1,NULL) ) as kuning,
        count(if(status_center='merah',1,NULL) ) as merah,
        count(if(status_center='hitam',1,NULL) ) as hitam from center where id_karyawan='$r[id_karyawan]'
                        ");
                        echo mysqli_error($con);
                        $hasil = mysqli_fetch_array($qhasil);

                        $cek_l=mysqli_query($con,"SELECT sum(detail_laporan.total_agt)as anggota,sum(detail_laporan.member)as member,sum(detail_laporan.anggota_hadir)as hadir, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, count(if(doortodoor='y',1,NULL) ) as hitung_dtd, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and 
                        laporan.id_karyawan='$r[id_karyawan]'  and laporan.tgl_laporan like '$tahun_kemarin-$bulan_kemarin-%%' and laporan.status_laporan='sukses'");
                        $kehadiran=mysqli_fetch_array($cek_l);
                        ?>
                        <tr>
                            <td><?=$no++?></td>
                            <td><?=$r['nama_karyawan']?></td>
                            <td><?=$hasil['total_center']?></td>
                            <td><?=$hasil['hijau']?></td>
                            <td><?=$hasil['kuning']?></td>
                            <td><?=$hasil['merah']?></td>
                            <td><?php
                            if($kehadiran['hadir']>0 && $kehadiran['anggota']>0){
                                echo round(($kehadiran['hadir']/$kehadiran['anggota']) * 100,2)."%"; 
                                
                                } 
                                ?>
                                </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>

                </table>
            </div>
        <?php
    }
    ?>
</div>

