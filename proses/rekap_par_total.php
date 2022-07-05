
<div class='content table-responsive'>
	<h2 class='page-header'>REKAP TOTAL PAR </h2>
	<i></i> <br />
	<!-- <a href="<?= $url . $menu ?>spl" class='btn btn-success'> <i class="fa fa-eye"></i> Lihat</a> -->

 <form method="get">
    <div class="col-md-12">
    <div class="col-md-4">
        <input type="hidden" name="menu" value='rekap_par_total'>
    <select name='tgl' class='form-control' required>
                        
                        <option value="">PILIH MINGGU SEBELUM</option>
                        <?php 
                        error_reporting(0);
                        $q_tgl = mysqli_query($con,"SELECT DISTINCT tgl_input FROM deliquency where id_cabang='$id_cabang'  order by tgl_input desc");
                        while($tgl_ = mysqli_fetch_array($q_tgl)){
                            ?>
                            <option value="<?=$tgl_['tgl_input']?>" <?=($_GET['tgl']===$tgl_['tgl_input']?"selected":"")?>><?=format_hari_tanggal($tgl_['tgl_input'])?></option>
                            <?php
                        }
                        ?>

                    </select>
                    <input type="submit" class='btn btn-primary' value="LIHAT">
    </div>
    </div>
 </form>
	<hr />

    <?php
    $q_hari =  array(
        'SENIN',
        'SELASA',
        'RABU',
        'KAMIS',
        'JUMAT',
      );
    ?>
    <div class="col-md-12">
    <table class='table table-bordered'>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>OS PAR</th>
            <th>TOTAL CLIENT PAR</th>
            <th>TOTAL AGT</th>
            <th>TOTAL ALASAN</th>
            <?php 
            foreach($q_hari as $hari){
                echo "<th>$hari</th>";
            }
            ?>
        </tr>
        <?php 
        $total_par = 0;
        $total_alasan=0;
        $total_os=0;
        $tgl = $_GET['tgl'];
        $total_semua = 0;
        $q_delin = mysqli_query($con,"select c.id_karyawan,k.nama_karyawan,count(d.id) as total, sum(sisa_saldo) as balance from deliquency d join center c on c.no_center=d.no_center join karyawan k on k.id_karyawan=c.id_karyawan 
        where d.tgl_input='$tgl' and d.id_cabang='$id_cabang' and c.id_cabang='$id_cabang' and k.id_cabang='$id_cabang'
        group by c.id_karyawan order by k.nama_karyawan");
        while($delin = mysqli_fetch_array($q_delin)){
            $hit = mysqli_query($con,"SELECT COUNT(*) AS total_alasan FROM alasan_par WHERE id_cabang='$id_cabang' and id_karyawan='$delin[id_karyawan]' AND id_loan IN(SELECT loan FROM deliquency WHERE id_cabang='$id_cabang' AND tgl_input='$tgl') ");
            $hit = mysqli_fetch_array($hit);
            $total_par += $delin['total'];
            $total_alasan += $hit['total_alasan'];
            $total_os += $delin['balance'];

            $total_nasabah = mysqli_query($con,"select * from total_nasabah where id_cabang='$id_cabang' and id_karyawan='$delin[id_karyawan]'");
            $total_nasabah = mysqli_fetch_array($total_nasabah)['total_nasabah'];
            $total_semua += $total_nasabah;
            ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$delin['nama_karyawan']?></td>
                <td><?=rupiah($delin['balance'])?></td>
                <td><?=$delin['total']?></td>
                <td><?=$total_nasabah?></td>
                <td><?=$hit['total_alasan']?> - (<?=round(($hit['total_alasan']/$delin['total'])*100)?> %)</td>
                <?php 
            foreach($q_hari as $hari){
                $perhari = mysqli_query($con,"SELECT SUM(sisa_saldo) AS os_par, COUNT(*) AS total_par FROM deliquency WHERE tgl_input='$tgl' AND hari='$hari' and id_cabang='$id_cabang' and staff like'%$delin[nama_karyawan]%'");
                $perhari = mysqli_fetch_array($perhari);
                ?>
                <td><?=angka($perhari['os_par'])?></td>
                <?php 
            }
            ?>
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="2"></th>
            <!-- <th>NAMA</th> -->
            <th><?=rupiah($total_os)?></th>
            <th><?=$total_par?></th>
            <th><?=$total_semua?></th>
            <th><?=$total_alasan?></th>
 <?php
            foreach($q_hari as $hari){
                $perhari = mysqli_query($con,"SELECT SUM(sisa_saldo) AS os_par, COUNT(*) AS total_par FROM deliquency WHERE tgl_input='$tgl' AND hari='$hari' and id_cabang='$id_cabang' ");
                $perhari = mysqli_fetch_array($perhari);
                ?>
                <th><?=angka($perhari['os_par'])?></th>
                <?php 
            }
            ?>
 
        </tr>
    </table>
    </div>
</div>



