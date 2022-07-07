
<?php 
$nama_hari = ['senin','selasa','rabu','kamis','jumat'];
?>
<div class='content table-responsive '>
    <h2 class='page-header'>REKAP PERUBAHAN CENTER  </h2>
    <form>
			<div>
				
					
				<input type="hidden" name='menu' value='rekap_center'/>
                <select name="staff"  class='btn' id="">
                    <option value="">Pilih Staff</option>
                    <?php 
                    $k = mysqli_query($con,"select * from karyawan where id_cabang='$id_cabang' and status_karyawan ='aktif' and id_jabatan=1 order by nama_karyawan asc");
                    while($staff = mysqli_fetch_array($k)){
                        if($staff['id_karyawan'] == $_GET['staff']){
                            echo "<option selected value='$staff[id_karyawan]'>$staff[nama_karyawan]</option>";

                        }
                        else
                        {
                            echo "<option value='$staff[id_karyawan]'>$staff[nama_karyawan]</option>";

                        }
                    }
                    ?>
                </select>
                <select name="hari" class='btn' id="">
                    <option value="">Pilih Hari</option>
                    <?php 
                    foreach($nama_hari as $hari){
                        if($hari == $_GET['hari']){
                            ?>
                        <option selected value="<?=$hari?>"><?=strtoupper($hari)?></option>
                            <?php
                        }
                        else{

                            ?>
                    <option value="<?=$hari?>"><?=strtoupper($hari)?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
				<input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d",(strtotime ( '-1 day' , strtotime ( date("Y-m-d")) ) )) )?>" class=""/>
				<input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
				<input type='submit' class="btn btn-info" name='cari' value='FILTER'/>
			</div>
			
		</form>
    <?php 
    if(isset($_GET['cari'])){
        error_reporting(0);
        $tglawal = $_GET['tglawal'];
        $tglakhir = $_GET['tglakhir'];
        @$title_tpk = "Rekap TPK per Staff ". format_hari_tanggal($tglawal). " - ". format_hari_tanggal($tglakhir) .'<br>';
        $cari = mysqli_query($con,"SELECT DISTINCT tgl_laporan FROM laporan l join karyawan k on k.id_karyawan=l.id_karyawan where k.id_cabang='$id_cabang' and (l.tgl_laporan between '$tglawal' and '$tglakhir')");
        
        ?>
        <table class='table table-bordered'>
            <tr >
                <th rowspan="1"></th>
                <th rowspan="1"></th>
                <th rowspan="1"></th>
                <th rowspan="1"></th>
                <th rowspan="1"></th>
                <?php 
                echo mysqli_error($con);
                $TGL = array();
                $startDate = new \DateTime($tglawal);
                $endDate = new \DateTime($tglakhir);

                $interval = \DateInterval::createFromDateString('1 day');
                $period = new \DatePeriod($startDate, $interval, $endDate);

                // foreach ($period as $date) {
                // echo $date->format('Y-m-d');
                // }

                foreach ($period as $date) {
                    $tgl_senin = $date->format('Y-m-d');
                    $tgl = $date->format('Y-m-d');
                    $pecah =strtolower(explode(",",format_hari_tanggal($tgl_senin))[0]);
                    if($pecah=='senin'){
                        $tgl_jumat = date("Y-m-d",(strtotime ( '+6 day' , strtotime ( date($tgl)) ) ));
                        $TGL[]=$tgl;
                            ?>
                        <th colspan="2" style="text-align: center;"><?=format_hari_tanggal($tgl)?> s.d<br> <?=format_hari_tanggal($tgl_jumat)?></th>
                            <?php

                    }
                }
                ?>

            </tr>
            <tr>
                <th colspan="1">NO</th>
                <th colspan="1">NO CENTER</th>
                <th colspan="1">STAFF</th>
                <th colspan="1">HARI</th>
                <th colspan="1">JAM</th>
                <?php 
                foreach($TGL as $tgl){
                    // $TGL[]=$tgl['tgl_laporan'];
                    ?>
                <th colspan="1" style="text-align: center;">BAYAR(%)</th>
                <th colspan="1" style="text-align: center;">KEHADIRAN(%)</th>
                    <?php
                }
                ?>

            </tr>
            <?php

$qhari="";
$qstaff="";
    if(!empty($_GET['hari']) ){
        $hari = aman($con,$_GET['hari']);
        $qhari = "and c.hari='$hari'";
    }

    if( !empty($_GET['staff']) ){
        $staff = aman($con,$_GET['staff']);
        $qstaff = "and k.id_karyawan='$staff'";
    }
    $q_center = mysqli_query($con,"SELECT * FROM center c JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` where c.id_cabang='$id_cabang' $qhari $qstaff order by no_center asc");
    
    
    while($center = mysqli_fetch_array($q_center)){
        ?>
        <tr>
            <th><?=$no++?></th>
            <td><?=$center['no_center']?></td>
            <td><?=$center['nama_karyawan']?></td>
            <td><?=strtoupper($center['hari'])?></td>
            <td><?=$center['jam_center']?></td>
            <?php 
            $bg='';
            foreach($TGL as $tgl){
                $tgl_jumat = date("Y-m-d",(strtotime ( '+7 day' , strtotime ( date($tgl)) ) ));
                // echo $tgl_jumat;
                $qh = mysqli_query($con,"SELECT * FROM detail_laporan d 
                JOIN laporan l ON d.id_laporan=l.id_laporan 
                JOIN karyawan k ON k.`id_karyawan`=l.`id_karyawan` 
                WHERE k.id_cabang='$id_cabang' and (l.tgl_laporan between '$tgl' and '$tgl_jumat') and d.no_center='$center[no_center]' and l.status_laporan='sukses'");
                $detail = mysqli_fetch_array($qh);
                $qck = mysqli_query($con,"SELECT id from center_kosong where id_cabang='$id_cabang' and no_center='$center[no_center]' and (tgl_transaksi between '$tgl' and '$tgl_jumat')");
               
                $total_anggota = $detail['total_agt'];
                $total_bayar = $detail['total_bayar'];
                if($total_bayar>0){
                    $persen_bayar = round(($total_bayar/$total_anggota)*100);
                }
                else $persen_bayar=0;
                $hadir = $detail['anggota_hadir'];
                if($hadir>0){
                    $persen_hadir = round(($hadir/$total_anggota)*100);
                }
                else $persen_hadir=0;

                
                @$warna =($detail['status']==="null"?"merah":$detail['status']);
                if(mysqli_num_rows($qck)) $bg='#968e8d';
                else {

                        if(mysqli_num_rows($qh)){
                            
                            if($warna=='merah'){
                                $bg = "#f7332d";
                        }
                        else if($warna=='hijau'){
                            $bg='#87f58c';
                        }
                        else if($warna=='kuning'){
                            $bg='#f4f72d';
                        }
                        else {
                            $bg='#f7332d';
                        }
                    }
                    else $bg='';
                }
                
                
                
                ?>
                <td style="text-align: center;background-color: <?=$bg?>;"><?=$persen_bayar?>%</td>
                <td style="text-align: center;background-color: <?=$bg?>;"><?=$persen_hadir?>%</td>
                <?php
            }
            ?>
        </tr>
        <?php
        $warna='';
    }
    ?>
        </table>
        <?php
    }
    ?>
    
</div>