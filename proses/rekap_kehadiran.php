<?php 
$bulan_awal = date("m");
$tahun_ini = date("Y");
$bulan_kemarin = '02';

?>
<div class='content table-responsive'>
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
            laporan.id_karyawan='$r[id_karyawan]'  and laporan.tgl_laporan like '$tahun_ini-$bulan_awal-%%' and laporan.status_laporan='sukses'");
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
</div>