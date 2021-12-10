
<style>
    #rekap_semua table  tr th{vertical-align: middle; text-align: center;
}
</style>
<?php
$tgl_minggu = date("Y-m-d",strtotime('last monday', strtotime("$tgl_banding")));
$bulan_tahun = date("Y-m",strtotime("$tgl_banding"));
$tahun  = sprintf("%0d",date("Y",strtotime("$tgl_banding")));
$bulan_zero=sprintf("%0d",date("m",strtotime("$tgl_banding")));
?>
<div class='col-lg-12' id='rekap_semua'>
    <a href="#rekap" onclick="printPageArea('rekap_semua')" class="btn btn-success">print <i class="fa fa-print"></i></a>
    <table class="table table-bordered" style="border: 1px;">
    <TR>
        <th colspan="20">REKAP CAPAIAN CASHFLOW</th>
    </TR>
    <tr>
        <th rowspan="2">NO</th>
        <th rowspan="2">NAMA</th>
        <th colspan="6">MINGGU <br/>
        <?=$tgl_minggu?> s/d <?=$tgl_banding?>
        </th>
        <th colspan="6">BULAN <br/>
            <?=$bulan[$bulan_zero]?> - <?=$tahun?>
        </th>
        <th colspan="6">TAHUN <br/> <?=$tahun?></th>
        
    </tr>
    <tr>
        <th colspan="1">AM</th>
        <th colspan="1">AK</th>
        <th colspan="1">NETT</th>
        <th colspan="1">TARGET</th>
        <th colspan="1">DISBURSE</th>
        <th colspan="1">TARGET</th>
        <th colspan="1">AM</th>
        <th colspan="1">AK</th>
        <th colspan="1">NETT</th>
        <th colspan="1">TARGET</th>
        <th colspan="1">DISBURSE</th>
        <th colspan="1">TARGET</th>
        <th colspan="1">AM</th>
        <th colspan="1">AK</th>
        <th colspan="1">NETT</th>
        <th colspan="1">TARGET</th>
        <th colspan="1">DISBURSE</th>
        <th colspan="1">TARGET</th>
    </tr>
        <?php
        error_reporting(1);
        $total_cminggu =0 ;
        $total_cbulan  =0;
        $total_ctahun  =0;
        $totaltargetdisminggu=0;
        $totaltargetdisbulan=0;
        $totaltargetdistahun=0;

        $total_masuk_minggu = 0;
        $total_keluar_minggu = 0;
        $total_nett_minggu = 0;
        $total_masuk_bulan = 0;
        $total_keluar_bulan = 0;
        $total_nett_bulan = 0;
        $total_masuk_tahun = 0;
        $total_keluar_tahun = 0;
        $total_nett_tahun = 0;


        $totaldisminggu = 0;
        $totaldisbulan = 0;
        $totaldistahun = 0;

        $query = mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$id_cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
        while($staff = mysqli_fetch_array($query)){
            $qagt  = mysqli_query($con,"SELECT SUM(anggota_masuk) AS masuk, SUM(anggota_keluar) AS keluar, SUM(anggota_masuk) - SUM(anggota_keluar) AS nett FROM anggota where tgl_anggota between '$tgl_minggu' and '$tgl_banding' and id_karyawan='$staff[id_karyawan]' group by id_karyawan ");
            $anggotaminggu=mysqli_fetch_array($qagt);
            $masukminggu = $anggotaminggu['masuk'];
            $keluarminggu = $anggotaminggu['keluar'];
            $nettminggu = $anggotaminggu['nett'];

            $total_masuk_minggu += $masukminggu;
            $total_keluar_minggu += $keluarminggu;
            $total_nett_minggu += $nettminggu;

            
            $qbulan  = mysqli_query($con,"SELECT SUM(anggota_masuk) AS masuk, SUM(anggota_keluar) AS keluar, SUM(anggota_masuk) - SUM(anggota_keluar) AS nett FROM anggota where tgl_anggota like '$bulan_tahun-%' and id_karyawan='$staff[id_karyawan]' group by id_karyawan ");
            $anggotabulan=mysqli_fetch_array($qbulan);
            $masukbulan = $anggotabulan['masuk'];
            $keluarbulan = $anggotabulan['keluar'];
            $nettbulan = $anggotabulan['nett'];

            $total_masuk_bulan += $masukbulan;
            $total_keluar_bulan += $keluarbulan;
            $total_nett_bulan += $nettbulan;

            
            $qtahun  = mysqli_query($con,"SELECT SUM(anggota_masuk) AS masuk, SUM(anggota_keluar) AS keluar, SUM(anggota_masuk) - SUM(anggota_keluar) AS nett FROM anggota where tgl_anggota like '$tahun-%-%' and id_karyawan='$staff[id_karyawan]' group by id_karyawan ");
            $anggotatahun=mysqli_fetch_array($qtahun);
            $masuktahun = $anggotatahun['masuk'];
            $keluartahun = $anggotatahun['keluar'];
            $netttahun = $anggotatahun['nett'];

            $total_masuk_tahun += $masuktahun;
            $total_keluar_tahun += $keluartahun;
            $total_nett_tahun += $netttahun;

            $qdisminggu = mysqli_query($con,"SELECT SUM(disburse) AS total FROM disburse WHERE (tgl_disburse between '$tgl_minggu' and '$tgl_banding')  and id_karyawan='$staff[id_karyawan]'");
            $cdisminggu = mysqli_fetch_array($qdisminggu);
            $cdisminggu = $cdisminggu['total'];


            $qdisbulan = mysqli_query($con,"SELECT SUM(disburse) AS total FROM disburse WHERE tgl_disburse like '$bulan_tahun-%'   and id_karyawan='$staff[id_karyawan]'");
            $cdisbulan = mysqli_fetch_array($qdisbulan);
            $cdisbulan = $cdisbulan['total'];


            $qdistahun = mysqli_query($con,"SELECT SUM(disburse) AS total FROM disburse WHERE tgl_disburse like '$tahun-%-%'   and id_karyawan='$staff[id_karyawan]'");
            $cdistahun = mysqli_fetch_array($qdistahun);
            $cdistahun = $cdistahun['total'];


            $totaldisminggu += $cdisminggu;
            $totaldisbulan += $cdisbulan;
            $totaldistahun += $cdistahun;

            $cagt = mysqli_query($con,"SELECT * FROM cashflow where id_karyawan='$staff[id_karyawan]' and tahun_cashflow='$tahun'");
            $cagt = mysqli_fetch_array($cagt);
            $cagt = $cagt['net_cashflow'];

            $qdis = mysqli_query($con,"SELECT * FROM target_disburse where id_karyawan='$staff[id_karyawan]'");
            $dis = mysqli_fetch_array($qdis);
            $disminggu = $dis['target_minggu'];
            $disbulan = $dis['target_bulan'];
            $distahun = $dis['target_tahun'];
            ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$staff['nama_karyawan']?></td>
                <th colspan="1"><?=($masukminggu==null?"0":$masukminggu)?></th>
                <th colspan="1"><?=($keluarminggu==null?"0":$keluarminggu)?></th>
                <th colspan="1"><?=($nettminggu==null?"0":$nettminggu)?></th>
                <th colspan="1"><?=$cminggu=round($cagt/48)?></th>
                <th colspan="1"><?=($cdisminggu==null?"0":angka($cdisminggu))?></th>
                <th colspan="1"><?=($disminggu==null?"0":angka($disminggu))?></th>

                <th colspan="1"><?=($masukbulan==null?"0":$masukbulan)?></th>
                <th colspan="1"><?=($keluarbulan==null?"0":$keluarbulan)?></th>
                <th colspan="1"><?=($nettbulan==null?"0":$nettbulan)?></th>
                <th colspan="1"><?=$cbulan=round($cagt/12)?></th>
                <th colspan="1"><?=($cdisbulan==null?"0":angka($cdisbulan))?></th>
                <th colspan="1"><?=($disbulan==null?"0":angka($disbulan))?></th>
                
                <th colspan="1"><?=($masuktahun==null?"0":$masuktahun)?></th>
                <th colspan="1"><?=($keluartahun==null?"0":$keluartahun)?></th>
                <th colspan="1"><?=($netttahun==null?"0":$netttahun)?></th>
                <th colspan="1"><?=$ctahun=round($cagt)?></th>
                <th colspan="1"><?=($cdistahun==null?"0":angka($cdistahun))?></th>
                <th colspan="1"><?=($distahun==null?"0":angka($distahun))?></th>
                
            </tr>
            <?php
            $total_cminggu +=$cminggu;
            $total_cbulan +=$cbulan;
            $total_ctahun +=$ctahun;

            $totaltargetdisminggu += $disminggu;
            $totaltargetdisbulan += $disbulan;
            $totaltargetdistahun+= $distahun;
        } 
        ?>
        <tr>
                <td colspan="2">TOTAL SEMUA</td>
                <th colspan="1"><?=($total_masuk_minggu==null?"0":$total_masuk_minggu)?></th>
                <th colspan="1"><?=($total_keluar_minggu==null?"0":$total_keluar_minggu)?></th>
                <th colspan="1"><?=($total_nett_minggu==null?"0":$total_nett_minggu)?></th>
                <th colspan="1"><?=($total_cminggu==null?"0":$total_cminggu)?></th>
                <th style="text-align:left"><?=($totaldisminggu==null?"0":angka($totaldisminggu))?></th>
                <th style="text-align:left"><?=($totaltargetdisminggu==null?"0":angka($totaltargetdisminggu))?> </th>

                <th colspan="1"><?=($total_masuk_bulan==null?"0":$total_masuk_bulan)?> </th>
                <th colspan="1"><?=($total_keluar_bulan==null?"0":$total_keluar_bulan)?> </th>
                <th colspan="1"><?=($total_nett_bulan==null?"0":$total_nett_bulan)?> </th>
                <th colspan="1"><?=($total_cbulan==null?"0":$total_cbulan)?> </th>
                <th style="text-align:left"><?=($totaldisbulan==null?"0":angka($totaldisbulan))?></th>
                <th style="text-align:left"><?=($totaltargetdisbulan==null?"0":angka($totaltargetdisbulan))?> </th>

                <th colspan="1"><?=($total_masuk_tahun==null?"0":$total_masuk_tahun)?> </th>
                <th colspan="1"><?=($total_keluar_tahun==null?"0":$total_keluar_tahun)?> </th>
                <th colspan="1"><?=($total_nett_tahun==null?"0":$total_nett_tahun)?> </th>
                <th colspan="1"><?=($total_ctahun==null?"0":$total_ctahun)?> </th>
                <th style="text-align:left"><?=($totaldistahun==null?"0":angka($totaldistahun))?></th>
                <th style="text-align:left"><?=($totaltargetdistahun==null?"0":angka($totaltargetdistahun))?></th>
                
            </tr>
    </table>
</div>



