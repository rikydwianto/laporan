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
    
        <input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d",(strtotime ( '-4 day' , strtotime ( date("Y-m-d")) ) )) )?>" class=""/>
        <input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
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
    ?>
    <table class='table table-bordered'>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>CTR</th>
            <?php 
            foreach($TGL as $tgl){
                ?>
                <th><?=$tgl?></th>
                <?php
            }
            ?>
        </tr>
        <?php 
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
        $q = mysqli_query($con,"SELECT * from deliquency deli join center c ON deli.`no_center`=c.`no_center` where deli.id_cabang='$id_cabang' and tgl_input ='$tglawal' $qhari  $qstaff group by id_detail_nasabah order by deli.no_center,deli.nasabah ");
echo mysqli_error($con);
        while($delin = mysqli_fetch_array($q)){
            ?>
        <tr>
            <td><?=$no++?></td>
            <td><?=$delin['nasabah']?></td>
            <td><?=$delin['no_center']?></td>
            <?php 
            $a=1;

            foreach($TGL as $tgl){
                if($a < count($TGL)){

                    $tgl_next = $TGL[$a];
                    // if(!$tgl_next){
                        // }
                // 
                $selisih = hitung_tabungan($con,$id_cabang,$delin['id_detail_nasabah'],$tgl,$tgl_next,"sukarela");
                if($selisih>0){
                    $bg='#87f58c';
                }
                else if($selisih==0)
                     $bg=""; 
                else
                $bg ="#f7332d";

                ?>
                <td style="background-color: <?=$bg?>"><?=angka($selisih)?></td>
                <?php
                }
                else{
                    ?>
                    <td><?=angka(hitung_sekarang($con,$id_cabang,$delin['id_detail_nasabah'],$tgl,"sukarela"))?></td>

                    <?php
                }
                $a++;
            }
            ?>
        </tr>
            <?php
        }
        ?>
    </table>
    </div>

  
</div>
