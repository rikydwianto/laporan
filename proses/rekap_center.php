
<div class='content '>
    <h2 class='page-header'>REKAP PERUBAHAN CENTER  </h2>
    <form>
			<div>
				
					
				<input type="hidden" name='menu' value='rekap_center'/>
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
                while($tgl = mysqli_fetch_array($cari)){
                    $TGL[]=$tgl['tgl_laporan'];
                    ?>
                <th colspan="2" style="text-align: center;"><?=$tgl['tgl_laporan']?></th>
                    <?php
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

    $q_center = mysqli_query($con,"SELECT * FROM center c JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` where c.id_cabang='$id_cabang' order by no_center asc");
    while($center = mysqli_fetch_array($q_center)){
        ?>
        <tr>
            <th><?=$no++?></th>
            <td><?=$center['no_center']?></td>
            <td><?=$center['nama_karyawan']?></td>
            <td><?=strtoupper($center['hari'])?></td>
            <td><?=$center['jam_center']?></td>
            <?php 
            foreach($TGL as $tgl){
                $qh = mysqli_query($con,"SELECT * FROM detail_laporan d 
                JOIN laporan l ON d.id_laporan=l.id_laporan 
                JOIN karyawan k ON k.`id_karyawan`=l.`id_karyawan` 
                WHERE k.id_cabang='$id_cabang' and l.tgl_laporan='$tgl' and d.no_center='$center[no_center]'");
                $detail = mysqli_fetch_array($qh);
                
                @$warna =($detail['status']==="null"?"merah":$detail['status']);
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
                    $bg='';
                }
                $total_anggota = $detail['total_agt'];
                $total_bayar = $detail['total_bayar'];
                $persen_bayar = round(($total_bayar/$total_anggota)*100);
                $hadir = $detail['anggota_hadir'];
                $persen_hadir = round(($hadir/$total_anggota)*100);
                ?>
                <td style="text-align: center;background-color: <?=$bg?>;"><?=$persen_bayar?>%</td>
                <td style="text-align: center;background-color: <?=$bg?>;"><?=$persen_hadir?>%</td>
                <?php
            }
            ?>
        </tr>
        <?php
    }
    ?>
        </table>
        <?php
    }
    ?>
    
</div>