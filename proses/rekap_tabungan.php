<div class='content table-responsive'>
	<h3 class='page-header'>REKAP TABUNGAN</h3>
  

  <?php
 if(isset($_GET['tglawal']) || isset($_GET['tglakhir']))
{
	$tglawal = $_GET['tglawal'];
	$tglakhir = $_GET['tglakhir'];
}
else{
    $tgl = date("Y-m-d");
	$tglawal = date("Y-m-d",strtotime('last monday', strtotime("$tgl")));
	$tglakhir = date("Y-m-d");
}
	 

 
$nama_hari = ['senin','selasa','rabu','kamis','jumat'];
?>    <form action="">
        <input type="hidden" name='menu' value='rekap_tabungan'/>
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
                <?php 
                $tabungan = array(
                    "sukarela" => "SUKARELA",
                    "hariraya" => "HARI RAYA",
                    "wajib" => "WAJIB",
                    "pensiun" => "PENSIUN",
                );
                ?>
                <select name="tab" required class='' id="">
                    <option value="">Pilih tabungan</option>
                    <?php 
                    $sel="";
                    foreach($tabungan as $tab => $x){
                        if($tab==$_GET['tab']) $sel='selected';
                        else $sel="";
                        ?>
                        <option value="<?=$tab?>" <?=$sel?>><?=$x?></option>

                        <?php
                    }
                    ?>
                </select>

                <select name='tglawal' class='btn' required>
                        
                        <option value="">PILIH MINGGU SEBELUM</option>
                        <?php 
                        error_reporting(0);
                        $q_tgl = mysqli_query($con,"SELECT DISTINCT tgl_input FROM deliquency where id_cabang='$id_cabang'  order by tgl_input desc");
                        while($tgl_ = mysqli_fetch_array($q_tgl)){
                            $hari = format_hari_tanggal($tgl_['tgl_input']);
                            if(strtolower(explode(",",$hari)[0])=='senin'){
                            ?>
                            <option value="<?=$tgl_['tgl_input']?>" <?=($_GET['tglawal']===$tgl_['tgl_input']?"selected":"")?>><?=format_hari_tanggal($tgl_['tgl_input'])?></option>
                            <?php

                            }
                        }
                        ?>

                    </select>
                    <select name='tglakhir' class='btn' required>
                        
                        <option value="">PILIH MINGGU INI</option>
                        <?php
                        $q_tgl = mysqli_query($con,"SELECT DISTINCT tgl_input FROM deliquency where id_cabang='$id_cabang' order by tgl_input desc");
                        while($tgl_ = mysqli_fetch_array($q_tgl)){
                            $hari = format_hari_tanggal($tgl_['tgl_input']);

                            if(strtolower(explode(",",$hari)[0])=='jumat'){
                            ?>
                            <option value="<?=$tgl_['tgl_input']?>" <?=($_GET['tglakhir']===$tgl_['tgl_input']?"selected":"")?>><?=format_hari_tanggal($tgl_['tgl_input'])?></option>
                            <?php
   
                            }
   
                        }
                        ?>

                    </select>
        <!-- <input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d",(strtotime ( '-4 day' , strtotime ( date("Y-m-d")) ) )) )?>" class=""/> -->
        <!-- <input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/> -->
        <input type='submit' class="btn btn-info" name='cari' value='FILTER'/>
        <!-- <a href="<?=$url.$menu?>rekap_kegiatan&tglawal=<?=date("Y-m-d",(strtotime ( '-1 day' , strtotime ( date("Y-m-d"))  )))?>&tglakhir=<?=date("Y-m-d",(strtotime ( '-1 day' , strtotime ( date("Y-m-d"))  )))?>&cari=FILTER" class="btn btn-danger">KEMARIN</a>
                <a href="<?=$url.$menu?>rekap_kegiatan&tglawal=<?=date("Y-m-d")?>&tglakhir=<?=date("Y-m-d")?>&cari=FILTER" class="btn btn-danger">HARI INI</a> -->
    
    
            </form>
    <hr>
    <div class="col-md-12">
        <h2 style='text-align:center'>REKAP TABUNGAN DARI DELINSAVING<br> 
        <?=format_hari_tanggal($tglawal)?> s/d <?=format_hari_tanggal($tglakhir)?>
        </h2>
    <?php 
    $TGL   = array();
 
    $qtgl = mysqli_query($con,"SELECT tgl_input from deliquency where id_cabang='$id_cabang' and (tgl_input between '$tglawal' and '$tglakhir')  group by tgl_input order by tgl_input asc");
    while($tgl = mysqli_fetch_array($qtgl)){
        $TGL[]=$tgl['tgl_input'];
    }
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
                
                $tgl_jumat[] = date("Y-m-d",(strtotime ( '+6 day' , strtotime ( date($tgl)) ) ));
                $TGL[]=$tgl;
            }

        }
        error_reporting(0);
    ?>
    <table class='table table-bordered'>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>CTR</th>
            <th>SALDO AWAL</th>
            <?php
            $a=0; 
            foreach($TGL as $tgl){
                
                ?>
                <th><?=$tgl?> s/d <br/><?=$tgl_jumat[$a]?></th>
                <?php
                $a++;
            }
            ?>
            <th>SALDO AKHIR</th>
        </tr>
        <?php 
         $simpanan=$_GET['tab'];
          $qhari="";
          $qstaff="";
              if(!empty($_GET['hari']) ){
                  $hari = strtoupper( aman($con,$_GET['hari']));
                  $qhari = "and upper(deli.hari)='$hari'";
              }
          
              if( !empty($_GET['staff']) ){
                  $staff = aman($con,$_GET['staff']);
                  $qstaff = "and c.id_karyawan='$staff'";
              }
        $q = mysqli_query($con,"SELECT * from deliquency deli join center c ON deli.`no_center`=c.`no_center` where deli.id_cabang='$id_cabang' and tgl_input = '$tglawal' $qhari  $qstaff group by id_detail_nasabah order by deli.no_center,deli.nasabah ");

echo mysqli_error($con);
        while($delin = mysqli_fetch_array($q)){
            ?>
        <tr>
            <td><?=$no++?></td>
            <td><?=$delin['nasabah']?></td>
            <td><?=$delin['no_center']?></td>
            <td>
                <?php $saldo_awal = hitung_sekarang($con,$id_cabang,$delin['id_detail_nasabah'],$TGL[0],$simpanan); ?>
                <?=angka($saldo_awal)?></td>
            <?php 
            $a=0;
            $b=0;
            foreach($TGL as $tgl){
                $senin_sebelum = $TGL[$a-1];
                if($a==0){
                    $saldo_sebelumnya = $saldo_awal;
                }else{
                    $saldo_sebelumnya = hitung_tabungan($con,$id_cabang,$delin['id_detail_nasabah'],$senin_sebelum,$saldo_awal,$simpanan);

                }
                $tgl_jumat =date("Y-m-d",(strtotime ( '+6 day' , strtotime ( date($tgl)) ) ));
                $saldo_sekarang = hitung_tabungan($con,$id_cabang,$delin['id_detail_nasabah'],$tgl,$saldo_awal,$simpanan);
                $saldo_awal=$saldo_sekarang-$saldo_awal;
                $selisih=$saldo_sekarang-$saldo_sebelumnya;
                if($selisih==0){
                    $bg='';
                }
                elseif($selisih<1)
                {
                    $bg = "#f7332d";
                }
                else{
                    $bg='#87f58c';
                }
?>

                    <td style="background-color:<?=$bg?> ;"><?=angka($selisih)?></td>

                    <?php
                    $a++;
            }
            $selisih2=0;
            $saldo_awal=0;
            $cari_tgl = mysqli_query($con,"SELECT distinct tgl_input from deliquency where id_cabang='$id_cabang' and (tgl_input between '$tgl' and '$tgl_jumat') order by tgl_input");
            $cari_tgl = mysqli_fetch_array($cari_tgl)['tgl_input'];
            ?>
            <td><?=angka(hitung_sekarang($con,$id_cabang,$delin['id_detail_nasabah'],$cari_tgl,$simpanan))?></td>
        </tr>
            <?php
        }
        ?>
    </table>
    </div>

  
</div>
